<?php

/**
 * This is the model class for table "idingcan_shop".
 *
 * The followings are the available columns in table 'idingcan_shop':
 * @property string $shop_id
 * @property string $shop_name
 * @property string $shop_logo
 * @property string $shop_description
 * @property string $shop_banner
 * @property string $shop_tips
 * @property string $shop_announcement
 * @property string $shop_address
 * @property string $shop_province
 * @property integer $shop_city
 * @property integer $shop_region
 * @property string $shop_area
 * @property string $shop_opening_hours
 * @property string $ordering_time
 * @property integer $flag
 * @property string $uid
 * @property integer $status
 * @property string $ordering
 */
class Admin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Admin the static model class
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
		return 'idingcan_shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_name, shop_address, uid', 'required'),
			array('shop_city, shop_region, flag, status', 'numerical', 'integerOnly'=>true),
			array('shop_name', 'length', 'max'=>30),
			array('shop_logo, shop_description, shop_banner, shop_announcement, shop_opening_hours, ordering_time', 'length', 'max'=>255),
			array('shop_tips', 'length', 'max'=>100),
			array('shop_address', 'length', 'max'=>50),
			array('shop_province', 'length', 'max'=>4),
			array('shop_area, uid, ordering', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('shop_id, shop_name, shop_logo, shop_description, shop_banner, shop_tips, shop_announcement, shop_address, shop_province, shop_city, shop_region, shop_area, shop_opening_hours, ordering_time, flag, uid, status, ordering', 'safe', 'on'=>'search'),
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
			'shop_id' => 'Shop',
			'shop_name' => 'Shop Name',
			'shop_logo' => 'Shop Logo',
			'shop_description' => 'Shop Description',
			'shop_banner' => 'Shop Banner',
			'shop_tips' => 'Shop Tips',
			'shop_announcement' => 'Shop Announcement',
			'shop_address' => 'Shop Address',
			'shop_province' => 'Shop Province',
			'shop_city' => 'Shop City',
			'shop_region' => 'Shop Region',
			'shop_area' => 'Shop Area',
			'shop_opening_hours' => 'Shop Opening Hours',
			'ordering_time' => 'Ordering Time',
			'flag' => 'Flag',
			'uid' => 'Uid',
			'status' => 'Status',
			'ordering' => 'Ordering',
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

		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('shop_name',$this->shop_name,true);
		$criteria->compare('shop_logo',$this->shop_logo,true);
		$criteria->compare('shop_description',$this->shop_description,true);
		$criteria->compare('shop_banner',$this->shop_banner,true);
		$criteria->compare('shop_tips',$this->shop_tips,true);
		$criteria->compare('shop_announcement',$this->shop_announcement,true);
		$criteria->compare('shop_address',$this->shop_address,true);
		$criteria->compare('shop_province',$this->shop_province,true);
		$criteria->compare('shop_city',$this->shop_city);
		$criteria->compare('shop_region',$this->shop_region);
		$criteria->compare('shop_area',$this->shop_area,true);
		$criteria->compare('shop_opening_hours',$this->shop_opening_hours,true);
		$criteria->compare('ordering_time',$this->ordering_time,true);
		$criteria->compare('flag',$this->flag);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('ordering',$this->ordering,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}