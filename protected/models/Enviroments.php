<?php

/**
 * This is the model class for table "enviroments".
 *
 * The followings are the available columns in table 'enviroments':
 * @property integer $id
 * @property string $endpoint
 * @property string $user
 * @property string $pass
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $user_id
 * @property boolean $ssl
 */
class Enviroments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Enviroments the static model class
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
		return 'am_enviroments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('endpoint, user, pass, name, description, created_at', 'required'),
			array('created_at, user_id,ssl', 'numerical', 'integerOnly'=>true),
			array('endpoint', 'length', 'max'=>512),
			array('user, pass, name', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, endpoint, user, pass, name, description, created_at', 'safe', 'on'=>'search'),
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
			'endpoint' => 'Endpoint',
			'user' => 'User',
			'pass' => 'Password',
			'name' => 'Name',
			'description' => 'Description',
			'created_at' => 'Created At',
			'user_id' => 'User',
			'ssl' => 'SSL',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('endpoint',$this->endpoint,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('pass',$this->pass,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ssl',$this->ssl);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}