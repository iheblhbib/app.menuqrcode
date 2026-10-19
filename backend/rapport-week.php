<?php  include('../../config.menuqrcode/config.php');

$object = new application(); 

$data = array();

$object->query = " SELECT DISTINCT
                        date(created) as date
                    FROM
                        acces_liste
                    WHERE
                        market = '".$_POST['market']."'  and  date(created) >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                          
                    ORDER BY
                            date ASC
                    LIMIT 8";

$result = $object->get_result();

foreach ($result as $row) {

    $sub_array = array();

    $access = $object->CountAccessDuration($_POST['market'],'day',$row['date']); 

    $sub_array[] = $row['date'];
    $sub_array[] = ($access) ? $access : 0 ; 

    $data[] = $sub_array;
 
}

echo json_encode($data);
