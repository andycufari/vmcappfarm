<?
function validate_access(){
    $cfkey_secure = getenv("cfkey");
    if($_POST["cfkey"] != $cfkey_secure){
        header('HTTP/1.1 403 Forbidden');
        $result = array();
        $result["status"] = "ERROR";
        $result["message"] = "Access not allowed";
        echo json_encode($result);
        exit();
    }
}

function return_json($status,$message,$cod_error){
    $result = array();
    if($status != "OK"){
        header('HTTP/1.1 409 Conflict');
        
        $result["status"] = "ERROR";
        $result["message"] = $message;
        $result["cod_error"] = $cod_error;
        echo json_encode($result);
    }else{
         header('HTTP/1.1 200 OK');
         $result["status"] = "OK";
         echo json_encode($result);
    }
    exit();
}
?>