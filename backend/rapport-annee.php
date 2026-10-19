<?php  include('../../config.menuqrcode/config.php');

$object = new application(); 

$data = array();

$object->query = " SELECT DISTINCT month ,
                        year , 
                        market,
                        sum(counted ) as sum 
                    FROM
                        archive_market_mois  
                    WHERE 
                        market = '".$_POST['market']."'  AND 
                        CONCAT(year, '-', LPAD(month, 2, '0'), '-01') >= DATE_SUB(NOW(), INTERVAL 13 MONTH)
                    GROUP BY 
                        month , year
                    ORDER BY
                        year , month   ASC ";

$result = $object->get_result();

foreach ($result as $row) {

    $sub_array = array(); 

    $sub_array[] = $row['year'].'-'.$row['month'];
    $sub_array[] =  $row['sum']; 

    $data[] = $sub_array;
 
}

echo json_encode($data);


// include('../../config.menuqrcode/config.php');

// $object = new application(); 

// $data = array();

// $object->query = " SELECT DISTINCT
//                          date_format(created,'%Y-%m') as date
//                     FROM
//                         acces_liste
//                     WHERE
//                         market = '".$_POST['market']."'  and   YEAR(created) = YEAR(CURRENT_DATE())  
                          
//                     ORDER BY
//                             date ASC ";

// $result = $object->get_result();

// foreach ($result as $row) {

//     $sub_array = array();

//     $access = $object->CountAccessDuration($_POST['market'],'mois',$row['date'].'-01'); 

//     $sub_array[] = $row['date'];
//     $sub_array[] = ($access) ? $access : 0 ; 

//     $data[] = $sub_array;
 
// }

// echo json_encode($data);
