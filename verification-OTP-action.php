<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'config/PHPMailer/src/PHPMailer.php';
require 'config/PHPMailer/src/SMTP.php';


$mail = new PHPMailer(true);

include('../config.menuqrcode/config.php');

$object = new application();

if(isset($_POST["key"]))
{
	$error = '';
	$success = '';
	$verified = true;
	$data = array(
		':user'	=>	$object->decrypt($_POST["key"])
	);

	$object->query = "
		SELECT * FROM login_otp
		WHERE user = :user 
		order by created desc
		LIMIT 1 "; 

	$object->execute($data);

	$total_row = $object->row_count();

	if($total_row == 0)
	{
		$error = 'Erreur système';
	}
	else
	{  
		$result = $object->statement_result();

		foreach($result as $row)
		{ 
    			if($row["otp"] == $_POST['code'])
    			{
                    $_SESSION['id'] = $row['user'];  
                            
                    $success = 'Bienvenue'; 
                    
                    $data = array(
                                ':verified'  => 	'oui', 
                                ':id'        => 	$object->decrypt($_POST["key"]), 
                                );
                
                    $object->query = " UPDATE user set
                                    verified = :verified
                                WHERe id = :id  ";
    
                    $object->execute($data);  
                    
                    $data = array(
                        ':user'         => $row['user'],
                        ':ip'           => $object->getIPAddress(), 
                        ':browser'      => $object->getBrowser(), 
                    );
                    
            		$object->query = "INSERT INTO login (user, ip ,browser) VALUES (:user, :ip ,:browser) ";
			        $object->execute($data);
                    
                    $user = json_decode($object->get_user($row['user']));

                    $message = ' <!DOCTYPE html>
                                    <html lang="en">
                                    <head>
                                        <meta charset="utf-8">
                                        <title>Menu QR Code</title>
                                        <meta name="viewport" content="width=device-width, initial-scale=1">
                                    	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
                                        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
                                    	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                                    </head>
                                    <body>
                                    <table width="100%" height="100%" bgcolor="#E4E6E9" cellspacing="0" cellpadding="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td width="100%" align="center" valign="top" bgcolor="#E4E6E9" style="background-color:#E4E6E9; min-height: 200px;">
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="table-td-wrap" align="center" width="458">
                                                                    <table class="table-space" height="18" style="height: 18px; font-size: 0px; line-height: 0; width: 450px; background-color: #e4e6e9;" width="450" bgcolor="#E4E6E9" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="18" style="height: 18px; width: 450px; background-color: #e4e6e9;" width="450" bgcolor="#E4E6E9" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="8" style="height: 8px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="8" style="height: 8px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                    
                                                                    <table class="table-row" width="450" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-row-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;" valign="top" align="left">
                                                                                    <table class="table-col" align="left" width="378" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td class="table-col-td" width="378" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; width: 378px;" valign="top" align="left">
                                                                                                    <table class="header-row" width="378" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td class="header-row-td" width="378" style="font-family: Arial, sans-serif; font-weight: normal; line-height: 19px; color: #cda45e; margin: 0px; font-size: 18px; padding-bottom: 10px; padding-top: 15px;" valign="top" align="left">Merci de vous être inscrit chez nous !</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                    <div style="font-family: Arial, sans-serif; line-height: 20px; color: #444444; font-size: 13px;">
                                                                                                        Cher <b>'.ucwords($user->name).'</b> !
                                                                                                        <br>
                                                                                                    </div>
                                                                                                    <div style="font-family: Arial, sans-serif; line-height: 20px; color: #444444; font-size: 13px;">
                                                                                                        L\'équipe de Menu QR Code se joint à moi pour vous souhaiter la bienvenue sur notre plateforme de digitalisation des menus ! 
                                    
                                                                                                            <br>  Afin de finaliser la configuration de votre compte, veuillez cliquer sur le lien ci-dessous :
                                                                                                        
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                    
                                                                    <table class="table-space" height="12" style="height: 12px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="12" style="height: 12px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="12" style="height: 12px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="12" style="height: 12px; width: 450px; padding-left: 16px; padding-right: 16px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="center">&nbsp;
                                                                                    <table bgcolor="#E8E8E8" height="0" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td bgcolor="#E8E8E8" height="1" width="100%" style="height: 1px; font-size:0;" valign="top" align="left">&nbsp;</td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="16" style="height: 16px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="16" style="height: 16px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                    
                                                                    <table class="table-row" width="450" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-row-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;" valign="top" align="left">
                                                                                    <table class="table-col" align="left" width="378" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td class="table-col-td" width="378" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; width: 378px;" valign="top" align="left">
                                                                                                    <div style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; text-align: center;">
                                                                                                        <a href="https://menuqrcode.tn/login" style="color: #ffffff; text-decoration: none; margin: 0px; text-align: center; vertical-align: baseline; border: 4px solid #cda45e; padding: 4px 9px; font-size: 15px; line-height: 21px; background-color: #cda45e;">&nbsp; Connexion &nbsp;</a>
                                                                                                    </div>
                                                                                                    <table class="table-space" height="16" style="height: 16px; font-size: 0px; line-height: 0; width: 378px; background-color: #ffffff;" width="378" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td class="table-space-td" valign="middle" height="16" style="height: 16px; width: 378px; background-color: #ffffff;" width="378" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="12" style="height: 12px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="12" style="height: 12px; width: 450px; padding-left: 16px; padding-right: 16px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="center">&nbsp;
                                                                                    <table bgcolor="#E8E8E8" height="0" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td bgcolor="#E8E8E8" height="1" width="100%" style="height: 1px; font-size:0;" valign="top" align="left">&nbsp;</td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                    
                                    
                                                                    <table class="table-space" height="6" style="height: 6px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="6" style="height: 6px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-row" width="450" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-row-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;" valign="top" align="left">
                                                                                    <table class="table-col" align="left" width="378" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td class="table-col-td" width="378" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; width: 378px;" valign="top" align="left">
                                                                                                    <div style="font-family: Arial, sans-serif; line-height: 20px; color: #444444; font-size: 13px;">
                                                                                                         Si vous avez des questions ou des commentaires, n\'hésitez pas à nous contacter à l\'adresse suivante <a href="mailto:contact@menuqrcode.tn">contact@menuqrcode.tn</a>.
                                                                                                        <br> <br>
                                                                                                        Cordialement,
                                                                                                            
                                                                                                        <br> L\'équipe Menu QR Code</a>.
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="12" style="height: 12px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="12" style="height: 12px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-row-fixed" width="450" bgcolor="#FFFFFF" style="table-layout: fixed; background-color: #ffffff;" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-row-fixed-td" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 1px; padding-right: 1px;" valign="top" align="left">
                                                                                    <table class="table-col" align="left" width="448" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td class="table-col-td" width="448" style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;" valign="top" align="left">
                                                                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="table-layout: fixed;">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td width="100%" align="center" bgcolor="#f5f5f5" style="font-family: Arial, sans-serif; line-height: 24px; color: #bbbbbb; font-size: 13px; font-weight: normal; text-align: center; padding: 9px; border-width: 1px 0px 0px; border-style: solid; border-color: #e3e3e3; background-color: #f5f5f5;" valign="top">
                                                                                                                    <a href="https://Menuqrcode.tn" style="color: #cda45e; text-decoration: none; background-color: transparent;">Menuqrcode.tn &copy; <script>document.write(new Date().getFullYear())</script></a>
                                                                                                                    <br>
                                                                                                       
                                                                                                                    <a href="https://www.facebook.com/menuqrcodetun" style="color: #5b7a91; text-decoration: none; background-color: transparent;">Facebook</a> .
                                                                                                                    <a href="https://www.instagram.com/menu_qr_code/" style="color: #dd5a43; text-decoration: none; background-color: transparent;">Instagram</a>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="1" style="height: 1px; font-size: 0px; line-height: 0; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="1" style="height: 1px; width: 450px; background-color: #ffffff;" width="450" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table class="table-space" height="36" style="height: 36px; font-size: 0px; line-height: 0; width: 450px; background-color: #e4e6e9;" width="450" bgcolor="#E4E6E9" cellspacing="0" cellpadding="0" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="table-space-td" valign="middle" height="36" style="height: 36px; width: 450px; background-color: #e4e6e9;" width="450" bgcolor="#E4E6E9" align="left">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <style type="text/css">
                                    body{margin-top:20px;}
                                    </style>
                                    
                                    <script type="text/javascript">
                                    
                                    </script>
                                </body>
                            </html>
                        ';
                     
                 
 
                    $mail->isSMTP();                                             
                    $mail->Host       = 'mail.menuqrcode.tn';                   
                    $mail->SMTPAuth   = true;                                    
                    $mail->Username   = 'no-reply@menuqrcode.tn';                    
                    $mail->Password   = 'jf@-}iHpT7[d';                             
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          
                    $mail->Port       = 465;                                  
                 
                    $mail->setFrom('no-reply@menuqrcode.tn', 'Menu QR Code');
                    $mail->addAddress($user->email, $user->name);  
                    
                   $object->query="SELECT * FROM user where statut = 'Activer'  and email is not null  and type != 'gestionnaire'  ";
                    $result = $object->get_result();
                    foreach($result as $item){
                        $mail->addBCC($item['email'],$item['name']);  
                    }     
                   
                    $mail->isHTML(true);                                 
                    $mail->Subject = 'Bienvenue sur Menu QR Code';
                    $mail->Body    = utf8_decode($message);
                
                    $mail->send();  
                    
				}
				else
				{
					$error = 'Code incorrecte';
				} 
		    
		}

	}

	$output = array(
		'error'		=>	$error,
		'success'	=>	$success, 
	);

	echo json_encode($output);
}
?>