<?php

include('../config.menuqrcode/config.php');

$object = new application();  

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch') {

        $output = array();

        $main_query = " SELECT
                            *
                        FROM
                            qrcode q 
                        WHERE
                             gestionnaire =  '".$_SESSION['id']."' ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND (libelle LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }
 
        $order_query = 'ORDER BY id ASC ';
    

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
            
			$row['url'] = str_replace('../','https://',$row['url']); 

            $sub_array[] = $object->clean_input($row["libelle"])   ;
            
            $sub_array[] = '<a title="Menu" target="blank" style="font-size: x-large;" href="https://menuqrcode.tn/qrcode?data='.($object->encrypt($row["id"])).'&pro=1"><i class="fal fa-bars"></i></a>';                 
            
            $sub_array[] = '<a href="'.$row["url"].'" class="glightbox" data-glightbox="title: '.$row['libelle'].';"  ><img src="'.$row["url"].'" style="background-color: #2c5199;" alt="qrcode" class="img-fluid img-thumbnail" width="50" height="50"></a>';
          
            $sub_array[] = '<button style="font-size:x-small" type="button" title="Liste des markets" class="markets btn  btn-sm btn-circle btn-primary" data-id="'.$row["id"].'"><i class="fad fa-store"></i></button>';   
            
          	$sub_array[] = $object->CountAccessQRCode($row["id"]); 
            
            $statut = ($row["statut"] == '0') ? 'success' : 'danger';
            
            $libelle = ($row["statut"] == '0') ? 'Activer' : 'Désactiver';

            $sub_array[] = '<span title="Activer" class="badge bg-' . $statut . '  "   >' .$libelle. '</span>';
		
		
            
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
    
    
	if($_POST["action"] == 'fetch_market' ) 
    { 
    
    	$object->query  = " SELECT
                j.market,
                j.qrcode,
                j.etat,
                m.market as libelle,
                m.logo
            FROM
                market_qrcode j
                LEFT JOIN market m ON j.market = m.id
            WHERE
                j.qrcode = '".$_POST["id"]."' 
    	    ORDER BY libelle ASC "; 
    
    	$result = $object->get_result();
    	
    	$html = '';
    
    	foreach($result as $row)
    	{	
    		$etat = ($row["etat"] == '1') ?  'selected' : '' ; 
    		
    	    $html .= '<option value="'.$row['market'].'" data-market="'.$row['market'].'"   data-qrcode="'.$row['qrcode'].'" data-etat="'.$row["etat"].'" '.$etat.' > '.$object->clean_input($row["libelle"]).'</option>';
 
    	} 
    		
    	echo  ($html);
    }
    
	if($_POST["action"] == 'update' ) 
    { 
        $success = '';
        
        $error = '';
        
		$data = array( 
	    	':qrcode'	=>	$_POST['id']
		);
        
    	$object->query = "  UPDATE
                                market_qrcode
                            SET
                                etat = '0'
                            WHERE
                                qrcode = :qrcode  ";
        
    	$object->execute($data);
    
        foreach ($_POST['markets'] as $market) {
            $data = array(
                ':market'  => $market,
                ':qrcode'	    =>	$_POST['id']
            );

            
        	$object->query = "  UPDATE
                                    market_qrcode
                                SET
                                    etat = '1'
                                WHERE
                                    market = :market AND 
                                    qrcode = :qrcode  ";
            
        	$object->execute($data);
        }
        
        $success = 'Mis à jour avec succès';
        
        $output = array(
                'error' => $error,
                'success' => $success,
            );
            
        echo json_encode($output);
    }
    
 
 
 
}

 
 
