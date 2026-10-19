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
    		$order_column = array('l.libelle','l.code','l.etat','created');
    
    		$output = array();
    
    		$main_query = " SELECT
                                a.*,
                                l.libelle,
                                l.code
                            FROM
                                market_langue a 
                                LEFT JOIN langue as l ON l.id = a.langue
                            WHERE
                                a.market = ".$_SESSION['market']."  AND l.statut = 'Activer' ";
            
    		$search_query = '';
    
    		if(isset($_POST["search"]["value"]))
    		{
    			$search_query .= 'AND (l.libelle LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR l.code LIKE "%'.$_POST["search"]["value"].'%" ';
    			$search_query .= 'OR l.etat LIKE "%'.$_POST["search"]["value"].'%"  ) ';
    		}
    
    		if(isset($_POST["order"]))
    		{
    			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
    		}
    		else
    		{
    			$order_query = 'ORDER BY a.etat ASC ';
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
    			
	            $sub_array[] = empty($row["libelle"]) ? '--' :  ($row["libelle"]); 
                
                $sub_array[] = empty($row["code"]) ? '--' :  ($row["code"]);   
                    
                $etat = ($row["etat"] == '1') ? 'success' : 'danger';
                   
                $libelle = ($row["etat"] == '1') ? 'Activer' : 'Desactiver';
                
                if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 
    
                $sub_array[] = '<button style="font-size:x-small" type="button" name="etat_button" class="  btn btn-' . $etat . '  btn-sm etat_button"  data-id="' . $row["id"] . '"data-etat="' . $row["etat"] . '">' .$libelle. '</button>';
       
                }else{
                    $sub_array[] = '<span  class="text-' . $etat . '  "  >' .$libelle. '</span>';
                     
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
    	 
    	if($_POST["action"] == 'etat')
    	{
    		$data = array(
    			':etat'		=>	$_POST['next_etat']
    		);
    
    		$object->query = "
    		UPDATE market_langue 
    		SET etat = :etat 
    		WHERE id = '".$_POST["id"]."'
    		";
    
    		$object->execute($data);
    
    		echo 'Langue  '.$_POST['next_etat'].'';
    	}
    	

}   


?>