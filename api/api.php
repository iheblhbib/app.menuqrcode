<?php
 

include('../../config.menuqrcode/config.php'); 

header('Content-Type: application/json; charset=utf-8');

/* ------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------ */

// Nettoyage basique des paramètres injectés dans le SQL.
// La classe "application" fournie ne montre pas de binding de
// paramètres (->query / ->execute() sans arguments) : ce filtre
// est donc une protection minimale. Si ta classe supporte des
// requêtes préparées / du binding, remplace ce mécanisme par
// celui-ci, ce sera plus sûr.
function clean_param($value) {
    $value = trim((string) $value);
    return preg_replace('/[^A-Za-z0-9 _\-\.@]/', '', $value);
}

function fail($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

function run_query($sql) {
    $object = new application();
    $object->query = $sql;
    $object->execute();
    $result = $object->statement_result();
    return is_array($result) ? $result : [];
}

// La colonne `browser` contient le user-agent brut (ex: "Mozilla/5.0
// (iPhone; CPU iPhone OS ...) AppleWebKit/... Safari/..."), donc un GROUP BY
// direct en SQL donne une ligne par user-agent unique au lieu d'une ligne
// par navigateur. On regroupe donc ici, côté PHP, sous un nom de navigateur
// propre (Chrome, Safari, Firefox, Edge, Opera, Samsung Internet...).
function detect_browser($ua) {
    $ua = (string) $ua;
    if ($ua === '') return 'Inconnu';
    if (stripos($ua, 'EdgA/') !== false || stripos($ua, 'EdgiOS/') !== false || stripos($ua, 'Edg/') !== false) return 'Edge';
    if (stripos($ua, 'OPR/') !== false || stripos($ua, 'Opera') !== false) return 'Opera';
    if (stripos($ua, 'SamsungBrowser') !== false) return 'Samsung Internet';
    if (stripos($ua, 'CriOS') !== false) return 'Chrome (iOS)';
    if (stripos($ua, 'FxiOS') !== false) return 'Firefox (iOS)';
    if (stripos($ua, 'Chrome') !== false) return 'Chrome';
    if (stripos($ua, 'Firefox') !== false) return 'Firefox';
    if (stripos($ua, 'MSIE') !== false || stripos($ua, 'Trident') !== false) return 'Internet Explorer';
    if (stripos($ua, 'Safari') !== false) return 'Safari';
    return 'Autre';
}

/* ------------------------------------------------------------
 * Paramètres communs
 * ------------------------------------------------------------ */

$action = isset($_GET['action']) ? clean_param($_GET['action']) : '';
$market = isset($_GET['market']) ? clean_param($_GET['market']) : ''; 
$days   = isset($_GET['days']) ? intval($_GET['days']) : 30;
$limit  = isset($_GET['limit']) ? intval($_GET['limit']) : 8;

if ($days <= 0 || $days > 365) $days = 30;
if ($limit <= 0 || $limit > 50) $limit = 8;
if ($market === '') fail('Paramètre "market" manquant.');

/* ------------------------------------------------------------
 * Router
 * ------------------------------------------------------------ */

switch ($action) {

    case 'summary': {
        $access = run_query("
            SELECT COUNT(*) AS total
            FROM `acces_liste`
            WHERE `market` = '{$market}'
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
        ");

        $feedback = run_query("
            SELECT COUNT(*) AS total, ROUND(AVG(`rating`), 2) AS moyenne
            FROM `feedback`
            WHERE `market` = '{$market}'
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
        ");

        $login = run_query("
            SELECT COUNT(*) AS total
            FROM `login`
            WHERE `user` = (SELECT gestionnaire FROM market WHERE id = '{$market}' )
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
        ");

        echo json_encode([
            'success'       => true,
            'periode_jours' => $days,
            'acces'         => isset($access[0]['total']) ? (int) $access[0]['total'] : 0,
            'feedback'      => isset($feedback[0]['total']) ? (int) $feedback[0]['total'] : 0,
            'note_moyenne'  => isset($feedback[0]['moyenne']) ? (float) $feedback[0]['moyenne'] : 0,
            'connexions'    => isset($login[0]['total']) ? (int) $login[0]['total'] : 0,
        ]);
        break;
    }

    case 'access_series': {
        $rows = run_query("
            SELECT DATE(`created`) AS jour, COUNT(*) AS total
            FROM `acces_liste`
            WHERE `market` = '{$market}'
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            GROUP BY DATE(`created`)
            ORDER BY jour ASC
        ");
        echo json_encode(['success' => true, 'data' => $rows]);
        break;
    }

    case 'login_series': {
        $rows = run_query("
            SELECT DATE(`created`) AS jour, COUNT(*) AS total
            FROM `login`
            WHERE `user` =(SELECT gestionnaire FROM market WHERE id = '{$market}' )
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            GROUP BY DATE(`created`)
            ORDER BY jour ASC
        ");
        echo json_encode(['success' => true, 'data' => $rows]);
        break;
    }

    case 'feedback_ratings': {
        $rows = run_query("
            SELECT `rating`, COUNT(*) AS total
            FROM `feedback`
            WHERE `market` = '{$market}'
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
            GROUP BY `rating`
            ORDER BY `rating` ASC
        ");
        echo json_encode(['success' => true, 'data' => $rows]);
        break;
    }

    case 'browser_stats': {
        $rows = run_query("
            SELECT `browser`
            FROM `acces_liste`
            WHERE `market` = '{$market}'
              AND `created` >= DATE_SUB(NOW(), INTERVAL {$days} DAY)
        ");

        $counts = [];
        foreach ($rows as $row) {
            $name = detect_browser($row['browser']);
            if (!isset($counts[$name])) $counts[$name] = 0;
            $counts[$name]++;
        }
        arsort($counts);
        $counts = array_slice($counts, 0, 6, true);

        $data = [];
        foreach ($counts as $name => $total) {
            $data[] = ['browser' => $name, 'total' => $total];
        }

        echo json_encode(['success' => true, 'data' => $data]);
        break;
    }

    case 'latest_feedback': {
        $rows = run_query("
            SELECT `name`, `rating`, `commentaire`, `created`
            FROM `feedback`
            WHERE `market` = '{$market}'
            ORDER BY `created` DESC
            LIMIT {$limit}
        ");
        echo json_encode(['success' => true, 'data' => $rows]);
        break;
    }

    default:
        fail('Action inconnue : "' . $action . '".', 404);
}