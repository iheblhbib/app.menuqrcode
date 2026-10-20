<?php

// Connexion « en tant que » un client sur un point de vente précis, depuis l'administration (market.php).
// Le lien est signé par l'admin (securite_signature_connexion) et expire : sans signature valide, rien ne se passe.

include('../config.menuqrcode/config.php');
include_once(__DIR__ . '/securite.php');

$object = new application();

if(isset($_POST["action"]) && $_POST['action'] == 'login-market')
{
    $id = isset($_POST['id']) ? (string) $_POST['id'] : '';
    $market = isset($_POST['market']) ? (string) $_POST['market'] : '';
    $expire = isset($_POST['expire']) ? (string) $_POST['expire'] : '';
    $signature = isset($_POST['signature']) ? $_POST['signature'] : '';

    if($market === '' || !securite_verifier_jeton_connexion($id, $market, $expire, $signature))
    {
        http_response_code(403);
        echo 'Lien de connexion invalide ou expiré. Relancez la connexion depuis l\'administration.';
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['id'] = $id;

    $_SESSION['market'] = $market;

    setcookie("id" , $object->encrypt($_SESSION['id']) , time() + (86400 * 10) );

    header("location:".$object->client_url."");
    exit();
}

http_response_code(403);
echo 'Accès refusé.';
