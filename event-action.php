<?php

include('../config.menuqrcode/config.php');

$object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

$page_actuel = $object->get_page_url();

if(isset($_POST["action"]))
    {
    	if($_POST["action"] == 'fetch')
    	{
    		$order_column = array('order_event', 'banner','titre','texte','debut', 'fin', 'statut');
    
    		$output = array();
    
    		$main_query = "
    		SELECT * FROM event WHERE market = '".$_SESSION['market']."' and statut = 'Activer' 
    		";
    
    		$search_query = '';
    
    		if(isset($_POST["search"]["value"]))
    		{
    			$search_query .= ' AND (fin LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR debut LIKE "%'.$_POST["search"]["value"].'%"  ) ';
    		}
    
    		if(isset($_POST["order"]))
    		{
    			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
    		}
    		else
    		{
    			$order_query = 'ORDER BY order_event ASC ';
    		}
    
    		$limit_query = '';
    
    		if($_POST["length"] != -1)
    		{
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
    		
    		foreach($result as $row)
    		{	 
    			$sub_array = array(); 
    			
				$row['banner'] = str_replace('../','https://',$row['banner']);  
    			 
    		    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
             
                    $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';
    			}else{
                     $sub_array[] = '--';
                }
	            $sub_array[] = '<a href="'.$row["banner"].'" class="glightbox" data-glightbox="title: '.$row['titre'].'; "  ><img src="'.$row["banner"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="50" height="50"></a>';
                $sub_array[] = html_entity_decode($row["titre"]);
    			$sub_array[] = html_entity_decode($row["texte"]);
    			$sub_array[] = $row["debut"];
    			$sub_array[] = $row["fin"]; 
    			
                $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
                
                if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

                    $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
                  
        			$sub_array[] = '<button style="font-size:x-small" type="button" name="edit_button" title="Éditer" class="btn btn-circle btn-sm btn-warning  edit_button" data-id="'.$row["id"].'"><i class="fas fa-edit"></i></button>'; 
                }else{
                    $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>';
                    
                    $sub_array[] = '--'; 
                }
    			
    			$data[] = $sub_array;
    	
    		}
    
    		$output = array(
    			"draw"    			=> 	intval($_POST["draw"]),
    			"recordsTotal"  	=>  $total_rows,
    			"recordsFiltered" 	=> 	$filtered_rows,
    			"data"    			=> 	$data
    		);
    			
    		echo json_encode($output);
    
    	}
    	if($_POST["action"] == 'fetch2')
    	{
    		$order_column = array( 'banner','titre','texte','debut', 'fin', 'statut');
    
    		$output = array();
    
    		$main_query = "
    		SELECT * FROM event WHERE market = '".$_SESSION['market']."' and statut != 'Activer' 
    		";
    
    		$search_query = '';
    
    		if(isset($_POST["search"]["value"]))
    		{
    			$search_query .= ' AND (fin LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR debut LIKE "%'.$_POST["search"]["value"].'%"  ) ';
    		}
    
    		if(isset($_POST["order"]))
    		{
    			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
    		}
    		else
    		{
    			$order_query = 'ORDER BY order_event ASC ';
    		}
    
    		$limit_query = '';
    
    		if($_POST["length"] != -1)
    		{
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
    		
    		foreach($result as $row)
    		{	 
    			$sub_array = array(); 
    			
				$row['banner'] = str_replace('../','https://',$row['banner']);  
	            $sub_array[] = '<a href="'.$row["banner"].'" class="glightbox" data-glightbox="title: '.$row['titre'].'; "  ><img src="'.$row["banner"].'" style="background-color: #2c5199;" alt="image" class="img-fluid img-thumbnail" width="50" height="50"></a>';
                $sub_array[] = html_entity_decode($row["titre"]);
    			$sub_array[] = html_entity_decode($row["texte"]);
    			$sub_array[] = $row["debut"];
    			$sub_array[] = $row["fin"]; 
    			
                $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
                if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

                    $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
                  
        			$sub_array[] = '<button style="font-size:x-small" type="button" name="edit_button" title="Éditer" class="btn btn-circle btn-sm btn-warning  edit_button" data-id="'.$row["id"].'"><i class="fas fa-edit"></i></button>'; 
                }else{
                    $sub_array[] = '<span  class="text-' . $statut . '  "  >' .$row["statut"]. '</span>';
                    
                    $sub_array[] = '--'; 
                }
    			$data[] = $sub_array;
    	
    		}
    
    		$output = array(
    			"draw"    			=> 	intval($_POST["draw"]),
    			"recordsTotal"  	=>  $total_rows,
    			"recordsFiltered" 	=> 	$filtered_rows,
    			"data"    			=> 	$data
    		);
    			
    		echo json_encode($output);
    
    	}
    
    	if($_POST["action"] == 'Add')
    	{
    		$error = '';
    
    		$success = '';
    

			$banner = '';
			if($_FILES["banner"]["name"] != '')
			{
				$banner = upload_image();
			}
			else
			{
				$banner = 'img/defaultbanner.jpg';
			}
			
                $data = array(
				':market'		=>    $object->clean_input($_SESSION["market"]),
				':titre'		=>   $object->clean_input($_POST["titre"]),
				':texte'		=>   $object->clean_input($_POST["texte"]),
				':debut'		=>   $object->clean_input($_POST["debut"]),
				':fin'      	=>	$object->clean_input($_POST["fin"]) , 
				':banner'		=>	$banner 
			);
            
            
            $object->query = "
			INSERT INTO event 
			       ( market,  titre,   texte,  debut,  fin,  banner ) 
			VALUES (:market, :titre , :texte, :debut, :fin, :banner )
			";
      
			$object->execute($data);


			$success = 'Événements Ajouté';
    		
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success
    		);
    
    		echo json_encode($output);
    
    	}
    
    	if($_POST["action"] == 'fetch_single')
    	{
    		$object->query = "
    		SELECT * FROM event 
    		WHERE id = '".$_POST["id"]."'
    		";
    
    		$result = $object->get_result();
    
    		$data = array();
    
    		foreach($result as $row)
    		{  
    		    
    			$data['debut'] = $row['debut'];
    			$data['fin'] = $row['fin']; 
    			$data['banner'] = str_replace('../','https://',$row['banner']); ;
    			$data['titre'] = html_entity_decode($row['titre']);
    			$data['texte'] = html_entity_decode($row['texte']);
    		}
    
    		echo json_encode($data);
    	}
    
    	if($_POST["action"] == 'Edit')
    	{
    		$error = '';
    
    		$success = '';
     
    		
		    $banner = $_POST["hidden_banner"];
    		if($_FILES["banner"]["name"] != '')
    		{
    			$banner = upload_image();
    		}else{
    		    $banner    = str_replace('https://','../',$_POST["hidden_banner"]);  
    		} 
    
    	
			$data = array( 
		    	':titre'		=>   $object->clean_input($_POST["titre"]),
				':texte'		=>   $object->clean_input($_POST["texte"]),
		        ':debut'        =>	$object->clean_input($_POST["debut"]) ,
				':fin'          =>	$object->clean_input($_POST["fin"]) , 
				':banner'		=>	$banner
			
			);

			$object->query = "
			UPDATE event 
			SET  
			titre = :titre, 
			texte = :texte, 
			debut = :debut, 
			fin = :fin, 
			banner = :banner
			WHERE id = '".$_POST['hidden_id']."'
			";
            
			$object->execute($data);

			$success = 'Événements modifié';
    
    
    	
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success
    		);
    
    		echo json_encode($output);
    
    	}
    
    	if($_POST["action"] == 'delete')
    	{
    
    		$object->query = "
    		DELETE FROM event   
    		WHERE id = '".$_POST["id"]."'
    		";
    
    		$object->execute();
    
    		echo 'Événements supprimé';
    	}
    	
		if($_POST["action"] == 'change_statut')
    	{
    		$data = array(
    			':statut'		=>	$_POST['next_statut']
    		); 
    
    		$object->query = "
    		UPDATE event 
    		SET statut = :statut 
    		WHERE id = '".$_POST["id"]."'
    		";
    
    		$object->execute($data);
    
    		echo 'Événements '.$_POST['next_statut'].'';
    	}
    	
        	    
     	if($_POST["action"] == 'update_order')
    	{
    	    for($i=0; $i<count($_POST["id_table"]); $i++)
            {
                $data = array(
                    ':order_event' => $_POST["newpos"][$i],
                    ':id' => $_POST["id_table"][$i],
                );
            
                $object->query = "  UPDATE event  SET 
                                        order_event = :order_event 
                                    WHERE id = :id ";
                 
                $object->execute($data);
            }
            
            echo 'Mis à jour avec succés';
    	}

    
 
    }
    
 
 function upload_image()
{
    if (isset($_FILES['banner'])) {
        // uniquement des images, sous un nom aléatoire (jamais le nom d'origine)
        $new_name = securite_nom_fichier($_FILES['banner'], array('jpg', 'jpeg', 'png', 'webp', 'gif'));
        if ($new_name === false) {
            securite_refuser(400, 'Format de bannière non autorisé (JPG, PNG, WEBP ou GIF).');
        }
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        move_uploaded_file($_FILES['banner']['tmp_name'], $destination);
        return $destination;
    }
} 
 
?>