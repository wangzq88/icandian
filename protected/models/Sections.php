<?php

/**
 * This is the model class for table "{{sections}}".
 *
 * The followings are the available columns in table '{{sections}}':
 * @property string $section_id
 * @property string $section_name
 * @property string $area_id
 * @property integer $ordering
 * @property integer $status
 * @property string $parent_id
 */
class Sections extends CActiveRecord
{

	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sections the static model class
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
		return '{{sections}}';
	}

	/**
	 * 返回所有省份信息，有缓存
	 */
	public function getAllSectionsByAreaID($area_list = array()) {
		$section_list = array();
		if ($area_list && is_array($area_list)) {
			$area_list = implode(',',$area_list);
			$sql = "SELECT * FROM {{sections}} WHERE area_id IN ($area_list) AND status=1 ORDER BY `ordering`";
			$dependency = new CDbCacheDependency("SELECT timestamp FROM {{operation_log}} WHERE name='sections' ");
			$section_list = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		}
		return	$section_list;		
	}	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('area_id', 'required'),
			array('ordering', 'numerical', 'integerOnly'=>true),
			array('section_name', 'length', 'max'=>30),
			array('area_id, parent_id', 'length', 'max'=>10),
			array('status', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('section_id, section_name, area_id, status, parent_id', 'safe', 'on'=>'search'),
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
			'section_id' => 'Section',
			'section_name' => 'Section Name',
			'area_id' => 'Area',
			'ordering' => 'Ordering',
			'status' => 'Status',
			'parent_id' => 'Parent',
		);
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

		$criteria->compare('section_id',$this->section_id,true);
		$criteria->compare('section_name',$this->section_name,true);
		$criteria->compare('area_id',$this->area_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('parent_id',$this->parent_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}