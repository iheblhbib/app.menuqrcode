<?php  include('../../config.menuqrcode/config.php');

$object = new application(); 



$data = array();

$object->query = " SELECT DISTINCT date,
                        sum(counted ) as sum 
                    FROM
                        archive_market_jours  
                    WHERE market = '".$_POST['market']."'  and   date >= DATE_SUB(NOW(), INTERVAL 31 DAY)
                        GROUP BY date
                    ORDER BY
                            date   ASC ";

$result = $object->get_result();

foreach ($result as $row) {

    $sub_array = array(); 

    $sub_array[] =  $row['date'];
    $sub_array[] =  $row['sum']; 

    $data[] = $sub_array;
 
}

echo json_encode($data);


// $data = array();

// $object->query = " SELECT DISTINCT
//                         date(created) as date
//                     FROM
//                         acces_liste
//                     WHERE
//                         market = '".$_POST['market']."'  and   YEAR(created) = YEAR(CURRENT_DATE()) AND MONTH(created) = MONTH(CURRENT_DATE())  
                          
//                     ORDER BY
//                             date ASC ";

// $result = $object->get_result();

// foreach ($result as $row) {

//     $sub_array = array();

//     $access = $object->CountAccessDuration($_POST['market'],'day',$row['date']); 

//     $sub_array[] = $row['date'];
//     $sub_array[] = ($access) ? $access : 0 ; 

//     $data[] = $sub_array;
 
// }

// echo json_encode($data);
