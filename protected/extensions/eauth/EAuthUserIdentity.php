<?php
/**
 * EAuthUserIdentity class file.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
 * EAuthUserIdentity is a base User Identity class to authenticate with EAuth.
 * @package application.extensions.eauth
 */
class EAuthUserIdentity extends CBaseUserIdentity {

	const ERROR_NOT_AUTHENTICATED = 3;

	/**
	 * @var EAuthServiceBase the authorization service instance.
	 */
	protected $service;

	/**
	 * @var string the unique identifier for the identity.
	 */
	protected $id;

	/**
	 * @var string the display name for the identity.
	 */
	protected $name;

	protected $userinfo;
	/**
	 * Constructor.
	 * @param EAuthServiceBase $service the authorization service instance.
	 */
	public function __construct($service) {
		$this->service = $service;
	}

	/**
	 * Authenticates a user based on {@link service}.
	 * This method is required by {@link IUserIdentity}.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
		if ($this->service->isAuthenticated) {

			//debo consultar si existe en db y esta autorizado
			$user_login = 'enncloud-'.$this->service->id;
			$user = Yii::app()->db->createCommand()
		    ->select('*')
		    ->from('am_users')
		    ->where('user=\''.$user_login.'\'')
		    ->queryRow();

		    if($user){
		    	if($user['activated'] == 1){
		    		$this->id = $user['id'];
					$this->name = $user['username'];

					$this->setState('id', $this->id);
					$this->setState('name', $this->name);
					$this->setState('service', $this->service->serviceName);
				
					$this->setState('email',$this->service->getAttribute('email'));	
					$this->userinfo = $user;
					// You can save all given attributes in session.
					//$attributes = $this->service->getAttributes();
					//$session = Yii::app()->session;
					//$session['eauth_attributes'][$this->service->serviceName] = $attributes;


					$this->errorCode = self::ERROR_NONE;
		    	}else{
		    		throw new CHttpException(403, 'You have no permission to access, Wait for authorization');
		    		$this->errorCode = self::ERROR_NOT_AUTHENTICATED;
		    	}
		    }else{
		    	$pass = sha1($user_login);
		    	$username = $this->service->name;
				$email = $this->service->getAttribute('email');
				$time = time();
		    	$user = Yii::app()->db->createCommand("INSERT INTO am_users (user,username,email,password,admin_level,activated,created) VALUES ('$user_login','$username','$email','$pass','0','0','$time')");
		    	$user->execute();
		    	$this->errorCode = self::ERROR_NOT_AUTHENTICATED;
		    }
			
		}
		else {
			$this->errorCode = self::ERROR_NOT_AUTHENTICATED;
		}
		return !$this->errorCode;
	}

	/**
	 * Returns the unique identifier for the identity.
	 * This method is required by {@link IUserIdentity}.
	 * @return string the unique identifier for the identity.
	 */
	public function getId() {
		return $this->id;
	}

	public function isGuest(){
		if(isset($this->id)){
			return true;
		}
		return false;
	}


	/**
	 * Returns the display name for the identity.
	 * This method is required by {@link IUserIdentity}.
	 * @return string the display name for the identity.
	 */
	public function getName() {
		return $this->name;
	}
}
