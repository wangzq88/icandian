<?php

/**
 * This is the model class for table "{{user_apply}}".
 *
 * The followings are the available columns in table '{{user_apply}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $mobile
 * @property string $salt
 * @property string $flag
 * @property integer $status
 * @property string $timestamp
 */
class UserApply extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserApply the static model class
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
		return '{{user_apply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, salt, timestamp,activation_code', 'required'),

			array('username', 'length', 'max'=>30),
			array('password,activation_code', 'length', 'max'=>32),
			array('email', 'length', 'max'=>100),
			array('email', 'email'),
			array('mobile', 'length', 'max'=>15),
			array('salt', 'length', 'max'=>6),
			array('flag', 'in', 'range'=>array(1,2,3)),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, mobile, salt, flag, timestamp', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'mobile' => 'Mobile',
			'salt' => 'Salt',
			'flag' => 'Flag',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}