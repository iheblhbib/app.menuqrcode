<?php

    include('../config.menuqrcode/config.php');
    
    $object = new application(); 
    
    $page_actuel = $object->get_page_url();
    

if(isset($_POST["action"]))
    {
    	if($_POST["action"] == 'fetch')
    	{
    		$order_column = array('code','expire','expire_date', 'statut');
    
    		$output = array();
    
    		$main_query = "
    		SELECT * FROM vip WHERE market = '".$_SESSION['market']."' and statut = 'Activer' 
    		";
    
    		$search_query = '';
    
    		if(isset($_POST["search"]["value"]))
    		{
    			$search_query .= ' AND (code LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR expire_date LIKE "%'.$_POST["search"]["value"].'%"  ) ';
    		}
    
    		if(isset($_POST["order"]))
    		{
    			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
    		}
    		else
    		{
    			$order_query = 'ORDER BY id DESC ';
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
    			  
	            $sub_array[] = html_entity_decode($row["code"]);
    			
    			$sub_array[] = ($row["expire"] == '1') ? 'Non' : 'Oui';
    			
    			$sub_array[] = ($row["expire"] == '1') ? '--' : date('d/m/Y',strtotime($row['expire_date'])) ;  
                
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
    		$order_column = array('code','expire','expire_date', 'statut');
    
    		$output = array();
    
    		$main_query = "
    		SELECT * FROM vip WHERE market = '".$_SESSION['market']."' and statut != 'Activer' 
    		";
    
    		$search_query = '';
    
    		if(isset($_POST["search"]["value"]))
    		{
    			$search_query .= ' AND (code LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR expire_date LIKE "%'.$_POST["search"]["value"].'%"  ) ';
    		}
    
    		if(isset($_POST["order"]))
    		{
    			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
    		}
    		else
    		{
    			$order_query = 'ORDER BY id DESC ';
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
    			  
	            $sub_array[] = html_entity_decode($row["code"]);
    			
    			$sub_array[] = ($row["expire"] == '1') ? 'Non' : 'Oui';
    			
    			$sub_array[] = ($row["expire"] == '1') ? '--' : date('d/m/Y',strtotime($row['expire_date'])) ;  
                
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
  
            $data = array(
				':market'		=>   $object->clean_input($_SESSION["market"]),
				':code'		    =>   $object->clean_input($_POST["code"]),
				':expire'		=>   $object->clean_input($_POST["expire"]),
				':expire_date'  =>   ($_POST["expire"] == '2' ) ? $object->clean_input($_POST["expire_date"]) : null, 
 
			);
            
            
            $object->query = "
			INSERT INTO vip  ( market,  code ,  expire,  expire_date ) 
			          VALUES (:market, :code , :expire, :expire_date )
			";
      
			$object->execute($data);

            $VIP = $object->connect->lastInsertId();
			
            if ($VIP > 0) {
                $success = 'Accès VIP  Ajouté';
            }else{
                $error = '<div class="alert alert-danger">Erreur d\'insertion</div>'; 
            } 
    		
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success,
    			'data'  => $data
    		);
    
    		echo json_encode($output);
    
    	}
    
    	if($_POST["action"] == 'fetch_single')
    	{
    		$object->query = "
    		SELECT * FROM vip 
    		WHERE id = '".$_POST["id"]."'
    		";
    
    		$result = $object->get_result();
    
    		$data = array();
    
    		foreach($result as $row)
    		{  
    		    
    			$data['code'] = html_entity_decode($row['code']);
    			$data['expire'] = html_entity_decode($row['expire']);
    			$data['expire_date'] = $row['expire_date'];  
    		}
    
    		echo json_encode($data);
    	}
    
    	if($_POST["action"] == 'Edit')
    	{
    		$error = '';
    
    		$success = ''; 
     
			$data = array( 
		    	':code'		    =>   $object->clean_input($_POST["code"]),
				':expire'		=>   $object->clean_input($_POST["expire"]),
		        ':expire_date'  =>	($_POST["expire"] == '2' ) ?$object->clean_input($_POST["expire_date"]) : null,  
		        ':id'           => $_POST['hidden_id']
			
			);

			$object->query = "
			UPDATE vip 
			SET  
			code = :code, 
			expire = :expire, 
			expire_date = :expire_date 
			WHERE id = :id
			";
            
			$object->execute($data);

			$success = 'Accès VIP  modifié';
    
    
    	
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success
    		);
    
    		echo json_encode($output);
    
    	}
 
    	
		if($_POST["action"] == 'change_statut')
    	{
    		$data = array(
    			':statut'		=>	$_POST['next_statut'],
    			':id'           => $_POST['id']
    		); 
    
    		$object->query = "
    		UPDATE vip 
    		SET statut = :statut 
    		WHERE id = :id
    		";
    
    		$object->execute($data);
    
    		echo 'Accès VIP  '.$_POST['next_statut'].'';
    	}
    	
 

    
 
    }
    
  



?>