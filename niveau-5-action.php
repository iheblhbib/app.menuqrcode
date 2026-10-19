<?php

include('../config.menuqrcode/config.php');

$object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

$market = json_decode($object->get_market($_POST['market'])); 

$page_actuel = $object->get_page_url();

$type = ['' ,'Lien Externe', 'Menu Interne' , 'PDF'] ;

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch_archive') {

        $output = array();

        $main_query = "  SELECT * FROM categorie_sub_sub_sub WHERE market = '".$market->id."' AND etat = '1' ";

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
            
            
            $vip  = ($market->vip == '2' && $row['vip'] == 2 ) ? ' vip_line ' : ''; 
            
            $sub_array = array(); 
            
			$row['image'] = str_replace('../','https://',$row['image']);
			
			$row['background'] = str_replace('../','https://',$row['background']);
			
			if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
			}else{
                 $sub_array[] = '--';
            }

            $sub_array[] = $object->clean_input($row["libelle"] );
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
  
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>';
            }
            
            $sub_array[] = $type[$row['type']] ; 
            
            $sub_array[] =  ($row['type'] == '1' ) ? '<a target="_blank"  href="'.$row['external_link'].'" ><i class="fa-duotone fa-link"></i> Lien Externe</a> ' : '' ;
            
            $sub_array[] =  ($row['type'] == '2' ) ? '<a class="menu-link"  data-link="https://menuqrcode.tn/qrcode?data='.$object->encrypt($row['market_link']).'"  href="#" ><i class="fa-duotone fa-link"></i> Menu Interne</a>' : '' ;
            
            $sub_array[] =  ($row['type'] == '3' ) ? '<a target="_blank"  href="'.$row['pdf'].'" ><i class="fa-duotone fa-file-pdf"></i> PDF</a> ' : '' ;
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

                 
                $sub_array[] = '
                    <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
                    <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fad fa-times"></i></button>
                    ';
       
            
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

        $output = array();

        $main_query = "  SELECT * FROM categorie_sub_sub_sub WHERE market = '".$market->id."' AND etat = '0' ";

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
            
            
            $vip  = ($market->vip == '2' && $row['vip'] == 2 ) ? ' vip_line ' : ''; 
            
            $sub_array = array(); 
            
			$row['image'] = str_replace('../','https://',$row['image']);
			
			$row['background'] = str_replace('../','https://',$row['background']);
			
			if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
			}else{
                 $sub_array[] = '--';
            }

            $sub_array[] = $object->clean_input($row["libelle"] );
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
  
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>';
            }
            
            $sub_array[] = $type[$row['type']] ; 
            
            $sub_array[] =  ($row['type'] == '1' ) ? '<a target="_blank"  href="'.$row['external_link'].'" ><i class="fa-duotone fa-link"></i> Lien Externe</a> ' : '' ;
            
            $sub_array[] =  ($row['type'] == '2' ) ? '<a class="menu-link"  data-link="https://menuqrcode.tn/qrcode?data='.$object->encrypt($row['market_link']).'"  href="#" ><i class="fa-duotone fa-link"></i> Menu Interne</a>' : '' ;
            
            $sub_array[] =  ($row['type'] == '3' ) ? '<a target="_blank"  href="'.$row['pdf'].'" ><i class="fa-duotone fa-file-pdf"></i> PDF</a> ' : '' ;
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

                 
                $sub_array[] = '
                    <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
                   <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fad fa-times"></i></button>
                    ';
                 
            
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
                $output = array();
        
                $main_query = " SELECT
                                    c.*,
                                    l.libelle AS langue
                                FROM
                                    categorie_sub_sub_sub c
                                LEFT JOIN langue_categorie_sub_sub_sub l ON
                                    l.categorie = c.id
                                WHERE
                                      l.code = '".$raw['code']."' AND c.market = '".$market->id."' AND c.etat = '0'  ";
        
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
        			
        			$row['background'] = str_replace('../','https://',$row['background']);
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
             
                        $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
        			}else{
                         $sub_array[] = '--';
                    }

                    $sub_array[] = $object->clean_input($row["langue"] );
                     
                    $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
                     
                    $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
  
                    $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
                    
                    if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                        $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
                    }else{
                        $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>';
                    }
                                
                    $sub_array[] = $type[$row['type']] ; 
            
                    $sub_array[] =  ($row['type'] == '1' ) ? '<a target="_blank"  href="'.$row['external_link'].'" ><i class="fa-duotone fa-link"></i> Lien Externe</a> ' : '' ;
                    
                    $sub_array[] =  ($row['type'] == '2' ) ? '<a class="menu-link"  data-link="https://menuqrcode.tn/qrcode?data='.$object->encrypt($row['market_link']).'"  href="#" >' : '' ;
                    
                    $sub_array[] =  ($row['type'] == '3' ) ? '<a target="_blank"  href="'.$row['pdf'].'" ><i class="fa-duotone fa-file-pdf"></i> PDF</a> ' : '' ;
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
         
                        
                    $sub_array[] = '
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
                        <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fad fa-times"></i></button>
                        ';
                    
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
			':libelle'	=>	$_POST["libelle"], 
			':market'	=>	$market->id, 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            categorie_sub_sub_sub 
		                    WHERE 
		                        libelle = :libelle AND 
		                        market  = :market and etat = '0' ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Déjà existe</div>';
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
			
	        $background = '';
			if($_FILES["background"]["name"] != '')
			{
				$background = upload_background();
			}
			else
			{
				$background = '../app.menuqrcode.tn/img/carte_bg.gif';
			}
			
			$pdf = '';
			if($_FILES["pdf"]["name"] != '')
			{
				$pdf = upload_file();
			}
		    
            $data = array(
				':market'			            =>	$object->clean_input($_POST["market"]),
				':libelle'			            =>	$object->clean_input($_POST["libelle"]), 
				':type'			                =>	$object->clean_input($_POST["type"]), 
				':image'          	            =>  $image,
				':background'          	        =>  $background, 
				':market_link'			        =>	($_POST["type"] == 2 ) ? $object->clean_input($_POST["market_link"]) : null ,
				':external_link'		        =>	($_POST["type"] == 1 ) ? $object->clean_input($_POST["external_link"]) : null ,
				':pdf'		                    =>	($_POST["type"] == 3 ) ? $pdf : null ,
				':order_categorie'	            =>	$object->getMaximum($_POST["market"],'categorie_sub_sub_sub') +1 ,
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) ,
				':display_background'		    =>	($_POST["display_background"]) , 
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null , 
			);
            
            $object->query = "INSERT INTO categorie_sub_sub_sub( 
                                                market,
                                                libelle, 
                                                type, 
                                                image,
                                                background,  
                                                market_link,
                                                external_link,
                                                pdf,
                                                order_categorie,
                                                icon,
                                                display_image,
                                                display_background,
                                                vip,
                                                code_vip
                                            ) VALUES (
                                                :market,
                                                :libelle,  
                                                :type,  
                                                :image,
                                                :background,  
                                                :market_link,
                                                :external_link,
                                                :pdf,
                                                :order_categorie,
                                                :icon,
                                                :display_image,
                                                :display_background,
                                                :vip,
                                                :code_vip
                                                )	";
		
			$object->execute($data); 
			
			$categorie = $object->connect->lastInsertId();
			
            if ($categorie > 0) {
                $success = 'Mis à jour avec succés';
                
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
            			        INSERT INTO langue_categorie_sub_sub_sub (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code)
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
                                categorie_sub_sub_sub
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            
			$row['image'] = str_replace('../','https://',$row['image']);
			
			$row['background'] = str_replace('../','https://',$row['background']);
			
			$data['libelle']            = $object->clean_input($row['libelle']); 
			
			$data['image']              = $row['image'];
			
			$data['background']         = $row['background'];
			
			$data['type']               = $row['type'];
			
			$data['market_link']        = $row['market_link'];
			
			$data['external_link']      = $row['external_link'];
			
			$data['pdf']                = $row['pdf'];
			
			$data['icon']               = $row['icon']; 
			
			$data['display_image']      = $row['display_image']; 
			
			$data['display_background'] = $row['display_background']; 
			
			$data['vip']                = $row['vip']; 
			
			$data['code_vip']           = $row['code_vip']; 
        }
        
        if($market->multi_langue == '1'){ 
    		
    		$object->query="SELECT
                                *
                            FROM
                                langue_categorie_sub_sub_sub
                            WHERE
                                categorie = '".$_POST["id"]."' ";
                                    
            $result = $object->get_result();
            
            foreach($result as $row)
            {
                $data['libelle'.$row['code']] = $object->clean_input($row['libelle']);
            }
        }

        echo json_encode($data);
    }
 
    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';

        $data = array(
			':libelle'	=>	$_POST["libelle"], 
			':market'	=>	$market->id, 
            ':id'       => $_POST['hidden_id'],
        );

        $object->query = "  SELECT
                                *
                            FROM
                                categorie_sub_sub_sub
                            WHERE
                                libelle = :libelle AND 
                                market = :market AND 
                                id != :id and etat = '0' 
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Déjà existe</div>';
        } else {

	
		    $image = $_POST["hidden_categorie_image"];
    		if($_FILES["image"]["name"] != '')
    		{
    			$image = upload_image();
    		}else{
    		    $image    = str_replace('https://','../',$_POST["hidden_categorie_image"]);  
    		}
    
    		$background =  $_POST["hidden_categorie_background"];
    		if($_FILES["background"]["name"] != '')
    		{
    			$background = upload_background();
    		} else{
    		    $background    = str_replace('https://','../',$_POST["hidden_categorie_background"]);  
    		}
    		
    		$pdf = $_POST["hidden_categorie_pdf"];
    		
    		if($_FILES["pdf"]["name"] != '')
    		{
    			$pdf =  upload_file();
    		}

		    
            $data1 = array( 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]), 
				':type'			            =>	$object->clean_input($_POST["type"]), 
				':image'          	            =>  $image,
				':background'          	        =>  $background,
				':pdf'			                =>	($_POST["type"] == 3 ) ? $pdf : null ,
				':market_link'			        =>	($_POST["type"] == 2 ) ? $object->clean_input($_POST["market_link"]) : null ,
				':external_link'		        =>	($_POST["type"] == 1 ) ? $object->clean_input($_POST["external_link"]) : null ,
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) ,
				':display_background'		    =>	($_POST["display_background"]) ,
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null , 
				':id'                           =>  $_POST['hidden_id'],
				
			);

            $object->query = "UPDATE categorie_sub_sub_sub SET 
                                			libelle = :libelle, 
                                			type = :type, 
                                			image = :image,
                                			background = :background,
                                			pdf= :pdf,
                                			market_link = :market_link,
                                			external_link = :external_link,  
                                			icon = :icon ,
                                			display_image = :display_image ,
                                			display_background = :display_background, 
                                			vip = :vip, 
                                			code_vip = :code_vip 

                        			WHERE id = :id ";
			$object->execute($data1);
 
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
                                            langue_categorie_sub_sub_sub 
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
        
                        $object->query = " UPDATE langue_categorie_sub_sub_sub SET
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
         
                        $object->query = " INSERT INTO langue_categorie_sub_sub_sub (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code) ";
        
        			    $object->execute($data);
            		}
                }
            }

            $success = 'Mis à jour avec succés';
        }

        $output = array(
            'error' => $error,
            'success' => $success,
            'data' => $data1,
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'statut') {
        $data = array(
            ':statut' => $_POST['next_statut'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE categorie_sub_sub_sub SET 
                                    statut = :statut
	                        WHERE 
	                            id = :id ";

        $object->execute($data);
        
        $object->change_statut_table($_SESSION['market'],$_SESSION['id'],$_POST['id'],$_POST['next_statut'],'statut_categorie_sub_sub_sub') ;

        echo 'Mis à jour avec succés';
    }

    if ($_POST["action"] == 'etat') {
        $data = array(
            ':etat' => $_POST['next_etat'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE categorie_sub_sub_sub SET 
                                    etat = :etat
	                        WHERE 
	                            id = :id ";

        $object->execute($data); 

        echo 'Mis à jour avec succés';
    }
    
 	if($_POST["action"] == 'update_order')
	{
	    for($i=0; $i<count($_POST["id_table"]); $i++)
        {
            $data = array(
                ':order_categorie' => $_POST["newpos"][$i],
                ':id' => $_POST["id_table"][$i],
            );
        
            $object->query = "  UPDATE categorie_sub_sub_sub  SET 
                                    order_categorie = :order_categorie 
                                WHERE id = :id ";
             
            $object->execute($data);
        }
        
        echo 'Mis à jour avec succés';
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


function upload_background()
{
    if(isset($_FILES["background"]))
    {
        $filename = $_FILES['background']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = rand() . '.' . $extension;
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        $source_path = $_FILES['background']['tmp_name'];

        // Set the desired maximum width and height
        $max_width = 1080;
        $max_height = 1920;

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

 
function upload_file()
{
    if (isset($_FILES["pdf"])) {
        // uniquement de vrais fichiers PDF, sous un nom aléatoire
        $new_name = securite_nom_fichier($_FILES['pdf'], array('pdf'));
        if ($new_name === false) {
            securite_refuser(400, 'Seuls les fichiers PDF sont acceptés.');
        }
        $destination = 'pdf/' . $new_name;
        move_uploaded_file($_FILES['pdf']['tmp_name'], $destination);
        return $destination;
    }
}
