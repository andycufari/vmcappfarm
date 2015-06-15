<?php

class DoProvisioning{
	public $app; //objeto de aplicación
	public $vmc;

	private $dblink;
	public $msg_log;
	public $f_error;
	public $services;
	public $error;
	public $app_domain;
	public $initialize_log;
	public $adduser_log;
	public $deluser_log;
	public $manifest_json;

	public $status;
	public $provisioningstate;

	public $OAUTH_CLIENT_HOST;
	public $OAUTH_CLIENT_USE_SSL;
	public $OAUTH_CLIENT_URL_TOKEN;
	public $OAUTH_CLIENT_URL_AUTHORIZE;
	

	public function __construct($obj_app){
		global $dblink;
		global $msg_log;

		/*$this->OAUTH_CLIENT_HOST = 'auth.red.enncloud.com';
		$this->OAUTH_CLIENT_USE_SSL = 1;
		$this->OAUTH_CLIENT_URL_TOKEN = '/oauth2/token';
		$this->OAUTH_CLIENT_URL_AUTHORIZE = '/oauth2/authorize';*/
		
		$this->SHELL_PATH = './shell/';
		$this->TMP_PATH = './tmp/';
		
		$this->initialize_log ="";
		$this->adduser_log = "";
		$this->deluser_log = "";
		$this->LOG_FILE = 'provisioning.log';
		$this->msg_log = $msg_log;

		$this->dblink = $dblink;

		$this->app = $obj_app;
		$this->vmc = new VMCPHP();
		//seteo el dominio
		$this->app_domain = str_replace("api", $this->app->appcode, $this->app->endpoint);
		$endpoint = "http://".$this->app->endpoint;
		$this->log("Connecting to platform...");
		$this->vmc->target = $endpoint;
		$this->vmc->login($this->app->user,$this->app->pass);
		//verifico la conexión

		if(!isset($this->vmc->user)){
			$this->log("Trying to connect...2");
			$this->vmc->login($this->app->user,$this->app->pass);
			if(!isset($this->vmc->user)){
				$this->vmc->login($this->app->user,$this->app->pass);
				$this->log("Trying to connect...3");
				if(!isset($this->vmc->user)){
					return $this->return_error("Error: Connection refused, endpoint: $endpoint,user:".$this->app->user.",pass:******");	
				}
				
			}
	       
	    }
	}


	public function do_full_provisioning(){
		
		$this->log("Starting full application deploy...");

		if(!$this->create_services()){
			$this->delete_services();
			return $this->return_error("Fatal Error creating services...");
		}

		$manifest = $this->generate_manifest();

		if(!$this->create_app($manifest)){
			$this->delete_services();
			return $this->return_error("Fatal Error creating application in platfom.");
		}
		//subo los archivos 
		if(!$this->upload_app()){
			return $this->return_error("Error while trying to upload application's files. Please try updating application.");
		}

		//compruebo el estado de la aplicacion
		$manifest = $this->vmc->app_info($this->app->appcode);
		if(!$manifest){
			sleep(20);	
			$manifest = $this->vmc->app_info($this->app->appcode);
			if(!$manifest){
				sleep(20);
				$manifest = $this->vmc->app_info($this->app->appcode);
			}
		}

		
		if($manifest["state"] != "STARTED"){
			//pruebo una vez mas arrancarla
			$this->start_app();
			sleep(20);
			$manifest = $this->vmc->app_info($this->app->appcode);
		}
		
		if($manifest["state"] != "STARTED"){
			return $this->return_error("Impossible to start application..\nSTATE:..".json_encode($manifest));
		}
		$this->log("Application start OK.\nStarting to execute web services...");

		//llamo a los procesos de la aplicación
		$this->call_install_app_ws();

		//llamo al proceso de alta usuario de la aplicacion
		$this->call_adduser_app_ws();

		$this->status = 1;
		return true;

	}

	/**
	 * function create services
	 * return true if services are created
	 * */

