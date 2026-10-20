<?php

include('../config.menuqrcode/config.php');

$object = new application();

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

$page_actuel = $object->get_page_url();

$market = json_decode($object->get_market($_SESSION['market'])); 

if (isset($_POST["action"])) {   

    if ($_POST["action"] == 'fetch') {
        $order_column = array(   'name', ' num', 'email',  'pseudo', ' ' , 'statut');

        $output = array();
        
        $addons = ($object->is_client_user()) ?  " owner = ".$_SESSION['id']." " :  " owner = (SELECT owner FROM user WHERE id = ".$_SESSION['id']." ) " ;

        $main_query = "  SELECT * FROM user WHERE statut = 'Activer'   and type = 'employee' and  $addons ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND ( name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR num LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR pseudo LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR email LIKE "%' . $_POST["search"]["value"] . '%" '; 
            $search_query .= 'OR statut LIKE "%' . $_POST["search"]["value"] . '%") ';
        }

        if (isset($_POST["order"])) {
            $order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = 'ORDER BY id DESC ';
        }

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
            $sub_array = array();  

            $sub_array[] = empty($row["name"]) ? '--' :  ucwords($row["name"]);
            
            $sub_array[] =  empty($row["num"]) ? '' : $row["num"];
            
            $sub_array[] = empty($row["email"]) ? '--' :  ($row["email"]);

            $sub_array[] = empty($row["pseudo"]) ? '--' :  ($row["pseudo"]); 
           
            if ($object->autorisation('u', $_SESSION['id'], $page_actuel) != 2) 
            { 
                $sub_array[] = '--';
                
                $sub_array[] = '--';
                
                $sub_array[] = '--';
                
                $sub_array[] = '--';

            } else { 
                
                $sub_array[] = '<button style="font-size:x-small" type="button" name="market_button" class="  btn btn-info  btn-sm market_button"  data-id="' . $row["id"] . '" ><i class="fad fa-house"></i></button>';
                
                $sub_array[] = '<button style="font-size:x-small" type="button" name="permission_button" class="  btn btn-info  btn-sm permission_button"  data-id="' . $row["id"] . '" ><i class="fad fa-key"></i></button>';
 
                $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; 
    
                $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
      
                $sub_array[] = ' <button style="font-size:x-small" type="button" name="edit_button" class=" btn btn-circle btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button> ';
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

    if ($_POST["action"] == 'fetch2') {
        $order_column = array(   'name', ' num', 'email',  'pseudo', ' ' , 'statut');

        $output = array();
        
        $addons = ($object->is_client_user()) ?  " owner = ".$_SESSION['id']." " :  " owner = (SELECT owner FROM user WHERE id = ".$_SESSION['id']." ) " ;

        $main_query = "  SELECT * FROM user WHERE statut != 'Activer'   and type = 'employee' and  $addons ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) {
            $search_query .= 'AND ( name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR num LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR pseudo LIKE "%' . $_POST["search"]["value"] . '%" ';
            $search_query .= 'OR email LIKE "%' . $_POST["search"]["value"] . '%" '; 
            $search_query .= 'OR statut LIKE "%' . $_POST["search"]["value"] . '%") ';
        }

        if (isset($_POST["order"])) {
            $order_query = 'ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = 'ORDER BY id DESC ';
        }

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
            $sub_array = array();  

            $sub_array[] = empty($row["name"]) ? '--' :  ucwords($row["name"]);
            
            $sub_array[] = (empty($row["num"]) ? '' : (number_format(str_replace(' ', '',$row["num"]), 0, ",", " ") )) ;
            
            $sub_array[] = empty($row["email"]) ? '--' :  ($row["email"]);

            $sub_array[] = empty($row["pseudo"]) ? '--' :  ($row["pseudo"]); 
           
            if ($object->autorisation('u', $_SESSION['id'], $page_actuel) != 2) 
            { 
                $sub_array[] = '--';
                
                $sub_array[] = '--';
                
                $sub_array[] = '--';
                
                $sub_array[] = '--';

            } else { 
                
                $sub_array[] = '<button style="font-size:x-small" type="button" name="market_button" class="  btn btn-info  btn-sm market_button"  data-id="' . $row["id"] . '" ><i class="fad fa-house"></i></button>';
                
                $sub_array[] = '<button style="font-size:x-small" type="button" name="permission_button" class="  btn btn-info  btn-sm permission_button"  data-id="' . $row["id"] . '" ><i class="fad fa-key"></i></button>';
 
                $statut = ($row["statut"] == 'Activer') ? 'success' : 'danger'; 
    
                $sub_array[] = '<button style="font-size:x-small" type="button" name="statut_button" class="  btn btn-' . $statut . '  btn-sm statut_button"  data-id="' . $row["id"] . '"data-statut="' . $row["statut"] . '">' .$row["statut"]. '</button>';
      
                $sub_array[] = ' <button style="font-size:x-small" type="button" name="edit_button" class=" btn btn-circle btn-warning btn-sm edit_button" data-id="' . $row["id"] . '"><i class="fad fa-edit"></i></button> ';
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
     
    if ($_POST["action"] == 'Add') {
        $error = '';

        $success = '';

		$data = array(
			':pseudo'	=>	$_POST["pseudo"], 
		);

		$object->query = "
		SELECT * FROM user 
		WHERE pseudo = :pseudo  
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Pseudo déjà existe</div>';
		}
		else
		{ 
		    
            $data = array(
				':name'	            =>	$object->clean_input($_POST["name"]), 
				':num'              => ($_POST["num"]) ? $object->clean_input($_POST["num"]) : null,
				':email'            => ($_POST["email"]) ? $object->clean_input($_POST["email"]) : null,
				':pseudo'			=>	$object->clean_input($_POST["pseudo"]),
				':password'	        =>	md5($object->clean_input($_POST["password"])),
				':user_profile'		=>	'img/man.png',
				':type'		        =>	'employee', 
				':owner'		    =>	$_SESSION['id'],  
				':lifetime'		    =>	'2', 
			);
            
            $object->query = " INSERT INTO user  (name,   num, email, pseudo, password, user_profile, type, owner , lifetime) 
			                           VALUES (:name,:num, :email, :pseudo, :password, :user_profile, :type,   :owner , :lifetime)			";
		
			$object->execute($data); 
			
            if ($object->connect->lastInsertId() != 0) {
                $success = 'Utilisateur Ajouté';
                
                
			    $object->query="  INSERT INTO user_route(owner,user, route)
                            SELECT
                                ".$_SESSION['id'].",
                                u.id,
                                r.id
                            FROM
                                route r,
                                user u
                            WHERE
                                u.type = 'employee' AND u.id NOT IN(
                                SELECT
                                    user
                                FROM
                                    user_route
                                WHERE
                                    route = r.id
                            )
                        ";
                                        
                $object->execute();
                
			    $object->query="  INSERT INTO permission(owner,user, route)
                            SELECT
                                ".$_SESSION['id'].",
                                u.id,
                                r.id
                            FROM
                                route r,
                                user u
                            WHERE
                                u.type = 'employee' AND u.id NOT IN(
                                SELECT
                                    user
                                FROM
                                    permission
                                WHERE
                                    route = r.id
                            )
                        ";
                                        
                $object->execute();
                
			    $object->query="   INSERT INTO user_market(owner,user, market)
                            SELECT
                                ".$_SESSION['id'].",
                                u.id,
                                m.id
                            FROM
                                market m,
                                user u
                            WHERE
                                u.type = 'employee' AND u.id NOT IN(
                                SELECT
                                    user
                                FROM
                                    user_market
                                WHERE
                                    market = m.id
                            ) AND m.id in (SELECT id FROM market WHERE gestionnaire = ".$_SESSION['id']." ) 
                        ";
                                        
                $object->execute();
                
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
                                user
		                    WHERE 
                                id = '" . $_POST["id"] . "'
		";

        $result = $object->get_result();

        $data = array();

        foreach ($result as $row) {
            $data['name'] = $row['name'];  
            $data['num'] = $row['num'];
            $data['email'] = $row['email'];
            $data['pseudo'] = $row['pseudo'];  
        }

        echo json_encode($data);
    }

    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';

        $data = array(
            ':pseudo' => $_POST["pseudo"],
            ':id' => $_POST['hidden_id'],
        );

        $object->query = "  SELECT
                                *
                            FROM
                                user
                            WHERE
                                pseudo = :pseudo AND id != :id
		";

        $object->execute($data);

        if ($object->row_count() > 0) {
            $error = '<div class="alert alert-danger">Pseudo déjà existe</div>';
        } else {
            
            if($_POST["password"])
            {
                $data = array(
    				':name'	            =>	$object->clean_input($_POST["name"]), 
    				':num'              => ($_POST["num"]) ? $object->clean_input($_POST["num"]) : null,
    				':email'            => ($_POST["email"]) ? $object->clean_input($_POST["email"]) : null,
    				':pseudo'			=>	$object->clean_input($_POST["pseudo"]),
    				':password'	        =>	md5($object->clean_input($_POST["password"])),   
                    ':id' => $_POST['hidden_id'],
    
                );
    
                $object->query = "UPDATE user SET
                                    			name = :name, 
                                    			num = :num,
                                    			email = :email,
                                    			pseudo= :pseudo,
                                    			password = :password 
    
                            			WHERE id = :id ";
    			$object->execute($data);
                            			
            }else{
                $data = array(
    				':name'	            =>	$object->clean_input($_POST["name"]),  
    				':num'              => ($_POST["num"]) ? $object->clean_input($_POST["num"]) : null,
    				':email'            => ($_POST["email"]) ? $object->clean_input($_POST["email"]) : null,
    				':pseudo'			=>	$object->clean_input($_POST["pseudo"]),    
                    ':id' => $_POST['hidden_id'],
    
                );
    
                $object->query = "UPDATE user SET
                                    			name = :name, 
                                    			num = :num,
                                    			email = :email,
                                    			pseudo= :pseudo 
    
                            			WHERE id = :id ";
                $object->execute($data);
            }
    

            $success = 'Utilisateur Modifié';
        }

        $output = array(
            'error' => $error,
            'success' => $success,
            'id' => $_POST['hidden_id'],
        );

        echo json_encode($output);

    }

    if ($_POST["action"] == 'statut') {
        $data = array(
            ':statut' => $_POST['next_statut'],
        );

        $object->query = "
		UPDATE user
		SET statut = :statut
		WHERE id = '" . $_POST["id"] . "'
		";

        $object->execute($data);

        echo 'Statut utilisateur changé à ' . $_POST['next_statut'] . '';
    }

    if ($_POST["action"] == 'edit_permission') {
        $data = array(
            ':statut' => $_POST['next_statut'], 
            ':route' => $_POST['route'],
            ':user' => $_POST['user'],
        );

        $object->query = "
		UPDATE permission
		SET ".$_POST['crud']." = :statut
		WHERE route = :route AND user = :user
		";

        $object->execute($data);

        echo 'Permission changé';
    }

    if ($_POST["action"] == 'edit_route') {
        $data = array(
            ':etat'     => $_POST['next_etat'], 
            ':route'    => $_POST['route'],
            ':user'     => $_POST['user'],
        );

        $object->query = "
		UPDATE user_route
		SET etat = :etat
		WHERE route = :route AND user = :user
		";

        $object->execute($data);

        echo 'Permission changé';
    }

    if ($_POST["action"] == 'permission') {

        $order_column = array('r.libelle', 'ur.etat', 'p.c', 'p.r', 'p.u' );

        $output = array();

        $main_query = " SELECT
                            id as idd ,
                            libelle,
                            url,
                            (SELECT market FROM user_market WHERE  user = 258 and etat = '2' LIMIT 1) as market , 
                            (SELECT etat FROM user_route WHERE  route =  idd and user = ".$_POST['id']." ) as etat ,
                           	(SELECT c FROM permission WHERE  route =  idd and user = ".$_POST['id']." ) as c ,
                          	(SELECT r FROM permission WHERE  route =  idd and user = ".$_POST['id']." ) as r ,
                         	(SELECT u FROM permission WHERE  route =  idd and user = ".$_POST['id']." ) as u , 
                            (SELECT d FROM permission WHERE  route =  idd and user = ".$_POST['id']." ) as d  
                        FROM
                            `route`
                        WHERE
                                statut = 'Activer'   ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) { 
            
            $search_query .= ' AND ( libelle LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }

        if (isset($_POST["order"])) {
            $order_query = ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = ' ORDER BY idd ASC ';
        }

        $limit_query = '';

        if ($_POST["length"] != -1) {
            $limit_query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $object->query = $main_query . $search_query  . $order_query;

        $object->execute();

        $filtered_rows = $object->row_count();

        $object->query .= $limit_query;
  
        $result = $object->get_result();
        
        $object->query = $main_query;

        $object->execute();

        $total_rows = $object->row_count();

        $data = array();
 
        foreach ($result as $row) {
            
            $row_market = json_decode($object->get_market($row['market'])); 
  
            $sub_array = array();
              
        	$sub_array[] =  ucwords($row['libelle']) ;
        	
            $etat = ($row["etat"] == '2') ?  'checked' : '' ;
            
        	$sub_array[] = '
        		<div class="form-check form-switch ">
                  <input class="form-check-input button_route" name="button_route" type="checkbox" data-route="'.$row["idd"].'" data-user="'.$_POST['id'].'" data-etat="'.$row["etat"].'" '.$etat.' />
                </div>
        		'; 
        		
        	if($row["etat"] == '2'){	
			
			$create = ($row["c"] == '2') ?  'checked' : '' ;
			
			$sub_array[] = '
			<div class="form-check form-switch ">
                  <input class="form-check-input button_permission" name="button_permission" type="checkbox"  data-crud="c" data-route="'.$row["idd"].'" data-user="'.$_POST['id'].'" data-statut="'.$row["c"].'" '.$create.'>
                </div>
			'; 
			
			$read = ($row["r"] == '2') ?  'checked' : '' ;
			
			$sub_array[] = '
			 <div class="form-check form-switch ">
                  <input class="form-check-input button_permission" name="button_permission" type="checkbox"  data-crud="r" data-route="'.$row["idd"].'" data-user="'.$_POST['id'].'" data-statut="'.$row["r"].'" '.$read.'>
                </div>
			'; 
			
			$update = ($row["u"] == '2') ?  'checked' : '' ;
			
			$sub_array[] = '
			  <div class="form-check form-switch ">
                  <input class="form-check-input button_permission" name="button_permission" type="checkbox"  data-crud="u" data-route="'.$row["idd"].'" data-user="'.$_POST['id'].'" data-statut="'.$row["u"].'" '.$update.'>
                </div>
			'; 
			
			$delete = ($row["d"] == '2') ?  'checked' : '' ;
			
			$sub_array[] = '
			  <div class="form-check form-switch ">
                  <input class="form-check-input button_permission" name="button_permission" type="checkbox"  data-crud="d" data-route="'.$row["idd"].'" data-user="'.$_POST['id'].'" data-statut="'.$row["d"].'" '.$delete.'>
                </div>
			'; 
        	}else{
        	    $sub_array[] = '';
        	    $sub_array[] = '';
        	    $sub_array[] = ''; 
        	    $sub_array[] = ''; 
        	}
           
            if($row['url'] == 'niveau-4'){
                if($row_market->niveau == '4' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'niveau-3'){
                if($row_market->niveau == '3' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'niveau-0'){
                if($row_market->ingredient == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'feedback'){
                if($row_market->feedback == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'event'){
                if($row_market->event == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'qrcode'){
                if($row_market->design_qrcode == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'commande'){
                if($row_market->commande == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'utilisateur'){
                if($row_market->utilisateur == '2' ){
                    $data[] = $sub_array;
                } 
            }else if($row['url'] == 'vip'){
                if($row_market->vip == '2' ){
                    $data[] = $sub_array;
                } 
            }else{
                 $data[] = $sub_array;
            }
             
        } 

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $filtered_rows,
            "data" => $data,
        );

        echo json_encode($output);

    }
    
	if ($_POST["action"] == 'changeall') {
	    
	    $data = array(
			':etat'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':user'	    =>	$_POST['user']
		);

    	$object->query = "  UPDATE
                                user_route
                            SET
                                etat = :etat
                            WHERE
                                user = :user  ";

		$object->execute($data);
		 	    
	    $data = array(
			':c'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':r'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':u'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':d'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':user'	    =>	$_POST['user']
		);

    	$object->query = "  UPDATE
                                permission
                            SET
                                c = :c,
                                r = :r,
                                u = :u,
                                d = :d
                            WHERE
                                user = :user  ";

		$object->execute($data);

		echo 'Autorisation changé';
	}

    if ($_POST["action"] == 'edit_market') {
        $data = array(
            ':etat'     => $_POST['next_etat'], 
            ':market'    => $_POST['market'],
            ':user'     => $_POST['user'],
        );

        $object->query = "
		UPDATE user_market
		SET etat = :etat
		WHERE market = :market AND user = :user
		";

        $object->execute($data);

        echo 'Permission changé';
    }

    if ($_POST["action"] == 'market') {

        $order_column = array('m.market', 'ur.etat' );

        $output = array();

        $main_query = " SELECT
                        	m.id , 
                            m.market,
                            um.etat
                        FROM
                            	market m  
                                LEFT JOIN user_market um ON um.market = m.id 
                            WHERE
                            m.statut = 'Activer' and um.user = ".$_POST['id']."  AND m.gestionnaire = (SELECT owner FROM user WHERE id = ".$_POST['id']." )   ";

        $search_query = '';

        if (isset($_POST["search"]["value"])) { 
            
            $search_query .= ' AND (m.market LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }

        if (isset($_POST["order"])) {
            $order_query = ' ORDER BY ' . $order_column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $order_query = ' ORDER BY m.id ASC ';
        }

        $limit_query = '';

        if ($_POST["length"] != -1) {
            $limit_query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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
             
        			
        	$sub_array[] =  ucwords($row['market']) ;
        	
            $etat = ($row["etat"] == '2') ?  'checked' : '' ;
            
        	$sub_array[] = '
        		<div class="form-check form-switch ">
                  <input class="form-check-input button_market" name="button_market" type="checkbox" data-market="'.$row["id"].'" data-user="'.$_POST['id'].'" data-etat="'.$row["etat"].'" '.$etat.' />
                </div>
        		';  
           

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
    
	if ($_POST["action"] == 'marketAll') {
	    
	    $data = array(
			':etat'		=>	($_POST['what'] == 'enable') ? '2' : '1',
			':user'	    =>	$_POST['user']
		);

    	$object->query = "  UPDATE
                                user_market
                            SET
                                etat = :etat
                            WHERE
                                user = :user  ";

		$object->execute($data);
  
		echo 'Autorisation changé';
	}
}
