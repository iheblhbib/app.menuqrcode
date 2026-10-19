<?php  include('../../config.menuqrcode/config.php');

// $object = new application(); 

// $data = array();

// $object->query = " SELECT DISTINCT
//                         DATE_FORMAT(created, '%H') AS hour
//                     FROM
//                         `acces_liste`
//                     WHERE
//                         market = '".$_POST['market']."' AND created >(NOW() - INTERVAL 24 HOUR)  ";

// $result = $object->get_result();

// foreach ($result as $row) {

//     $sub_array = array();

//     $access = $object->CountAccessDuration($_POST['market'],'hour',date('Y-m-d'),$row['hour']); 

//     $sub_array[] = $row['hour'];
//     $sub_array[] = ($access) ? $access : 0 ; 

//     $data[] = $sub_array;
 
// }

// echo json_encode($data);


$object = new application(); 

include_once(__DIR__ . '/../securite.php');
securite_exiger_connexion($object);
$data = array();

// Requête optimisée qui compte les accès groupés par heure
$object->query = "
    SELECT 
        DATE_FORMAT(created, '%H') AS hour,
        COUNT(*) as access_count
    FROM 
        acces_liste
    WHERE 
        market = '" . $_POST['market'] . "' 
        AND created > (NOW() - INTERVAL 24 HOUR)
    GROUP BY 
        hour
    ORDER BY 
        hour
";

$result = $object->get_result();

// Initialiser un tableau avec toutes les heures à 0
$hours = array_fill_keys(array_map(function($h) {
    return str_pad($h, 2, '0', STR_PAD_LEFT);
}, range(0, 23)), 0);

// Remplir les heures présentes dans la base de données
foreach ($result as $row) {
    $hours[$row['hour']] = $row['access_count'];
}

// Préparer le format final pour json_encode
foreach ($hours as $hour => $count) {
    $data[] = [$hour, $count];
}

echo json_encode($data);