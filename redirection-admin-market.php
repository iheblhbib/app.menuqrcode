<?php

//login_action.php

include('../config.menuqrcode/config.php');

$object = new application();

unset($_SESSION['id']);
 
unset($_SESSION['market']);

unset($_COOKIE['id']);

if(isset($_POST["action"]))
{

    if($_POST['action'] == 'login-market'){
        
        $_SESSION['id'] = $_POST['id'];
        
        $_SESSION['market'] = $_POST['market']; 
    
        setcookie("id" , $object->encrypt($_SESSION['id']) , time() + (86400 * 10) );
              
        header("location:".$object->client_url."");      
    } 
}
?>