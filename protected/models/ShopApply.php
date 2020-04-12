<?php

/**
 * This is the model class for table "{{shop_apply}}".
 *
 * The followings are the available columns in table '{{shop_apply}}':
 * @property string $id
 * @property string $shop_name
 * @property string $shop_description
 * @property string $shop_address
 * @property string $xing_ming
 * @property string $mobile
 * @property string $qq
 * @property string $phone
 * @property integer $status
 * @property string $uid
 */
class ShopApply extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShopApply the static model class
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
		return '{{shop_apply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_name, shop_description, shop_address, xing_ming, mobile, qq', 'required'),
			array('shop_name', 'length', 'max'=>30),
			array('shop_description', 'length', 'max'=>255),
			array('shop_address', 'length', 'max'=>50),
			array('xing_ming', 'length', 'max'=>20),
			array('mobile', 'length', 'max'=>18),
			array('qq, phone', 'length', 'max'=>15),
			array('status', 'in', 'range'=>array('1','2','3')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, shop_name, shop_description, shop_address, xing_ming, mobile, qq, phone, status, uid', 'safe', 'on'=>'search'),
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
			'shop_name' => 'Shop Name',
			'shop_description' => 'Shop Description',
			'shop_address' => 'Shop Address',
			'xing_ming' => 'Xing Ming',
			'mobile' => 'Mobile',
			'qq' => 'Qq',
			'phone' => 'Phone',
			'status' => 'Status',
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
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('shop_description',$this->shop_description,true);
		$criteria->compare('shop_address',$this->shop_address,true);
		$criteria->compare('xing_ming',$this->xing_ming,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('uid',$this->uid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}