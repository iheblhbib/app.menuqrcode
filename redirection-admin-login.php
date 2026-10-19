<?php

// Connexion « en tant que » un client, depuis l'administration (client.php, permission.php).
// Le lien est signé par l'admin (securite_signature_connexion) et expire : sans signature valide, rien ne se passe.

include('../config.menuqrcode/config.php');
include_once(__DIR__ . '/securite.php');

$object = new application();

if(isset($_POST["action"]) && $_POST['action'] == 'login-admin')
{
    $id = isset($_POST['id']) ? (string) $_POST['id'] : '';
    $expire = isset($_POST['expire']) ? (string) $_POST['expire'] : '';
    $signature = isset($_POST['signature']) ? $_POST['signature'] : '';

    if(!securite_verifier_jeton_connexion($id, '', $expire, $signature))
    {
        http_response_code(403);
        echo 'Lien de connexion invalide ou expiré. Relancez la connexion depuis l\'administration.';
        exit();
    }

    unset($_SESSION['market']);

    session_regenerate_id(true);

    $_SESSION['id'] = $id;

    setcookie("id" , $object->encrypt($_SESSION['id']) , time() + (86400 * 10) );

    header("location:".$object->client_url."");
    exit();
}

http_response_code(403);
echo 'Accès refusé.';
