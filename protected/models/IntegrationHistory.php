<?php

/**
 * This is the model class for table "{{integration_history}}".
 *
 * The followings are the available columns in table '{{integration_history}}':
 * @property string $id
 * @property integer $integration
 * @property string $flag
 * @property string $primary_key
 * @property string $timestamp
 */
class IntegrationHistory extends CActiveRecord
{
	private $_pk = 'id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IntegrationHistory the static model class
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
		return '{{integration_history}}';
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
			array('integration, flag, primary_key, timestamp', 'required'),
			array('integration', 'numerical', 'integerOnly'=>true),
			array('flag', 'length', 'max'=>1),
			array('primary_key, timestamp', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, integration, flag, primary_key, timestamp', 'safe', 'on'=>'search'),
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
			'integration' => 'Integration',
			'flag' => 'Flag',
			'primary_key' => 'Primary Key',
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
		$criteria->compare('integration',$this->integration);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('primary_key',$this->primary_key,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}