	public function create_services(){


		$this->log("Creating application services...");
		$this->services = array();
	    $query = "SELECT S.* FROM am_applications_cfservices ACS INNER JOIN am_cfservices S ON S.id = ACS.cfservice_id WHERE ACS.application_id = '".$this->app->id."' ";
	    	    
	    $result = mysql_query($query,$this->dblink);
	    $icont = 0;
	    
	    $num = mysql_num_rows($result);
	    $services_cf = array();
	    if($num > 1){
	    	$serv_ret = $this->vmc->services();
	    	
	    	foreach ($serv_ret as $service){
	    		array_push($services_cf, $service["name"]);	
	    	}
	    	
	    }
	    while($cfs = mysql_fetch_object($result)){
	    	$service_name = $cfs->cfname."-".$this->app->appcode;
	    	//array_push($services,array($cfs->cfname,$this->app->appcode."_".$cfs->cfname)); //$this->app->appcode."_".$cfs->cfname]["type"] = $cfs->cfname;
	    	if(in_array($service_name, $services_cf)){
	    		$this->log("Service already exists, service name:$service_name");
	    		$this->services[$service_name] =  array("type"=> $cfs->cfname); 
	    			//si ya existe no lo creo
	    		continue;
	    	}
	    	//array_push($this->services,array($service_name => $cfs->cfname));
	    	$ret = $this->vmc->create_service($cfs->cfname,$service_name);
	    	
	    	$arr_aux = json_decode($ret);
	    
	    	if((isset($arr_aux->code))&&($arr_aux->code == 503)){ //checkeo error en servicio!
	    		$this->log("Error in platform service,".$cfs->cfname."response:".$ret);
	    	}else{
	    		$this->services[$service_name] =  array("type"=> $cfs->cfname);
	    		$this->log("\nService ".$cfs->cfname.".. OK");
	    	}
	    		

	    }
	    
	    //@todo:analizar respuesta!
	    $this->log("Creating services...End");
	    return true;
	    
	}

	public function delete_services(){
		$this->log("Startin to delete services...");
		if(!isset($this->services)){
			$query = "SELECT S.* FROM am_applications_cfservices ACS INNER JOIN am_cfservices S ON S.id = ACS.cfservice_id WHERE ACS.application_id = '".$this->app->id."' ";
	    	    
	    	$result = mysql_query($query,$this->dblink);
	    	$services_cf = array();
	    	while($cfs = mysql_fetch_object($result)){	
	    		$service_name = $cfs->cfname."-".$this->app->appcode;
	    		$services_cf[$service_name] =  array("type"=> $cfs->cfname);
	    	}
	    	$this->services = $services_cf; 
	    }
	    //@todo: recuperar desde el manifest de la aplicación y borrarlos todos + merge con los del query
		foreach($this->services as $key => $value){
			$res = $this->vmc->delete_service($key);
			$this->log('Deleting service: '.$key.' response:'.$res);
		}
		$this->log("Services deleted...");
		return true;
	}


	public function create_app($manifest){
		$this->log("Creating application...");
		$appinfo = $this->vmc->app_info($this->app->appcode);
		if((!isset($appinfo["code"])) || ($appinfo["code"] != 301)){
			$this->log("Application exist so updating...");
			$res = $this->vmc->update_app($this->app->appcode,$manifest);
			$this->log("Update response:\n".json_encode($res));
			return 1;
		}
		$res = $this->vmc->create_app($this->app->appcode,$manifest);
		
		$this->log("Application created, response:\n".json_encode($res));
		if((isset($res["code"])) && ($res["code"] == 500)){
			$this->log("Error while creating application, response:".json_encode($res));
			return false;
		}
		return true;
	}


