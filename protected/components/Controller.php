<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

define('ADMIN', 1);
define('REGULAR_USER',0);

class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $userData;

	private $_conf;

	public function init() {
    
	    // Load the user
	    if (!Yii::app()->user->isGuest)
	        //$this->userData = Users::model()->findByPk(Yii::app()->user->id);
	        $this->userData = Yii::app()->user->getUserData();
	    
	}

	public function allowUser($min_level) { //-1 no login required 0..3: admin level
	    $current_level = -1;
	    if ($this->userData !== null)
	        $current_level = $this->userData->admin_level;
	    if ($min_level > $current_level) {
	        throw new CHttpException(403, 'You have no permission to view this content');
	    }
	}

	public function isAdmin(){
		if($this->userData->admin_level == ADMIN){
			return 1;
		}else{
			return 0;
		}
	}

	public function getConfByKey($key){
		if(!isset($this->_conf[$key])){
			//->find('postID=:postID', array(':postID'=>10));
			$criteria=new CDbCriteria;
			$criteria->select='value';  // only select the 'title' column
			$criteria->condition="t.key='".$key."'";
			
			$model=Config::model()->find($criteria); // $params is not needed
			//$model = Config::Model()->find('"key"=:key',array(':key'=>$key));
			$this->_conf[$key] = $model->value;
		}
		return $this->_conf[$key];
	}


	public function getMongoFs(){
		$json = getenv("VCAP_SERVICES");
		$varphp = json_decode($json);
		if((isset($varphp))&&($varphp)){
			$host = $varphp->{"mongodb-1.8"}[0]->{"credentials"}->{"hostname"};
			$pass = $varphp->{"mongodb-1.8"}[0]->{"credentials"}->{"password"}; 
			$user = $varphp->{"mongodb-1.8"}[0]->{"credentials"}->{"username"}; 
			$port = $varphp->{"mongodb-1.8"}[0]->{"credentials"}->{"port"}; 
			$dbname = $varphp->{"mongodb-1.8"}[0]->{"credentials"}->{"name"}; 

			$mongohost = 'mongodb://'.$user.':'.$pass.'@'.$host.':'.$port.'/db';
		}else{
			$mongohost = 'mongodb://localhost:27017';
		}

		$connection = new Mongo($mongohost);
		$db = $connection->selectDB('db');
		$M = new MongoFs($db);
		return $M;

	}

	public function getRedisConfig(){
		$json = getenv("VCAP_SERVICES");
        $return = array();
        if((isset($json))&&($json)){
	        $varphp = json_decode($json);
			$return["host"] = $varphp->{"redis-2.2"}[0]->{"credentials"}->{"hostname"};
	        $return["port"] = $varphp->{"redis-2.2"}[0]->{"credentials"}->{"port"};
	        $return["dbname"] = $varphp->{"redis-2.2"}[0]->{"credentials"}->{"name"};
	        $return["pass"] = $varphp->{"redis-2.2"}[0]->{"credentials"}->{"password"};
	        return $return;
	    }else{
	    	return '127.0.0.1:6379';
	    }
        //Resque::setBackend("{$this->_redis_host['host']}:{$this->_redis_host['port']}", $this->_db);
        //Resque::redis()->auth($this->_redis_host['pass']);

	}

}