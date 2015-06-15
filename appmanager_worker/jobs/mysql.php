<?php
$json = getenv("VCAP_SERVICES");            
if((isset($json))&&($json)){
	$obj = json_decode($json);
    $db_host = $obj->{"mysql-5.1"}[0]->{"credentials"}->{"host"};
    $db_provisioning = $obj->{"mysql-5.1"}[0]->{"credentials"}->{"name"};
    $db_pass = $obj->{"mysql-5.1"}[0]->{"credentials"}->{"password"};
	$db_user = $obj->{"mysql-5.1"}[0]->{"credentials"}->{"user"};
}else{
	$db_provisioning = "appmanager";
	$db_user = "appmanager";
	$db_pass = "v2qfjdkfrr";
	$db_host = "localhost";
}
?>