	public function call_install_app_ws(){
		$this->log("Executing application install web service...");
		if(!$this->app->initialize_app_url){
			$this->log("Install url not found at application config...");
			return 1;
		}
		
		$curl_parameters["cfkey"] = $this->app->cfkey;
		$json = $this->call_app_ws($this->app->initialize_app_url,$curl_parameters);
		$this->log_install = $json;
		$this->initialize_log = $this->curl_response;
		//tratar el result para verificar el ok y marcar el status y el mensaje de log!
	 	if($json){
	 		$arr_result = json_decode($json);	 		
	 		if($arr_result->status != "OK"){
	 			$this->log("Error in web service call.");
	 		}else{
	 			$this->log("Application installed OK.");
	 		}	
	 	}
	}


	public function call_adduser_app_ws(){
		$this->log("Executing application add_user web service...");
		if(!$this->app->adduser_url){
			$this->log("Add user url not found at application config...");
			return 1;
		}

		/*
		user_id=id
		user_name=name
		user_email=email
		user_admin=0|1
		*/
		$arr_aux = explode("-",$this->app->app_user);
		$oauth_user_id = $arr_aux[1];
		$curl_parameters["cfkey"] = $this->app->cfkey;
		$curl_parameters["user_id"] = $oauth_user_id;
		$curl_parameters["user_name"] = $this->app->app_username;
		$curl_parameters["user_email"] = $this->app->app_useremail;
		$curl_parameters["user_admin"] = 1;

		$json = $this->call_app_ws($this->app->adduser_url,$curl_parameters);
		$this->adduser_log = $this->curl_response;
		if($json){
	 		$arr_result = json_decode($json);	 		
	 		if((isset($arr_result->status))&&($arr_result->status == "OK")){
	 			$this->log("User added OK.");
	 		}else{
	 			$this->log("Error in web service call.");
	 			
	 		}	
	 	}

	}

