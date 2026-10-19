<?php

    include('../config.menuqrcode/config.php'); 
    
    $object = new application(); 
    
    $page_actuel = $object->get_page_url();
 
    if(isset($_POST["action"]))
    { 
        if($_POST["action"] == 'fetch')
    	{
            $order_column = array('c.reference','c.num_table', 'total','c.name', 'c.tel', ' c.email', ' c.tel',  'c.id', 'c.id', 'e.libelle');
    
            $output = array();
    
            $main_query = " SELECT
                                c.*,
                                SUM(l.prix) as total,
                                e.libelle,
                                e.color
                            FROM
                                commande c
                            LEFT JOIN commande_ligne l ON
                                l.commande = c.id
                                LEFT JOIN commande_etat e ON e.id = c.etat
                            WHERE c.market = '".$_SESSION['market']."' AND  e.visible = '1' ";
    
            $search_query = '';
    
            if (isset($_POST["search"]["value"])) { 
                
                $search_query .= ' AND (c.reference LIKE "%' . $_POST["search"]["value"] . '%" ';
                $search_query .= 'OR c.name LIKE "%' . $_POST["search"]["value"] . '%" ';
                $search_query .= 'OR c.email LIKE "%' . $_POST["search"]["value"] . '%" '; 
                $search_query .= 'OR c.tel LIKE "%' . $_POST["search"]["value"] . '%" ';
                $search_query .= 'OR c.num_table LIKE "%' . $_POST["search"]["value"] . '%" '; 
                $search_query .= 'OR e.libelle LIKE "%' . $_POST["search"]["value"] . '%" ) ';
            }
    
            if (isset($_POST["order"])) {
                $order_query = ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
            } else {
                $order_query = ' ORDER BY c.created DESC ';
            }
            
            $groupe_query = ' GROUP BY c.id' ;
    
            $limit_query = '';
    
            if ($_POST["length"] != -1) {
                $limit_query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }
    
            $object->query = $main_query . $search_query . $groupe_query . $order_query;
    
            $object->execute();
    
            $filtered_rows = $object->row_count();
    
            $object->query .= $limit_query;  
    
            $result = $object->get_result();
         
            $object->query = $main_query . $groupe_query;
    
            $object->execute();
    
            $total_rows = $object->row_count();
    
            $data = array();
            
            if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
                $result = null;
            }            

            foreach ($result as $row) {
                $sub_array = array();
  
                $sub_array[] = date('H:i:s d/m/Y',strtotime($row['created']));
                $sub_array[] = ($row["reference"]) ;
                $sub_array[] = ($row["num_table"])  ;
                $sub_array[] = number_format( $row['total'] ,3,',',' ').' TND ';  
                $sub_array[] = ($row["name"]) ;
                $sub_array[] = ($row["tel"]) ? ($row["tel"]) : '--';
                $sub_array[] = ($row["email"]) ? ($row["email"]) : '--';
                $sub_array[] = '<i style="font-size: x-large;" class="fad fa-boxes  text-info viewArticles" data-id="'.$row["id"].'"></i>';
                // $sub_array[] = '<i style="font-size: x-large;" class="fad fa-receipt  text-danger viewTicket" data-id="'.$row["id"].'"></i>';
                if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                    $sub_array[] = '<span class="badge rounded-pill view_etat  bg-'.$row['color'].'"  data-id="'.$row['id'].'"    data-etat="'.$row['etat'].'">'.ucwords($row["libelle"]).'</span>'; 
                    
                    $sub_array[] =  '<button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"  ><i class="fa-duotone fa-edit"></i></button>';
                }else{ 
                    $sub_array[] = ucwords($row["libelle"]);
                    
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
    	
        $nb = 1 ;
        $object->query="SELECT * FROM commande_etat WHERE statut = 'Activer'  order by order_etat";
               
        $result = $object->get_result();
        foreach($result as $row){ 
            if($_POST["action"] == 'fetch'.$nb )
        	{
                $order_column = array('c.reference','c.num_table', 'total', 'c.name', 'c.tel', ' c.email', ' c.tel', 'c.id', 'c.id', 'e.libelle');
        
                $output = array();
        
                $main_query = " SELECT
                                    c.*,
                                    SUM(l.prix) as total,
                                    e.libelle,
                                    e.color
                                FROM
                                    commande c
                                LEFT JOIN commande_ligne l ON
                                    l.commande = c.id
                                    LEFT JOIN commande_etat e ON e.id = c.etat
                                WHERE c.market = '".$_SESSION['market']."' AND  e.id = '".$row['id']."' ";
        
                $search_query = '';
        
                if (isset($_POST["search"]["value"])) { 
                    
                    $search_query .= ' AND (c.reference LIKE "%' . $_POST["search"]["value"] . '%" ';
                    $search_query .= 'OR c.name LIKE "%' . $_POST["search"]["value"] . '%" ';
                    $search_query .= 'OR c.email LIKE "%' . $_POST["search"]["value"] . '%" '; 
                    $search_query .= 'OR c.tel LIKE "%' . $_POST["search"]["value"] . '%" ';
                    $search_query .= 'OR c.num_table LIKE "%' . $_POST["search"]["value"] . '%" '; 
                    $search_query .= 'OR e.libelle LIKE "%' . $_POST["search"]["value"] . '%" ) ';
                }
        
                if (isset($_POST["order"])) {
                    $order_query = ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
                } else {
                    $order_query = ' ORDER BY c.created DESC ';
                }
                
                $groupe_query = ' GROUP BY c.id' ;
        
                $limit_query = '';
        
                if ($_POST["length"] != -1) {
                    $limit_query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
                }
        
                $object->query = $main_query . $search_query . $groupe_query . $order_query;
        
                $object->execute();
        
                $filtered_rows = $object->row_count();
        
                $object->query .= $limit_query; 
        
                $result = $object->get_result();
             
                $object->query = $main_query . $groupe_query;
        
                $object->execute();
        
                $total_rows = $object->row_count();
        
                $data = array();
                
                if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
                    $result = null;
                } 
    
                foreach ($result as $row) {
                    $sub_array = array();
                    $sub_array[] = date('H:i:s d/m/Y',strtotime($row['created']));
                    $sub_array[] = ($row["reference"]) ;
                    $sub_array[] = ($row["num_table"])  ;
                    $sub_array[] = number_format( $row['total'] ,3,',',' ').' TND '; 
                    $sub_array[] = ($row["name"]) ;
                    $sub_array[] = ($row["tel"]) ? ($row["tel"]) : '--';
                    $sub_array[] = ($row["email"]) ? ($row["email"]) : '--';
                    $sub_array[] = '<i style="font-size: x-large;" class="fad fa-boxes  text-info viewArticles" data-id="'.$row["id"].'"></i>';
                    // $sub_array[] = '<i style="font-size: x-large;" class="fad fa-receipt  text-danger viewTicket" data-id="'.$row["id"].'"></i>';
                    
                    if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
                        $sub_array[] = '<span class="badge rounded-pill view_etat  bg-'.$row['color'].'"  data-id="'.$row['id'].'"    data-etat="'.$row['etat'].'">'.ucwords($row["libelle"]).'</span>'; 
                        
                        $sub_array[] =  '<button  type="button" name="edit_button" class=" btn btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"  ><i class="fa-duotone fa-edit"></i></button>';
                    }else{ 
                        $sub_array[] = ucwords($row["libelle"]);
                        
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
        	$nb++;
        }
        
  
        
        if($_POST['action'] == 'fetch_single'){
            $object->query = " SELECT * FROM commande WHERE id = '".$_POST["id"]."'  ";
            $result = $object->get_result();
            
            $data = array();
             
            foreach($result as $row){
                $data['reference'] = $row['reference'];
                $data['name'] = ucwords($row['name']);
                $data['email'] = $row['email'];
                $data['tel'] = $row['tel'];
                $data['num_table'] = $row['num_table']; 
                
                $object->query = " SELECT * FROM commande_ligne WHERE commande = '".$_POST["id"]."'  ";
                $result = $object->get_result();
                
                $sub_array = array(); 
                foreach($result as $row){
                    $sub_data = array();
                    
                    $sub_data['id'] = $row['id'];
                    $sub_data['article'] = $row['article'];
                    $sub_data['quantite'] = $row['quantite']; 
                    $sub_array[] = $sub_data;
                }
                $data['ligne'] = $sub_array;
            }
            
            echo json_encode($data);
        }
        
        if($_POST['action'] == 'fetch_swal'){
            $object->query = " SELECT * FROM commande WHERE id = '".$_POST["id"]."'  ";
            $result = $object->get_result();
            
            $data = array();
             
            foreach($result as $row){
                $data['reference'] = $row['reference'];
                $data['name'] = ucwords($row['name']);
                $data['email'] = $row['email'];
                $data['tel'] = $row['tel'];
                $data['num_table'] = $row['num_table'];    
                $data['date'] = date('H:i:s d/m/Y',strtotime($row['created']));
                $data['etat'] = $object->getByID($row['etat'],'commande_etat'); 
                $data['total'] = number_format($object->total_commande($row['id']),3,',',' ').' TND'; 
                
                $object->query = "  SELECT
                                        l.*,
                                        a.libelle
                                    FROM
                                        commande_ligne l
                                    LEFT JOIN article a ON
                                        a.id = l.article
                                    WHERE
                                        l.commande =  '".$_POST["id"]."'  ";
                                        
                $result = $object->get_result();
                
                $sub_array = array(); 
                foreach($result as $row){
                    $sub_data = array();
                    
                    $sub_data['id'] = $row['id'];
                    $sub_data['article'] = $row['libelle'];
                    $sub_data['quantite'] = $row['quantite'];
                    $sub_data['note'] = ($row['note']) ? $row['note'] : '--';
                    $sub_data['prix'] = number_format( ($row['prix_unitaire']),3,',',' ').' TND';
                    $sub_data['total'] = number_format( ($row['prix_unitaire'] * $row['quantite'] ),3,',',' ').' TND';
                    $sub_array[] = $sub_data;
                }
                $data['ligne'] = $sub_array;
            }
            
            echo json_encode($data);
        }
        
        if($_POST['action'] == 'Edit'){
            
            $success = '';
            
            $error = '';
            
            // $data = array(
            //         ':name' => ($_POST['name']) ? $object->clean_input($_POST['name']) : null,
            //         ':email' => ($_POST['email']) ? $object->clean_input($_POST['email']) : null, 
            //         ':tel' => ($_POST['tel']) ? $object->clean_input($_POST['tel']) : null, 
            //         ':num_table' => ($_POST['num_table']) ? $object->clean_input($_POST['num_table']) : null,    
            //         ':id' => $_POST['hidden_id'],
            //     );
                
            // $object->query="UPDATE commande set 
            //                         name = :name,
            //                         email = :email,
            //                         tel = :tel,
            //                         num_table = :num_table 
            //                     WHERE id = :id
            // ";
            
            // $object->execute($data); 
            
            $object->query="DELETE FROM  commande_ligne  WHERE commande = '".$_POST['hidden_id']."' ";
            
            $object->execute();
 
            $success = 'Commande Modifié';
                
            $nb=0;
            
            foreach($_POST["article"] as $elem => $val){
                
                $article = json_decode($object->get_categorie( $_POST['article'][$nb],'article')) ;
                 
                $prix = ($article->prix) ? $article->prix : 0 ;
                
                $data = array(
                        ':market'       => $_SESSION['market'],
                        ':commande'     => $_POST['hidden_id'],
                        ':article'      => $_POST['article'][$nb],
                        ':quantite'     => $_POST['quantite'][$nb],
                        ':prix'         => $prix*$_POST['quantite'][$nb],  
                        ':prix_unitaire'=> $prix,  
                    );
                    
                $object->query=" INSERT INTO commande_ligne( 
                                        market,
                                        commande,
                                        article,
                                        quantite,
                                        prix ,
                                        prix_unitaire
                                    )
                                    VALUES(
                                        :market,
                                        :commande,
                                        :article,
                                        :quantite,
                                        :prix ,
                                        :prix_unitaire 
                                    )
                ";
                
                $object->execute($data);

                $nb++;
             }


            $output = array(
                'error' => $error,
                'success' => $success,
                'id' => $_POST['hidden_id']
            );
    
            echo json_encode($output);
        }

    }
    
    if (isset($_POST["action_etat"])) {
        if ($_POST["action_etat"] == 'edit_etat') {
            $data = array(
                ':etat' => ($object->clean_input($_POST["etat"])),
                ':id' => $_POST['hidden_id_etat'],
            );
    
            $object->query = "  UPDATE commande SET
                                    etat = :etat
                                WHERE id = :id";
    
            $object->execute($data);
    
            $error = '';
    
            $success = 'Mis à jour avec succés';
    
            $output = array(
                'error' => $error,
                'success' => $success,
            );
    
            echo json_encode($output);
        }
    } 

 