<?php  include('../../config.menuqrcode/config.php');

$object = new application(); 

include_once(__DIR__ . '/../securite.php');
securite_exiger_connexion($object);
 

$data = array();

$object->query = "  SELECT
                            COUNT(*) AS device
                        FROM
                            acces_liste
                        WHERE
                            market = '".$_POST['market']."'  and MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) and browser like '%iphone%'
                            ";

$result = $object->get_result();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array['value'] = $row['device'];
    $sub_array['name'] = 'iPhone';
    $data[] = $sub_array;
}

$object->query = "  SELECT
                            COUNT(*) AS device
                        FROM
                            acces_liste
                        WHERE
                            market = '".$_POST['market']."'  and MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) and browser like '%android%'
                            ";

$result = $object->get_result();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array['value'] = $row['device'];
    $sub_array['name'] = 'Android';
    $data[] = $sub_array;
}

$object->query = "  SELECT
                            COUNT(*) AS device
                        FROM
                            acces_liste
                        WHERE
                            market = '".$_POST['market']."'  and MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) and browser like '%windows%'
                            ";

$result = $object->get_result(); 

foreach ($result as $row) {
    $sub_array = array();
    $sub_array['value'] = $row['device'];
    $sub_array['name'] = 'Windows';
    $data[] = $sub_array;
}

$object->query = "  SELECT
                            COUNT(*) AS device
                        FROM
                            acces_liste
                        WHERE
                            market = '".$_POST['market']."'  and MONTH(created) = MONTH(CURRENT_DATE()) AND YEAR(created) = YEAR(CURRENT_DATE()) and browser like '%macintosh%'
                            ";

$result = $object->get_result();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array['value'] = $row['device'];
    $sub_array['name'] = 'Mac';
    $data[] = $sub_array;
}
 

echo json_encode($data);
