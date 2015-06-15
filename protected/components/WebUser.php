<?php
class WebUser extends CWebUser
{
    public $id;

    private $_model; 
 
   /* public function login($identity, $duration) {
        $this->setState('__userInfo', $identity->getUser());
        parent::login($identity, $duration);
    }*/

    function isAdmin(){
        $user = $this->loadUser(Yii::app()->user->getId());
        if(!$user)
            return false;
        return intval($user->admin_level) == 1;
        
    }

    // Load user model.
     protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=Users::model()->findByPk($id);
        }
        return $this->_model;
    }

    function getUserData(){
         $user = $this->loadUser(Yii::app()->user->getId());
         return $user;  
    }

    function getMaxApp(){
        $user = $this->loadUser(Yii::app()->user->getId());
        if($user->max_applications == 0){
            return MAX_APPLICATIONS;
        }
        return $user->max_applications;
    }

    function getMaxAppRun(){
        $user = $this->loadUser(Yii::app()->user->getId());
        if($user->max_applications_running == 0){
            return MAX_APPLICATIONS_RUNNING;
        }
        return $user->max_applications;
    }

}
?>