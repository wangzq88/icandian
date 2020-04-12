<?php

/**
 * This is the model class for table "idingcan_package".
 *
 * The followings are the available columns in table 'idingcan_package':
 * @property string $package_id
 * @property string $food_ids
 * @property string $remark
 * @property string $categories_id
 * @property string $shop_id
 */
class Package extends CActiveRecord
{
	private $_cacheTime = 2592000;//30天
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Package the static model class
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
		return '{{package}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('food_ids,package_price, categories_id, shop_id', 'required'),
			array('package_price', 'numerical'),
//			array('alias', 'length', 'max'=>6),
//			array('alias','match','pattern'=>'/^[A-Za-z0-9]+$/'),
			array('food_ids', 'length', 'max'=>50),
			array('package_remark', 'length', 'max'=>255),
			array('package_img', 'length', 'max'=>255),
			array('categories_id, shop_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('package_id, food_ids,package_price, package_remark,package_img, categories_id, shop_id', 'safe', 'on'=>'search'),
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
			'package_id' => 'Package',
//			'alias' => 'Alias',
			'food_ids' => 'Food Ids',
			'package_price' => 'Package Price',
			'package_remark' => 'Remark',
			'package_img' => 'Image',
			'categories_id' => 'Categories',
			'shop_id' => 'Shop',
		);
	}

	public function getPackageListByShopID($shop_id) {
		$sql = "SELECT * FROM ".$this->tableName()." WHERE shop_id=$shop_id ORDER BY ordering DESC";
		$dependency = new CDbCacheDependency('SELECT update_time FROM {{shop}} WHERE shop_id='.$shop_id);
		$package = Yii::app()->db->cache($this->_cacheTime, $dependency)->createCommand($sql)->queryAll();
		return	$package;			
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

		$criteria->compare('package_id',$this->package_id,true);
		$criteria->compare('food_ids',$this->food_ids,true);
		$criteria->compare('package_price',$this->food_price);
		$criteria->compare('package_remark',$this->remark,true);
		$criteria->compare('package_img',$this->package_img,true);	
		$criteria->compare('categories_id',$this->categories_id,true);
		$criteria->compare('shop_id',$this->shop_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}