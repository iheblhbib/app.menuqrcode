<?php
/*
 * Cron planning menuqrcode : active / désactive categorie_sub_sub, categorie_sub, categorie et article
 * selon la table `planning`. À lancer toutes les minutes depuis cPanel > Cron Jobs.
 *
 * Règles :
 *  - un élément sans ligne dans `planning` n'est jamais touché ;
 *  - un élément dont le planning est en pause (planning_actif = 0) n'est jamais touché ;
 *  - un élément supprimé (etat <> '0') n'est jamais touché ;
 *  - on n'écrit en base que si le statut change (pas d'UPDATE inutile chaque minute).
 */

if (php_sapi_name() !== 'cli') {      // empêche de lancer le script depuis un navigateur
    http_response_code(403);
    exit('Forbidden');
}

// Config partagée : /home5/qdwlxrcz/config.menuqrcode/ (le script est dans un dossier voisin, ex. /home5/qdwlxrcz/cron/)
define('CONFIG_PATH', __DIR__ . '/../config.menuqrcode/config.php');

require CONFIG_PATH;   // fixe déjà Africa/Tunis et ouvre la connexion dans new application()

date_default_timezone_set('Africa/Tunis');

// Verrou : si un passage précédent tourne encore, on n'en lance pas un deuxième en parallèle
$lock = fopen(__DIR__ . '/planning-cron.lock', 'c');
if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
    exit(0);
}

try {
    $object = new application();          // même classe que les menus et l'admin
    $pdo = $object->connect;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $jour  = (int) date('N');               // 1..7
    $hier  = $jour === 1 ? 7 : $jour - 1;
    $heure = $pdo->quote(date('H:i:s'));

    // Créneau ouvert maintenant :
    //  - créneau normal du jour (12:00 -> 20:00)
    //  - créneau de nuit commencé aujourd'hui (20:00 -> 02:00, avant minuit)
    //  - créneau de nuit commencé hier (20:00 -> 02:00, après minuit)
    $ouvert = "(
        (p.jour = $jour AND p.heure_debut <= p.heure_fin AND $heure >= p.heure_debut AND $heure < p.heure_fin)
     OR (p.jour = $jour AND p.heure_debut >  p.heure_fin AND $heure >= p.heure_debut)
     OR (p.jour = $hier AND p.heure_debut >  p.heure_fin AND $heure <  p.heure_fin)
    )";

    $total = 0;
    foreach (['categorie_sub_sub', 'categorie_sub', 'categorie', 'article'] as $table) {   // liste fixe, jamais venue de la base
        $sql = "UPDATE `$table` t
                JOIN (
                    SELECT p.id_element, MAX($ouvert) AS ouvert
                    FROM planning p
                    WHERE p.type = '$table' AND p.planning_actif = 1
                    GROUP BY p.id_element
                ) x ON x.id_element = t.id
                SET t.statut = IF(x.ouvert = 1, 'Activer', 'Desactiver')
                WHERE t.etat = '0'
                  AND t.statut <> IF(x.ouvert = 1, 'Activer', 'Desactiver')";
        $total += $pdo->exec($sql);
    }

    // Trace du dernier passage (pour vérifier que le cron tourne bien)
    file_put_contents(__DIR__ . '/planning-cron.last', date('Y-m-d H:i:s') . " OK, $total changement(s)\n");
} catch (Throwable $e) {
    error_log('[planning-cron] ' . $e->getMessage());
    file_put_contents(__DIR__ . '/planning-cron.last', date('Y-m-d H:i:s') . ' ERREUR : ' . $e->getMessage() . "\n");
    exit(1);
}
