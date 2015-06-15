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
    $time = time();
    $user_name               = $_POST["user_name"];
    $user_id              	 = $_POST["user_id"];
    $user_login              = 'enncloud-'.$user_id;
    $user_admin              = $_POST["user_admin"];
    $user_email              = $_POST["user_email"];
    $password                = $_POST["password"];//md5("enncloud_".rand(166,5000).rand(455,6500));
    $password_md5            = md5($_POST["password"]);
    //if($user_admin == 1){ $user_name = "admin"; }
    $query = "SELECT * FROM am_users WHERE userlogin = '$user_login'";
    $res = mysql_query($query,$con);
    $num = mysql_num_rows($res);
    
    if($num > 0){
    	//aca deberia actualizar el usuario activandolo en caso que en tiempo de autenticación lo de de alta
        $query = "UPDATE am_users SET asctivated = 1 WHERE userlogin = '$user_login'";
        $user = mysql_fetch_array($res);
        //$json = json_encode($user);
        //return_json("ERROR","Error: User already exists JSON:".$json,"3");

    }else{
        $query = "INSERT INTO am_users (user,username,email,password,admin_level,activated,created) VALUES ('$user_login','$user_name','$user_email','$pass','$user_admin','1','$time')";
        if(!mysql_query($query,$con)){
            return_json("ERROR","Error creating user","4");
        }
       
    }

}else{
    $error = 'Could not connect to database: '.$vmc_dbname;
    $error_code="1";

}
return_json("OK","","");

?>
        