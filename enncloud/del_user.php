<?
//includes cf_api?

require_once('cf_api.php');
//checks security
validate_access();


$vmc_json = getenv("VCAP_SERVICES");
$vmc_varphp = json_decode($vmc_json);

$vmc_dbhost = $vmc_varphp->{"mysql-5.1"}[0]->{"credentials"}->{"hostname"};
$vmc_dbpass = $vmc_varphp->{"mysql-5.1"}[0]->{"credentials"}->{"password"};
$vmc_dbuser = $vmc_varphp->{"mysql-5.1"}[0]->{"credentials"}->{"user"};
$vmc_dbport = $vmc_varphp->{"mysql-5.1"}[0]->{"credentials"}->{"port"};
$vmc_dbname = $vmc_varphp->{"mysql-5.1"}[0]->{"credentials"}->{"name"};

$error = "";

$con = mysql_connect($vmc_dbhost,$vmc_dbuser,$vmc_dbpass);
if (!$con){
  return_json("ERROR","Could not connect: ". mysql_error(),"1");
}

if(mysql_select_db($vmc_dbname,$con)){
    if (! $_POST["user_id"]) {
            return_json("ERROR","Error: Param user_id is empty","2");//return error    
        }
    $user_id              = $_POST["user_id"];
    $user_login              = 'enncloud-'.$user_id;

    $query = "SELECT id FROM am_users WHERE userlogin = '$user_login'";
    $res = mysql_query($query,$con);
    $num = mysql_num_rows($res);

    if($num == 0){
        return_json("ERROR","Error: User not exist","3");

    }else{
            $query = "DELETE FROM am_users WHERE userlogin='$user_login'";
            $res = mysql_query($query,$con);
            if(!$res){
                return_json("ERROR","Error trying to delete user","4");
            }
    }

}else{
    $error = 'Could not connect to database: '.$vmc_dbname;
    $error_code="1";

}
return_json("OK","","");

?>
        