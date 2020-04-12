<?php

/**
 * This is the model class for table "idingcan_ext_users".
 *
 * The followings are the available columns in table 'idingcan_ext_users':
 * @property string $id
 * @property string $uid
 * @property string $user_uid
 * @property string $timestamp
 * @property string $flag
 * @property string $params
 */
class ExtUsers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ExtUsers the static model class
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
		return '{{ext_users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, timestamp, flag', 'required'),
			array('uid', 'length', 'max'=>32),
			array('user_uid, timestamp', 'length', 'max'=>10),
			array('flag', 'length', 'max'=>1),
			array('params', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, user_uid, timestamp, flag, params', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'user_uid' => 'User Uid',
			'timestamp' => 'Timestamp',
			'flag' => 'Flag',
			'params' => 'Params',
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
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('user_uid',$this->user_uid,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('params',$this->params,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}