<?php

/**
 * This is the model class for table "idingcan_food".
 *
 * The followings are the available columns in table 'idingcan_food':
 * @property string $food_id
 * @property string $food_name
 * @property string $food_img
 * @property double $food_price
 * @property integer $food_quantity
 * @property string $ordering
 * @property integer $flag
 * @property string $shop_id
 */
class Food extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	private $_pk = 'food_id';
	public $flag_text = '';
	public $categories_name = '';
	public $attribs_text = '';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Food the static model class
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
		return '{{food}}';
	}

	public function primaryKey()
	{
		return $this->_pk;
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_name,food_price, categories_id', 'required'),
//			array('alias','match','pattern'=>'/^[A-Za-z0-9]+$/'),
//			array('alias', 'length', 'max'=>6),
			array('ordering,flag,categories_id,shop_id', 'numerical', 'integerOnly'=>true),
			array('is_new,is_hot,is_facia,is_book', 'boolean'),
			array('food_price', 'numerical'),
			array('food_name', 'length', 'max'=>30),
			array('food_img', 'length', 'max'=>255),
			array('food_remark', 'length', 'max'=>255),
			array('attribs', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('food_name,  flag,categories_id, shop_id', 'safe', 'on'=>'search'),
			array('shop_id', 'unsafe', 'on'=>'create,update,delete')			
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
			'food_id' => 'Food',
			'food_name' => 'Food Name',
			'food_img' => 'Food Img',
			'food_price' => 'Food Price',
			'food_remark' => 'Food Remark',
			'attribs' => 'Attribs',
			'ordering' => 'Ordering',
			'flag' => 'Flag',
			'is_new' => 'is new',
			'is_hot' => 'is hot',
			'categories_id' => 'Categories',
			'status' => 'Status',
			'shop_id' => 'Shop',
		);
	}
	/**
	 * 获取餐店的所有美食
	 */
	public function getFoodListByShopID($shop_id) {
		$sql = "SELECT * FROM ".$this->tableName()." WHERE shop_id=$shop_id ORDER BY ordering DESC";
		$dependency = new CDbCacheDependency('SELECT update_time FROM {{shop}} WHERE shop_id='.$shop_id);
		$food = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		return	$food;				
	}
	
	public function getFoodStatus($food,$shop = '') {
		$time = time();
		$isbook = false;
		if($food['flag'] == 1) {
			$isbook = true;
		} elseif($food['flag'] == 2) {
			$week = date('N',$time);//星期中的第几天，数字表示.
			$attribs_list = explode (',',$food['attribs']);
			$isbook = in_array($week, $attribs_list);
		} elseif ($food['flag'] == 3) {
			$tmp = explode('-',$food['attribs']);
			$date = date('j',$time);
			if ($date >= $tmp[0] && $date <= $tmp[1])
				$isbook = true;
		}
		$isbook = $food['is_book'] > 0 ? $isbook : false;
		if ($isbook) {
			if(!$shop) 
				$shop = Shop::model()->findByPk($food['shop_id'],'status=:status',array(':status' => 1));
			$isbook = false;//是否真正营业状态当中,默认为否
			$now = date('H:i');
			$shop_opening_hours = json_decode($shop['shop_opening_hours'],true);
			//星期几的营业时间
			$shop_opening_hours = $shop_opening_hours[date('N',$time)];
			//上午和下午的营业时间段
			$list = explode(' ',$shop_opening_hours);
			//上午的营业时间段
			$tmp = explode('-',$list[0]);
			//下午的营业时间
			$tmp1 = explode('-',$list[1]);			
			if ($now <= $tmp[1]) {
				$now_opening_hours = $tmp[0].'～'.$tmp[1];
				//处于这个时间段，表示正在营业当中
				if ($now > $tmp[0]) {
					$isbook = true;
				}
			} else {
				$now_opening_hours = $tmp1[0].'～'.$tmp1[1];
				if ($now >= $tmp1[0] && $now < $tmp1[1]) {
					$isbook = true;
				}
			}			
		}				
		return $isbook;
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

		$criteria->compare('food_name',$this->food_name,true);
		$criteria->compare('food_price',$this->food_price);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('categories_id',$this->categories_id,true);
		$criteria->compare('shop_id',$this->shop_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}