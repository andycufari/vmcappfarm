<?php

/**
 * This is the model class for table "applications".
 *
 * The followings are the available columns in table 'applications':
 * @property integer $id
 * @property string $appcode
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $initialize_app_url
 * @property string $version
 * @property integer $cfappframework_id
 * @property string $cfappframework_attr
 * @property string $appfile_path
 * @property string $appfile_type
 * @property integer $enviroment_id
 * @property integer $user_id
 * @property string $provisioning_log
 * @property integer $provisioningstate
 * @property integer $status
 * @property string $install_log
 * @property integer $client_id
 * @property integer $repository_id
 */
class Applications extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Applications the static model class
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
		return 'am_applications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('appcode, description, version, cfappframework_id, cfappframework_attr, appfile_type, enviroment_id', 'required'),
			array('created_at, updated_at, cfappframework_id, enviroment_id, user_id, provisioningstate, status,repository_id', 'numerical', 'integerOnly'=>true),
			array('cfappframework_attr, appfile_type', 'length', 'max'=>16),
			array('name, initialize_app_url', 'length', 'max'=>255),
			array('version', 'length', 'max'=>8),
			array('appfile_path', 'length', 'max'=>256),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, appcode, name, description, created_at, updated_at, initialize_app_url, version, cfappframework_id, cfappframework_attr, appfile_path, appfile_type, enviroment_id, user_id, provisioning_log, provisioningstate, status, install_log,repository_id', 'safe', 'on'=>'search'),
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
			'appcode' => 'Appcode',
			'name' => 'Name',
			'description' => 'Description',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'initialize_app_url' => 'Initialize App Url',
			'version' => 'Version',
			'cfappframework_id' => 'Framework',
			'cfappframework_attr' => 'RAM',
			'appfile_path' => 'Appfile Path',
			'appfile_type' => 'Appfile Type',
			'enviroment_id' => 'Enviroment',
			'user_id' => 'User',
			'provisioning_log' => 'Provisioning Log',
			'provisioningstate' => 'Provisioningstate',
			'status' => 'Status',
			'install_log' => 'Install Log',
		
			'client_id' => 'Client ID',
			'repository_id' => 'ID Repositorio',
			
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
		$criteria->compare('appcode',$this->appcode,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);
		
		$criteria->compare('initialize_app_url',$this->initialize_app_url,true);
		$criteria->compare('version',$this->version,true);
		$criteria->compare('cfappframework_id',$this->cfappframework_id);
		$criteria->compare('cfappframework_attr',$this->cfappframework_attr,true);
		$criteria->compare('appfile_path',$this->appfile_path,true);
		$criteria->compare('appfile_type',$this->appfile_type,true);
		$criteria->compare('enviroment_id',$this->enviroment_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('provisioning_log',$this->provisioning_log,true);
		$criteria->compare('provisioningstate',$this->provisioningstate);
		$criteria->compare('status',$this->status);
		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('repository_id',$this->repository_id);
		$criteria->compare('install_log',$this->install_log,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	
	public function save_cfservices_rels($arr_services){
		$command= Yii::app()->db->createCommand('DELETE FROM am_applications_cfservices WHERE application_id = '.$this->id);
		$command->execute();
		foreach ($arr_services as $s){
			$this->add_cfservice_rel($s);
		}
	}

	public function add_cfservice_rel($s){
		$command= Yii::app()->db->createCommand("INSERT INTO am_applications_cfservices (application_id,cfservice_id) VALUES('".$this->id."','".$s."')");
		$command->execute();
	}

	public function del_cfservice_rel($s){
		$command= Yii::app()->db->createCommand('DELETE FROM am_applications_cfservices WHERE application_id = '.$this->id.' AND cfservice_id='.$s);
		$command->execute();
	}

	public function save_client(){

		//guardo y relaciono el cliente de oauth
		if(!$this->client_id){
			$command= Yii::app()->db->createCommand('SELECT * FROM am_enviroments WHERE id = '.$this->enviroment_id);
			$row = $command->queryRow();
			if($row){
				$website = 'http://'.str_replace('api', $this->appcode, $row['endpoint']);
				//$website = 'http://'.$this->appcode.'.'.$row['endpoint'].'/';
				$redirect_url = $website.$this->oauth_return_url;
				$identifier = md5($this->appcode);
				$secret = sha1($this->appcode).$identifier;
				$created_at = date("Y-m-d H:i:s");
				$updated_at = date("Y-m-d H:i:s"); //2012-02-07 11:24:27
				$command = Yii::app()->db->createCommand("INSERT INTO oauth2_clients (name,redirect_uri,website,identifier,secret,created_at,updated_at) VALUES ('".$this->appcode."','$redirect_url','$website','$identifier','$secret','$created_at','$updated_at')");
				$command->execute();
				$client_id = Yii::app()->db->lastInsertID;
				$cfkey = md5($this->appcode.rand(100,1000));
				$command = Yii::app()->db->createCommand("UPDATE am_applications SET client_id='$client_id',cfkey='$cfkey' WHERE id='".$this->id."'");
				$command->execute();

			}
		}
		
	}
	
}