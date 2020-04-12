<?php

/**
 * This is the model class for table "idingcan_categories".
 *
 * The followings are the available columns in table 'idingcan_categories':
 * @property string $categories_id
 * @property string $categories_name
 * @property string $shop_id
 */
class FoodCategories extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FoodCategories the static model class
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
		return '{{categories}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('categories_name','required'),
			array('categories_name', 'length', 'max'=>30),
			array('categories_description', 'length', 'max'=>255),
			array('ordering,shop_id', 'numerical', 'integerOnly'=>true),
			array('ordering,shop_id', 'length', 'max'=>10),
			array('status', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('categories_id, categories_name, categories_description, shop_id', 'safe', 'on'=>'search'),
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
			'categories_id' => 'Categories',
			'categories_name' => 'Categories Name',
			'categories_description' => 'Categories Description',
			'shop_id' => 'Shop',
		);
	}
	
	public function getFoodCategoriesByIDs($ids = array(),$shop_id) {
		$result_list = array();
		if ($ids && is_array($ids)) {
			$ids = implode(',',$ids);
			$dependency = new CDbCacheDependency('SELECT update_time FROM {{shop}} WHERE shop_id='.$shop_id);
			$sql = "SELECT * FROM ".$this->tableName()." WHERE categories_id IN ($ids) ORDER BY `ordering` DESC";
			$result_list = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		}
		return $result_list;
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

		$criteria->compare('categories_id',$this->categories_id,true);
		$criteria->compare('categories_name',$this->categories_name,true);
		$criteria->compare('categories_description',$this->categories_description,true);
		$criteria->compare('shop_id',$this->shop_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}