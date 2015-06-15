<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	private $_username;
	private $_userinfo;

	public function authenticate()
	{

        $record=Users::model()->findByAttributes(array('user'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password!==md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
		    if($record->activated != 1){
		    	$this->errorCode=self::ERROR_USERNAME_INVALID;
		       	return !$this->errorCode;
		    }
            
	       $this->_id = $record->id;
	       
	       $this->setState('title', $record->username);
	       $this->_username = $record->username;
	       $this->_userinfo = $record;
	       $this->errorCode=self::ERROR_NONE;

	       if($record->last_ip != Yii::app()->request->userHostAddress){ //si cambio la ultima ip la actualizo.
	       		//$userip = User::model()->findByPk($user->id);
	       		$record->last_ip = Yii::app()->request->userHostAddress;
	       		$record->save();	
	    	}

        }
        return !$this->errorCode;

	}

	private function crypt_blowfish_bycarluys($password, $digito = 7) {  
		$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';  
		$salt = sprintf('$2a$%02d$', $digito);  
		for($i = 0; $i < 22; $i++)  
		{  
 			$salt .= $set_salt[mt_rand(0, 63)];  
		}  
		return crypt($password, $salt);  
	}  

	public function getId()
    {
        return $this->_id;
    }
}