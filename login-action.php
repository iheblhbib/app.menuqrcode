<?php

//login_action.php

include('../config.menuqrcode/config.php');

$object = new application();

if(isset($_POST["pseudo"]))
{
	$error = '';
	$success = '';
	$verified = true;
	$otp_id = '';
	$data = array(
		':pseudo'	=>	$_POST["pseudo"]
	);

	$object->query = "
		SELECT * FROM user
		WHERE pseudo = :pseudo and type in ('gestionnaire' , 'employee' )  ";
	
// 	die($object->query);

	$object->execute($data);

	$total_row = $object->row_count();

	if($total_row == 0)
	{
		$error = 'Identifiants incorrects. Veuillez réessayer.';
	}
	else
	{
// 		$result = $statement->fetchAll();

		$result = $object->statement_result();

		foreach($result as $row)
		{ 
    			if($row["statut"] == 'Activer')
    			{
    				if(md5($_POST["password"]) == $row["password"])
    				{
    				    
				       if($row["verified"] == 'non'){
            		     
            		    	if($row['next_sms'] <  date('Y-m-d H:i:s') ){
            		    	    
        		    	    	$error = 'Compte non vérifié';
            		    	
            		    	    $verified = false ;
            		    	    
            		    	    $otp_id = $row['id'];
            		    	
        	                    $otp = rand(100000,999999);
        
                                $data = array(
                                ':user'  => $row['id'],
                                ':otp' => $otp, 
                                );
                
                                $object->query = "
                                INSERT INTO login_otp
                                (user , otp) 
                                VALUES (:user , :otp)
                                ";
                
                                $object->execute($data); 
                                
                                if(!empty($row["num"])){ 
                                    
                                    $sms = "Cher Client,\r\n";  
                                    $sms .= "\r\n";  
                                    $sms .= "Votre code de validation est : ".$otp."\r\n"; 
                                    $sms .= "\r\n"; 
                                    $sms .= "Menu QR Code Tunisie"; 
                                    
                                    $object->send_SMS('56557032',$object->clean_input($row["num"]),$sms);
                                     
                                    $data = array(
                                        ':id'       => $row['id'],
                                        ':next_sms' => date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime(date('Y-m-d H:i:s')))), 
                                    );
                    
                                    $object->query = "
                                    UPDATE user set
                                        next_sms = :next_sms
                                    WHERe id = :id 
                                    ";
                    
                                    $object->execute($data); 
                                }
				            }else{
				                $error = 'Compte non vérifié, SMS envoyé. Réessayez après 15 min.';
				            }

                    
            		    }else{
        		
        					$_SESSION['id'] = $row['id']; 
            
                            // setcookie("id" , $object->encrypt($_SESSION['id']) , time() + (86400 ) );
                            $success = 'Bienvenue';
                            
                             $data = array(
                                ':user'         => $row['id'],
                                ':ip'           => $object->getIPAddress(), 
                                ':browser'      => $object->getBrowser(), 
                            );
                            
                    		$object->query = "INSERT INTO login (user, ip ,browser) VALUES (:user, :ip ,:browser) ";
					        $object->execute($data);
					
            		    }
                
    				}
    				else
    				{
    					$error = 'Identifiants incorrects. Veuillez réessayer.';
    				}
    			} 
    			else
    			{
    				$error = 'Compte désactivé, contactez nous +216 71 581 836';
    			}
		    
		}

	}

	$output = array(
		'error'		=>	$error,
		'success'	=>	$success,
		'verified'  => $verified,
		'key'       =>  $object->encrypt($otp_id),
	);

	echo json_encode($output);
}
?>