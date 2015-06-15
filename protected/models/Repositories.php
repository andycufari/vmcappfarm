<?php

/**
 * This is the model class for table "am_repositories".
 *
 * The followings are the available columns in table 'am_repositories':
 * @property integer $id
 * @property string $name
 * @property integer $description
 * @property string $repo_url
 * @property string $type
 * @property string $repo_user
 * @property string $repo_passwd
 * @property string $key_file
 * @property integer $user_id
 */
class Repositories extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Repositories the static model class
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
		return 'am_repositories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, repo_url, type,user_id', 'required'),
			
			array('name, key_file', 'length', 'max'=>255),
			array('repo_url', 'length', 'max'=>512),
			array('description', 'length', 'max'=>512),
			array('type', 'length', 'max'=>8),
			array('repo_user, repo_passwd', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, repo_url, type, repo_user, repo_passwd, key_file,user_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'description' => 'Description',
			'repo_url' => 'Repository URL',
			'type' => 'Type',
			'repo_user' => 'User',
			'repo_passwd' => 'Password',
			'key_file' => 'Key File',
			'user_id' => 'User ID',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description);
		$criteria->compare('repo_url',$this->repo_url,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('repo_user',$this->repo_user,true);
		$criteria->compare('repo_passwd',$this->repo_passwd,true);
		$criteria->compare('key_file',$this->key_file,true);
		$criteria->compare('user_id',$this->user_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}