	public function call_app_ws($url,$params){

		$curl_options = array(
			CURLOPT_URL            => 'http://'.$this->app_domain.$url,
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
	  	$this->curl_response = $json;
	  	if((!$json)||($httpcode != 200)){
	  		
	  		$this->curl_header_response = $httpcode;
	  		$this->log("Connectio failed at 'http://".$this->app_domain.$url."'.. Url not found or empty response. HTTP_CODE:".$httpcode);
	  		return 0;
	  	}
	  	$this->log("Service response: \n".$json);
	 	curl_close( $curl );
	 	return $json;

	}

	/*

	public function app_env(){
		/*
		$arraux = explode(',', $_POST["extra"]);
		if(count($arraux) != 2){
			echo "Error, malformed".
			die();
		}

		$var = $arraux[0];
		$val = $arraux[1];
		
		$manifest = $vmc->app_info($appname);
		
		["env"]=>
		  array(2) {
		    [0]=>
		    string(6) "hola=1"
		    [1]=>
		    string(6) "chau=1"
		  }

		$arr = array('hola=2','chau=3','nueva=1');
		$manifest["env"] = $arr;
		$ret = $vmc->update_app($appname,$manifest);
		
		//$command = $path.'/../provisioning/cf_app_env.sh '.$app->appcode.' env-add '.$env->endpoint.' '.$env->user.' '.$env->pass.' '.$var.'='.$val;
		//$res = shell_exec($command);
		
	}*/

	public function generate_manifest(){
		$this->log("Making application manifest...");
	    $manifest["name"] = $this->app->appcode;
	    $manifest["staging"]["model"] = $this->app->cfcode;
	    //$manifest["staging"]["stack"] = $this->app->cfcode;
	    $manifest["uris"][0] = $this->app_domain;
	    $manifest["instances"] = 1;
	    $manifest["resources"]["memory"] = $this->app->cfappframework_attr;
	    $manifest["env"][0] = 'OAUTH_CLIENT_ID='.$this->app->identifier;
	    $manifest["env"][1] = 'OAUTH_CLIENT_SECRET='.$this->app->secret;
	    $manifest["env"][2] = 'OAUTH_CLIENT_USE_SSL='.$this->OAUTH_CLIENT_USE_SSL;
	    $manifest["env"][3] = 'OAUTH_CLIENT_URL_TOKEN='.$this->OAUTH_CLIENT_URL_TOKEN;
	    $manifest["env"][4] = 'OAUTH_CLIENT_URL_AUTHORIZE='.$this->OAUTH_CLIENT_URL_AUTHORIZE;
	    $manifest["env"][5]	= 'OAUTH_CLIENT_HOST='.$this->OAUTH_CLIENT_HOST;
	    $manifest["env"][6] = 'cfkey='.$this->app->cfkey;
		
	    //servicios:!!!

	    $manifest["services"] = $this->services;
	    $this->log("The manifest: ".json_encode($manifest));
	    $this->log("OK...");
	    $this->manifest_json = json_encode($manifest);
	    return $manifest;

	}

	/*

--- 
applications:
  .:
    instances: 1
    framework:
      info:
        mem: 128M
        exec:
        description: PHP Application
      name: php
    services:
      mysql-d6d4f:
        type: mysql
      mongodb-96099:
        type: mongodb
    mem: 128M
    url: ${name}.${target-base}
    name: borrar2

	*/
	public function update_app(){
		
		$this->log("Updating application starts, application name: ".$this->app->appcode."...\n");
		//identifico el framework y entorno
		$endpoint = "http://".$this->app->endpoint;
		
		//actualizo o creo servicios
		if(!$this->create_services()){
			$this->return_error("Fatal error creating services.");
		}

	 	$manifest = $this->generate_manifest();
		//$manifest = $this->vmc->app_info($this->app->appcode);
		$res = $this->vmc->update_app($this->app->appcode,$manifest);
		$this->log('Updating application manifest, RESPONSE: '.$res);
		//subo los archivos 
		if(!$this->upload_app()){
			$this->return_error("Error while trying to upload application's files. Please try updating application.");
		}
		return true;
		//hago un start
		//$this->start_app();

	}

	public function start_app(){
		$this->log("Executing start command to application: ".$this->app->appcode."...");
		//$command = './cf_manage_app.sh '.$this->app->appcode.' start '.$this->app->endpoint.' '.$this->app->user.' '.$this->app->pass;
		//$res = shell_exec($command);
		//$this->log($res);
		
		$manifest = $this->vmc->app_info($this->app->appcode);
		if($manifest["state"] != "STARTED"){
			$manifest["state"] = 'STARTED';
			$this->vmc->update_app($this->app->appcode,$manifest);
			$this->log("Application STARTED");
		}
		$this->log("End start command.");
		return true;
	}

	public function stop_app(){
		
		$this->log("Executing stop command to application: ".$this->app->appcode."...\n");
		//$command = './cf_manage_app.sh '.$this->app->appcode.' stop '.$this->app->endpoint.' '.$this->app->user.' '.$this->app->pass;
		//$res = shell_exec($command);
		//$this->log($res);
		$manifest = $this->vmc->app_info($this->app->appcode);
		
		if($manifest["state"] != "STOPPED"){
			$manifest["state"] = 'STOPPED';
			$this->vmc->update_app($this->app->appcode,$manifest);
			$this->log("Application STOPPED");
		}
		$this->log("End stop command.");
		return true;
	}

	public function scale_app($scale){
		if($scale == "-1"){
			$this->log("Descale ");
		}else{
			$this->log("Scale ");
		}
		$this->log("application: ".$this->app->appcode."...\n");

		//$command = './cf_scale_app.sh '.$this->app->appcode.' '.$scale.' '.$this->app->endpoint.' '.$this->app->user.' '.$this->app->pass;
		//$res = shell_exec($command);
		//$this->log($res);
		$manifest = $this->vmc->app_info($this->app->appcode);
		$instances = $manifest["instances"] + $scale;
		if($instances < 0){
			$this->log("Instance number can't be negative.");
			return true;
		}
		$manifest["instances"] = $instances_actuales;

		$ret = $vmc->update_app($appname,$manifest);
		$this->log("End scale action.");
		return true;
	}

	public function upload_app(){

		$this->log('Upload application...');
		
		switch ($this->app->appfile_type) {
			case 'ZIP':
				$tmpfile = rand(1,1000).$this->app->appfile_path;
				$tmpfilepath = $this->TMP_PATH.$tmpfile;
				$M = getMongoFs();
				$this->log("Starting upload application Zip file ...");
				$file = "uploads/".$this->app->appfile_path;
				if(!$M->is_file($file)){
					$this->log("ERROR: Zip file not found, file:".$this->app->appfile_path);
					return false;
				}
				//export zip from mongodb
				
				$M->export($file,$tmpfilepath);
				//$script = $this->SHELL_PATH.'cf_upload_app.sh';
				
				
				//$command = $script.' '.$this->app->appcode.' '.$tmpfile.' '.$this->app->endpoint.' '.$this->app->user.' '.$this->app->pass;
				break;
			case 'SVN':
				if(!$this->app->repo_url){
					$this->log("ERROR: SVN not found, url:".$this->app->repo_url);
					return false;
				}
				$tmpfile = rand(1,1000).$this->app->appcode.".zip";
				$tmpfilepath = $this->TMP_PATH.$tmpfile;
				//$arr_svn_target = explode('|', $this->app->repo_url);
				$target_url = $this->app->repo_url;
				if($this->app->repo_user){
					$svnuser = "--username ".$this->app->repo_user;
				}else{
					$svnuser = "";
				}
				if($this->app->repo_passwd){
					$svnpass = "--password ".$this->app->repo_passwd;
				}else{
					$svnpass = "";
				}
				$script = $this->SHELL_PATH.'cf_svn_upload.sh';
				if(is_dir('tmp/'.$this->app->appcode.'/')){
					$mkdir = "";
				}else{
					$mkdir = "mkdir";
				}
				
				$command = $script.' '.$this->app->appcode.' '.$tmpfile.' '.$target_url.' '.$svnuser.' '.$svnpass.' '.$mkdir;		
				$this->log("COMMAND: $command");
				$this->log("Packing....".$tmpfilepath);
				$res = system($command);
				//$this->log($res);
				break;
			case 'GIT':
				if(!$this->app->repo_url){

					$this->log("ERROR: GIT file not found, url:".$this->app->repo_url);
					return false;
				}
				if($this->app->key_file){
					$M = getMongoFs();
					$file = "uploads/".$this->app->key_file;
					if(!$M->is_file($file)){
						$this->log("ERROR: GIT KEY file not found, FILE:".$this->app->key_file);
						return false;
					}

					$tmpkeyfile = "git-key";
					$tmpkeyfilepath = $this->TMP_PATH.$tmpkeyfile;
					$tmpfile = rand(1,1000).$this->app->appcode.".zip";
					$tmpfilepath = $this->TMP_PATH.$tmpfile;
					$M->export($file,$tmpkeyfilepath);
					if(!is_file($tmpkeyfilepath)){
						$this->log("ERROR: GIT KEY export not found, FILE:".$tmpkeyfilepath);
						return false;
					}
					$key_contents = file_get_contents($tmpkeyfilepath);
					if(!strpos($key_contents, "BEGIN RSA PRIVATE KEY")) {
						$this->log("ERROR: GIT KEY GIVEN is not valid!");
						return false;
					}
					
					$ch = chmod($this->TMP_PATH, 0777);	
					if(!$ch){
						$this->return_error("PERMISIONS PROBLEMS");
					}
					$ch = chmod($tmpkeyfilepath, 0700);
					if(!$ch){
						$this->return_error("PERMISIONS PROBLEMS");
					}
					chmod($this->SHELL_PATH.'ssh_git_key',0777);
					//$this->log("CREANDO DIRECTORIO!!!!.....".$this->TMP_PATH.$this->app->appcode);
					//if(!mkdir($this->TMP_PATH.$this->app->appcode)){
					//	$this->log("ERROR: CAN NOT CREATE DIRECTORY!".$this->TMP_PATH.$this->app->appcode);
					//	return false;
					//}
					$script = $this->SHELL_PATH.'cf_git_upload_key.sh '.$this->app->repo_url.' '.$this->TMP_PATH.$this->app->appcode.' '.$tmpfile;
					$this->log("CALLING GIT SCRIPT: ".$script);
					$res = system($script);
					$this->log($res);
					if(is_dir($this->TMP_PATH.$this->app->appcode)){
						$script = $this->SHELL_PATH.'git_zip.sh '.$this->TMP_PATH.$this->app->appcode.' '.$tmpfile;
						$this->log("CALLING GIT ZIP: ".$script);
						$res = system($script);
						$this->log($res);
					}else{
						$this->return_error("ERROR IN GIT CLONE");
					}

				}else{
					$script = $this->SHELL_PATH.'cf_git_upload.sh';
					$command = $script.' '.$this->app->appcode.' '.$this->app->repo_url.' '.$tmpfile;	
					$this->log("uploading....");
					$res = system($command);
					$this->log($res);
				}
				
				
				break;
			default:
				# code...
				break;
		}
		//hago el upload de los archivos de la app...
		if(!is_file($tmpfilepath)){
			return $this->return_error("ERROR EN ZIP..");
		}
		$this->log("Uploading....".$tmpfilepath);
		
		$res = $this->vmc->upload_app($this->app->appcode,$tmpfilepath);
		
		if($res != 1){
			return $this->return_error("Error in UPLOAD!: $res");
		}
		
		unlink($tmpfilepath);
		$this->stop_app();
		sleep(10);
		$this->start_app();
		sleep(30);
		//checkeo que haya subido files!!
		//$files_str = $this->vmc->app_files($this->app->appcode,'app');
		
		//$this->log($res);
		//if(json_decode($files_str) != NULL){
		//	return false;
		//}

		// me llena de datos el log @todo: pasar a un archivo todo el log
		$this->log("application uploaded successfully.");

		$this->log('End upload.');
		return true;
	}

	public function delete_app(){
		
		$this->log("Starting delete, application name: ".$this->app->appcode."...");
		
		$res = $this->vmc->delete_app($this->app->appcode);
		$this->log($res);
		
		$this->status = 9;
		$this->delete_services();
		$this->log("Application deleted... OK");
		return true;
	}


	public function save_app(){

		$time =  time();
		$this->provisioningstate = 0;
		if($this->deluser_log != ""){
			$this->deluser_log =  mysql_real_escape_string($this->deluser_log);	
		}else{
			$this->deluser_log = mysql_real_escape_string($this->app->deluser_log);
		}

		if($this->manifest_json){
			$manifest = $this->manifest_json;
		}else{
			$manifest = $this->app->manifest;
		}
		
		$this->msg_log = mysql_real_escape_string($this->msg_log);
		if($this->initialize_log != ""){
			$this->initialize_log = mysql_real_escape_string($this->initialize_log);	
		}else{
			$this->initialize_log = $this->app->install_log;
		}
		if($this->adduser_log != ""){
			$this->adduser_log = mysql_real_escape_string($this->adduser_log);	
		}else{
			$this->adduser_log = $this->app->adduser_log;
		}
		$query = "UPDATE am_applications SET status='".$this->status."',provisioningstate='".$this->provisioningstate."' WHERE id='".$this->app->id."'";
		

		mysql_query($query,$this->dblink);
		$query = "UPDATE am_applications SET provisioning_log='".$this->msg_log."',adduser_log='".$this->adduser_log."',install_log='".$this->initialize_log."',deluser_log='".$this->deluser_log."',updated_at='".$time."',manifest='".$manifest."' WHERE id='".$this->app->id."'";
		
		mysql_query($query,$this->dblink);

		return 1;
	}

	public function log($txt){
		$this->msg_log.= "\n".$txt;
		echo "\n".$txt;
	}

	public function return_error($txt){
		$this->msg_log .= "\n".$txt;
		$this->f_error = 1;
		$this->status = -1;
		$this->provisioningstate = 0;
		$this->error = $txt;
		$this->save_app();
		echo "\n".$txt;
		return false;
	}


}

