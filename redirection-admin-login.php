<?php

//login_action.php

include('../config.menuqrcode/config.php');

$object = new application();

setcookie(
    "id",
    null,
    time() + (10 * 365 * 24 * 60 * 60)
  );
 
 unset($_SESSION['id']);
 
 unset($_COOKIE['id']);
 
 unset($_SESSION['market']);

 

if(isset($_POST["action"]))
{

    if($_POST['action'] == 'login-admin'){
        
        $_SESSION['id'] = $_POST['id']; 
    
        setcookie("id" , $object->encrypt($_SESSION['id']) , time() + (86400 * 10) );
              
        header("location:".$object->client_url."");      
    } 
}
?>