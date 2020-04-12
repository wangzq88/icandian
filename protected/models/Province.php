<?php

/**
 * This is the model class for table "idingcan_province".
 *
 * The followings are the available columns in table 'idingcan_province':
 * @property string $province_id
 * @property string $province_name
 */
class Province extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Province the static model class
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
		return '{{province}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('province_id, province_name', 'required'),
			array('province_id', 'length', 'max'=>4),
			array('province_name', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('province_id, province_name', 'safe', 'on'=>'search'),
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
			'province_id' => 'Province',
			'province_name' => 'Province Name',
		);
	}
	/**
	 * 返回所有省份信息，有缓存
	 */
	public function getAllProvinces() {
//		$sql = "SELECT * FROM idingcan_province";
//		$dependency = new CDbCacheDependency('SELECT COUNT(*) FROM idingcan_province');
//		$provinces = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
//		return	$provinces;		
		return include Yii::app()->basePath.DS.'data'.DS.'province.php';
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

		$criteria->compare('province_id',$this->province_id,true);
		$criteria->compare('province_name',$this->province_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}