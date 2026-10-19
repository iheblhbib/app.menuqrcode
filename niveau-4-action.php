<?php

include('../config.menuqrcode/config.php');
include('planning-functions.php');

$object = new application(); 

$market = json_decode($object->get_market($_POST['market'])); 

$page_actuel = $object->get_page_url();

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch_archive') {

        $output = array();

        $main_query = "  SELECT * FROM categorie_sub_sub WHERE market = '".$market->id."'  AND etat = '1' ";

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

            $sub_array[] = $object->clean_input($row["libelle"] ) .' <b title="Sous Catégories" class="suffix text-primary ">'.$object->CountTable($market->id,'categorie_sub',$row["id"]).'</b> ' ;
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
            
            if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>'; 
            }else{
                $sub_array[] = '--'; 
            }
  
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';

            $planning_badge = planning_badge_for($object, $market->id, 'categorie_sub_sub', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            }
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) {  
                 
                 $sub_array[] = '<div class="text-nowrap">
                 <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-duotone fa-trash-xmark"></i></button>
                    </div>    ';
            
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

        $main_query = "  SELECT * FROM categorie_sub_sub WHERE market = '".$market->id."'  AND etat = '0' ";

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

            $sub_array[] = $object->clean_input($row["libelle"] ) .' <b title="Sous Catégories" class="suffix text-primary ">'.$object->CountTable($market->id,'categorie_sub',$row["id"]).'</b> ' ;
            
            $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
             
            $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
  
              if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>'; 
            }else{
                $sub_array[] = '--'; 
            }
            
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';

            $planning_badge = planning_badge_for($object, $market->id, 'categorie_sub_sub', $row['id']);
            
            if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
            }else{
                $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
            }
            
            if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

                
                $sub_array[] = '<div class="text-nowrap">
                <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                        <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
                       <button  type="button" name="delete_button" class=" btn btn-danger btn-sm delete_button" data-id="' . $row["id"] . '" data-etat="' . $row["etat"] . '"><i class="fa-duotone fa-trash-xmark"></i></button>
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
                                    categorie_sub_sub c
                                LEFT JOIN langue_categorie_sub_sub l ON
                                    l.categorie = c.id
                                WHERE
                                      l.code = '".$raw['code']."' AND c.market = '".$market->id."'  AND c.etat = '0' ";
        
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

                    $sub_array[] = $object->clean_input($row["langue"] ) .' <b title="Sous Catégories" class="suffix text-primary ">'.$object->CountTable($market->id,'categorie_sub',$row["id"]).'</b> ' ;
                     
                    $sub_array[] = ($row['display_image'] == '2' ) ? '' : '<a href="'.$row["image"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["image"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
                     
                    $sub_array[] = ($row['display_background'] == '2' ) ? '' :'<a href="'.$row["background"].'" class="glightbox '.$vip.'" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["background"].'" alt="image" class="img-fluid img-thumbnail" width="35" height="35"></a>';
   
                    if($object->autorisation('r',$_SESSION['id'],$page_actuel) == 2 ) {  
                        $sub_array[] = '<button  type="button" name="categorie_liste" class="btn btn-sm btn-info categorie_liste" data-id="'.$row["id"].'"><i class="fa-duotone fa-list-tree"></i></button>'; 
                    }else{
                        $sub_array[] = '--'; 
                    }
            
                    $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';

                    $planning_badge = planning_badge_for($object, $market->id, 'categorie_sub_sub', $row['id']);
                    
                    if($object->autorisation('d',$_SESSION['id'],$page_actuel) == 2 ) { 
                        $sub_array[] = '<button  type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>' . $planning_badge;
                    }else{
                        $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>' . $planning_badge;
                    }
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
        
                        
                        $sub_array[] = '
                        <div class="text-nowrap">
                        <button  type="button" name="duplicate_button" class=" btn btn-info btn-sm duplicate_button" data-id="' . $row["id"] . '"><i class="fa-duotone fa-copy"></i></button>
                            <button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>
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
			':libelle'	=>	$_POST["libelle"], 
			':market'	=>	$market->id, 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            categorie_sub_sub 
		                    WHERE 
		                        libelle = :libelle AND 
		                        market  = :market and etat = '0' ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Déjà existe</div>';
		}
		else if(($error = planning_validate($_POST)) != '')
		{
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
			
	        $background = '';
			if($_FILES["background"]["name"] != '')
			{
				$background = upload_background();
			}
			else
			{
				$background = '../app.menuqrcode.tn/img/carte_bg.gif';
			}
		    
            $data = array(
				':market'			            =>	$object->clean_input($_POST["market"]),
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
			//	':address'			            =>	$object->clean_input($_POST["address"]),
			//	':tel'			                =>	$object->clean_input($_POST["tel"]),
			//	':email'			            =>	$object->clean_input($_POST["email"]),
				':image'          	            =>  $image,
			//	':background'          	        =>  $background,
			//	':background_color'			    =>	$object->clean_input($_POST["background_color"]),
			//	':text_color'			        =>	$object->clean_input($_POST["text_color"]),
			//	':article_text_color'		    =>	$object->clean_input($_POST["article_text_color"]),
				':order_categorie'	            =>	$object->getMaximum($_POST["market"],'categorie_sub_sub') +1 ,
			//	':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) ,
			//	':display_background'		    =>	($_POST["display_background"]) , 
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null , 
			//	':facebook'		                =>	($_POST["facebook"]) ? $object->clean_input($_POST["facebook"]) : null,
			//	':instagram'		            =>	($_POST["instagram"]) ? $object->clean_input($_POST["instagram"]) : null,
			//	':wifi_ssid'		            =>	($_POST["wifi_ssid"]) ? $object->clean_input($_POST["wifi_ssid"]) : null,
			//	':wifi_password'		        =>	($_POST["wifi_password"]) ? $object->clean_input($_POST["wifi_password"]) : null,
			);
            
            $object->query = "INSERT INTO categorie_sub_sub( 
                                                market,
                                                libelle,  
                                                image, 
                                                order_categorie, 
                                                display_image, 
                                                vip,
                                                code_vip 
                                            ) VALUES (
                                                :market,
                                                :libelle,  
                                                :image, 
                                                :order_categorie,
                                                :display_image,
                                                :vip,
                                                :code_vip 
                                                )	";
		
			$object->execute($data); 
			
			$categorie = $object->connect->lastInsertId();
			
            if ($categorie > 0) {
                $success = 'Mis à jour avec succés';

                planning_save($object, $market->id, 'categorie_sub_sub', $categorie, $_POST);

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
            			        INSERT INTO langue_categorie_sub_sub (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code)
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
                                categorie_sub_sub
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            
			$row['image'] = str_replace('../','https://',$row['image']);
			
			$row['background'] = str_replace('../','https://',$row['background']);
			
			$data['libelle']            = $object->clean_input($row['libelle']);
			
			$data['address']            = $object->clean_input($row['address']);
			
			$data['tel']                = $object->clean_input($row['tel']);
			
			$data['email']              = $object->clean_input($row['email']);
			
			$data['image']              = $row['image'];
			
			$data['background']         = $row['background'];
			
			$data['background_color']   = $row['background_color'];
			
			$data['text_color']         = $row['text_color'];
			
			$data['article_text_color'] = $row['article_text_color'];
			
			$data['icon']               = $row['icon']; 
			
			$data['display_image']      = $row['display_image']; 
			
			$data['display_background'] = $row['display_background']; 
			
			$data['vip']                = $row['vip']; 
			
			$data['code_vip']           = $row['code_vip']; 
			
			$data['facebook']           = $row['facebook']; 
			
			$data['instagram']          = $row['instagram']; 
			
			$data['wifi_ssid']          = $row['wifi_ssid']; 
			
			$data['wifi_password']      = $row['wifi_password'];
        }

        $data['planning'] = planning_get($object, 'categorie_sub_sub', $_POST["id"]);
        
        if($market->multi_langue == '1'){ 
    		
    		$object->query="SELECT
                                *
                            FROM
                                langue_categorie_sub_sub
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
                                categorie_sub_sub
                            WHERE
                                libelle = :libelle AND 
                                market = :market AND 
                                id != :id and etat = '0' 
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Déjà existe</div>';
        } else if (($error = planning_validate($_POST)) != '') {
            // créneau invalide : rien n'est enregistré
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
		    
            $data1 = array( 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
			//	':address'			            =>	$object->clean_input($_POST["address"]),
			//	':tel'			                =>	$object->clean_input($_POST["tel"]),
			//	':email'			            =>	$object->clean_input($_POST["email"]),
				':image'          	            =>  $image,
			//	':background'          	        =>  $background,
			//	':background_color'			    =>	$object->clean_input($_POST["background_color"]),
			//	':text_color'			        =>	$object->clean_input($_POST["text_color"]),
			//	':article_text_color'		    =>	$object->clean_input($_POST["article_text_color"]), 
			//	':icon'		                    =>	($_POST["icon"]) ? $object->clean_input($_POST["icon"]) : null,
				':display_image'		        =>	($_POST["display_image"]) ,
			//	':display_background'		    =>	($_POST["display_background"]) ,
				':vip'		                    =>	($_POST["vip"]) ?  $_POST["vip"] : '1' , 
				':code_vip'		                =>	($_POST["vip"] == '2') ? ($_POST["code_vip"]) : null ,  
				//':facebook'		                =>	($_POST["facebook"]) ? $object->clean_input($_POST["facebook"]) : null,
				//':instagram'		            =>	($_POST["instagram"]) ? $object->clean_input($_POST["instagram"]) : null,
				//':wifi_ssid'		            =>	($_POST["wifi_ssid"]) ? $object->clean_input($_POST["wifi_ssid"]) : null,
				//':wifi_password'		        =>	($_POST["wifi_password"]) ? $object->clean_input($_POST["wifi_password"]) : null,
				':id'                           => $_POST['hidden_id'],
				
			);

            $object->query = "UPDATE categorie_sub_sub SET 
                                			libelle = :libelle, 
                                			image = :image, 
                                			display_image = :display_image , 
                                			vip = :vip, 
                                			code_vip = :code_vip  

                        			WHERE id = :id ";
			$object->execute($data1);

            planning_save($object, $market->id, 'categorie_sub_sub', $_POST['hidden_id'], $_POST);

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
                                            langue_categorie_sub_sub 
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
        
                        $object->query = " UPDATE langue_categorie_sub_sub SET
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
         
                        $object->query = " INSERT INTO langue_categorie_sub_sub (langue, categorie, libelle, code) VALUES (:langue, :categorie, :libelle, :code) ";
        
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

        $object->query = "  UPDATE categorie_sub_sub SET 
                                    statut = :statut
	                        WHERE 
	                            id = :id ";

        $object->execute($data);
        
        $object->change_statut_table($_SESSION['market'],$_SESSION['id'],$_POST['id'],$_POST['next_statut'],'statut_categorie_sub_sub') ;

        // changement manuel : le cron ne doit plus écraser ce choix
        planning_pause($object, 'categorie_sub_sub', $_POST['id']);

        echo 'Mis à jour avec succés';
    }
    
    if ($_POST["action"] == 'etat') {
        $data = array(
            ':etat' => $_POST['next_etat'],
            ':id' => $_POST['id'],
        );

        $object->query = "  UPDATE categorie_sub_sub SET 
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
        
            $object->query = "  UPDATE categorie_sub_sub  SET 
                                    order_categorie = :order_categorie 
                                WHERE id = :id ";
             
            $object->execute($data);
        }
        
        echo 'Mis à jour avec succés';
	}
	
	if ($_POST["action"] == 'duplicate') {
    
    $success = ''; 
    $error = '';  

    // Récupérer la catégorie du niveau 4 (categorie_sub_sub)
    $object->query = "SELECT * FROM categorie_sub_sub WHERE id = '".$_POST['id']."'";
    $result_sub_sub_categorie = $object->get_result();

    foreach ($result_sub_sub_categorie as $sub_sub_categorie) {

        // Dupliquer la catégorie du niveau 4
        $id_sub_sub_categorie = $object->duplicateData('categorie_sub_sub', $sub_sub_categorie, ['id', 'market'], $_POST['market']);
        $object->duplicateLangueData('langue_categorie_sub_sub', $id_sub_sub_categorie, 'categorie', $sub_sub_categorie['id']);

        // Récupérer et dupliquer les sous-catégories associées (Niveau 3 - categorie_sub)
        $object->query = "SELECT * FROM categorie_sub WHERE categorie = '".$sub_sub_categorie['id']."'";
        $result_sub_categorie = $object->get_result();

        foreach ($result_sub_categorie as $sub_categorie) {
            
            // Dupliquer la sous-catégorie du niveau 3
            $sub_categorie['categorie'] = $id_sub_sub_categorie; // Mise à jour du parent
            $id_sub_categorie = $object->duplicateData('categorie_sub', $sub_categorie, ['id', 'market'], $_POST['market']);
            $object->duplicateLangueData('langue_categorie_sub', $id_sub_categorie, 'categorie', $sub_categorie['id']);

            // Récupérer et dupliquer les sous-sous-catégories associées (Niveau 2 - categorie)
            $object->query = "SELECT * FROM categorie WHERE categorie = '".$sub_categorie['id']."'";
            $result_categorie = $object->get_result();

            foreach ($result_categorie as $categorie) {
                
                // Dupliquer la sous-sous-catégorie du niveau 2
                $categorie['categorie'] = $id_sub_categorie; // Mise à jour du parent
                $id_categorie = $object->duplicateData('categorie', $categorie, ['id', 'market'], $_POST['market']);
                $object->duplicateLangueData('langue_categorie', $id_categorie, 'categorie', $categorie['id']);

                // Récupérer et dupliquer les articles liés à cette sous-sous-catégorie (Niveau 1 - article)
                $object->query = "SELECT * FROM article WHERE categorie = '".$categorie['id']."'";
                $result_article = $object->get_result();

                foreach ($result_article as $article) {

                    $article['categorie'] = $id_categorie; // Mise à jour du parent
                    $id_article = $object->duplicateData('article', $article, ['id', 'market'], $_POST['market']);
                    $object->duplicateLangueData('langue_article', $id_article, 'article', $article['id']);
                }
            }
        }
    }
    
    if ($id_sub_sub_categorie > 0) { 
        $success = 'Catégorie et sous-niveaux dupliqués avec succès';
    } else {
        $error = "Erreur d'insertion";
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
