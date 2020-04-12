<?php

/**
 * This is the model class for table "{{shop_comment}}".
 *
 * The followings are the available columns in table '{{shop_comment}}':
 * @property string $id
 * @property string $uid
 * @property string $username
 * @property string $avatar
 * @property string $content
 * @property string $parent_id
 * @property integer $status
 * @property string $timestamp
 */
class ShopComment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShopComment the static model class
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
		return '{{shop_comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('status', 'boolean'),
			array('uid, shop_id,parent_id, timestamp', 'length', 'max'=>10),
			array('timestamp, shop_id,parent_id, timestamp', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>30),
			array('avatar, content', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, username, avatar, content, parent_id, status, timestamp', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'avatar' => 'Avatar',
			'content' => 'Content',
			'parent_id' => 'Parent',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}