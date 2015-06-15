<?php

class ApplicationsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	private $curl_response;

	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','upload','index','view','admin','ajaxmanage','manage','files','viewfile','checkprovstatus'),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Upload file
	 * 
	 */
	public function actionUpload(){
		Yii::import("ext.EAjaxUpload.qqFileUploader");

	    $folder='uploads/';// folder for uploaded files
	    $allowedExtensions = array("zip");//,//array("jpg","jpeg","gif","exe","mov" and etc...
	    $sizeLimit = 39 * 1024 * 1024;// maximum file size in bytes
	    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	    $result = $uploader->handleUpload($folder);
	    $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	    echo $result;// it's array
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */

	public function actionCreate()
	{
		//$this->allowUser(ADMIN); 
		$model=new Applications;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//listo los framworks
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_cfappframeworks');
		$cfframeworks = $command->queryAll();
		
		//listo los entornos
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_enviroments'); 
		$command->where('user_id='.Yii::app()->user->getId());
		$enviroments = $command->queryAll();

		//listo los servicios
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_cfservices'); //@todo: filtrar por usuario
		$services = $command->queryAll();
		$appservices = array();

		///listo los repositorios
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_repositories'); 
		$command->where('user_id='.Yii::app()->user->getId());
		$repositories = $command->queryAll();
		/*
		$cfframeworks = cfappframeworks::model()->findAll(
                 array('order' => 'name'));

		$frameworks_list = CHtml::listData($cfframeworks, 
                'id', 'name');
		*/
		if(isset($_POST['Applications']))
		{

			$length = 6;
			$chars = array_merge(range(0,9), range('a','z'));
			shuffle($chars);
			$appcode = implode(array_slice($chars, 0, $length));
			if(!isset($_POST['Applications']['appcode'])){
				$_POST['Applications']['appcode'] = "enncloud-dev-".$appcode;	
			}
			$_POST['Applications']['created_at'] = time();
			$_POST['Applications']['updated_at'] = time();
			$_POST['Applications']['user_id'] = Yii::app()->user->getId();
			$request = Yii::app()->request;

			$cfservices_arr = $request->getPost('services', 0);

			$model->attributes=$_POST['Applications'];

			
			if($model->save()){
				$model->save_client();
				if($cfservices_arr){ ///guardo los cfservices asociados a la appp
					$model->save_cfservices_rels($cfservices_arr);	
				}
				

				$this->redirect(array('admin'));//,'id'=>$model->id));
			}
				
		}


		$this->render('create',array(
			'model'=>$model,
			'cfframeworks' =>$cfframeworks,
			'enviroments' => $enviroments,
			'services' => $services,
			'appservices' => $appservices,
			'repositories' => $repositories,
			
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(($model->user_id != Yii::app()->user->getId())&&(!$this->isAdmin())){
			echo "Error, not allowed";
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//listo los framworks
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_cfappframeworks');
		$cfframeworks = $command->queryAll();
		
		//listo los entornos
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_enviroments'); //@todo: filtrar por usuario
		$command->where('user_id='.Yii::app()->user->getId());
		$enviroments = $command->queryAll();

		//listo los servicios
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_cfservices'); //@todo: filtrar por usuario
		$services = $command->queryAll();

		//traigo los servicios asociados si los tiene
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_applications_cfservices'); 
		$command->where('application_id='.$model->id);
		$appserv_result = $command->queryAll();
		
		$appservices = array();

		///listo los repositorios
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_repositories'); 
		$command->where('user_id='.Yii::app()->user->getId());
		$repositories = $command->queryAll();
		
		foreach ($appserv_result as $s) {
			array_push($appservices, $s['cfservice_id']);
		}
		
		if(isset($_POST['Applications']))
		{
			$model->attributes=$_POST['Applications'];
			$request = Yii::app()->request;
			$cfservices_arr = $request->getPost('services', 0);

			if($model->save()){
				if($cfservices_arr){
					$model->save_cfservices_rels($cfservices_arr);	
				}
			}
			$this->redirect(array('admin'));//,'id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model, 'cfframeworks' =>$cfframeworks, 'enviroments'=>$enviroments,'services'=>$services, 'appservices'=>$appservices,'repositories' =>$repositories
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Applications');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function do_provisioning($appid,$background = 1){
		//create our client object
		//$gmclient= new GearmanClient();
		
		// add the default server (localhost)
		//@todo: meter configuracion parametrizada
		//'127.0.0.1',43514
		/*$gmclient->addServer('127.0.0.1',4730);
		if($background){
			# run reverse client in the background
			$job_handle = $gmclient->doBackground("provisioning", $appid);
	
		}else{
			$job_handle = $gmclient->do("provisioning", $appid);		
		}
		
		if ($gmclient->returnCode() != GEARMAN_SUCCESS)
		{
		  echo "bad return code\n";
		  exit;
		}
		return $job_handle;
		*/
		$config = $this->getRedisConfig();
		if(is_array($config)){
			Resque::setBackend($config["host"].":".$config["port"]);
        	Resque::redis()->auth($config["pass"]);
		}else{
			Resque::setBackend($config);
		}

		$args = array(
			'time' => time(),
			'array' => array(
				'appid' => $appid,
				'OAUTH_CLIENT_HOST' => getenv("OAUTH_CLIENT_HOST"),
				'OAUTH_CLIENT_USE_SSL' => getenv("OAUTH_CLIENT_USE_SSL"),
				'OAUTH_CLIENT_URL_AUTHORIZE' => getenv("OAUTH_CLIENT_URL_AUTHORIZE"),
				'OAUTH_CLIENT_URL_TOKEN' => getenv("OAUTH_CLIENT_URL_TOKEN")

			),
		);

		$resque_token = Resque::enqueue('provisioning', 'Provisioning', $args, true);
		return $resque_token;
		//echo "Queued job ".$jobId."\n\n";
	}

	public function actionAjaxmanage(){

		$action = Yii::app()->request->getPost('action',0);
		$appid = Yii::app()->request->getPost('id',0);
		$data = Yii::app()->request->getPost('data',0);
		if((!$action)||(!$appid)){
			echo "ERROR";
			die();
		}

		$vmc = new VMCPHP;
		$app = $this->loadModel($appid);

		if(($app->user_id != Yii::app()->user->getId())&&(!$this->isAdmin())){
			echo "Error, not allowed";
		}


		$env = Enviroments::model()->findByPk($app->enviroment_id);
		
		$vmc->target = 'http://'.$env->endpoint;
		$app_domain = str_replace('api', $app->appcode, $env->endpoint);
		$vmc->login($env->user,$env->pass);
		if(!$this->checkCfConnection($vmc,$env)){
			echo "Error en conexión con plataforma. Intente nuevamente.\nSi el problema persiste contacte con el administrador del sistema.";
			die();
		}
		//$vmc_manifest = $vmc->app_info($app->appcode);
		switch ($action) {
			case 'add_service':
				if(!$data){
					echo "Service not found".
					die();
				}
				//traigo los servicios asociados si los tiene
				$command= Yii::app()->db->createCommand();
				$command->select('id');
				$command->from('am_applications_cfservices'); 
				$command->where('application_id='.$app->id);
				$appserv_result = $command->queryAll();
				$appservices = array();
				foreach ($appserv_result as $s) {
					if($s['cfservice_id'] == $data){
						echo "Service already exists in application.";
						die();
					}
				}

				$cfserv = Cfservices::model()->findByPk($data);
				//echo "---->".$data."-->".$cfserv->cfname;
				
				//die();
				$sname = $cfserv->cfname."-".$app->appcode;
				$ret = $vmc->create_service($cfserv->cfname,$sname);
				$arr_aux = json_decode($ret);
	    		if((isset($arr_aux->code))&&($arr_aux->code == 503)){ //checkeo error en servicio!
	    			echo "Error in platform service,".$cfserv->cfname." Response:".$ret;
	    			die();
				}
				$vmc->bind_service($sname,$app->appcode);
				$app->add_cfservice_rel($data);
				echo "refresh";
				break;

			case 'delete_service':
				if(!$data){
					echo "Service not found".
					die();
				}
				$vmc->delete_service($data);
				$vmc->unbind_service($data,$app->appcode);
				$arr_aux = explode("-",$data);
				$command= Yii::app()->db->createCommand();
				$command->select('id');
				$command->from('am_cfservices'); 
				$command->where("cfname='".$arr_aux[0]."'");
				$appserv_result = $command->queryAll();
				$app->del_cfservice_rel($appserv_result[0]["id"]);
				echo "refresh";
				break;
			case 'update':
				$app->provisioningstate = 2;
				$app->status = 0;
				$app->save();
				$this->do_provisioning($app->id);
				echo "refresh";
				break;

			case 'reset':
				$app->provisioningstate = 0;
				$app->status = 0;
				$app->save();
				echo "refresh";
				break;

			case 'deploy':
				$app->provisioningstate = 1;
				$app->status = 0;
				$app->save();
				$this->do_provisioning($app->id);
				echo "refresh";
				break;

			case 'stop':
				$vmc->app_stop($app->appcode);
				$app->provisioningstate = 0;
				$app->status = 3;
				$app->save();
				echo "Application STOPED!";
				break;
			case 'start':
				$vmc->app_start($app->appcode);
				$app->provisioningstate = 0;
				$app->status = 1;
				$app->save();
				echo "Application STARTED!";
				break;
			case 'scale':
				$vmc->app_instances_scale($app->appcode);
				$app->provisioningstate = 0;
				$app->status = 1;
				$app->save();
				echo "Application instances ".$instances_actuales;
				break;
			case 'descale':
				$ret = $vmc->app_instances_descale($app->appcode);
				if(!$ret){
					echo "Instance number can't be negative.";
				}else{
					$app->provisioningstate = 0;
					$app->status = 1;
					$app->save();
					echo "Application instances ".$instances_actuales;	
				}
				break;
			case 'delete':
				$app->provisioningstate = 9;
				$app->status = 0;
				$app->save();
				$this->do_provisioning($app->id);
				echo "refresh";
				break;
			case 'log':
				//echo urlencode( str_replace( "\n", "\\n", $app->provisioning_log ) );  
				echo htmlentities($app->provisioning_log);
				//$logshow = str_replace("\\n", "&lt;br>", $app->provisioning_log);
				//echo stripslashes($logshow);
				break;
			
			case 'add_env':
				if(!$data){
					echo "Enviroment variable note defined.";
					die();
				}
				$ret = $vmc->app_addenv($app->appcode,$data);
				if(!$ret){
					echo "Enviroment variable already exists.";
				}else{
					echo "refresh";	
				}
				break;
			case 'del_env':
				if(!$data){
					echo "Enviroment variable note defined.";
					die();
				}
				$ret = $vmc->app_delenv($app->appcode,$data);
				if(!$ret){
					echo "Enviroment variable not exists.";
				}else{
					echo "refresh";	
				}
				break;
			
			case 'install_log':
				echo $app->install_log;
				break;
			case 'install':
				
				$ret = $this->curl2Application($app,$app_domain.$app->initialize_app_url,array());
				$app->install_log = $ret;
				$app->save();
				echo $ret;
				break;
			case 'adduser':
				$user = Users::model()->findByPk(Yii::app()->user->getId());
				$arr_aux = explode("-",$user->user);
				$oauth_user_id = $arr_aux[1];
				
				$curl_parameters["user_id"] = $oauth_user_id;
				$curl_parameters["user_name"] = $user->username;
				$curl_parameters["user_email"] = $user->email;
				$curl_parameters["user_admin"] = 1;

				$ret = $this->curl2Application($app,$app_domain.$app->adduser_url,$curl_parameters);
				$app->adduser_log = $ret;
				$app->save();
				echo $ret;
				break;
			case 'adduser_log':
				echo $app->adduser_log;
				break;
			case 'deluser':
				$user = Users::model()->findByPk(Yii::app()->user->getId());
				$arr_aux = explode("-",$user->user);
				$oauth_user_id = $arr_aux[1];
				
				$curl_parameters["user_id"] = $oauth_user_id;
				//$curl_parameters["user_name"] = $user->username;
				//$curl_parameters["user_email"] = $user->email;
				$curl_parameters["user_admin"] = 1;

				$ret = $this->curl2Application($app,$app_domain.$app->deluser_url,$curl_parameters);
				$app->adduser_log = $ret;
				$app->save();
				echo $ret;
				break;
			case 'deluser_log':
				echo $app->deluser_log;
				break;
			default:
				echo 0;
				break;
		}

	}

	public function actionCheckprovstatus(){
		
		$appids = Yii::app()->request->getPost('appid', 0);
		$array_app = explode(",",$appids);
		foreach($array_app as $a){
			if(is_numeric($a)){
				$app = $this->loadModel($a);
				if($app->provisioningstate == 0){
					echo 1;
					die();
				}
			}
		}
		echo 0;

	}


	public function checkCfConnection($vmc,$env){
		if(!$vmc->login($env->user,$env->pass)){

			if(!$vmc->login($env->user,$env->pass)){
				
				if(!$vmc->login($env->user,$env->pass)){
					return false;
				}
			}
		}
		return true;
	}

	public function curl2Application($app,$url,$params){
		$params["cfkey"] = $app->cfkey;
		$curl_options = array(
			CURLOPT_URL            => 'http://'.$url,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => http_build_query( $params ),
			CURLOPT_HTTP_VERSION   => 1.0,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => 1,
	  	);

	  	$curl = curl_init();
	  	curl_setopt_array( $curl, $curl_options );
	  	$json = curl_exec( $curl );
	  	//analizo la cabecera
	  	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	  	$this->curl_response = $httpcode;
	  	//echo $httpcode;
	  	//echo $json;
	  	if((!$json)||($httpcode != 200)){
	  		//return 0;
	  	}
	 	curl_close( $curl );
	 	return $json;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Applications();
		$model->unsetAttributes(); 
		if(!Yii::app()->user->isAdmin()){
			$model->user_id = Yii::app()->user->getId();
		}
		$model->search();
		 // clear any default values
		if(isset($_GET['Applications']))
			$model->attributes=$_GET['Applications'];

		//listo los servicios

		if($this->isAdmin()){
			$where = 1;
		}else{
			$where = 'A.user_id ='.Yii::app()->user->getId();
		}
		$command= Yii::app()->db->createCommand('SELECT A.*,E.endpoint,E.name as enviroment_name,CF.name AS framework  FROM am_applications A 
			INNER JOIN am_cfappframeworks CF ON CF.id = A.cfappframework_id
			INNER JOIN am_enviroments E ON E.id = A.enviroment_id
			 WHERE '.$where);
		$apps = $command->query();

		$this->render('admin',array(
			'model'=>$model, 'apps' => $apps,
		));
	}

	private function getFiles($dir = 1){
		$appid = Yii::app()->request->getQuery('id',0);
		$path = Yii::app()->request->getQuery('path','app');
		$path = str_replace("//", "/", $path);
		if((!$appid)||(!is_numeric($appid))){
			echo "ERROR";
			die();
		}

		$vmc = new VMCPHP;
		$app = $this->loadModel($appid);

		//valido usuario
		if(Yii::app()->user->getId() != $app->user_id){
			echo "error, you are not allowed. ";
			die();
		}

		$return_data = array();

		$env = Enviroments::model()->findByPk($app->enviroment_id);
		
		$vmc->target = 'http://'.$env->endpoint;
		$vmc->login($env->user,$env->pass);

		if(!$this->checkCfConnection($vmc,$env)){
			//echo "Error en conexión con plataforma. Intente nuevamente.\nSi el problema persiste contacte con el administrador del sistema.";
			$platform["vmc_status"] = -1;
			$platform["files"] = 0;

			$cfinfo = array();
			
		}else{
			
			$cfinfo = $vmc->app_info($app->appcode);
			
			if(!isset($cfinfo["code"])){
				$platform["vmc_status"] = 1;
			}else{
				$platform["vmc_status"] = 0;
			}
			$files_str = $vmc->app_files($app->appcode,$path);
			$platform["files"] = 1;
			if(json_decode($files_str) != NULL){
				$platform["files"] = 0;
			}	
			if($dir){
				$platform["arrfiles"] = explode("\n", $files_str);
			}else{
				$platform["strfile"] = $files_str;
			}
			
			$platform["path"] = $path;
			
			
		}
		$return_data["app"] = $app;
		$return_data["platform"] = $platform;
		$return_data["cfinfo"] = $cfinfo;
		return $return_data;
 	}

	public function actionViewfile(){
		$ret = $this->getFiles(0);

		$this->render('v-file',array(
			'app' => $ret["app"], 'cfinfo' => $ret["cfinfo"],'platform' => $ret["platform"]
		));
	}


	public function actionFiles(){
		$ret = $this->getFiles();

		$this->render('files',array(
			'app' => $ret["app"], 'cfinfo' => $ret["cfinfo"],'platform' => $ret["platform"]
		));

	}


	public function actionManage(){

		$appid = $_GET["id"];

		if((!$appid)||(!is_numeric($appid))){
			echo "ERROR";
			die();
		}

		$vmc = new VMCPHP;
		$app = $this->loadModel($appid);

		//valido usuario
		if(Yii::app()->user->getId() != $app->user_id){
			echo "error, you are not allowed. ";
			die();
		}


		$env = Enviroments::model()->findByPk($app->enviroment_id);
		$platform["entorno"] = $env->name;
		$vmc->target = 'http://'.$env->endpoint;
		$vmc->login($env->user,$env->pass);

		//listo los servicios
		$command= Yii::app()->db->createCommand();
		$command->select('*');
		$command->from('am_cfservices'); //@todo: filtrar por usuario
		$platform["services"] = $command->queryAll();

		if(!$this->checkCfConnection($vmc,$env)){
			//echo "Error en conexión con plataforma. Intente nuevamente.\nSi el problema persiste contacte con el administrador del sistema.";
			$platform["vmc_status"] = -1;
			$platform["files"] = 0;
			$cfinfo = array();
			
		}else{
			
			$cfinfo = $vmc->app_info($app->appcode);
			
			if(!isset($cfinfo["code"])){
				$platform["vmc_status"] = 1;
				$platform["entorno"] = $env->name;
			}else{
				$platform["vmc_status"] = 0;
			}
			$files_str = $vmc->app_files($app->appcode,'app');
			$platform["files"] = 1;
			if(json_decode($files_str) != NULL){
				$platform["files"] = 0;
			}
			$error_conexion = 0;
		}
		$platform["app_domain"] = str_replace('api', $app->appcode, $env->endpoint);

		$this->render('manage',array(
			'app' => $app, 'cfinfo' => $cfinfo,'platform' => $platform
		));


	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Applications::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='applications-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
