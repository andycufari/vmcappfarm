<?php
include_once('jobs/mysql.php');
include_once('jobs/provisioning_lib.php');
include_once('jobs/VMCPHP.php');
include_once('jobs/MongoFs.php');

require 'resque.php';

//$dblink = 0;

class Provisioning
{
  public function perform()
  {
    $appid = $this->args['array']['appid'];
    /*'OAUTH_CLIENT_HOST' => getenv("OAUTH_CLIENT_HOST"),
    'OAUTH_CLIENT_USE_SSL' => getenv("OAUTH_CLIENT_USE_SSL"),
    'OAUTH_CLIENT_URL_AUTHORIZE' => getenv("OAUTH_CLIENT_URL_AUTHORIZE"),
    'OAUTH_CLIENT_URL_TOKEN' => getenv("OAUTH_CLIENT_URL_TOKEN")
    */
    $OAUTH_CLIENT_HOST = $this->args['array']['OAUTH_CLIENT_HOST'];
    if(!$OAUTH_CLIENT_HOST){ $OAUTH_CLIENT_HOST = 'auth.red.enncloud.com';}
    $OAUTH_CLIENT_USE_SSL = $this->args['array']['OAUTH_CLIENT_USE_SSL'];
    if(!$OAUTH_CLIENT_USE_SSL){ $OAUTH_CLIENT_USE_SSL = 1;}
    $OAUTH_CLIENT_URL_AUTHORIZE = $this->args['array']['OAUTH_CLIENT_URL_AUTHORIZE'];
    if(!$OAUTH_CLIENT_URL_AUTHORIZE){ $OAUTH_CLIENT_URL_AUTHORIZE = '/oauth2/authorize';}
    $OAUTH_CLIENT_URL_TOKEN = $this->args['array']['OAUTH_CLIENT_URL_TOKEN'];
    if(!$OAUTH_CLIENT_URL_TOKEN){ $OAUTH_CLIENT_URL_TOKEN = '/oauth2/token';}

    global $db_user;
    global $db_pass;
    global $db_host;
    global $db_provisioning;
    global $dblink;

    $dblink = mysql_connect($db_host,$db_user,$db_pass);
 
    mysql_select_db($db_provisioning, $dblink);
    
    echo "------------------PROVISIONING----------------".date("d/m/Y h:i:s")."-------------------------\n";

    //busco la aplicacion validando que tenga estado a procesar y que no este en proceso
    $query = "SELECT A.*,CF.cfcode,U.user AS app_user,U.username AS app_username, U.email AS app_useremail,CF.name AS cfmodel,E.endpoint,E.pass,E.user,C.redirect_uri,C.identifier,C.secret,R.type AS repo_type,R.repo_user,R.repo_passwd,R.key_file,R.repo_url FROM am_applications A 
      INNER JOIN am_enviroments E ON E.id = A.enviroment_id
      INNER JOIN am_cfappframeworks CF ON CF.id = A.cfappframework_id
      INNER JOIN oauth2_clients C ON C.id = A.client_id
      INNER JOIN am_users U ON A.user_id = U.id 
      LEFT JOIN am_repositories R ON A.repository_id = R.id 
      WHERE A.provisioningstate<>0 AND A.provisioningstate <> 10 AND A.id=$appid LIMIT 1";

   
  
   $result = mysql_query($query,$dblink);
   $o_i = mysql_fetch_object($result); 
   //valido que traiga resultado
   if(!$o_i){
     echo "Error en query: \n".$query;
     $query = "UPDATE am_applications SET provisioningstate='0' WHERE id='$appid'";
     mysql_query($query,$dblink);
     return false;
   }
   
   $query = "UPDATE am_applications SET provisioningstate='10' WHERE id='$o_i->id'";
   mysql_query($query,$dblink);

    echo "\nEncuentra aplicación a procesar ID:". $o_i->id;
        $ret = 1;
    $msg_log = "";
    $f_global_error = 0;

    $query = "UPDATE am_applications SET provisioningstate='10' WHERE id='$o_i->id'";
    mysql_query($query,$dblink);

    echo "\nEncuentra aplicación a procesar ID:". $o_i->id;
    


    $provisioning = new DoProvisioning($o_i);
    $provisioning->OAUTH_CLIENT_HOST = $OAUTH_CLIENT_HOST;
    $provisioning->OAUTH_CLIENT_URL_TOKEN = $OAUTH_CLIENT_URL_TOKEN;
    $provisioning->OAUTH_CLIENT_URL_AUTHORIZE = $OAUTH_CLIENT_URL_AUTHORIZE;
    $provisioning->OAUTH_CLIENT_USE_SSL = $OAUTH_CLIENT_USE_SSL;
    //verifico la acción
    if($provisioning->f_error){
      return false;
    }
    
      switch ($o_i->provisioningstate) {
        case 1: // que hay que provisionar la aplicacion completa
          $provisioning->do_full_provisioning();
          if($provisioning->status != -1){
            $provisioning->status = 1;  
          }
          break;
        case 2: // que debo actualizar la aplicacion
          $provisioning->update_app();
          if($provisioning->status != -1){
            $provisioning->status = 2;  
          }
          break;
        
        case 9:
          $provisioning->delete_app();
          if($provisioning->status !=-1){
            $provisioning->status = 9;  
          }
          
          break;
        
        default:
          $provisioning->status = $o_i->status;      
          break;
      }

    $provisioning->save_app();

    mysql_close($dblink);
    echo "\n-----------------FINALIZA --------------".date("d/m/Y h:i:s")."-------------------------\n";
    return $ret;

  }
}

?>