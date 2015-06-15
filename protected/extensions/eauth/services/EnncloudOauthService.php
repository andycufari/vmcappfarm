<?php
/**
 * GoogleOAuthService class file.
 * 
 * Register application: https://code.google.com/apis/console/
 * 
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)).'/EOAuth2Service.php';

/**
 * Google provider class.
 * @package application.extensions.eauth.services
 */
class EnncloudOauthService extends EOAuth2Service {	
	private $_conf;
	protected $name = 'enncloud_oauth';
	protected $title = 'Enncloud';
	protected $type = 'OAuth';
	protected $jsArguments = array('popup' => array('width' => 500, 'height' => 450));

	protected $client_id = '';
	protected $client_secret = '';
	protected $scope = '';
	protected $providerOptions = array(
		'authorize' => '',
		'access_token' => '',
	);
	
	protected function fetchAttributes() {
		$info = (array)$this->makeSignedRequest($this->scope);
				
		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['name'];
		$this->attributes['email'] = $info['email'];
		
		if (!empty($info['link']))
			$this->attributes['url'] = $info['link'];
		
		/*if (!empty($info['gender']))
			$this->attributes['gender'] = $info['gender'] == 'male' ? 'M' : 'F';
		
		if (!empty($info['picture']))
			$this->attributes['photo'] = $info['picture'];
		
		$info['given_name']; // first name
		$info['family_name']; // last name
		$info['birthday']; // format: 0000-00-00
		$info['locale']; // format: en*/
	}

	private function getOauthConfig($key){
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


	public function init($component, $options = array()){
		if(!getenv("OAUTH_CLIENT_HOST")){
			$OAUTH_CLIENT_HOST = $this->getOauthConfig('OAUTH_CLIENT_HOST');
			$OAUTH_AUTHORIZE_URL = $OAUTH_CLIENT_HOST.$this->getOauthConfig('OAUTH_CLIENT_URL_AUTHORIZE');
			$OAUTH_TOKEN_URL = $OAUTH_CLIENT_HOST.$this->getOauthConfig('OAUTH_CLIENT_URL_TOKEN');
			$this->providerOptions = array(
			'authorize' => $OAUTH_AUTHORIZE_URL,
			'access_token' => $OAUTH_TOKEN_URL);
			$this->scope = $OAUTH_CLIENT_HOST.'/userinfo/basic.json';
			$this->client_id = $this->getOauthConfig('OAUTH_CLIENT_ID');
			$this->client_secret = $this->getOauthConfig('OAUTH_CLIENT_SECRET');
			$this->title = $options["title"];
			$this->component = $component;

		}else{
			$oauth_ssl = getenv("OAUTH_CLIENT_USE_SSL");
			if($oauth_ssl == 1){ $http = "https"; } else { $http = "http"; }

			$OAUTH_CLIENT_HOST = getenv("OAUTH_CLIENT_HOST");
			$OAUTH_AUTHORIZE_URL = $http.'://'.$OAUTH_CLIENT_HOST.getenv("OAUTH_CLIENT_URL_AUTHORIZE");
			$OAUTH_TOKEN_URL = $http.'://'.$OAUTH_CLIENT_HOST.getenv("OAUTH_CLIENT_URL_TOKEN");
			$this->providerOptions = array(
			'authorize' => $OAUTH_AUTHORIZE_URL,
			'access_token' => $OAUTH_TOKEN_URL);
			$this->scope = $http.'://'.$OAUTH_CLIENT_HOST.'/userinfo/basic.json';

			$this->client_id = $options["client_id"];
			$this->client_secret = $options["client_secret"];
			$this->title = $options["title"];
			$this->component = $component;
		}
		/*
		array(3) { ["client_id"]=> string(32) "4836b1500e7e7de5864c5dcc21d92648" ["client_secret"]=> string(32) "ab2433e3c589b2caf660d23a50b9b95d" ["title"]=> string(8) "Enncloud" } object(EAuth)#47 (9) { ["services"]=> array(1) { ["enncloud"]=> array(4) { ["class"]=> string(20) "EnncloudOauthService" ["client_id"]=> string(32) "4836b1500e7e7de5864c5dcc21d92648" ["client_secret"]=> string(32) "ab2433e3c589b2caf660d23a50b9b95d" ["title"]=> string(8) "Enncloud" } } ["popup"]=> bool(false) ["cache"]=> bool(false) ["cacheExpire"]=> int(0) ["redirectView":protected]=> string(8) "redirect" ["behaviors"]=> array(0) { } ["_initialized":"CApplicationComponent":private]=> bool(true) ["_e":"CComponent":private]=> NULL ["_m":"CComponent":private]=> NULL }
			*/		
		
	}

	protected function getCodeUrl($redirect_uri) {
		$this->setState('redirect_uri', $redirect_uri);
		$url = parent::getCodeUrl($redirect_uri);
		if (isset($_GET['js']))
			$url .= '&display=popup';
		return $url;
	}
	
	protected function getTokenUrl($code) {
		return $this->providerOptions['access_token'];
	}
	
	protected function getAccessToken($code) {
		$params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $this->getState('redirect_uri'),
		);
		return $this->makeRequest($this->getTokenUrl($code), array('data' => $params));
	}
	
	/**
	 * Save access token to the session.
	 * @param stdClass $token access token array.
	 */
	protected function saveAccessToken($token) {
		$this->setState('auth_token', $token->access_token);
		$this->setState('expires', time() + $token->expires_in - 60);
		$this->access_token = $token->access_token;
	}
		
	/**
	 * Makes the curl request to the url.
	 * @param string $url url to request.
	 * @param array $options HTTP request options. Keys: query, data, referer.
	 * @param boolean $parseJson Whether to parse response in json format.
	 * @return string the response.
	 */
	protected function makeRequest($url, $options = array(), $parseJson = true) {
		$options['query']['alt'] = 'json';
		return parent::makeRequest($url, $options, $parseJson);
	}
	
	/**
	 * Returns the error info from json.
	 * @param stdClass $json the json response.
	 * @return array the error array with 2 keys: code and message. Should be null if no errors.
	 */
	protected function fetchJsonError($json) {
		if (isset($json->error)) {
			return array(
				'code' => $json->error->code,
				'message' => $json->error->message,
			);
		}
		else
			return null;
	}
}