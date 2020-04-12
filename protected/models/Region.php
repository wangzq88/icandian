<?php

/**
 * This is the model class for table "idingcan_region".
 *
 * The followings are the available columns in table 'idingcan_region':
 * @property integer $region_id
 * @property string $region_name
 * @property integer $city_id
 */
class Region extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Region the static model class
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
		return '{{region}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('region_name, city_id', 'required'),
			array('city_id,ordering', 'numerical', 'integerOnly'=>true),
			array('region_name', 'length', 'max'=>15),
			array('status', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('region_id, region_name, city_id,status', 'safe', 'on'=>'search'),
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

	public function scopes()
    {
        return array(
            'published'=>array(
                'condition'=>'status=1',
            ),
            'orderDes'=>array(
                'order'=>'ordering DESC'
            ),
        );
    }	
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'region_id' => 'Region',
			'region_name' => 'Region Name',
			'city_id' => 'City',
			'ordering' => 'Ordering',
			'status' => 'Status'
		);
	}

	/**
	 * 返回所有区域信息，有缓存
	 */
	public function getAllRegion() {
		$sql = "SELECT * FROM {{region}}";
		$dependency = new CDbCacheDependency("SELECT timestamp FROM {{operation_log}} WHERE name='region' ");
		$regions = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		return	$regions;		
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

		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('region_name',$this->region_name,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}