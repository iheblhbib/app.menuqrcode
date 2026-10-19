<?php

include('../config.menuqrcode/config.php');
include('planning-functions.php');

$object = new application(); 

$market = json_decode($object->get_market($_POST['market'])); 
 
$page_actuel = $object->get_page_url();

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch_archive') {
        
        $addons = ($_POST['categorie']) ? " AND categorie =  '".$_POST['categorie']."' " : '';

        $output = array();

        $main_query = "  SELECT * FROM categorie WHERE market = '".$market->id."' AND etat = '1' ".$addons;

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR statut LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }
 
        $order_query = 'ORDER BY order_categorie ASC ';
    

        $limit_query = '';

        if ($_POST["length"] != -1) {
            $limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $object->query = $main_query . $search_query . $order_query;

        $object->execute();

        $filtered_rows = $object->row_count();

        $object->query .= $limit_query;  

        $result = $object->get_result(); 

        $object->query = $main_query;

        $object->execute();

        $total_rows = $object->row_count();

        $data = array();
        
        if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
            $result = null;
        }

        foreach ($result as $row) {
            $sub_array = array(); 
            
            $vip  = ($market->vip == '2' && $row['vip'] == 2 ) ? ' vip_line ' : ''; 
            
			$row['image'] = str_replace('../','https://',$row['image']); 
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
            }else{
                 $sub_array[] = '--';
            }
             
            $sub_array[] = $object->clean_input($row["libelle"] )  .' <b title="Sous Catégories" class="suffix text-primary '.$vip.'">'.$object->CountTable($market->id,'article',$row["id"]).'</b> ' ;
            
            $sub_array[] = ($row['etat_prix']) ? $row['prix'] : '' ;
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>';
             
            }else{  
                $sub_array[] = '--';
            }
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'categorie', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            }
            
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) {   
                $sub_array[] = '<div class="text-nowrap">
                <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                        <button  type="button" name="delete_button" class=" btn btn-success btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-light fa-trash-undo"></i></button>
                        </div>'; 
            }else{  
                $sub_array[] = '--';
            }
             
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $filtered_rows,
            "data" => $data,
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'fetch') {
        
        $addons = ($_POST['categorie']) ? " AND categorie =  '".$_POST['categorie']."' " : '';

        $output = array();

        $main_query = "  SELECT * FROM categorie WHERE market = '".$market->id."' AND etat = '0' ".$addons;

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR statut LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }
 
        $order_query = 'ORDER BY order_categorie ASC ';
    

        $limit_query = '';

        if ($_POST["length"] != -1) {
            $limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $object->query = $main_query . $search_query . $order_query;

        $object->execute();

        $filtered_rows = $object->row_count();

        $object->query .= $limit_query;  

        $result = $object->get_result(); 

        $object->query = $main_query;

        $object->execute();

        $total_rows = $object->row_count();

        $data = array();
        
        if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
            $result = null;
        }

        foreach ($result as $row) {
            $sub_array = array(); 
            
            $vip  = ($market->vip == '2' && $row['vip'] == 2 ) ? ' vip_line ' : ''; 
            
			$row['image'] = str_replace('../','https://',$row['image']); 
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
            }else{
                 $sub_array[] = '--';
            }
             
            $sub_array[] = $object->clean_input($row["libelle"] )  .' <b title="Sous Catégories" class="suffix text-primary '.$vip.'">'.$object->CountTable($market->id,'article',$row["id"]).'</b> ' ;
            
            $sub_array[] = ($row['etat_prix']) ? $row['prix'] : '' ;
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
            
            if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>';
                      
            
            }else{
                
                $sub_array[] = '--'; 
            }
             
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'categorie', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            }
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
 
                $sub_array[] = '<div class="text-nowrap">
                <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-light fa-trash-xmark"></i></button>
                       </div> ';
            
            }else{ 
                
                $sub_array[] = '--';
            }
             
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $filtered_rows,
            "data" => $data,
        );

        echo json_encode($output);

    }
    
    if($market->multi_langue == '1'){ 
        
        $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
        
        $result = $object->get_result();
        
        foreach($result as $raw){
    
        	if($_POST["action"] == 'fetch'.$raw['code'])
        	{
        	    $addons = ($_POST['categorie']) ? " AND c.categorie =  '".$_POST['categorie']."' " : '';
        	    
                $output = array();
        
                $main_query = " SELECT
                                    c.*,
                                    l.libelle AS langue
                                FROM
                                    categorie c
                                LEFT JOIN langue_categorie l ON
                                    l.categorie = c.id
                                WHERE
                                      l.code = '".$raw['code']."' AND c.market = '".$market->id."' AND c.etat = '0' ".$addons;
        
                $search_query = '';
        
                if (isset($_POST["search"]["value"])) {
                    $search_query .= ' AND (l.libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
                    $search_query .= 'OR c.statut LIKE "%' . $_POST["search"]["value"] . '%" ) ';
                }
         
                $order_query = 'ORDER BY c.order_categorie ASC ';
            
        
                $limit_query = '';
        
                if ($_POST["length"] != -1) {
                    $limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
                }
        
                $object->query = $main_query . $search_query . $order_query;
        
                $object->execute();
        
                $filtered_rows = $object->row_count();
        
                $object->query .= $limit_query;  
        
                $result = $object->get_result(); 
        
                $object->query = $main_query;
        
                $object->execute();
        
                $total_rows = $object->row_count();
        
                $data = array();
                
                if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
                    $result = null;
                }
        
                foreach ($result as $row) {
                    
                    $vip  = ($market->vip == '2' && $row['vip'] == 2 ) ? ' vip_line ' : ''; 
                    
                    $sub_array = array(); 
                    
        			$row['image'] = str_replace('../','https://',$row['image']); 
                    
                    $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';

                    $sub_array[] = $object->clean_input($row["langue"] )  .' <b title="Sous Catégories" class="suffix text-primary '.$vip.'">'.$object->CountTable($market->id,'article',$row["id"]).'</b> ' ;
                    
                    $sub_array[] = ($row['etat_prix']) ? $row['prix'] : '' ;
                    
                    $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['langue'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;"  alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
                     
                     if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                        $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>';
                              
                    
                    }else{
                        
                        $sub_array[] = '--'; 
                    }
                     
                    $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'categorie', $row['id']);
                    
                    if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                        $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
                    }else{
                        $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
                    }
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
         
                        $sub_array[] = '<div class="text-nowrap">
                        <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                                <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                               <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-light fa-trash-xmark"></i></button>
                              </div>  ';
                    
                    }else{ 
                        
                        $sub_array[] = '--';
                    }
                    $data[] = $sub_array;
                }
        
                $output = array(
                    "draw" => intval($_POST["draw"]),
                    "recordsTotal" => $total_rows,
                    "recordsFiltered" => $filtered_rows,
                    "data" => $data,
                );
        
                echo json_encode($output);
        
        	}
        }
    }
       
    if ($_POST["action"] == 'Add') {
        $error = '';

        $success = '';

		$data = array(
			':libelle'	    =>	$_POST["libelle"],
			':categorie'	=>	($_POST["categorie"]) ? $_POST['categorie'] : null, 
			':market'	    =>	$market->id, 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            categorie 
		                    WHERE 
		                        libelle = :libelle AND 
		                        categorie = :categorie AND 
		                        market  = :market 
		                        and etat = '0' ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Déjà existe</div>';
		} else if(($error = planning_validate($_POST)) != '') {
            // créneau invalide : rien n'est enregistré
        }
		else
		{ 
		    
	        $image = '';
			if($_FILES["image"]["name"] != '')
			{
				$image = upload_image();
			}
			else
			{
			    
				$image = '../app.menuqrcode.tn/img/no-photo.webp';
			} 
		    
            $data = array( 
				':market'			            =>	$object->clean_input($_POST["market"]),
				':categorie'	                =>	(($_POST["categorie"]) ? $_POST['categorie'] : null), 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':image'          	            =>  $image, 
				':order_categorie'	            =>	$object->getMaximum($_POST["market"],'categorie',(($_POST["categorie"]) ? $_POST['categorie'] : null)) +1 ,
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) , 
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null ,  
				':etat_prix'		            =>	(!empty($_POST["etat_prix"]) ? $_POST["etat_prix"] : "1") , 
				':prix'		                    =>	($_POST["prix"] ) ? ($_POST["prix"]) : null , 
			);
            
            $object->query = "INSERT INTO categorie( 
                                                market,
                                                categorie,
                                                libelle, 
                                                image, 
                                                order_categorie,
                                                icon,
                                                display_image,
                                                vip,
                                                code_vip ,
                                                etat_prix , 
                                                prix
                                            ) VALUES (
                                                :market,
                                                :categorie,
                                                :libelle, 
                                                :image, 
                                                :order_categorie,
                                                :icon,
                                                :display_image,
                                                :vip,
                                                :code_vip  ,
                                                :etat_prix , 
                                                :prix
                                                )	";
		
			$object->execute($data); 
			
			$categorie = $object->connect->lastInsertId();
			
            if ($categorie > 0) {
                $success = 'Mis à jour avec succés';

                planning_save($object, $market->id, 'categorie', $categorie, $_POST);
                
                    if($market->multi_langue == '1'){ 
        
                        $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                        
                        $result = $object->get_result();
                        
                        foreach($result as $row){
                           
                            $data = array(
                                ':langue'           => $object->clean_input($row['id']),
                                ':categorie'        => $object->clean_input($categorie),
                                ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                                ':code'             => $object->clean_input($row['code']),
                            );
            
            
                            $object->query = "
            			        INSERT INTO langue_categorie (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code)
            			    ";
            
            			    $object->execute($data);
                        }
                    }
            }else{
                $error = '<div class="alert alert-danger">Erreur d\'insertion</div>'; 
            } 
		}

        $output = array(
            'error' => $error,
            'success' => $success,
            'data' => $data ,
            'id' => $object->connect->lastInsertId()
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'fetch_single') {
        $object->query = "  SELECT 
                                *   
                            FROM 
                                categorie
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            
			$row['image'] = str_replace('../','https://',$row['image']); 
			
			$data['libelle']            = $object->clean_input($row['libelle']);
			
			$data['categorie']          = ($_POST["categorie"]) ? $_POST['categorie'] : null ; 
			
			$data['image']              = $row['image']; 
			
			$data['icon']               = $row['icon']; 
			
			$data['display_image']      = $row['display_image']; 
			
			$data['vip']                = $row['vip']; 
			
			$data['code_vip']           = $row['code_vip'];  
			
			$data['etat_prix']          = $row['etat_prix'];  
			
			$data['prix']               = $row['prix'];  
        }
        
        if($market->multi_langue == '1'){ 
    		
    		$object->query="SELECT
                                *
                            FROM
                                langue_categorie
                            WHERE
                                categorie = '".$_POST["id"]."' ";
                                    
            $result = $object->get_result();
            
            foreach($result as $row)
            {
                $data['libelle'.$row['code']] = $object->clean_input($row['libelle']);
            }
        }

        $data['planning'] = planning_get($object, 'categorie', $_POST["id"]);


        echo json_encode($data);
    }
 
    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';
        
        if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
    
            $data = array(
    			':libelle'	=>	$_POST["libelle"], 
    			':categorie'	=>	($_POST["categorie"]) ? $_POST['categorie'] : null, 
    			':market'	=>	$market->id, 
                ':id'       => $_POST['hidden_id'],
            );
    
            $object->query = "  SELECT
                                    *
                                FROM
                                    categorie
                                WHERE
                                    libelle = :libelle AND 
                                    categorie = :categorie AND 
                                    market = :market AND 
                                    id != :id and etat = '0' 
    		";
    
            $object->execute($data);
    
            if ($object->row_count() > 0) {
                $error = '<div class="alert alert-danger">Déjà existe</div>';
            } else if(($error = planning_validate($_POST)) != '') {
            // créneau invalide : rien n'est enregistré
        } else {
    
    	
		    $image = $_POST["hidden_categorie_image"];
    		if($_FILES["image"]["name"] != '')
    		{
    			$image = upload_image();
    		}else{
    		    $image    = str_replace('https://','../',$_POST["hidden_categorie_image"]);  
    		} 
		    
            $data1 = array( 
                ':categorie'	                =>	 $_POST["categorie"]  , 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':image'          	            =>  $image,  
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) ,
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null ,  
			    ':etat_prix'		            =>	(!empty($_POST["etat_prix"]) ? $_POST["etat_prix"] : "1") , 
				':prix'		                    =>	($_POST["prix"] ) ? ($_POST["prix"]) : null , 
				':id'                           => $_POST['hidden_id'],
				
			);

            $object->query = "UPDATE categorie SET 
                                            categorie = :categorie,
                                			libelle = :libelle,
                                			image = :image, 
                                			icon = :icon ,
                                			display_image = :display_image , 
                                			vip = :vip, 
                                			code_vip = :code_vip, 
                                			etat_prix = :etat_prix, 
                                			prix = :prix 

                        			WHERE id = :id ";
			$object->execute($data1);

            planning_save($object, $market->id, 'categorie', $_POST['hidden_id'], $_POST);
 
            if($market->multi_langue == '1'){ 
        
                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                
                $result = $object->get_result();
                
                foreach($result as $row){
                    
                    $data = array( 
                        ':categorie'     => $object->clean_input($_POST['hidden_id']),
                        ':code'         => $object->clean_input($row['code']),
                    );
                    
                    $object->query = "  SELECT 
                                            * 
                                        FROM 
                                            langue_categorie 
            		                    WHERE  
            		                        categorie = :categorie AND code = :code  ";
                    $object->execute($data);
        
            		if($object->row_count() > 0)
            		{
                        $data = array(
                            ':libelle'      =>  ($object->clean_input($_POST['libelle'.$row['code']])),
                            ':code'         => $object->clean_input($row['code']),
                            ':categorie'       => $object->clean_input($_POST['hidden_id']),
                            );
        
                        $object->query = " UPDATE langue_categorie SET
                    			            libelle = :libelle
                    			           WHERE code = :code and categorie = :categorie ";
                  
        			    $object->execute($data);
            		}
            		else
            		{
                        $data = array(
                            ':langue'           => $object->clean_input($row['id']),
                            ':categorie'        => $object->clean_input($_POST['hidden_id']),
                            ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                            ':code'             => $object->clean_input($row['code']),
                        );
         
                        $object->query = " INSERT INTO langue_categorie (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code) ";
        
        			    $object->execute($data);
            		}
                }
            }
    
                $success = 'Mis à jour avec succés';
            }
    
        }
        $output = array(
            'error' => $error,
            'success' => $success, 
        );

        echo json_encode($output);
    }

    if ($_POST["action"] == 'statut') {
        $data = array(
            ':statut' => $_POST['next_statut'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE categorie SET 
                                    statut = :statut
	                        WHERE 
	                            id = :id ";

        $object->execute($data);
        
        $object->change_statut_table($_SESSION['market'],$_SESSION['id'],$_POST['id'],$_POST['next_statut'],'statut_categorie') ;

        // changement manuel : le cron ne doit plus écraser ce choix
        planning_pause($object, 'categorie', $_POST['id']);

        echo 'Mis à jour avec succés';
    }
    
          
    if ($_POST["action"] == 'etat') {
        $data = array(
            ':etat' => $_POST['next_etat'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE categorie  SET 
                                    etat = :etat
	                        WHERE 
	                            id = :id ";

        $object->execute($data); 

        echo 'Mis à jour avec succés';
    }
    
 	if($_POST["action"] == 'update_order')
	{
	    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
    	    for($i=0; $i<count($_POST["id_table"]); $i++)
            {
                $data = array(
                    ':order_categorie' => $_POST["newpos"][$i],
                    ':id' => $_POST["id_table"][$i],
                );
            
                $object->query = "  UPDATE categorie  SET 
                                        order_categorie = :order_categorie 
                                    WHERE id = :id ";
                 
                $object->execute($data);
            }
            
            echo 'Mis à jour avec succés';
	    }else{
	        echo 'Aucune modification';
	    }
	}
	
    if ($_POST["action"] == 'duplicate') {
        
        $success = ''; 
        
        $error = '' ; 
 
        
        $object->query = "SELECT * FROM categorie WHERE id = '".$_POST['id']."'";
        $result_categorie = $object->get_result();

        foreach ($result_categorie as $categorie) {
 
            $id_categorie = $object->duplicateData( 'categorie', $categorie , ['id','market'] ,$_POST['market'] );
 
            $object->duplicateLangueData( 'langue_categorie', $id_categorie, 'categorie', $categorie['id']);

            $object->query = "SELECT * FROM article WHERE categorie = '".$categorie['id']."'";
            
            $result_article = $object->get_result();

            foreach ($result_article as $article) {
 
                $article['categorie'] = $id_categorie;
                $id_article = $object->duplicateData( 'article', $article , ['id','market'] ,$_POST['market'] );
 
                $object->duplicateLangueData( 'langue_article', $id_article, 'article', $article['id']);
            }
        }
        
        if($id_categorie > 0){ 
            $success = 'Catégorie dupliqué avec succès';
        }else{
            $error =  "Erreur d\'insertion";
        }  
        
        
        $output = array(
            'error' => $error,
            'success' => $success, 
        );

        echo json_encode($output);
        
    }
 
}


function upload_image()
{
    if(isset($_FILES["image"]))
    {
        $filename = $_FILES['image']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = rand() . '.' . $extension;
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        $source_path = $_FILES['image']['tmp_name'];

        // Set the desired maximum width and height
        $max_width = 800;
        $max_height = 600;

        // Get the original image dimensions
        list($width, $height) = getimagesize($source_path);

        // Calculate the proportional scaling factor
        $scale = min($max_width / $width, $max_height / $height);

        // Calculate the new dimensions
        $new_width = $width * $scale;
        $new_height = $height * $scale;

        // Create a new image with the resized dimensions and a transparent background
        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagesavealpha($resized_image, true);
        $transparent_color = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
        imagefill($resized_image, 0, 0, $transparent_color);

        // Load the original image based on its extension
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $source_image = imagecreatefromjpeg($source_path);
        } elseif ($extension === 'png') {
            $source_image = imagecreatefrompng($source_path);
        } elseif ($extension === 'webp') {
            $source_image = imagecreatefromwebp($source_path);
        } else {
            // Unsupported image format
            return false;
        }

        // Enable alpha blending for transparent images
        imagealphablending($resized_image, true);
        imagesavealpha($resized_image, true);

        // Resize the image
        imagecopyresampled($resized_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Save the resized image based on its extension
        if ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($resized_image, $destination);
        } elseif ($extension === 'png') {
            imagepng($resized_image, $destination);
        } elseif ($extension === 'webp') {
            imagewebp($resized_image, $destination);
        }

        // Free up memory
        imagedestroy($resized_image);
        imagedestroy($source_image);

        return $destination;
    }
}