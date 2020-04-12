<?php

/**
 * This is the model class for table "{{order_item}}".
 *
 * The followings are the available columns in table '{{order_item}}':
 * @property string $item_id
 * @property string $food_name
 * @property string $alias
 * @property string $food_price
 * @property integer $amount
 * @property string $shop_id
 * @property string $shop_name
 * @property string $order_id
 */
class OrderItem extends CActiveRecord
{
	private $_pk = 'item_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OrderItem the static model class
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
		return '{{order_item}}';
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
			array('food_name, food_price, amount, shop_id, shop_name, order_id', 'required'),
			array('amount', 'numerical'),
			array('food_name, shop_name', 'length', 'max'=>30),
			array('food_price', 'length', 'max'=>5),
			array('shop_id, order_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_id, food_name,food_price, amount, shop_id, shop_name, order_id', 'safe', 'on'=>'search'),
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
			'item_id' => 'Item',
			'food_name' => 'Food Name',
			'food_price' => 'Food Price',
			'amount' => 'Amount',
			'shop_id' => 'Shop',
			'shop_name' => 'Shop Name',
			'order_id' => 'Order',
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

		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('food_name',$this->food_name,true);
//		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('food_price',$this->food_price,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('order_id',$this->order_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}