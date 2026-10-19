<?php
/*
 * Contrôles de sécurité communs de l'espace client.
 * À inclure après config.php, une fois $object = new application() créé.
 * Le même fichier existe dans l'administration (admin.menuqrcode) pour la partie jeton.
 * Compatible PHP 7.3.
 */

/*
 * Clé secrète partagée entre l'admin et l'espace client.
 * Si config.php définit MENUQR_SECRET, on l'utilise ; sinon on dérive la clé du contenu
 * de config.php (fichier commun aux deux sites, hors dépôt, qui contient déjà des secrets).
 */
function securite_secret()
{
    if (defined('MENUQR_SECRET') && MENUQR_SECRET !== '') {
        return MENUQR_SECRET;
    }
    $reflexion = new ReflectionClass('application');
    return hash_file('sha256', $reflexion->getFileName());
}

/* Signature du lien « se connecter en tant que » généré dans l'admin. */
function securite_signature_connexion($id, $market, $expire)
{
    return hash_hmac('sha256', 'connexion|' . $id . '|' . $market . '|' . $expire, securite_secret());
}

function securite_verifier_jeton_connexion($id, $market, $expire, $signature)
{
    if (!ctype_digit((string) $id) || !ctype_digit((string) $expire) || !is_string($signature)) {
        return false;
    }
    if ($market !== '' && !ctype_digit((string) $market)) {
        return false;
    }
    if ((int) $expire < time()) {
        return false;
    }
    return hash_equals(securite_signature_connexion($id, $market, $expire), $signature);
}

/* Arrête la requête avec un message JSON (les pages d'action sont appelées en AJAX). */
function securite_refuser($code, $message)
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('error' => $message, 'success' => ''));
    exit;
}

/* Identifiants des points de vente que l'utilisateur peut gérer (gestionnaire ou employé). */
function securite_markets_utilisateur($object, $user)
{
    $requete = $object->connect->prepare("
        SELECT id FROM market WHERE gestionnaire = :user
        UNION
        SELECT market FROM user_market WHERE user = :user2 AND etat = '2'
    ");
    $requete->execute(array(':user' => $user, ':user2' => $user));

    $ids = array();
    foreach ($requete->fetchAll(PDO::FETCH_COLUMN) as $id) {
        $ids[] = (string) $id;
    }
    return $ids;
}

function securite_market_autorise($object, $market)
{
    if (empty($_SESSION['id']) || $market === '' || $market === null) {
        return false;
    }
    return in_array((string) $market, securite_markets_utilisateur($object, $_SESSION['id']), true);
}

/*
 * À appeler en haut de chaque page d'action réservée aux clients connectés :
 * exige une session, et vérifie que le point de vente envoyé ou en session appartient à l'utilisateur.
 */
function securite_exiger_connexion($object)
{
    if (!$object->is_login()) {
        securite_refuser(401, 'Session expirée, reconnectez-vous.');
    }

    if (isset($_POST['market']) && $_POST['market'] !== '' && !securite_market_autorise($object, $_POST['market'])) {
        securite_refuser(403, 'Accès refusé à ce point de vente.');
    }

    if (!empty($_SESSION['market']) && !securite_market_autorise($object, $_SESSION['market'])) {
        unset($_SESSION['market']);
        securite_refuser(403, 'Accès refusé à ce point de vente.');
    }
}

/*
 * Vérifie un fichier envoyé et renvoie un nom aléatoire sûr (avec extension en minuscules),
 * ou false si l'extension n'est pas autorisée ou si une image n'en est pas une.
 */
function securite_nom_fichier($fichier, $extensions)
{
    if (!isset($fichier['tmp_name'], $fichier['name']) || !is_uploaded_file($fichier['tmp_name'])) {
        return false;
    }

    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $extensions, true)) {
        return false;
    }

    $images = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    if (in_array($extension, $images, true) && @getimagesize($fichier['tmp_name']) === false) {
        return false;
    }

    if ($extension === 'pdf') {
        $debut = file_get_contents($fichier['tmp_name'], false, null, 0, 5);
        if ($debut !== '%PDF-') {
            return false;
        }
    }

    return bin2hex(random_bytes(12)) . '.' . $extension;
}
