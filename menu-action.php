<?php
/*
 * API JSON de l'éditeur de menu (menu.php).
 * Lecture seule : arbre, listes, recherche. Toutes les écritures (Add, Edit, statut, etat,
 * update_order, duplicate) passent toujours par niveau-N-action.php.
 * Compatible PHP 7.3.
 */

include('../config.menuqrcode/config.php');
include('planning-functions.php');

$object = new application();

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

header('Content-Type: application/json; charset=utf-8');

function out($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (!$object->is_login()) {
    out(['error' => 'Session expirée, reconnectez-vous.'], 401);
}
if (empty($_SESSION['market'])) {
    out(['error' => 'Aucun point de vente sélectionné.'], 400);
}

$market = json_decode($object->get_market($_SESSION['market']));
$pdo    = $object->connect;
$niveau = (int) $market->niveau;
$TABLES = [4 => 'categorie_sub_sub', 3 => 'categorie_sub', 2 => 'categorie', 1 => 'article'];

function droits($object, $level)
{
    $d = [];
    foreach (['r', 'c', 'u', 'd'] as $right) {
        $d[$right] = $object->autorisation($right, $_SESSION['id'], 'niveau-' . $level) == 2;
    }
    return $d;
}

function img($path)
{
    return $path ? str_replace('../', 'https://', $path) : '';
}

function rows($pdo, $sql, $params)
{
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

/* Planning de tout le market pour un type : id_element => ['resume' => ..., 'actif' => bool] */
function plannings($pdo, $market_id, $type)
{
    $map = [];
    $by = [];
    try {
        $list = rows($pdo, "SELECT id_element, jour, heure_debut, heure_fin, planning_actif FROM planning
                            WHERE market = ? AND type = ? ORDER BY heure_debut, jour", [$market_id, $type]);
    } catch (Exception $e) {
        return [];   // table planning absente : l'éditeur fonctionne sans
    }
    foreach ($list as $r) {
        $by[$r['id_element']][] = $r;
    }
    foreach ($by as $id => $list) {
        $p = planning_group($list);
        $map[$id] = ['resume' => planning_resume($p), 'actif' => $p['actif']];
    }
    return $map;
}

/* Nombre d'enfants (non archivés) par parent : parent_id => ['total' => n, 'actifs' => n] */
function compte_enfants($pdo, $market_id, $table)
{
    $map = [];
    foreach (rows($pdo, "SELECT categorie, COUNT(*) AS total, SUM(statut = 'Activer') AS actifs
                         FROM `$table` WHERE market = ? AND etat = '0' GROUP BY categorie", [$market_id]) as $r) {
        $map[$r['categorie']] = ['total' => (int) $r['total'], 'actifs' => (int) $r['actifs']];
    }
    return $map;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

/* ------------------------------------------------------------------ arbre */
if ($action === 'tree') {
    $perms = [];
    for ($l = $niveau; $l >= 1; $l--) {
        $perms[$l] = droits($object, $l);
    }

    $nodes = [];
    $known = [];   // clés présentes, pour écarter les orphelins (parent archivé ou supprimé)
    for ($l = $niveau; $l >= 2; $l--) {
        $table = $TABLES[$l];
        $enfants = compte_enfants($pdo, $market->id, $TABLES[$l - 1]);
        $plan = plannings($pdo, $market->id, $table);
        $list = rows($pdo, "SELECT * FROM `$table` WHERE market = ? AND etat = '0' ORDER BY order_categorie ASC, id ASC", [$market->id]);
        foreach ($list as $r) {
            $parent = null;
            if ($l < $niveau) {
                $parent = ($l + 1) . '-' . $r['categorie'];
                if (!isset($known[$parent])) {
                    continue;
                }
            }
            $key = $l . '-' . $r['id'];
            $known[$key] = true;
            $c = isset($enfants[$r['id']]) ? $enfants[$r['id']] : ['total' => 0, 'actifs' => 0];
            $nodes[] = [
                'key'      => $key,
                'level'    => $l,
                'id'       => (int) $r['id'],
                'parent'   => $parent,
                'libelle'  => $r['libelle'],
                'statut'   => $r['statut'],
                'image'    => $r['display_image'] == '2' ? '' : img($r['image']),
                'count'    => $c['total'],
                'actifs'   => $c['actifs'],
                'planning' => isset($plan[$r['id']]) ? $plan[$r['id']] : null,
            ];
        }
    }

    out([
        'market' => [
            'id'           => (int) $market->id,
            'nom'          => $market->market,
            'niveau'       => $niveau,
            'multi_langue' => $market->multi_langue == '1',
            'vip'          => $market->vip == '2',
        ],
        'perms' => $perms,
        'nodes' => $nodes,
    ]);
}

/* ------------------------------------------------------------------ liste des enfants */
if ($action === 'list') {
    $level  = (int) (isset($_POST['level']) ? $_POST['level'] : 0);
    $parent = (int) (isset($_POST['parent']) ? $_POST['parent'] : 0);
    $etat   = (isset($_POST['etat']) && $_POST['etat'] === '1') ? '1' : '0';

    if (!isset($TABLES[$level]) || $level > $niveau) {
        out(['error' => 'Niveau invalide.'], 400);
    }
    if (!droits($object, $level)['r']) {
        out(['error' => "Vous n'avez pas accès à cette liste."], 403);
    }

    $table  = $TABLES[$level];
    $where  = 'market = ?';
    $params = [$market->id];
    if ($level < $niveau) {
        $where .= ' AND categorie = ?';
        $params[] = $parent;
    }

    // compteurs pour les filtres Actifs / Archivés
    $compte = rows($pdo, "SELECT etat, COUNT(*) AS n FROM `$table` WHERE $where GROUP BY etat", $params);
    $counts = ['0' => 0, '1' => 0];
    foreach ($compte as $c) {
        $counts[$c['etat']] = (int) $c['n'];
    }

    $list = rows($pdo, "SELECT * FROM `$table` WHERE $where AND etat = ? ORDER BY order_categorie ASC, id ASC",
                 array_merge($params, [$etat]));

    $enfants = $level > 1 ? compte_enfants($pdo, $market->id, $TABLES[$level - 1]) : [];
    $plan    = plannings($pdo, $market->id, $table);

    $allergenes = [];
    if ($level === 1 && $list) {
        $ids = array_map(function ($r) { return (int) $r['id']; }, $list);
        $in  = implode(',', $ids);   // entiers uniquement
        foreach (rows($pdo, "SELECT aa.article, al.emoji, al.libelle FROM allergens_article aa
                             JOIN allergens al ON al.id = aa.allergen WHERE aa.article IN ($in)", []) as $a) {
            $allergenes[$a['article']][] = trim($a['emoji'] . ' ' . $a['libelle']);
        }
    }

    $items = [];
    foreach ($list as $r) {
        $item = [
            'id'       => (int) $r['id'],
            'level'    => $level,
            'libelle'  => $r['libelle'],
            'statut'   => $r['statut'],
            'etat'     => $r['etat'],
            'image'    => img($r['image']),
            'show_img' => $r['display_image'] != '2',
            'planning' => isset($plan[$r['id']]) ? $plan[$r['id']] : null,
        ];
        if ($level > 1) {
            $c = isset($enfants[$r['id']]) ? $enfants[$r['id']] : ['total' => 0, 'actifs' => 0];
            $item['count']  = $c['total'];
            $item['actifs'] = $c['actifs'];
        }
        if ($level === 2) {
            $item['prix'] = $r['etat_prix'] == '2' ? $r['prix'] : '';
        }
        if ($level === 1) {
            $item['prix']        = $r['prix'];
            $item['description'] = trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode((string) $r['description']))));
            $item['type']        = $r['type'];
            $item['allergenes']  = isset($allergenes[$r['id']]) ? $allergenes[$r['id']] : [];
        }
        $items[] = $item;
    }

    out(['items' => $items, 'counts' => $counts]);
}

/* ------------------------------------------------------------------ recherche d'articles */
if ($action === 'search') {
    $q = trim(isset($_POST['q']) ? $_POST['q'] : '');
    if (mb_strlen($q) < 2 || !droits($object, 1)['r']) {
        out(['items' => []]);
    }
    $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
    $list = rows($pdo, "SELECT id, libelle, prix, categorie, statut, image, display_image FROM article
                        WHERE market = ? AND etat = '0' AND libelle LIKE ? ORDER BY libelle LIMIT 40",
                 [$market->id, $like]);
    $items = [];
    foreach ($list as $r) {
        $items[] = [
            'id'       => (int) $r['id'],
            'level'    => 1,
            'libelle'  => $r['libelle'],
            'prix'     => $r['prix'],
            'parent'   => $niveau > 1 ? '2-' . $r['categorie'] : null,
            'statut'   => $r['statut'],
            'image'    => img($r['image']),
            'show_img' => $r['display_image'] != '2',
        ];
    }
    out(['items' => $items]);
}

out(['error' => 'Action inconnue.'], 400);
