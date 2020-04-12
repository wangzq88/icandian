<?php

/**
 * This is the model class for table "{{message}}".
 *
 * The followings are the available columns in table '{{message}}':
 * @property string $id
 * @property string $send_uid
 * @property string $send_name
 * @property string $receive_uid
 * @property string $message
 * @property string $timestamp
 */
class Message extends CActiveRecord
{
	private $_pk = 'id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Message the static model class
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
		return '{{message}}';
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
			array('send_uid, send_name, receive_uid, message, timestamp', 'required'),
			array('send_uid, receive_uid, timestamp', 'length', 'max'=>10),
			array('send_uid, receive_uid, timestamp', 'numerical', 'integerOnly'=>true),
			array('send_name', 'length', 'max'=>30),
			array('message', 'length', 'max'=>255),
			array('flag', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, send_uid, send_name, receive_uid, message, timestamp', 'safe', 'on'=>'search'),
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
			'send_uid' => 'Send Uid',
			'send_name' => 'Send Name',
			'receive_uid' => 'Receive Uid',
			'message' => 'Message',
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
		$criteria->compare('send_uid',$this->send_uid,true);
		$criteria->compare('send_name',$this->send_name,true);
		$criteria->compare('receive_uid',$this->receive_uid,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}