<?php

include('../config.menuqrcode/config.php');

$object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);
  
$page_actuel = $object->get_page_url();

if (isset($_POST["action"])) {   
    
    if ($_POST["action"] == 'paiement') {
        
        $konnect = json_decode($object->get_token_konnect()); 
        
        $error = '';

        $success = ''; 
        
        $lien = ''; 
        
        $montant = $object->countTotalKonnect($_SESSION['id']) * 1000;
        
        $_POST['nom'] = $object->clean_input($_POST['nom']);  
        
        $_POST['prenom'] = $object->clean_input($_POST['prenom']);
        
        $_POST['tel'] = $object->clean_input($_POST['tel']);
        
        $_POST['email'] = $object->clean_input($_POST['email']);
         
        $paymentDetails = array(
            "receiverWalletId" => $konnect->wallet_id , 
            "token" => "TND",
            "amount" => $montant,
            "type" => "immediate",
            "description" => "Paiement Menu QR Code",
            "acceptedPaymentMethods" => [ "bank_card", "e-DINAR"],
            "lifespan" => 20,
            "checkoutForm" => false,
            "addPaymentFeesToAmount" => false, 
            "firstName" => $_POST['prenom'],
            "lastName"=> $_POST['nom'],
            "phoneNumber" => $_POST['tel'],
            "email" =>  $_POST['email'],
            "webhook" => "https://merchant.tech/api/notification_payment",
            "silentWebhook" => true,
            "successUrl" => "https://dev.konnect.network/gateway/payment-success",
            "failUrl" => "https://dev.konnect.network/gateway/payment-failure",
            "theme" => "light"
        );
        
        $paymentResponse  = $object->initiatePayment($paymentDetails,$konnect->api_key);
        
        
        if (!empty($paymentResponse)) {
            
            $success = " Initiation de paiement réussie.";
           
            $lien = $paymentResponse['payUrl'];
            
            
            $data = array( 
                ':montant'          =>  $object->clean_input($montant), 
                ':lien'             =>  $object->clean_input($paymentResponse['payUrl']), 
                ':reference'        =>  $object->clean_input($paymentResponse['paymentRef']), 
                ':nom'              =>  $object->clean_input($_POST['nom']), 
                ':prenom'           =>  $object->clean_input($_POST['prenom']), 
                ':societe'          =>  ($_POST['societe']) ? $object->clean_input($_POST['societe']) :  null, 
                ':tel'              =>  $object->clean_input($_POST['tel']), 
                ':email'            =>  $object->clean_input($_POST['email']), 
                ':address'          =>  $object->clean_input($_POST['address']), 
                ':etat'             =>  '2',   
                ':user'             =>  $_SESSION['id'],   
            );
 
            $object->query = "UPDATE konnect SET
                                        montant = :montant, 
                                        lien = :lien, 
                                        reference = :reference , 
                                        nom = :nom, 
                                        prenom = :prenom, 
                                        societe = :societe, 
                                        tel = :tel, 
                                        email = :email, 
                                        address = :address,
                                        etat = :etat
                                WHERE etat = '1' AND user = :user ";

            $object->execute($data);
            
        } else {
            $error = " L'initiation du paiement a échoué.";
        }



        $output = array(
            'error'     => $error,
            'success'   => $success, 
            'lien'      => $lien,
            'detias'    => $paymentDetails
        );

        echo json_encode($output);

    }
    
    if ($_POST["action"] == 'fetch') {

        $output = array();

        $main_query = "SELECT 
	                            l.*,
                                p.libelle as libelle_pack, 
                                p.tarif as tarif_pack ,
                                m.libelle as libelle_module ,
                                m.tarif as tarif_module  
                            FROM 
                                konnect_ligne l 
                                LEFT JOIN konnect k ON k.id = l.konnect
                                LEFT JOIN module m ON m.id = l.module
                                LEFT JOIN pack p ON p.id = l.pack
                            WHERE 
                                k.user = '".$_SESSION['id']."'  AND  
                                k.etat = '1' 
                             ";

        $search_query = ''; 
 
        $order_query = 'ORDER BY  l.created ASC ';
    

        $limit_query = ''; 

        $object->query = $main_query . $search_query . $order_query;

        $object->execute();

        $filtered_rows = $object->row_count();

        $object->query .= $limit_query; 

        $result = $object->get_result(); 

        $object->query = $main_query;

        $object->execute();

        $total_rows = $object->row_count();

        $data = array();  
        
        $total = 0 ; 

        foreach ($result as $row) {
             
            $sub_array = array(); 
            $prix = ($row['type'] == 'pack') ? $row['tarif_pack'] : $row['tarif_module']  ;
            $total += $prix ;
            $sub_array[]    = ($row['type'] == 'pack') ? 'Pack : '.ucwords($row['libelle_pack']) : 'Module : '.ucwords($row['libelle_module']); 
	        $sub_array[]    =  '<span class="prix_ligne" id="prix_ligne'.$row['id'].'" data-id="'.$row['id'].'" data-type="'.$row['type'].'">'.number_format($prix,3,',',' ').' TND <i data-id="'.$row['id'].'"    class="fas  fa-trash text-danger fs-5 delete-checkout"></i>';
	        
            $data[] = $sub_array;
        }
        
         $sub_array = array();    
        $sub_array[]    =  '<hr>';
        $sub_array[]    =  '<hr>';
        
        $data[] = $sub_array;
        
         $sub_array = array();    
        $sub_array[]    =  '<b>Total</b>';
        $sub_array[]    =  '<b>'.number_format($total,3,',',' ').' TND </b>';
        
        $data[] = $sub_array;

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $filtered_rows,
            "data" => $data,
        );

        echo json_encode($output);

    }
    
    if($_POST['action'] == 'fetch_konnect'){
        
	    $object->query ="  SELECT 
	                            l.*,
                                p.libelle as libelle_pack, 
                                p.tarif as tarif_pack ,
                                m.libelle as libelle_module ,
                                m.tarif as tarif_module  
                            FROM 
                                konnect_ligne l 
                                LEFT JOIN konnect k ON k.id = l.konnect
                                LEFT JOIN module m ON m.id = l.module
                                LEFT JOIN pack p ON p.id = l.pack
                            WHERE 
                                k.user = '".$_SESSION['id']."'  AND  
                                k.etat = '1' 
                            ORDER BY 
                               	l.created ASC  ";
 
	    $result = $object->get_result();
 
	    $data = array();
 
	    foreach($result as $row)
	    {
	        $prix = ($row['type'] == 'pack') ? $row['tarif_pack'] : $row['tarif_module']  ;
	        $sub_array = array(); 
	        $sub_array['id']            = $row['id']; 
	        $sub_array['type']          = $row['type']; 
	        $sub_array['produit']       = ($row['type'] == 'pack') ? 'Pack : '.ucwords($row['libelle_pack']) : 'Module : '.ucwords($row['libelle_module']); 
	        $sub_array['tarif']          =  '<span class="prix_ligne" id="prix_ligne'.$row['id'].'" data-id="'.$row['id'].'" data-type="'.$row['type'].'">'.number_format($prix,3,',',' ').' TND <i data-id="'.$row['id'].'"  style="    position: absolute;    right: 7px;" class="fas  fa-trash text-danger fs-5 delete-panier"></i>';
	        $sub_array['prix']          =  $prix;
	                 
	        
	        $data[] = $sub_array;
	    }
	    echo json_encode($data);
    } 
    
    if($_POST["action"] == 'delete'){
        
    	$success = '';
    	
    	$error = '';
    	  
		$data = array(
			':id'   =>	$_POST['id'],  
		);

		$object->query = "DELETE FROM  konnect_ligne WHERE id = :id ";

		$object->execute($data);
        
		$data = array(
			':id'   =>	$_POST['id'],  
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            konnect_ligne 
		                    WHERE 
		                         id = :id ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
		    $error = "Erreur d'insertion, Merci d'essayer plus tard ";
		}else{
		     $success = 'Produit supprimé avec succès'; 
		}
		
    	$output= array(
    	    'success'   => $success,
    	    'error'     => $error, 
    	    'counted'   => $object->countPanierKonnect($_SESSION['id'] ),
	    );
	    
	    echo json_encode($output);
        
    }
  
    if($_POST["action"] == 'add_produit'){
        
    	$success = '';
    	
    	$error = '';
        
		$data = array( 
			':user'	    =>	$_SESSION['id'], 
			':etat'     =>  '1' 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            konnect 
		                    WHERE  
		                        user = :user AND 
		                        etat = :etat ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
    		    $konnect = json_decode($object->get_one_konnect($_SESSION['id'] ));
		    
	    		$data = array(
	    		    ':konnect'      =>  $konnect->id, 
        			':type'         =>  $_POST['type'],
        			':key'          =>  $object->clean_input($object->decrypt($_POST['key'])),
        		);
        		
        		$key = $_POST['type'];
        
        		$object->query = "  SELECT 
        		                        * 
        	                        FROM 
        	                            konnect_ligne 
        		                    WHERE 
        		                        konnect = :konnect AND 
        		                        type = :type AND 
        		                        $key = :key ";
        
        		$object->execute($data);
        
        		if($object->row_count() > 0)
        		{
        		    $error = "Déjà existe";
        		    
        		} else{   
            	    $dataligne = array( 
        	            ':konnect'           =>  $konnect->id,
        	            ':type'             =>  $_POST['type'],
        	            ':pack'             => ($_POST['type'] == 'pack') ? $object->clean_input($object->decrypt($_POST['key'])) : null, 
                        ':module'           => ($_POST['type'] == 'module') ? $object->clean_input($object->decrypt($_POST['key'])) : null, 
        	        );
            	        
            	    $object->query ="INSERT INTO konnect_ligne( 
                                            konnect,
                                            type,
                                            pack,
                                            module 
                                        )
                                        VALUES(
                                            :konnect,
                                            :type,
                                            :pack,
                                            :module 
                                        )";
            	    
            	    $object->execute($dataligne);
            	    
    	        	if($object->connect->lastInsertId() > 0){
                	    $success = 'Produit ajouté au panier'; 
                	}else{
                	    $error = "Erreur d'insertion, Merci d'essayer plus tard ";
                	}
        		}
		    
		}else{
		     
    	    $data = array( 
    	        ':user'     =>  $_SESSION['id'],  
    	        ':etat'     => '1'
	        );
    	        
    	    $object->query ="INSERT INTO konnect( user ,   etat ) 
    	                                 VALUES (  :user,   :etat ) ";
    	    
    	    $object->execute($data);
    	    
    	    $konnect = $object->connect->lastInsertId() ;
    	    
    		$data = array(
    		    ':konnect'      =>  $konnect, 
    			':type'         =>  $_POST['type'],
    			':key'          =>  $object->clean_input($object->decrypt($_POST['key'])),
    		);
    		
    		$key = $_POST['type'];
    
    		$object->query = "  SELECT 
    		                        * 
    	                        FROM 
    	                            konnect_ligne 
    		                    WHERE 
    		                        konnect = :konnect AND 
    		                        type = :type AND 
    		                        $key = :key ";
    
    		$object->execute($data);
    
    		if($object->row_count() > 0)
    		{
    		    $error = "Déjà existe";
    		    
    		} else{ 
    	    
        	    $data = array(  
    	            ':konnect'          =>  $konnect ,
    	            ':type'             =>  $_POST['type'],
    	            ':pack'             => ($_POST['type'] == 'pack') ? $object->clean_input($object->decrypt($_POST['key'])) : null, 
                    ':module'           => ($_POST['type'] == 'module') ? $object->clean_input($object->decrypt($_POST['key'])) : null, 
    	        );
        	        
        	    $object->query ="INSERT INTO konnect_ligne(  konnect, type, pack, module ) 
        	                                      VALUES  ( :konnect, :type,   :pack, :module ) ";
        	    
        	    $object->execute($data); 
        	    
	        	if($object->connect->lastInsertId() > 0){
            	    $success = 'Produit ajouté au panier'; 
            	}else{
            	    $error = "Erreur d'insertion, Merci d'essayer plus tard ";
            	}
    		}
        	 
		}
		

    	

    	
    	$output= array(
    	    'success'   => $success,
    	    'error'     => $error, 
    	    'counted'   => $object->countPanierKonnect($_SESSION['id'] ),
	    );
	    
	    echo json_encode($output);
	}
    
}