<?php

//     include('../config.menuqrcode/config.php');
    
//     $object = new application(); 
    
// function duplicateData($object, $table, $data, $excludeFields = ['id','market'], $newMarketValue = 240) {
//     $columns = array_keys($data);
//     $filteredColumns = array_filter($columns, function($column) use ($excludeFields) {
//         return !in_array($column, $excludeFields);
//     });
//     $placeholders = ':' . implode(', :', $filteredColumns);
//     $columnsString = implode(', ', $filteredColumns);
    
//     // Append 'market' to columnsString and placeholders
//     $columnsString .= ', market';
//     $placeholders .= ', :market';
    
//     $query = "INSERT INTO $table ($columnsString) VALUES ($placeholders)";
//     $params = [];
//     foreach ($filteredColumns as $column) {
//         $params[":$column"] = $data[$column];
//     }
//     $params[':market'] = $newMarketValue;

//     $object->query = $query;
//     $object->execute($params);

//     return $object->connect->lastInsertId();
// }

// // Function to duplicate language data into a table
// function duplicateLangueData($object, $langueTable, $newParentId, $parentIdField, $oldParentId) {
//     $object->query = "SELECT * FROM $langueTable WHERE $parentIdField = '$oldParentId'";
//     $langueResults = $object->get_result();

//     foreach ($langueResults as $langueRow) {
//         // Update the parent ID reference
//         $langueRow[$parentIdField] = $newParentId;

//         // Perform insertion into language table
//         $columns = array_keys($langueRow);
//         $placeholders = ':' . implode(', :', $columns);
//         $columnsString = implode(', ', $columns);

//         $query = "INSERT INTO $langueTable ($columnsString) VALUES ($placeholders)";
        
//         $params = [];
//         foreach ($columns as $column) {
//             $params[":$column"] = $langueRow[$column];
//         }
        
//         $object->query = $query;
//         $object->execute($params); 
//     }
// }

// $object->query = "SELECT * FROM categorie_sub_sub WHERE market = '239'";
// $result_categorie_sub_sub = $object->get_result();

// foreach ($result_categorie_sub_sub as $categorie_sub_sub) {
//     // Duplicate categorie_sub_sub
//     $id_categorie_sub_sub = duplicateData($object, 'categorie_sub_sub', $categorie_sub_sub);

//     // Duplicate langue_categorie_sub_sub
//     duplicateLangueData($object, 'langue_categorie_sub_sub', $id_categorie_sub_sub, 'categorie', $categorie_sub_sub['id']);

//     $object->query = "SELECT * FROM categorie_sub WHERE categorie = '".$categorie_sub_sub['id']."'";
//     $result_categorie_sub = $object->get_result();

//     foreach ($result_categorie_sub as $categorie_sub) {
//         // Duplicate categorie_sub
//         $categorie_sub['categorie'] = $id_categorie_sub_sub; // Update parent ID reference
//         $id_categorie_sub = duplicateData($object, 'categorie_sub', $categorie_sub);

//         // Duplicate langue_categorie_sub
//         duplicateLangueData($object, 'langue_categorie_sub', $id_categorie_sub, 'categorie', $categorie_sub['id']);

//         $object->query = "SELECT * FROM categorie WHERE categorie = '".$categorie_sub['id']."'";
//         $result_categorie = $object->get_result();

//         foreach ($result_categorie as $categorie) {
//             // Duplicate categorie
//             $categorie['categorie'] = $id_categorie_sub; // Update parent ID reference
//             $id_categorie = duplicateData($object, 'categorie', $categorie);

//             // Duplicate langue_categorie
//             duplicateLangueData($object, 'langue_categorie', $id_categorie, 'categorie', $categorie['id']);

//             $object->query = "SELECT * FROM article WHERE categorie = '".$categorie['id']."'";
//             $result_article = $object->get_result();

//             foreach ($result_article as $article) {
//                 // Duplicate article
//                 $article['categorie'] = $id_categorie; // Update parent ID reference
//                 $id_article = duplicateData($object, 'article', $article);

//                 // Duplicate langue_article
//                 duplicateLangueData($object, 'langue_article', $id_article, 'article', $article['id']);
//             }
//         }
//     }
// }
