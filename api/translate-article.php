<?php
/**
 * Endpoint AJAX de traduction automatique gratuite (proxy vers MyMemory Translation API).
 * Reçoit : text (string, FR), targets (array de codes langue ISO, ex: en, es, de)
 * Retourne : { success, translations: {code: texte}, errors: {code: message} }
 *
 * Note : l'endpoint non-officiel translate.googleapis.com a été testé mais bloque
 * systématiquement les requêtes serveur/hébergement ("Sorry... automated queries"),
 * ce qui rend json_decode() muet. MyMemory (mymemory.translated.net) est gratuit,
 * sans clé API, et ne présente pas ce blocage.
 */

header('Content-Type: application/json; charset=utf-8');

$text    = isset($_POST['text']) ? trim($_POST['text']) : '';
$targets = isset($_POST['targets']) && is_array($_POST['targets']) ? $_POST['targets'] : [];

if ($text === '' || empty($targets)) {
    echo json_encode(['success' => false, 'message' => 'Texte ou langues cibles manquants']);
    exit;
}

function mymemory_translate($text, $source, $target, &$errorDetail = null)
{
    $url = 'https://api.mymemory.translated.net/get'
        . '?q=' . urlencode($text)
        . '&langpair=' . urlencode($source . '|' . $target);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $curlError) {
        $errorDetail = 'cURL: ' . $curlError;
        return null;
    }

    $data = json_decode($response, true);
    if (!is_array($data) || !isset($data['responseData']['translatedText'])) {
        $errorDetail = 'Réponse inattendue (HTTP ' . $httpCode . ')';
        return null;
    }

    if ((int) $data['responseStatus'] !== 200) {
        $errorDetail = $data['responseDetails'] ?? ('HTTP ' . $httpCode);
        return null;
    }

    return $data['responseData']['translatedText'];
}

$translations = [];
$errors = [];

foreach ($targets as $code) {
    $code = preg_replace('/[^a-zA-Z-]/', '', (string) $code);
    if ($code === '') {
        continue;
    }

    $errorDetail = null;
    $result = mymemory_translate($text, 'fr', $code, $errorDetail);

    if ($result !== null) {
        $translations[$code] = $result;
    } else {
        $errors[$code] = $errorDetail ?: 'Echec de traduction';
    }
}

echo json_encode([
    'success'      => count($translations) > 0,
    'translations' => $translations,
    'errors'       => $errors,
]);
