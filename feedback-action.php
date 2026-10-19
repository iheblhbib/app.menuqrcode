<?php
 

include('../config.menuqrcode/config.php');
    
$object = new application(); 

$page_actuel = $object->get_page_url();

// $rating = ['--','☆','☆☆','☆☆☆','☆☆☆☆','☆☆☆☆☆']; 
// $rating = ['--', '<i class="fa-duotone fa-star-half"></i>',
//                  '<i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i>',
//                  '<i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i>',
//                  '<i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i>',
//                  '<i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i><i class="fa-duotone fa-star-half"></i>' ];
                 
$rating = ['--', '1/5 😔',
                 '2/5 😟',
                 '3/5 😐',
                 '4/5 😊',
                 '5/5 😀' ];
 

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$market_id = $_SESSION['market'];

		$search        = isset($_POST['search']) ? trim($_POST['search']) : '';
		$rating_filter = (isset($_POST['rating_filter']) && $_POST['rating_filter'] !== '') ? intval($_POST['rating_filter']) : null;
		$sort          = isset($_POST['sort']) ? $_POST['sort'] : 'date_desc';
		$start         = isset($_POST['start']) ? intval($_POST['start']) : 0;
		$length        = isset($_POST['length']) ? intval($_POST['length']) : 9;
		$draw          = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

		$sort_map = array(
			'date_desc'   => 'created DESC',
			'date_asc'    => 'created ASC',
			'rating_desc' => 'rating DESC',
			'rating_asc'  => 'rating ASC',
		);
		$order_by = isset($sort_map[$sort]) ? $sort_map[$sort] : $sort_map['date_desc'];

		$main_query = "SELECT * FROM feedback WHERE market = :market ";
		$params = array(':market' => $market_id);

		if($search !== '')
		{
			$main_query .= "AND (name LIKE :search OR email LIKE :search OR commentaire LIKE :search) ";
			$params[':search'] = '%'.$search.'%';
		}

		if($rating_filter !== null)
		{
			$main_query .= "AND rating = :rating_filter ";
			$params[':rating_filter'] = $rating_filter;
		}

		$order_query = "ORDER BY ".$order_by." ";

		// Filtered count (search + rating filter applied)
		$object->query = $main_query . $order_query;
		$object->execute($params);
		$filtered_rows = $object->row_count();

		// Page of results
		$object->query = $main_query . $order_query . 'LIMIT '.$start.', '.$length;
		$object->execute($params);
		$result = $object->statement_result();

		// Total count for this market (no filters)
		$object->query = "SELECT * FROM feedback WHERE market = :market";
		$object->execute(array(':market' => $market_id));
		$total_rows = $object->row_count();

		$data = array();

		if ($object->autorisation('r', $_SESSION['id'], $page_actuel) != 2) {
			$result = null;
		}

		if ($result) {
			foreach($result as $row)
			{
				$ratingVal = intval($row['rating']);

				$userAgent = $row['browser'];
				preg_match('/\((.*?)\)/', $userAgent, $matches);
				$device = isset($matches[1]) ? $matches[1] : 'N/A';

				$data[] = array(
					'id'           => $row['id'],
					'date_full'    => ucwords($object->dateToFrench($row['created'], 'l j F Y')).' à '.date('H:i', strtotime($row['created'])),
					'name'         => $row['name'] ? $row['name'] : 'Anonyme',
					'email'        => $row['email'] ? $row['email'] : '',
					'rating'       => $ratingVal,
					'rating_label' => $ratingVal > 0 ? $rating[$ratingVal] : 'Pas de note',
					'commentaire'  => $row['commentaire'] ? $row['commentaire'] : '',
					'device'       => $device,
				);
			}
		}

		$output = array(
			"draw"    			=> 	$draw,
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}
	if($_POST["action"] == 'stats')
	{
		$object->query = "SELECT rating, COUNT(*) as cnt FROM feedback WHERE market = :market GROUP BY rating";
		$object->execute(array(':market' => $_SESSION['market']));
		$result = $object->statement_result();

		$distribution = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
		$total = 0;
		$sum = 0;

		foreach($result as $row)
		{
			$r   = intval($row['rating']);
			$cnt = intval($row['cnt']);
			$total += $cnt;

			if($r >= 1 && $r <= 5)
			{
				$distribution[$r] = $cnt;
				$sum += $r * $cnt;
			}
		}

		$average = $total > 0 ? round($sum / $total, 1) : 0;

		echo json_encode(array(
			'total'        => $total,
			'average'      => $average,
			'distribution' => $distribution
		));
	}
	if($_POST["action"] == 'feedback'){
	    
	    $data = array(
	            ':market'       =>  $object->decrypt($_POST['market']),
	            ':rating'       =>  $_POST['rating'],
	            ':commentaire'  =>  $_POST['commentaire'],
	            ':ip'           =>  $object->getIPAddress(),
                ':browser'      =>  $object->getBrowser()
	        );
	        
	    $object->query ="INSERT INTO feedback (  market, rating, commentaire,  ip, browser ) VALUES (  :market, :rating, :commentaire,  :ip, :browser ) ";
	    
	    $object->execute($data);
	    
    	$id = $object->connect->lastInsertId();
    	
    	$success = '';
    	
    	$error = '';
    	
    	if($id > 0){
    	   $success = 'success'; 
    	}else{
    	    $error = 'error';
    	}
    	
    	$output= array(
    	    'success' => $success,
    	    'error'     => $error
    	    );
    	    
    	    echo json_encode($output);
	}
}

 

?>