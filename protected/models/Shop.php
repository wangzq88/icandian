<?php

/**
 * This is the model class for table "idingcan_shop".
 *
 * The followings are the available columns in table 'idingcan_shop':
 * @property string $shop_id
 * @property string $shop_name
 * @property string $shop_description
 * @property string $shop_province
 * @property integer $shop_city
 * @property integer $shop_region
 * @property string $shop_area
 * @property string $ordering
 */
class Shop extends CActiveRecord
{
	public $open = false;
	public $now_opening_hours = '';
	public $now_ordering_time = '';
	public $new = false;
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Shop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_count,shop_region,shop_area,shop_section,shop_cuisine,timestamp,ordering', 'numerical', 'integerOnly'=>true),
			array('shop_name', 'length', 'max'=>30),
			array('shop_logo,shop_banner', 'length', 'max'=>150),
			array('shop_mobile', 'numerical'),
			array('shop_mobile', 'length', 'min' => 5,'max'=>15),
			array('shop_description,shop_announcement,shop_opening_hours', 'length', 'max'=>255),
			array('shop_province', 'normalizeProvince'),
			array('shop_city', 'normalizeCity'),
			array('shop_tips','length', 'max'=>100),
			array('shop_address, ordering_time', 'length', 'max'=>50),
			array('shop_area, ordering,timestamp', 'length', 'max'=>10),
			array('food_count', 'length', 'max'=>5),
			array('coupon,flag,status', 'in', 'range'=>array(0,1)),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shop_id, shop_name, shop_description, shop_province, shop_city, shop_region, shop_area, ordering', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'shop_id' => 'Shop',
			'shop_name' => 'Shop Name',
			'shop_logo' => 'Shop Logo',
			'shop_description' => 'Shop Description',
			'shop_banner' => 'Shop Banner',
			'shop_tips' => 'Shop Tips',
			'shop_announcemen' => 'Shop Announcemen',
			'shop_address' => 'Shop Address',
			'shop_province' => 'Shop Province',
			'shop_city' => 'Shop City',
			'shop_region' => 'Shop Region',
			'shop_area' => 'Shop Area',
			'shop_section' => 'Shop Section',
			'shop_opening_hours' => 'shop_opening_hours',
			'ordering_time' => 'ordering_time',
			'food_count' => 'Food Count',
			'shop_cuisine' => 'Shop Cuisine',
			'coupon' => 'Coupon',
			'timestamp' => 'Timestamp',
			'flag' => 'Flag',
			'uid' => 'UID',
			'status' => 'Status',
			'ordering' => 'Ordering',
		);
	}

	public function normalizeProvince($attribute,$params) {
		$province = Province::model()->findByPk($this->shop_province);
		$this->shop_province = $province->province_id;
	}
	
	public function normalizeCity($attribute,$params) {
		$city = City::model()->findByPk($this->shop_city);
		$this->shop_city = $city->city_id;
	}

	public function onFoodcount($flag) {
		if (!Yii::app()->user->isGuest && Yii::app()->user->flag == '2') {			
			$time = time();
			if ($flag == '1') {//表示增加
				$command = Yii::app()->db->createCommand("UPDATE {{shop}} SET food_count = food_count+1,update_time=$time WHERE shop_id=".Yii::app()->user->shop_id);
				$_SESSION['food_count'] = $_SESSION['food_count'] + 1;
			} else {
				$command = Yii::app()->db->createCommand("UPDATE {{shop}} SET food_count = food_count-1,update_time=$time WHERE shop_id=".Yii::app()->user->shop_id);
				$_SESSION['food_count'] = $_SESSION['food_count'] - 1;
			}	
			$command->execute();
		}
	}
	
	public function getShopInfo($shop_id,$status) {
		$dependency = new CDbCacheDependency('SELECT update_time FROM {{shop}} WHERE shop_id='.$shop_id);
		$command = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand("SELECT * FROM {{shop}} WHERE shop_id=$shop_id AND status=$status");
		return $command->queryRow();		
	}
	
	public function handlerShopLogic($row) {
		//查询所有菜系信息
		$cuisines = Cuisine::model()->getAllCuisine();	
		//现在的时间段
		$time = time();
		$now = date('H:i',$time);			

		$row['open'] = false;//是否真正营业状态当中,默认为否
		$shop_opening_hours = json_decode($row['shop_opening_hours'],true);
		//星期几的营业时间
		$shop_opening_hours = $shop_opening_hours[date('N')];
		$row['row_open_hours'] = $shop_opening_hours;
		//上午和下午的营业时间段
		$list = explode(' ',$shop_opening_hours);
		//上午的营业时间段
		$tmp = explode('-',$list[0]);
		//下午的营业时间
		$tmp1 = explode('-',$list[1]);			
		if ($now <= $tmp[1]) {
			$row['now_opening_hours'] = $tmp[0].'～'.$tmp[1];
			//处于这个时间段，表示正在营业当中
			if ($now > $tmp[0]) {
				$row['open'] = true;
			}
		} else {
			$row['now_opening_hours'] = $tmp1[0].'～'.$tmp1[1];
			if ($now >= $tmp1[0] && $now < $tmp1[1]) {
				$row['open'] = true;
			}
		}
		//说明中午没有休息，全天运营
		if ($tmp[1] == $tmp1[0]) {
			$row['now_opening_hours'] = $tmp[0].'～'.$tmp1[1];
		}
		//显示详细的营业时间
		$row['shop_opening_hours'] = $tmp[0].'～'.$tmp[1];
		if ($tmp1[0] != $tmp1[1]) {//如果前后时间相等，说明下午没有营业
			$row['shop_opening_hours'] .= ' '.$tmp1[0].'～'.$tmp1[1]; 
		}
		if ($tmp[1] == $tmp1[0]) {
			$row['shop_opening_hours'] = $tmp[0].'～'.$tmp1[1];
		}
		//显示详细的最佳订餐时间，包括上午和下午
		$row['row_ordering_time'] = $row['ordering_time'];
		if($row['ordering_time']) {
			$ordering_time = json_decode($row['ordering_time'],true);
			if($ordering_time['1']) {
				$list = explode('-',$ordering_time['1']);
				$row['ordering_time'] = $list[0].'～'.$list[1];
			}
			//下午订餐时间
			if($ordering_time['2']) {
				$list1 = explode('-',$ordering_time['2']);
				if ($list[1] == $list1[0]) {//说明全天接受订餐，中间没空格的时间
					$row['ordering_time'] = $list[0].'～'.$list1[1];
				} elseif ($list1[0] != $list1[1]) {//不相等说明下午接受预订
					$row['ordering_time'] .= ' '.$list1[0].'～'.$list1[1];
				}
			}				
		}	
		//显示现在订餐时间
		if ($now <= $tmp[1]) {//如果现在处于上午的营业时间，显示上午的订餐时间
			$row['now_ordering_time'] = $list[0].'～'.$list[1];
		} else {
			$row['now_ordering_time'] = $list1[0].'～'.$list1[1];
		}					
		//如果是在7天之内开张的，显示“新”
		if ($row['timestamp'] + 7*24*3600 > $time) {
			$row['new'] = true;
		} else {
			$row['new'] = false;
		}
		//显示菜系信息
		if ($row['shop_cuisine'] != 0) {
			foreach ($cuisines as $cui) {
				if($cui['cuisine_id'] == $row['shop_cuisine']) {
					$row['shop_cuisine'] = $cui['cuisine_name'];
					break;
				}
			}
		} else {
			$row['shop_cuisine'] = '不限';
		}				
		return $row;
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('shop_description',$this->shop_description,true);
		$criteria->compare('shop_province',$this->shop_province,true);
		$criteria->compare('shop_city',$this->shop_city);
		$criteria->compare('shop_region',$this->shop_region);
		$criteria->compare('shop_area',$this->shop_area,true);
		$criteria->compare('ordering',$this->ordering,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}