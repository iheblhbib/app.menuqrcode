<?php

    include('/home5/qdwlxrcz/config.menuqrcode/config.php');

    $object = new application();   
 
// 	$object->query = "  SELECT
//                          	*
//                         FROM
//                             alert
//                         WHERE
//                             client =  '".$_POST['client']."' AND 
//                             debut <= now() AND 
//                             statut = 'Activer' 
//                         LIMIT 1";
	$object->query = "  SELECT a.*
                        FROM alert a
                        JOIN user u ON a.client = 
                            CASE 
                                WHEN u.type = 'gestionnaire' THEN u.id 
                                WHEN u.type = 'employee' THEN u.owner 
                            END
                        WHERE u.id = '".$_POST['client']."'
                          AND a.debut <= NOW()
                          AND a.statut = 'Activer'
                        LIMIT 1;";
     
    $result = $object->get_result();
  
    $data = array(); 
    
    $status = false ;
    
    foreach($result as $row)
    {  
        $status = true ;
        $data['texte'] = ($row['texte']) ;  
        $data['forced'] = ($row['forced']) ;  
    } 
    
    $output = array(
            'data' => $data,
            'status' => $status 
        );
    
    echo json_encode($output);