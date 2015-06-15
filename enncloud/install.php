<?

require_once('cf_api.php');

//checks security
//validate_access();

$json = getenv("VCAP_SERVICES");
$varphp = json_decode($json);

//defino el nombre del sql que debe estar en la misma ruta que este instalador.
$sql_file = "appmanager.sql";

if(!is_file($sql_file)){
  return_json("ERROR","Error: SQL file not found ($sql_file)","0");
}
//init database vars

$host = $varphp->{"mysql-5.1"}[0]->{"credentials"}->{"hostname"};
$pass = $varphp->{"mysql-5.1"}[0]->{"credentials"}->{"password"};
$user = $varphp->{"mysql-5.1"}[0]->{"credentials"}->{"user"};
$dbname = $varphp->{"mysql-5.1"}[0]->{"credentials"}->{"name"};

//check connection

$con = mysql_connect($host,$user,$pass);
if (!$con){
  return_json("ERROR","Could not connect: ". mysql_error(),"1");
}


if(mysql_select_db($dbname,$con)){
        //check if database already exists
        //validar que ya no este instalado... para no pisar la base de datos!
        $result = mysql_query("SELECT * FROM am_cfservices LIMIT 1",$con);
        $num = mysql_num_rows($result);
        if ($num > 0){
            //descomentar para que no pise la db

          /*$OAUTH_CLIENT_ID = getenv("OAUTH_CLIENT_ID");
          $OAUTH_CLIENT_SECRET = getenv("OAUTH_CLIENT_SECRET");
          $oauth_ssl = getenv("OAUTH_CLIENT_USE_SSL");
          if($oauth_ssl == 1){ $http = "https"; } else { $http = "http"; }
          $OAUTH_CLIENT_HOST = $http.'://'.getenv("OAUTH_CLIENT_HOST");
          $OAUTH_AUTHORIZE_URL = $http.'://'.$OAUTH_CLIENT_HOST.getenv("OAUTH_CLIENT_URL_AUTHORIZE");
          $OAUTH_TOKEN_URL = $http.'://'.$OAUTH_CLIENT_HOST.getenv("OAUTH_CLIENT_URL_TOKEN");*/

            return_json("ERROR","Error:Database already exists!","2");
        }

              
          $contents = file_get_contents($sql_file);

          // Remove C style and inline comments
          $comment_patterns = array('/\/\*.*(\n)*.*(\*\/)?/', //C comments
                                    '/\s*--.*\n/', //inline comments start with --
                                    '/\s*#.*\n/', //inline comments start with #
                                    );
          $contents = preg_replace($comment_patterns, "\n", $contents);

          //Retrieve sql statements
          $statements = explode(";\n", $contents);
          $statements = preg_replace("/\s/", ' ', $statements);

          foreach ($statements as $query) {
              if (trim($query) != '') {
                  $res = mysql_query($query,$con);
                  if (!$res) {
                    return_json("ERROR","ERROR INSTALLING DATABASE: ".mysql_error($con)." --->QUERY:".$query,"3");
                  }
              }
          }

          return_json("OK","Database successfully installed!","");

  }else{
      return_json("ERROR","Error select database: " . mysql_error(),"4");
}

?>
