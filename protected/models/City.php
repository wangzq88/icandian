<?php

/**
 * This is the model class for table "idingcan_city".
 *
 * The followings are the available columns in table 'idingcan_city':
 * @property integer $city_id
 * @property string $city_name
 * @property string $province_id
 */
class City extends CActiveRecord
{
	private $_cacheTime = 604800;//一个星期
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return City the static model class
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
		return '{{city}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_name', 'length', 'max'=>12),
			array('province_id', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('city_id, city_name, province_id', 'safe', 'on'=>'search'),
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
			'city_id' => 'City',
			'city_name' => 'City Name',
			'province_id' => 'Province',
		);
	}

	/**
	 * 返回所有城市信息，有缓存
	 */
	public function getAllCities() {
//		$sql = "SELECT * FROM idingcan_city";
//		$dependency = new CDbCacheDependency('SELECT COUNT(*) FROM idingcan_city');
//		$cities = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
//		return	$cities;		
		return include Yii::app()->basePath.DS.'data'.DS.'cities.php';
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

		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('city_name',$this->city_name,true);
		$criteria->compare('province_id',$this->province_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}