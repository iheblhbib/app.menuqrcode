<?php

    include('/home5/qdwlxrcz/config.menuqrcode/config.php');

    $object = new application();   
    
    if(!empty($_GET['token']))
    {
		$data = array(
			':token'	=>	$object->clean_input($_GET['token']) 
		);

		$object->query = "  SELECT
                                *
                            FROM
                                token
                            WHERE
                                token = :token AND expiration >= CURRENT_DATE AND statut = 'Activer' ";

		$object->execute($data);

		if($object->row_count() == 0)
		{
            $response = array(
                'status' => 'error',
                'message' => 'Invalid or expired token. Please provide a valid token.',
            ); 
		}
		else
		{   
	        $object->query="SELECT 
                   *
                FROM 
                    evenement 
                WHERE 
                    statut = 'Activer'  ";
 
            $result = $object->get_result();
            
            $output = array();
            
            foreach($result as $row)
            {
                $sub_array = array();
                
                $sub_array = array();
                  
                $sub_array['id'] = ucwords($row['id']) ;
                $sub_array['title'] = ucwords($row['titre']) ;
                $sub_array['text'] = ucfirst($row['texte']) ;
                $sub_array['start'] = ($row['debut']) ;
                $sub_array['end'] = ($row['fin']) ;
                $sub_array['image'] = str_replace('../',"https://",$row['image']) ;
                    
                $output[] = $sub_array ; 
            }

            $response = array(
                'status' => 'success',
                'message' => 'Token is valid, Data retrieved successfully.',
                'data' => $output   
            );
            
		} 
    }else{
        $response = array(
            'status' => 'error',
            'message' => 'No token provided. Please include a valid token in your request.'
        );
    }
    echo json_encode($response);