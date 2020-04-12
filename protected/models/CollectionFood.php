<?php

/**
 * This is the model class for table "{{collection_food}}".
 *
 * The followings are the available columns in table '{{collection_food}}':
 * @property string $id
 * @property string $shop_id
 * @property string $shop_name
 * @property string $food_id
 * @property string $food_name
 * @property string $food_price
 * @property string $uid
 */
class CollectionFood extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CollectionFood the static model class
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
		return '{{collection_food}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, shop_name, food_id, food_name, food_price', 'required'),
			array('shop_id, food_id, uid', 'length', 'max'=>10),
			array('shop_name, food_name', 'length', 'max'=>30),
			array('food_price', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_id, shop_name, food_id, food_name, food_price, uid', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'shop_id' => 'Shop',
			'shop_name' => 'Shop Name',
			'food_id' => 'Food',
			'food_name' => 'Food Name',
			'food_price' => 'Food Price',
			'uid' => 'Uid',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('food_id',$this->food_id,true);
		$criteria->compare('food_name',$this->food_name,true);
		$criteria->compare('food_price',$this->food_price,true);
		$criteria->compare('uid',$this->uid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}