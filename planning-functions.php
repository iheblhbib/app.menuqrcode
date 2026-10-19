<?php
/*
 * Planning d'activation (jours + heures) — partagé par niveau-1 ... niveau-4.
 * Le cron (planning-cron.php) change le statut toutes les minutes ; ici on ne fait
 * que lire / enregistrer les créneaux depuis l'admin.
 */

const PLANNING_TYPES = ['categorie_sub_sub', 'categorie_sub', 'categorie', 'article'];
const PLANNING_JOURS = [1 => 'Lun', 2 => 'Mar', 3 => 'Mer', 4 => 'Jeu', 5 => 'Ven', 6 => 'Sam', 7 => 'Dim'];

/* Lignes `planning` -> ['actif' => bool, 'creneaux' => [['jours' => [1,2], 'debut' => '12:00', 'fin' => '20:00']]] */
function planning_group($rows)
{
    if (!$rows) {
        return null;
    }
    $creneaux = [];
    $actif = true;
    foreach ($rows as $r) {
        $debut = substr($r['heure_debut'], 0, 5);
        $fin   = substr($r['heure_fin'], 0, 5);
        $key   = $debut . '|' . $fin;
        if (!isset($creneaux[$key])) {
            $creneaux[$key] = ['jours' => [], 'debut' => $debut, 'fin' => $fin];
        }
        $creneaux[$key]['jours'][] = (int) $r['jour'];
        if ($r['planning_actif'] == 0) {
            $actif = false;
        }
    }
    foreach ($creneaux as &$c) {
        sort($c['jours']);
    }
    return ['actif' => $actif, 'creneaux' => array_values($creneaux)];
}

function planning_get($object, $type, $id)
{
    $st = $object->connect->prepare("SELECT jour, heure_debut, heure_fin, planning_actif FROM planning
                                     WHERE type = ? AND id_element = ? ORDER BY heure_debut, jour");
    $st->execute([$type, $id]);
    return planning_group($st->fetchAll(PDO::FETCH_ASSOC));
}

/* Texte court pour la liste : "Lun–Ven 12:00–20:00 · Sam 10:00–14:00" */
function planning_resume($planning)
{
    $parts = [];
    foreach ($planning['creneaux'] as $c) {
        $j = $c['jours'];
        if (count($j) === 7) {
            $jours = 'Tous les jours';
        } elseif (count($j) > 2 && end($j) - $j[0] === count($j) - 1) {
            $jours = PLANNING_JOURS[$j[0]] . '–' . PLANNING_JOURS[end($j)];   // jours consécutifs
        } else {
            $jours = implode(', ', array_map(function ($d) { return PLANNING_JOURS[$d]; }, $j));
        }
        $parts[] = $jours . ' ' . $c['debut'] . '–' . $c['fin'];
    }
    return implode(' · ', $parts);
}

/* Petit bloc affiché sous le bouton Statut ; un seul SELECT par page (cache par market + type) */
function planning_badge_for($object, $market_id, $type, $id)
{
    static $maps = [];
    $key = $market_id . '|' . $type;
    if (!isset($maps[$key])) {
        $st = $object->connect->prepare("SELECT id_element, jour, heure_debut, heure_fin, planning_actif FROM planning
                                         WHERE market = ? AND type = ? ORDER BY heure_debut, jour");
        $st->execute([$market_id, $type]);
        $rows = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $rows[$r['id_element']][] = $r;
        }
        $maps[$key] = array_map('planning_group', $rows);
    }
    if (empty($maps[$key][$id])) {
        return '';
    }
    $p = $maps[$key][$id];
    $html = '<div class="small text-muted mt-1 text-nowrap"><i class="fal fa-clock"></i> ' . htmlspecialchars(planning_resume($p)) . '</div>';
    if (!$p['actif']) {
        $html .= '<span class="badge bg-secondary">Planning en pause</span>';
    }
    return $html;
}

/* Vérifie le formulaire AVANT d'enregistrer quoi que ce soit. Retourne '' si OK. */
function planning_validate($post)
{
    if (($post['planning_mode'] ?? 'toujours') !== 'horaire') {
        return '';
    }
    $ok = 0;
    foreach ($post['planning'] ?? [] as $c) {
        $jours = array_filter((array) ($c['jours'] ?? []), function ($d) { return isset(PLANNING_JOURS[(int) $d]); });
        $debut = $c['debut'] ?? '';
        $fin   = $c['fin'] ?? '';
        if (!$jours) {
            continue;   // créneau sans jour coché = ignoré
        }
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $debut) || !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $fin)) {
            return '<div class="alert alert-danger">Disponibilité : heure invalide.</div>';
        }
        if ($debut === $fin) {
            return '<div class="alert alert-danger">Disponibilité : l\'heure de début et de fin sont identiques.</div>';
        }
        $ok++;
    }
    return $ok ? '' : '<div class="alert alert-danger">Disponibilité : cochez au moins un jour.</div>';
}

/* Remplace le planning de l'élément par celui du formulaire (à appeler après planning_validate). */
function planning_save($object, $market_id, $type, $id, $post)
{
    if (!in_array($type, PLANNING_TYPES, true)) {
        return;
    }
    $pdo = $object->connect;
    $pdo->beginTransaction();

    $st = $pdo->prepare("SELECT COUNT(*) FROM planning WHERE type = ? AND id_element = ?");
    $st->execute([$type, $id]);
    $avait_planning = $st->fetchColumn() > 0;

    $pdo->prepare("DELETE FROM planning WHERE type = ? AND id_element = ?")->execute([$type, $id]);

    if (($post['planning_mode'] ?? 'toujours') !== 'horaire') {
        // retour à « toujours disponible » : on rend l'élément visible (le cron ne le touchera plus)
        if ($avait_planning) {
            $pdo->prepare("UPDATE `$type` SET statut = 'Activer' WHERE id = ?")->execute([$id]);
        }
        $pdo->commit();
        return;
    }

    $actif = empty($post['planning_pause']) ? 1 : 0;
    $ins = $pdo->prepare("INSERT INTO planning (market, type, id_element, jour, heure_debut, heure_fin, planning_actif)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($post['planning'] ?? [] as $c) {
        foreach (array_unique((array) ($c['jours'] ?? [])) as $d) {
            $d = (int) $d;
            if (isset(PLANNING_JOURS[$d])) {
                $ins->execute([$market_id, $type, $id, $d, $c['debut'], $c['fin'], $actif]);
            }
        }
    }
    $pdo->commit();
}

/* Le restaurateur change le statut à la main : on met le planning en pause pour que le cron ne l'écrase pas. */
function planning_pause($object, $type, $id)
{
    $object->connect->prepare("UPDATE planning SET planning_actif = 0 WHERE type = ? AND id_element = ?")
        ->execute([$type, $id]);
}
