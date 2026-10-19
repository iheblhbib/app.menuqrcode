<?php
 
include('../config.menuqrcode/config.php');

$object = new application();

if(isset($_POST["pseudo"]))
{
	$error = '';
	
	$success = '';
	
	$otp_id = '';
	
	$data = array(
		':pseudo'	=>	$_POST["pseudo"]
	);

	$object->query = "
	SELECT * FROM user 
	WHERE pseudo = :pseudo
	";

	$object->execute($data);

	if($object->row_count() > 0)
	{
		$error = 'Nom d\'utilisateur déjà utilisé';
	}
	else
	{
		    $data = array(
	            ':name'                 => $object->clean_input($_POST["name"]) , 
	            ':num'                  => $object->clean_input($_POST["num"]) , 
	            ':email'                => $object->clean_input($_POST["email"]), 
	            ':pseudo'               => $object->clean_input($_POST["pseudo"]), 
	            ':password'             => md5($object->clean_input($_POST["password"])), 
	            ':user_profile'         => '../app.menuqrcode.tn/img/businessman.png', 
	            ':type'                 => 'gestionnaire',   
	            ':echeance'             => date('Y-m-d',strtotime(date('Y-m-d').' +7 days ')), 
                ':ip'                   => $object->getIPAddress(),
                ':browser'              => $object->getBrowser(),
                ':verified'             => 'non',
	        );

			$object->query = "
			INSERT INTO user   (name, num, email, pseudo, password, user_profile, type, echeance ,ip,browser , verified) 
			VALUES (:name, :num, :email , :pseudo, :password, :user_profile, :type,  :echeance, :ip , :browser , :verified)
			";
            
			$object->execute($data);
			
			$id = $object->connect->lastInsertId();
			
			if($id > 0){
                
                    $otp = rand(100000,999999);
    
                    $data = array(
                    ':user'  => $id,
                    ':otp' => $otp, 
                    );
    
                    $object->query = "
                    INSERT INTO login_otp (user , otp) 
                                 VALUES (:user , :otp)
                    ";
    
                    $object->execute($data); 
                    
                    if(!empty($_POST["num"])){ 
                        
                        $sms = "Cher Client,\r\n";  
                        $sms .= "\r\n";  
                        $sms .= "Votre code de validation est : ".$otp."\r\n"; 
                        $sms .= "\r\n"; 
                        $sms .= "Menu QR Code Tunisie"; 
                        
                        $object->send_SMS('56557032',$object->clean_input($_POST["num"]),$sms);
                         
                        $data = array(
                            ':id'       => $id,
                            ':next_sms' => date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime(date('Y-m-d H:i:s')))), 
                        );
        
                        $object->query = "
                        UPDATE user set
                            next_sms = :next_sms
                        WHERe id = :id 
                        ";
        
                        $object->execute($data);
                    }
                    
                $otp_id = $id;
                 
                $success = 'Bienvenue';
			}else{
			    $error = "Erreur système, veuillez réessayer ultérieurement !";
			}
	}

	$output = array(
		'error'		=>	$error,
		'success'	=>	$success,
		'key'       =>  $object->encrypt($otp_id),
	);

	echo json_encode($output);
}
?>