<?php
include('header.php');

$niveau = (int) $market->niveau;

// Langues du market (hors français, qui est la langue de base)
$langues = [];
if ($market->multi_langue == '1') {
    $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
    foreach ($object->get_result() as $row) {
        $langues[] = ['code' => $row['code'], 'libelle' => $row['libelle']];
    }
}

// Listes déroulantes fournies par la classe application
$options_icones     = $object->get_icones();
$options_vip        = $market->vip == '2' ? $object->get_vip_code_market($market->id) : '';
$options_allergenes = str_replace('<option value="" selected disabled>Choisissez</option>', '', $object->get_allergens());

$v = function ($f) { return $f . '?v=' . @filemtime($f); };
?>
<link rel="stylesheet" href="<?= $v('assets/css/menu-editeur.css') ?>">

<main id="main" class="main me-page">

    <div class="pagetitle d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1>Mon menu</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
                    <li class="breadcrumb-item"><a href="/"><?= htmlspecialchars($market->market) ?></a></li>
                    <li class="breadcrumb-item active">Menu</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="me-shell">

        <!-- ========== Arbre ========== -->
        <aside class="me-tree" id="meTree" aria-label="Structure du menu">
            <div class="me-tree-head">
                <div class="me-search">
                    <i class="fa-light fa-magnifying-glass"></i>
                    <input type="search" id="meSearch" placeholder="Rechercher une catégorie ou un article" autocomplete="off">
                </div>
                <button type="button" class="btn-close d-xl-none" id="meTreeClose" aria-label="Fermer"></button>
            </div>
            <div class="me-tree-body" id="meTreeBody">
                <div class="me-skeleton"></div><div class="me-skeleton"></div><div class="me-skeleton"></div>
            </div>
        </aside>
        <div class="me-tree-backdrop" id="meTreeBackdrop"></div>

        <!-- ========== Contenu ========== -->
        <section class="me-content">
            <div class="me-toolbar">
                <button type="button" class="btn btn-light btn-sm d-xl-none" id="meTreeOpen">
                    <i class="fa-light fa-sitemap"></i> Structure
                </button>
                <nav class="me-crumbs" id="meCrumbs" aria-label="Emplacement"></nav>
            </div>

            <div class="me-head">
                <div>
                    <h2 class="me-title" id="meTitle">&nbsp;</h2>
                    <div class="me-subtitle" id="meSubtitle"></div>
                </div>
                <div class="me-head-actions">
                    <button type="button" class="btn btn-light btn-sm" id="meEditParent" hidden>
                        <i class="fa-light fa-pen"></i> Modifier
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" id="meAdd" hidden>
                        <i class="fa-light fa-plus"></i> <span></span>
                    </button>
                </div>
            </div>

            <div class="me-filters" id="meFilters">
                <button type="button" class="me-chip active" data-etat="0">Actifs <span>0</span></button>
                <button type="button" class="me-chip" data-etat="1"><i class="fa-light fa-box-archive"></i> Archivés <span>0</span></button>
                <span class="me-hint" id="meHint"><i class="fa-light fa-grip-dots-vertical"></i> Glissez pour réordonner</span>
            </div>

            <div class="me-list" id="meList"></div>
        </section>
    </div>
</main>

