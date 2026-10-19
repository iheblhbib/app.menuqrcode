<?php

include('../config.menuqrcode/config.php');

$object = new application(); 

$market = json_decode($object->get_market($_POST['market'])); 
 

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch') {
          
        $output = array();

        $main_query = "  SELECT * FROM ingredient WHERE market = '".$market->id."' AND categorie =  '".$_POST['categorie']."' ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR prix LIKE "%' . $_POST["search"]["value"] . '%"   '; 
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

        foreach ($result as $row) {
            $sub_array = array();  
             
            $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';

            $sub_array[] = $object->clean_input($row["libelle"] )  ;
            
            $sub_array[] = (is_numeric($row["prix"])) ? $row["prix"].' DT' :  $row["prix"] ;  
            
            $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';

            $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
              
            $sub_array[] = '<button style="font-size:x-small" type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>';
            
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
                                    a.*,
                                    l.libelle AS langue_libelle,
                                    l.prix AS langue_prix 
                                FROM
                                    ingredient a
                                LEFT JOIN langue_ingredient l ON
                                    l.ingredient = a.id
                                WHERE
                                      l.code = '".$raw['code']."' AND a.market = '".$market->id."' AND a.categorie =  '".$_POST['categorie']."'  ";
        
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
        
                foreach ($result as $row) {
                    $sub_array = array();  
                    
                    $sub_array[] = '<span style=" cursor: move;" data-id="'.$row["id"].'"><i class="fad fa-arrows-alt"></i></span>';

                    $sub_array[] = $object->clean_input($row["langue_libelle"] )  ; 
            
                    $sub_array[] = (is_numeric($row["langue_prix"])) ? $row["langue_prix"].' DT' :  $row["langue_prix"] ;  
                    
                    $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger';
        
                    $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
            
                    $sub_array[] = ' <button style="font-size:x-small" type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button>';
                    
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
			':categorie'	=>	($_POST['categorie']) ? $_POST['categorie'] : null, 
			':market'	    =>	$market->id, 
		);

		$object->query = "  SELECT 
		                        * 
	                        FROM 
	                            ingredient 
		                    WHERE 
		                        libelle = :libelle AND 
		                        prix = :prix AND 
		                        categorie = :categorie AND 
		                        market  = :market ";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Déjà existe</div>';
		}
		else
		{ 
 
		    
            $data = array(
				':market'			            =>	$object->clean_input($_POST["market"]),
				':categorie'	                =>	($_POST['categorie'])  , 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':prix'			                =>	$object->clean_input($_POST["prix"]),  
				':order_categorie'	            =>	$object->getMaximum($_POST["market"],'ingredient',$_POST['categorie']) +1 , 
			);
            
            $object->query = "INSERT INTO ingredient( 
                                                market,
                                                categorie,
                                                libelle, 
                                                prix,   
                                                order_categorie 
                                            ) VALUES (
                                                :market,
                                                :categorie,
                                                :libelle, 
                                                :prix,   
                                                :order_categorie 
                                                )	";
		
			$object->execute($data); 
			
			$ingredient = $object->connect->lastInsertId();
			
            if ($ingredient > 0) {
                $success = 'Mis à jour avec succés';
                
                    if($market->multi_langue == '1'){ 
        
                        $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                        
                        $result = $object->get_result();
                        
                        foreach($result as $row){
                           
                            $data = array(
                                ':langue'           => $object->clean_input($row['id']),
                                ':ingredient'          => $object->clean_input($ingredient),
                                ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                                ':prix'             => $object->clean_input($_POST['prix'.$row['code']]), 
                                ':code'             => $object->clean_input($row['code']),
                            );
            
            
                            $object->query = "
            			        INSERT INTO langue_ingredient (langue, ingredient, libelle,prix, code) VALUES (:langue, :ingredient, :libelle,:prix,  :code)
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
                                ingredient
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) { 
			
			$data['libelle']            = $object->clean_input($row['libelle']);
			
			$data['prix']               = $object->clean_input($row['prix']); 
			
			$data['categorie']          = ($row['categorie'])   ;  
        }
        
        if($market->multi_langue == '1'){ 
    		
    		$object->query="SELECT
                                *
                            FROM
                                langue_ingredient
                            WHERE
                                ingredient = '".$_POST["id"]."' ";
                                    
            $result = $object->get_result();
            
            foreach($result as $row)
            {
                $data['libelle'.$row['code']] = $object->clean_input($row['libelle']);
                $data['prix'.$row['code']] = $object->clean_input($row['prix']); 
            }
        }

        echo json_encode($data);
    }
 
    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';

        $data = array(
			':libelle'	    =>	$_POST["libelle"], 
			':categorie'	=>	($_POST['categorie']) ? $_POST['categorie'] : null, 
			':market'	    =>	$market->id, 
            ':id'           => $_POST['hidden_id'],
        );

        $object->query = "  SELECT
                                *
                            FROM
                                categorie
                            WHERE
                                libelle = :libelle AND 
                                categorie = :categorie AND 
                                market = :market AND 
                                id != :id
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Déjà existe</div>';
        } else {

 
		    
            $data1 = array(  
				':categorie'	                =>	($_POST['categorie']) ? $_POST['categorie'] : null, 
				':libelle'			            =>	$object->clean_input($_POST["libelle"]),
				':prix'			                =>	$object->clean_input($_POST["prix"]),  
				':id'                           => $_POST['hidden_id'],
				
			);

            $object->query = "UPDATE ingredient SET 
                                			categorie = :categorie,
                                			libelle = :libelle,
                                			prix = :prix 

                        			WHERE id = :id ";
			$object->execute($data1);
 
            if($market->multi_langue == '1'){ 
        
                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                
                $result = $object->get_result();
                
                foreach($result as $row){
                    
                    $data = array( 
                        ':ingredient'     => $object->clean_input($_POST['hidden_id']),
                        ':code'         => $object->clean_input($row['code']),
                    );
                    
                    $object->query = "  SELECT 
                                            * 
                                        FROM 
                                            langue_ingredient 
            		                    WHERE  
            		                        ingredient = :ingredient AND code = :code  ";
                    $object->execute($data);
        
            		if($object->row_count() > 0)
            		{
                        $data = array(
                            ':libelle'          =>  ($object->clean_input($_POST['libelle'.$row['code']])),
                            ':prix'             =>  ($object->clean_input($_POST['prix'.$row['code']])), 
                            ':code'             => $object->clean_input($row['code']),
                            ':ingredient'        => $object->clean_input($_POST['hidden_id']),
                            );
        
                        $object->query = " UPDATE langue_ingredient SET
                    			            libelle = :libelle,
                    			            prix = :prix 
                    			           WHERE code = :code and ingredient = :ingredient ";
                  
        			    $object->execute($data);
            		}
            		else
            		{
                        $data = array(
                            ':langue'           => $object->clean_input($row['id']),
                            ':ingredient'          => $object->clean_input($_POST['hidden_id']),
                            ':libelle'          => $object->clean_input($_POST['libelle'.$row['code']]),
                            ':code'             => $object->clean_input($row['code']),
                            ':prix'             => ($object->clean_input($_POST['prix'.$row['code']])), 
                        );
         
                        $object->query = " INSERT INTO langue_ingredient (langue, ingredient, libelle, code, prix ) VALUES  (:langue, :ingredient, :libelle, :code, :prix ) ";
        
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

        $object->query = "  UPDATE ingredient SET 
                                    statut = :statut
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
        
            $object->query = "  UPDATE ingredient  SET 
                                    order_categorie = :order_categorie 
                                WHERE id = :id ";
             
            $object->execute($data);
        }
        
        echo 'Mis à jour avec succés';
	}
 
}

 