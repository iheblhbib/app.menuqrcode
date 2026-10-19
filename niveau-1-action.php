<?php

include('../config.menuqrcode/config.php');
include('planning-functions.php');

$object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

$market = json_decode($object->get_market($_POST['market'])); 
 
$page_actuel = $object->get_page_url();

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch_archive') {
          
        $output = array();
        
        $addons =  ($market->niveau != '1') ?  " AND categorie =  '".$_POST['categorie']."' " :  " " ; 

        $main_query = "  SELECT * FROM article WHERE market = '".$market->id."' AND etat = '1' $addons ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR prix LIKE "%' . $_POST["search"]["value"] . '%"   ';
            $search_query .= 'OR description LIKE "%' . $_POST["search"]["value"] . '%"   ';
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
            
			$row['image'] = str_replace('../','https://',$row['image']); 
             
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fa-duotone fa-arrows-alt"></i></span>';
            }else{
                 $sub_array[] = '--';
            }
            
            $sub_array[] = $object->clean_input($row["libelle"] )  ;
            
            $sub_array[] = (is_numeric($row["prix"])) ? $row["prix"].' DT' :  $row["prix"] ;
            
            $sub_array[] =  html_entity_decode($row["description"] )  ;
            
            $allergens = json_decode($object->get_allergens_by_article($row['id'])) ; 
            
            $ligneAllergen = '';
            foreach($allergens as $allergen){
                $ligneAllergen .= $allergen->libelle . ' ' . $allergen->emoji . '<br>' ;  
            }
            
            $sub_array[] =  $ligneAllergen;
            
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' :'<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            if($market->ingredient == '2')
            { 
                if($object->route($_SESSION['id'], 'niveau-0') == 2 )
                { 
                    $sub_array[] = '<i style="font-size:large;" class="fa-duotone fa-external-link-alt text-dark article_liste" data-id="'.$row["id"].'"></i>';
                }else{
                    $sub_array[] = '--';
                } 
            }
            
             $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'article', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) 
            {
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else
            {
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            } 
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) 
            {
                 $sub_array[] = '<div class="text-nowrap">
                 <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-duotone fa-trash-undo"></i></button>
                        </div>';
            }else
            {
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
        
        $addons =  ($market->niveau != '1') ?  " AND categorie =  '".$_POST['categorie']."' " :  " " ;

        $main_query = "  SELECT * FROM article WHERE market = '".$market->id."' AND etat = '0' $addons ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR prix LIKE "%' . $_POST["search"]["value"] . '%"   ';
            $search_query .= 'OR description LIKE "%' . $_POST["search"]["value"] . '%"   ';
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
            
			$row['image'] = str_replace('../','https://',$row['image']); 
             
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fa-duotone fa-arrows-alt"></i></span>';
            }else{
                 $sub_array[] = '--';
            }

            $sub_array[] = $object->clean_input($row["libelle"] ) ;
            
            $sub_array[] = (is_numeric($row["prix"])) ? $row["prix"].' DT' :  $row["prix"] ;
            
            $sub_array[] =  html_entity_decode($row["description"] )  ;
                       
            $allergens = json_decode($object->get_allergens_by_article($row['id'])) ; 
            
            $ligneAllergen = '';
            foreach($allergens as $allergen){
                $ligneAllergen .= $allergen->libelle . ' ' . $allergen->emoji . '<br>' ;  
            }
            
            $sub_array[] =  $ligneAllergen;
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' :'<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
            
            
            if($market->ingredient == '2')
            { 
                if($object->route($_SESSION['id'], 'niveau-0') == 2 )
                {
                    // $sub_array[] = '<button  type="button" name="article_liste" class="btn btn-sm btn-success article_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-external-link-alt"></i></button>';
                    $sub_array[] = '<i style="font-size:large;" class="fa-duotone fa-external-link-alt text-dark article_liste" data-id="'.$row["id"].'"></i>';
                }else{
                    $sub_array[] = '--';
                } 
            }
            
             $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'article', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) 
            {
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else
            {
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            } 
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) 
            {
                 $sub_array[] = '<div class="text-nowrap text-end">
                        <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-duotone fa-trash-xmark"></i></button>
                       </div> ';
            }else
            {
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
                
                $addons =  ($market->niveau != '1') ?  " AND a.categorie =  '".$_POST['categorie']."' " :  " " ;
        
                $main_query = " SELECT
                                    a.*,
                                    l.libelle AS langue_libelle,
                                    l.prix AS langue_prix,
                                    l.description AS langue_description
                                FROM
                                    article a
                                LEFT JOIN langue_article l ON
                                    l.article = a.id
                                WHERE
                                      l.code = '".$raw['code']."' AND a.market = '".$market->id."' AND a.etat = '0'  $addons  ";
        
                $search_query = '';
        
                if (isset($_POST["search"]["value"])) {
                    $search_query .= ' AND (l.libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
                    $search_query .= 'OR a.statut LIKE "%' . $_POST["search"]["value"] . '%" ) ';
                }
         
                $order_query = 'ORDER BY a.order_categorie ASC ';
            
        
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
                    
        			$row['image'] = str_replace('../','https://',$row['image']); 
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                        $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fa-duotone fa-arrows-alt"></i></span>';
                    }else{
                         $sub_array[] = '--';
                    }

                    $sub_array[] = $object->clean_input($row["langue_libelle"] )  ; 
            
                    $sub_array[] = (is_numeric($row["langue_prix"])) ? $row["langue_prix"].' DT' :  $row["langue_prix"] ;
                    
                    $sub_array[] =  html_entity_decode($row["langue_description"] )  ;
                                
                    $allergens = json_decode($object->get_allergens_by_article($row['id'])) ; 
                    
                    $ligneAllergen = '';
                    foreach($allergens as $allergen){
                        $ligneAllergen .= $allergen->libelle . ' ' . $allergen->emoji . '<br>' ;  
                    }
                    
                    $sub_array[] =  $ligneAllergen;
                    
                    $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['langue_libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;"  alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
                     
                    if($market->ingredient == '2')
                    { 
                        if($object->route($_SESSION['id'], 'niveau-0') == 2 )
                        {
                            $sub_array[] = '<button  type="button" name="article_liste" class="btn btn-sm btn-success article_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-external-link-alt"></i></button>';
                            // $sub_array[] = '<i style="font-size:large;" class="fa-duotone fa-external-link-alt text-dark article_liste" data-id="'.$row["id"].'"></i>';
                        }else
                        {
                            $sub_array[] = '--';
                        } 
                    }
                    
                     $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; $planning_badge = planning_badge_for($object, $market->id, 'article', $row['id']);
                    
                    if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) 
                    {
                        $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
                    }else
                    {
                        $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
                    } 
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) {
                    $sub_array[] = '<div class="text-nowrap">
                    <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-sliders"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-duotone fa-trash-xmark"></i></button>
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
        }
    }
      

    if ($_POST["action"] == 'Add') {
        $error = '';

        $success = '';

		$data = array(
			':libelle'	    =>	$_POST["libelle"],
			':prix'	        =>	$_POST["prix"],
			':categorie'	=>	($_POST["categorie"]) ? $_POST['categorie'] : null, 
			':market'	    =>	$market->id, 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            article 
		                    WHERE 
		                        libelle = :libelle AND 
		                        prix = :prix AND 
		                        categorie = :categorie AND 
		                        market  = :market and etat = '0' ";

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
				':categorie'	                => ($_POST["categorie"]) ? $object->clean_input($_POST['categorie']) : null, 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':prix'			                =>	$object->clean_input($_POST["prix"]),
				':description'			        =>	$object->clean_input($_POST["description"]),
				':image'          	            =>  $image, 
				':order_categorie'	            =>	$object->getMaximum($_POST["market"],'article',$_POST["categorie"]) +1 ,
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) , 
				':type'		        =>	($_POST["type"]) , 
			);
            
            $object->query = "INSERT INTO article( 
                                                market,
                                                categorie,
                                                libelle, 
                                                prix, 
                                                description, 
                                                image, 
                                                order_categorie,
                                                icon,
                                                display_image ,
                                                type
                                            ) VALUES (
                                                :market,
                                                :categorie,
                                                :libelle, 
                                                :prix, 
                                                :description, 
                                                :image, 
                                                :order_categorie,
                                                :icon,
                                                :display_image ,
                                                :type
                                                )	";
		
			$object->execute($data); 
			
			$article = $object->connect->lastInsertId();
			
            if ($article > 0) {
                $success = 'Mis à jour avec succés';

                planning_save($object, $market->id, 'article', $article, $_POST);
                
                    if($market->multi_langue == '1'){ 
        
                        $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                        
                        $result = $object->get_result();
                        
                        foreach($result as $row){
                           
                            $data = array(
                                ':langue'           => $object->clean_input($row['id']),
                                ':article'          => $object->clean_input($article),
                                ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                                ':prix'             => $object->clean_input($_POST['prix'.$row['code']]),
                                ':description'      => $object->clean_input($_POST['description'.$row['code']]),
                                ':code'             => $object->clean_input($row['code']),
                            );
            
            
                            $object->query = " INSERT INTO langue_article (langue, article, libelle,prix,description, code) VALUES (:langue, :article, :libelle,:prix,:description, :code) ";
            
            			    $object->execute($data);
                        }
                    }
                    
                    if (!empty($_POST['allergens']) && is_array($_POST['allergens'])) {
                        $allergens = $_POST['allergens'];   
                        foreach ($allergens as $allergen_id) {
                            $object->query = " INSERT INTO  allergens_article (article, allergen) VALUES (:article, :allergen) ";
                      
                            $data = array(
                                'article' => $article,
                                'allergen' => $allergen_id
                            );
                            
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
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'fetch_single') {
        $object->query = "  SELECT 
                                *   
                            FROM 
                                article
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            
			$row['image'] = str_replace('../','https://',$row['image']); 
			
			$data['libelle']            = $object->clean_input($row['libelle']);
			
			$data['prix']               = $object->clean_input($row['prix']);
			
			$data['description']        = html_entity_decode($row['description']);
			
			$data['categorie']          = ($_POST["categorie"]) ? $_POST['categorie'] : null ; 
			
			$data['image']              = $row['image']; 
			
			$data['icon']               = $row['icon']; 
			
			$data['type']               = $row['type']; 
			
			$data['display_image']      = $row['display_image'];  
        }
        
        $object->query = "SELECT * FROM allergens_article WHERE article = '" . $_POST["id"] . "'";
        $allergen_result = $object->get_result();
        
        $selected_allergens = [];
        foreach ($allergen_result as $allergen_row) {
            $selected_allergens[] = $allergen_row['allergen'];
        }
        
        // Add to the response data
        $data['allergens'] = $selected_allergens;
        
        if($market->multi_langue == '1'){ 
    		
    		$object->query="SELECT
                                *
                            FROM
                                langue_article
                            WHERE
                                article = '".$_POST["id"]."' ";
                                    
            $result = $object->get_result();
            
            foreach($result as $row)
            {
                $data['libelle'.$row['code']] = $object->clean_input($row['libelle']);
                $data['prix'.$row['code']] = $object->clean_input($row['prix']);
                $data['description'.$row['code']] = html_entity_decode($row['description']);
            }
        }

        $data['planning'] = planning_get($object, 'article', $_POST["id"]);


        echo json_encode($data);
    }
 
    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';

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
				':categorie'	                =>	($_POST["categorie"]) ? $object->clean_input($_POST['categorie']) : null, 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':prix'			                =>	$object->clean_input($_POST["prix"]),
				':description'			        =>	$object->clean_input($_POST["description"]),
				':image'          	            =>  $image,  
				':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) , 
				':type'		                    =>	($_POST["type"]) , 
				':id'                           =>  $_POST['hidden_id'],
				
			);

            $object->query = "UPDATE article SET 
                                			categorie = :categorie,
                                			libelle = :libelle,
                                			prix = :prix,
                                			description = :description,
                                			image = :image, 
                                			icon = :icon ,
                                			display_image = :display_image ,
                                			type = :type 

                        			WHERE id = :id ";
			$object->execute($data1);

            planning_save($object, $market->id, 'article', $_POST['hidden_id'], $_POST);
 
            if($market->multi_langue == '1'){ 
        
                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                
                $result = $object->get_result();
                
                foreach($result as $row){
                    
                    $data = array( 
                        ':article'     => $object->clean_input($_POST['hidden_id']),
                        ':code'         => $object->clean_input($row['code']),
                    );
                    
                    $object->query = "  SELECT 
                                            * 
                                        FROM 
                                            langue_article 
            		                    WHERE  
            		                        article = :article AND code = :code  ";
                    $object->execute($data);
        
            		if($object->row_count() > 0)
            		{
                        $data = array(
                            ':libelle'          =>  ($object->clean_input($_POST['libelle'.$row['code']])),
                            ':prix'             =>  ($object->clean_input($_POST['prix'.$row['code']])),
                            ':description'      =>  ($object->clean_input($_POST['description'.$row['code']])),
                            ':code'             => $object->clean_input($row['code']),
                            ':article'        => $object->clean_input($_POST['hidden_id']),
                            );
        
                        $object->query = " UPDATE langue_article SET
                    			            libelle = :libelle,
                    			            prix = :prix,
                    			            description = :description
                    			           WHERE code = :code and article = :article ";
                  
        			    $object->execute($data);
            		}
            		else
            		{
                        $data = array(
                            ':langue'           => $object->clean_input($row['id']),
                            ':article'          => $object->clean_input($_POST['hidden_id']),
                            ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                            ':code'             => $object->clean_input($row['code']),
                            ':prix'             => ($object->clean_input($_POST['prix'.$row['code']])),
                            ':description'      => ($object->clean_input($_POST['description'.$row['code']])),
                        );
         
                        $object->query = " INSERT INTO langue_article (langue, article, libelle, code, prix, description) VALUES  (:langue, :article, :libelle, :code, :prix, :description) ";
        
        			    $object->execute($data);
            		}
                }
            }
            
                
            $object->query = " DELETE FROM  allergens_article WHERE article = :article";
      
            $data = array(
                ':article' => $_POST['hidden_id'], 
            );
            
            $object->execute($data);
                
           if (!empty($_POST['allergens']) && is_array($_POST['allergens'])) {
                     
                $allergens = $_POST['allergens'];   
                foreach ($allergens as $allergen_id) {
                    $object->query = " INSERT INTO  allergens_article (article, allergen) VALUES (:article, :allergen) ";
              
                    $data = array(
                        ':article' => $_POST['hidden_id'],
                        ':allergen' => $allergen_id
                    );
                    
                    $object->execute($data);
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

    if ($_POST["action"] == 'duplicate') {
        
        $success = ''; 
        
        $error = '' ; 
        
        $object->query = "SELECT * FROM article WHERE id = '".$_POST['id']."'";
        
        $result_article = $object->get_result();

        foreach ($result_article as $article) {
  
            $id_article = $object->duplicateData( 'article', $article, ['id','market'],$_POST['market']);
            
            if($id_article > 0){
                $object->duplicateLangueData( 'langue_article', $id_article, 'article', $article['id']);
                
                $success = 'article dupliqué avec succès';
            }else{
                $error =  "Erreur d\'insertion";
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

        $object->query = "  UPDATE article SET 
                                    statut = :statut
	                        WHERE 
	                            id = :id ";

        $object->execute($data);
        
        $object->change_statut_table($_SESSION['market'],$_SESSION['id'],$_POST['id'],$_POST['next_statut'],'statut_article') ;

        // changement manuel : le cron ne doit plus écraser ce choix
        planning_pause($object, 'article', $_POST['id']);

        echo 'Mis à jour avec succés';
    }
    
              
    if ($_POST["action"] == 'etat') {
        $data = array(
            ':etat' => $_POST['next_etat'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE article  SET 
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
        
            $object->query = "  UPDATE article  SET 
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