<!-- ========== Panneau d'édition ========== -->
<div class="offcanvas offcanvas-end me-editor" tabindex="-1" id="meEditor" aria-labelledby="meEditorTitle">
    <form id="meForm" enctype="multipart/form-data" novalidate class="d-flex flex-column h-100">
        <div class="offcanvas-header">
            <div>
                <div class="me-editor-kicker" id="meEditorKicker"></div>
                <h5 class="offcanvas-title" id="meEditorTitle">Modifier</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
        </div>

        <div class="offcanvas-body">
            <div id="meFormError"></div>

            <!-- Textes par langue -->
            <section class="me-card">
                <?php if ($langues) { ?>
                    <ul class="nav me-lang-tabs" role="tablist">
                        <li><button type="button" class="me-lang active" data-lang="fr">FR <i class="me-dot"></i></button></li>
                        <?php foreach ($langues as $l) { ?>
                            <li><button type="button" class="me-lang" data-lang="<?= $l['code'] ?>"><?= strtoupper($l['code']) ?> <i class="me-dot"></i></button></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <div class="me-lang-pane active" data-lang="fr">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0" for="libelle">Nom <span class="text-danger">*</span></label>
                        <?php if ($langues) { ?>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="meTranslateAll">
                                <i class="fa-light fa-language"></i> Traduire dans toutes les langues
                            </button>
                        <?php } ?>
                    </div>
                    <input type="text" class="form-control" name="libelle" id="libelle" maxlength="150" data-fr="libelle">
                    <div class="me-only" data-levels="1">
                        <label class="form-label mt-3" for="prix">Prix <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="prix" id="prix" maxlength="150" data-fr="prix" placeholder="18">
                            <span class="input-group-text">DT</span>
                        </div>
                        <label class="form-label mt-3">Description</label>
                        <div class="me-quill" id="meQuill_fr"></div>
                        <textarea name="description" id="description" hidden></textarea>
                    </div>
                </div>

                <?php foreach ($langues as $l) { $c = $l['code']; ?>
                    <div class="me-lang-pane" data-lang="<?= $c ?>">
                        <div class="text-muted small mb-2"><?= htmlspecialchars($l['libelle']) ?></div>
                        <label class="form-label" for="libelle<?= $c ?>">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="libelle<?= $c ?>" id="libelle<?= $c ?>" maxlength="150" data-tr="libelle">
                        <div class="me-only" data-levels="1">
                            <label class="form-label mt-3" for="prix<?= $c ?>">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="prix<?= $c ?>" id="prix<?= $c ?>" maxlength="150" data-tr="prix">
                                <span class="input-group-text">DT</span>
                            </div>
                            <label class="form-label mt-3">Description</label>
                            <div class="me-quill" id="meQuill_<?= $c ?>"></div>
                            <textarea name="description<?= $c ?>" id="description<?= $c ?>" hidden></textarea>
                        </div>
                    </div>
                <?php } ?>
            </section>

            <!-- Champs communs -->
            <section class="me-card" id="meGeneral">
                <h6 class="me-card-title">Général</h6>
                <div class="row g-3">
                    <div class="col-12 me-only" data-parent="1">
                        <label class="form-label" for="meParent" id="meParentLabel">Emplacement</label>
                        <select class="form-select" name="categorie" id="meParent"></select>
                    </div>

                    <div class="col-sm-6 me-only me-keep" data-levels="2,3">
                        <label class="form-label" for="icon">Icône</label>
                        <select class="form-select" name="icon" id="icon"><?= $options_icones ?></select>
                    </div>

                    <div class="col-sm-6 me-only" data-levels="1">
                        <label class="form-label" for="type">Affichage</label>
                        <select class="form-select" name="type" id="type">
                            <option value="product">Article (nom + prix)</option>
                            <option value="text">Texte seul</option>
                        </select>
                    </div>

                    <div class="col-12 me-only" data-levels="1">
                        <label class="form-label" for="allergens">Allergènes</label>
                        <select class="selectpicker" name="allergens[]" id="allergens" multiple data-live-search="true" data-size="6" title="Aucun" data-width="100%"><?= $options_allergenes ?></select>
                    </div>

                    <div class="col-sm-5 me-only me-keep" data-levels="2">
                        <label class="form-label" for="etat_prix">Prix / note</label>
                        <select class="form-select" name="etat_prix" id="etat_prix">
                            <option value="1">Masqué</option>
                            <option value="2">Affiché</option>
                        </select>
                    </div>
                    <div class="col-sm-7 me-only me-keep" data-levels="2" id="meNoteWrap">
                        <label class="form-label" for="meNote">Texte affiché</label>
                        <input type="text" class="form-control" name="prix" id="meNote" placeholder="à partir de 15 DT">
                    </div>

                    <?php if ($market->vip == '2') { ?>
                        <div class="col-sm-5 me-only me-keep" data-vip="1">
                            <label class="form-label" for="vip">Accès VIP</label>
                            <select class="form-select" name="vip" id="vip">
                                <option value="1">Non</option>
                                <option value="2">Oui</option>
                            </select>
                        </div>
                        <div class="col-sm-7 me-only me-keep" data-vip="1" id="meCodeVipWrap">
                            <label class="form-label" for="code_vip">Code VIP</label>
                            <select class="form-select" name="code_vip" id="code_vip"><?= $options_vip ?></select>
                        </div>
                    <?php } ?>
                </div>
            </section>

            <!-- Photo -->
            <section class="me-card">
                <h6 class="me-card-title">Photo</h6>
                <label class="me-drop" id="meDrop">
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp" hidden>
                    <img id="mePreview" alt="" hidden>
                    <span class="me-drop-empty"><i class="fa-light fa-image"></i> Glissez une photo ici ou <u>parcourez</u><br><small>JPG, PNG ou WebP</small></span>
                </label>
                <input type="hidden" name="hidden_categorie_image" id="hidden_categorie_image">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="meDisplayImage" checked>
                    <label class="form-check-label" for="meDisplayImage">Afficher la photo sur le menu</label>
                </div>
                <input type="hidden" name="display_image" id="display_image" value="1">
            </section>

            <!-- Disponibilité (planning) -->
            <section class="me-card me-planning">
                <div class="row"><?php include('planning-form.php'); ?></div>
            </section>
        </div>

        <div class="me-editor-foot">
            <input type="hidden" name="market" value="<?= $market->id ?>">
            <input type="hidden" name="hidden_id" id="hidden_id">
            <input type="hidden" name="action" id="meAction" value="Add">
            <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Annuler</button>
            <button type="submit" class="btn btn-primary" id="meSave"><i class="fa-light fa-check"></i> Enregistrer</button>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>

<script>
    window.ME_CONFIG = <?= json_encode([
        'market'  => (int) $market->id,
        'niveau'  => $niveau,
        'vip'     => $market->vip == '2',
        'langues' => $langues,
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) ?>;
</script>
<script src="<?= $v('assets/js/menu-editeur.js') ?>"></script>
