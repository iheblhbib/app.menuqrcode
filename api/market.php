<?php

    header('Content-Type: application/json');
 
    include('/home5/qdwlxrcz/config.menuqrcode/config.php');

    $object = new application();  
    
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        
        $data = array(
			':token'	=>	$object->clean_input($_SERVER['HTTP_AUTHORIZATION']) 
		);

		$object->query = "  SELECT
                                *
                            FROM
                                token
                            WHERE
                                token = :token AND expiration >= CURRENT_DATE AND statut = 'Activer'";

		$object->execute($data);

		if($object->row_count() == 0)
		{
            $response = array(
                'status' => 'error',
                'message' => 'Invalid or expired token. Please provide a valid token.',
            ); 
		}else{
		    
		    // Get the raw JSON data from the request body
            $jsonData = file_get_contents('php://input');
            
            // Decode the JSON data into an associative array
            $post = json_decode($jsonData, true);
    
            if(!empty($post['data']))
            {
        		$data = array(
        			':id'	=>	$object->decrypt($post['data']) 
        		);
        
        		$object->query = "  SELECT 
        		                        * 
        	                        FROM 
        	                            market 
        		                    WHERE id = :id ";
        
        		$object->execute($data);
        
        		if($object->row_count() == 0)
        		{
        	        $response = array(
                        'status' => 'error',
                        'message' => 'No data found for the provided ID.'
                    );
        		}
        		else
        		{   
        	        $object->query="SELECT 
                            * 
                        FROM 
                            market 
                        WHERE 
                            id = '".$object->decrypt($post['data']) ."'  ";
         
                    $result = $object->get_result();
                    
                    $data = array();
                    
                    foreach($result as $market)
                    {
                        $market_array = array();
                        
                        foreach ($market as $key => $value) {
                            $value = (strpos($value, "../") === 0) ? str_replace('../',"https://",$value) : $value;
                            $market_array[$key] = $value ;
                        } 
                          
                        switch($market['niveau']){
                            case 2:
                        	        $object->query="SELECT 
                                            * 
                                        FROM 
                                            categorie 
                                        WHERE 
                                            market = '".$object->decrypt($post['data']) ."'  ";
                         
                                    $result = $object->get_result();
                                    
                                    $categorie_array = array();
                                    
                                    foreach($result as $categorie)
                                    {
                                        $categorie_ligne_array = array();
                        
                                        foreach ($categorie as $key => $value) {
                                            $value = (strpos($value, "../") === 0) ? str_replace('../',"https://",$value) : $value;
                                            $categorie_ligne_array[$key] = $value ;
                                        } 
                                        
                                        // articles 
                                               $object->query="SELECT 
                                                    * 
                                                FROM 
                                                    article 
                                                WHERE 
                                                    market = '".$object->decrypt($post['data']) ."' and categorie = '".$categorie['id']."'  LIMIT 5 ";
                                 
                                            $result = $object->get_result(); 
                                            
                                            $article_array = array(); 
                                            
                                            foreach($result as $article)
                                            {
                                                $article_ligne_array = array();
                                
                                                foreach ($article as $key => $value) {
                                                    $value = (strpos($value, "../") === 0) ? str_replace('../',"https://",$value) : $value;
                                                    if($key == 'description' || $key == 'image'){
                                                        // $article_ligne_array[$key] =  ($value) ;
                                                        $article_ligne_array[$key] = htmlspecialchars($value) ;
                                                    }else{
                                                        $article_ligne_array[$key] =  ($value) ;
                                                    }
                                                }  
                                                
                                                $article_array[] = $article_ligne_array;
                                            }
                                            
                                            $categorie_ligne_array['articles'] =   ($article_array);
                                        
                                        $categorie_array[] = $categorie_ligne_array;
                                        
                                    }
                                    $market_array['categories'] = $categorie_array;
                                    break;
                        }
                        
                        $data['market'] = $market_array;
                    }
                    
                    $response = array(
                        'status' => 'success',
                        'message' => 'Data retrieved successfully.',
                        'data' => $data
                    );
                    
        		} 
            }else{
                $response = array(
                    'status' => 'error',
                    'message' => 'No data received in the POST request.' 
                );
            }
		}
    }else{
          $response = array(
            'status' => 'error',
            'message' => 'Authorization header is missing.'
          );
    }
        
    echo json_encode($response, JSON_UNESCAPED_SLASHES);