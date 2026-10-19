<?php 

include('../config.menuqrcode/config.php'); 
    
    $object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

    if(isset($_POST["action_profil"]))
    {
        if($_POST['action_profil'] == 'update')
        {
            $error = '';
    
    		$success = ''; 
    		
    		$data = array(
               
                ':name'	        =>	$object->clean_input($_POST["name_profil"]),
                ':num'          =>	empty($object->clean_input($_POST["num_profil"])) ? null : $object->clean_input($_POST["num_profil"]) ,
                ':email'	    =>	empty($object->clean_input($_POST["email_profil"])) ? null : $object->clean_input($_POST["email_profil"]) , 
                ':id'	        =>	$_SESSION["id"] ,
               
    		);
    
    		$object->query = "
    		    UPDATE
                    user
                SET
                    name = :name,
                    num = :num,
                    email = :email 
                WHERE id = :id
                    ";
    		
            $object->execute($data);
 
    		$success = 'Mis à jour avec succés';
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success 
    		);
    
    		echo json_encode($output);
        }
    }
    
    if(isset($_POST["action_password"]))
    {
        if($_POST['action_password'] == 'update')
        {
            $error = '';
    
    		$success = '';
    
    		$data = array(
    			':id'		=>	$_SESSION['id']
    		);
    
    		$object->query = "  SELECT
                                    *
                                FROM
                                    user
                                WHERE 
                                    id  = :id";
    
    		$object->execute($data);
    
    		$result = $object->statement_result();
    
    		foreach ($result as $row) {
    			if (md5($_POST["old-password"]) != $row["password"]) {
    			 
    				$error = 'Le mot de passe actuel est incorrect.';
    			} else {
    				if ($_POST["new-password"] != $_POST["confirm-password"]) {
    					 
    					$error = 'Les mots de passe ne correspondent pas.';
    				} else {
    					$data = array(
    						':password'	=>	md5($_POST["new-password"]),
    						':id'		=>	$_SESSION['id']
    					);
    
    					$object->query = " UPDATE user SET password = :password WHERE id = :id ";
    
    					$object->execute($data);
     
    					$success = 'Mot de passe modifié avec succès.';
    				}
    			}
    		}
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success ,
    			'data'      => $result ,
    		);
    
    		echo json_encode($output);
        }
    } 