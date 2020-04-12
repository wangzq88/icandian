<?php

/**
 * This is the model class for table "idingcan_user".
 *
 * The followings are the available columns in table 'idingcan_user':
 * @property string $uid
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $mobile
 * @property string $salt
 * @property string $flag
 * @property string $timestamp
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'length', 'max'=>30),
			array('password', 'length', 'max'=>32),
			array('avatar', 'length', 'max'=>255),
			array('email', 'length', 'max'=>100),
			array('email', 'email'),
			array('mobile','match','pattern'=>'/^[0-9]{5,15}$/'),
			array('salt', 'length', 'max'=>6),
			array('flag', 'in', 'range'=>array(1,2,3)),
			array('valid_email,status', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, email, mobile, flag, status', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'mobile' => 'Mobile',
			'salt' => 'Salt',
			'flag' => 'Flag',
			'status' => 'Status',
			'timestamp' => 'Timestamp',
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

		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('status',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}