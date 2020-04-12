<?php

/**
 * This is the model class for table "idingcan_area".
 *
 * The followings are the available columns in table 'idingcan_area':
 * @property string $area_id
 * @property string $area_name
 * @property integer $region_id
 * @property integer $ordering
 * @property integer $status
 */
class Area extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Area the static model class
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
		return '{{area}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('area_name, region_id', 'required'),
			array('region_id, ordering', 'numerical', 'integerOnly'=>true),
			array('area_name', 'length', 'max'=>30),
			array('status', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('area_id, area_name, region_id, status', 'safe', 'on'=>'search'),
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
			'area_id' => 'Area',
			'area_name' => 'Area Name',
			'region_id' => 'Region',
			'ordering' => 'Ordering',
			'status' => 'Status',
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
	
	public function getPublishedAreaListByRegionID($region_id) {
		$sql = "SELECT * FROM ".$this->tableName()." ";
		return $this->published()->orderDes()->findAll('region_id=:region_id',array(':region_id'=>$region_id));
	}
	
	public function getPublishedAreaListByRegionIDs($region_id = array()) {
		$result_list = array();
		if ($region_id && is_array($region_id)) {
			$region_id = implode(',',$region_id);
			$sql = "SELECT * FROM ".$this->tableName()." WHERE region_id IN ($region_id) AND status=1 ORDER BY `ordering` DESC";
			$result_list = Yii::app()->db->createCommand($sql)->queryAll();
		}
		return $result_list;
	}
	
	/**
	 * 返回所有具体地段信息，有缓存
	 */
	public function getAllArea() {
		$sql = "SELECT * FROM ".$this->tableName();
		$dependency = new CDbCacheDependency("SELECT timestamp FROM {{operation_log}} WHERE name='area'");
		$areas = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		return	$areas;		
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

		$criteria->compare('area_id',$this->area_id,true);
		$criteria->compare('area_name',$this->area_name,true);
		$criteria->compare('region_id',$this->region_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}