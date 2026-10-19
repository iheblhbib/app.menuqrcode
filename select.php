<?php

   include('../config.menuqrcode/config.php');

    $object = new application();

    if(!$object->is_login())
    {
        header("location:".$object->client_url."login");
        exit();
    }


    if(!$object->is_user_actif($_SESSION['id']))
    {
        header("location:".$object->client_url."logout");
        exit();
    }

    $page_actuel = $object->get_page_url();

    $title = $object->get_page_libelle($page_actuel);

    if($_SERVER["HTTPS"] != "on")
    {
        header("Location: ".$object->client_url.$page_actuel);

        exit();
    }

     unset($_SESSION['market']);

    // information d'utilisateur
    $user = json_decode($object->get_user($_SESSION['id']));

    if($user->markets == '1'){
        $market = json_decode($object->get_market_user($_SESSION['id']));
        $_SESSION['market'] = $market->id ;
        header("location:".$object->client_url."");
        exit();
    }

    // liste des points de vente à afficher
    $markets = array();
    $show_form = false;

    if(!$object->is_employee_user()){

        $object->query = "SELECT * FROM market WHERE gestionnaire = ".$_SESSION['id']." ";

        $result = $object->get_result();

        foreach($result as $row){
            $markets[] = array(
                'id'     => $row['id'],
                'name'   => $row['market'],
                'logo'   => str_replace('../','https://',$row['logo']),
                'active' => ($row['statut'] == 'Activer'),
            );
        }

        $show_form = (count($markets) == 0);

    }else{
        $object->query="SELECT
                            market
                        FROM
                            user_market
                        WHERE
                            USER = ".$_SESSION['id']." AND etat = '2' AND market IN(
                            SELECT
                                id
                            FROM
                                market
                            WHERE
                                statut = 'Activer'
                        )   ";

        $result = $object->get_result();

        foreach($result as $row){
            $market = json_decode($object->get_market($row['market']));
            $markets[] = array(
                'id'     => $market->id,
                'name'   => $market->market,
                'logo'   => str_replace('../','https://',$market->logo),
                'active' => true,
            );
        }
    }

    $nb_actifs = count(array_filter($markets, function($m){ return $m['active']; }));
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title> Menu QR Code</title>

    <!-- Favicons -->
    <link href="img/logo-menu-qr-code.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="vendor/parsley/parsley.css" rel="stylesheet"/>

    <link href='vendor/fullcalendar/packages/core/main.css' rel='stylesheet' />
    <link href='vendor/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />
    <link href='vendor/fullcalendar/packages/timegrid/main.css' rel='stylesheet' />
    <link href='vendor/fullcalendar/packages/list/main.css' rel='stylesheet' />

        <!--datatable bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.1/css/fixedHeader.bootstrap5.min.css">
    <link rel="stylesheet" href="vendor/datatables/rowgroup/1.2.0/rowGroup.dataTables.css">
    <link rel="stylesheet" href="vendor/datatables/select/1.6.0/css/select.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <!-- Fontawesome -->
     <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/new-style.css" rel="stylesheet">

    <!-- Latest BS-Select compiled and minified CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .page<?=  $page_actuel ?> {
            color: #ffffff !important;
            background: #c39a5a !important;
            border-radius: 5px !important;
        }
    </style>
</head>

<body>

 <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="/" class="logo text-center">
        <img src="img/logo-menu-qr-code.png">
      </a>

      <i class="bi bi-arrows-fullscreen fullscreen_button" style="font-size: 27px; padding-left: 10px; cursor: pointer; color: #111111;"></i>
    </div>


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="<?= $user->user_profile ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $user->name; ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= $user->name; ?></h6>
              <span><?= $user->type ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" id="go-profil" data-id="<?= $user->id ?>" href="#">
                <i class="fal fa-user"></i>
                <span>Profil</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" id="go-password" data-id="<?= $user->id ?>" href="#">
                <i class="fal fa-key-skeleton"></i>
                <span>Mot de passe</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" id="buttonlogoutModal" data-toggle="modal" data-target="#logoutModal">
              <i class="fal fa-sign-out"></i>
                <span>Se déconnecter</span>
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </nav>

  </header>


<style>
    :root{
        /* palette du logo Menu QR Code : noir + or */
        --ms-ink: #111111;
        --ms-gold: #c39a5a;
        --ms-gold-dark: #9a7438;
        --ms-gold-light: #f3e6cf;
        --ms-gold-grad: linear-gradient(135deg, #b88a48 0%, #d9b67c 45%, #f3e6cf 100%);
        --ms-gold-soft: #f8f2e8;
        --ms-line: #ece4d6;
        --ms-muted: #7a7368;
        --ms-bg: #faf8f4;
        --ms-red: #d64545;
        --ms-green: #1f9d6b;
    }

    body{ background: var(--ms-bg); }
    .header .nav-profile span,
    .header .dropdown-header h6{ color: var(--ms-ink) !important; }
    .header{ border-bottom: 1px solid var(--ms-line); }

    .ms-main{
        margin-top: 60px;
        padding: 24px 28px 40px;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ---------- Bandeau d'accueil ---------- */
    .ms-hero{
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        padding: 30px 32px;
        color: #fff;
        background:
            radial-gradient(circle at 95% -10%, rgba(217,182,124,.35) 0, rgba(217,182,124,0) 45%),
            linear-gradient(120deg, #77622c 0%, #1e1b16 100%);
        box-shadow: 0 14px 34px -18px rgba(20,16,10,.55);
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 22px;
    }
    .ms-hero::before{
        /* filet or en haut, comme les coins du logo */
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: var(--ms-gold-grad);
    }
    .ms-hero::after{
        /* motif QR discret */
        content: "";
        position: absolute;
        right: -30px; bottom: -40px;
        width: 220px; height: 220px;
        background-image:
            linear-gradient(rgba(217,182,124,.09) 50%, transparent 50%),
            linear-gradient(90deg, rgba(217,182,124,.09) 50%, transparent 50%);
        background-size: 22px 22px;
        transform: rotate(12deg);
        border-radius: 24px;
        pointer-events: none;
    }
    .ms-hero__eyebrow{
        font-family: "Poppins", sans-serif;
        font-size: 12px;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #d9b67c;
        margin-bottom: 6px;
    }
    .ms-hero h1{
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        font-size: 28px;
        margin: 0 0 6px;
        color: #fff;
    }
    .ms-hero p{
        margin: 0;
        opacity: .85;
        font-size: 15px;
    }
    .ms-hero__stats{
        display: flex;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }
    .ms-chip{
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(217,182,124,.12);
        border: 1px solid rgba(217,182,124,.35);
    }
    .ms-chip i, .ms-chip b{ color: #e6c993; }
    .ms-chip b{ font-weight: 700; }

    .ms-search{
        position: relative;
        z-index: 1;
        width: 320px;
        max-width: 100%;
    }
    .ms-search i{
        position: absolute;
        left: 15px; top: 50%;
        transform: translateY(-50%);
        color: var(--ms-muted);
        font-size: 15px;
    }
    .ms-search input{
        width: 100%;
        height: 46px;
        border: 0;
        border-radius: 12px;
        padding: 0 16px 0 42px;
        font-size: 15px;
        color: var(--ms-ink);
        background: #fff;
        box-shadow: 0 6px 18px -8px rgba(0,0,0,.35);
        outline: none;
    }
    .ms-search input:focus{ box-shadow: 0 0 0 3px rgba(217,182,124,.6); }

    /* ---------- Grille ---------- */
    .ms-section-title{
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 28px 2px 14px;
    }
    .ms-section-title h2{
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: var(--ms-ink);
        margin: 0;
    }
    .ms-section-title span{ color: var(--ms-muted); font-size: 13px; }

    .ms-grid{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    .ms-card{
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--ms-line);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        animation: ms-in .45s ease both;
    }
    .ms-card:hover,
    .ms-card:focus-visible{
        transform: translateY(-4px);
        border-color: var(--ms-gold);
        box-shadow: 0 18px 36px -20px rgba(195,154,90,.55);
        color: inherit;
        outline: none;
    }

    .ms-card__logo{
        aspect-ratio: 16 / 10;
        display: grid;
        place-items: center;
        background:
            radial-gradient(circle at 50% 120%, #efe3cf 0, transparent 70%),
            var(--ms-gold-soft);
        border-bottom: 1px solid var(--ms-line);
        padding: 18px;
    }
    .ms-card__logo img{
        max-width: 70%;
        max-height: 100%;
        object-fit: contain;
        transition: transform .3s ease;
    }
    .ms-card:hover .ms-card__logo img{ transform: scale(1.06); }

    .ms-card__body{
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
    }
    .ms-card__name{
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 15px;
        color: var(--ms-ink);
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .ms-card__status{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--ms-green);
        margin-top: 3px;
        font-family: "Nunito", sans-serif;
    }
    .ms-card__status::before{
        content: "";
        width: 7px; height: 7px;
        border-radius: 50%;
        background: currentColor;
    }
    .ms-card__go{
        flex: none;
        width: 36px; height: 36px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: var(--ms-gold-soft);
        color: var(--ms-gold-dark);
        transition: background .25s ease, color .25s ease, transform .25s ease;
    }
    .ms-card:hover .ms-card__go{
        background: var(--ms-ink);
        color: #e6c993;
        transform: translateX(3px);
    }

    .ms-card__recent{
        position: absolute;
        top: 10px; left: 10px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 999px;
        background: var(--ms-ink);
        color: #e6c993;
        display: none;
    }
    .ms-card.is-recent .ms-card__recent{ display: inline-block; }
    .ms-card.is-recent{ border-color: var(--ms-gold); }

    /* point de vente désactivé */
    .ms-card.is-off .ms-card__logo img{ filter: grayscale(1); opacity: .55; }
    .ms-card.is-off .ms-card__name{ color: var(--ms-muted); }
    .ms-card.is-off .ms-card__status{ color: var(--ms-red); }
    .ms-card.is-off .ms-card__go{ background: #f2efea; color: var(--ms-muted); }

    .ms-empty{
        display: none;
        text-align: center;
        padding: 50px 20px;
        color: var(--ms-muted);
    }
    .ms-empty i{ font-size: 34px; margin-bottom: 10px; color: #d8c7a8; }

    @keyframes ms-in{
        from{ opacity: 0; transform: translateY(10px); }
        to{ opacity: 1; transform: none; }
    }

    /* ---------- Création du premier point de vente ---------- */
    .ms-create{
        max-width: 640px;
        margin: 28px auto 0;
        background: #fff;
        border: 1px solid var(--ms-line);
        border-radius: 18px;
        box-shadow: 0 18px 40px -28px rgba(20,16,10,.45);
        overflow: hidden;
    }
    .ms-create__head{
        display: flex;
        gap: 14px;
        align-items: center;
        padding: 22px 26px;
        border-bottom: 1px solid var(--ms-line);
        background: linear-gradient(180deg, #fdfbf7, #fff);
    }
    .ms-create__icon{
        width: 46px; height: 46px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: var(--ms-ink);
        color: #e6c993;
        font-size: 20px;
    }
    .ms-create__head h5{
        margin: 0;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        color: var(--ms-ink);
        font-size: 17px;
    }
    .ms-create__head p{ margin: 2px 0 0; color: var(--ms-muted); font-size: 13px; }
    .ms-create form{ padding: 22px 26px 26px; }
    .ms-create .ms-group-label{
        font-size: 11px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--ms-muted);
        font-weight: 700;
        margin: 6px 0 -4px;
    }
    .ms-create .form-label{ font-size: 13px; color: #4a4339; font-weight: 600; margin-bottom: 4px; }
    .ms-create .form-control{ border-radius: 10px; border-color: #e6dccb; min-height: 38px; }
    .ms-create .form-control:focus{ border-color: var(--ms-gold); box-shadow: 0 0 0 3px rgba(195,154,90,.15); }
    .ms-create .btn-primary{
        height: 46px;
        border-radius: 12px;
        background: var(--ms-ink);
        border-color: var(--ms-ink);
        color: #e6c993;
        font-weight: 600;
        letter-spacing: .02em;
    }
    .ms-create .btn-primary:hover,
    .ms-create .btn-primary:focus{ background: var(--ms-gold-grad); border-color: var(--ms-gold); color: var(--ms-ink); }

    @media (max-width: 576px){
        .ms-main{ padding: 16px 14px 32px; }
        .ms-hero{ padding: 22px 20px; border-radius: 14px; }
        .ms-hero h1{ font-size: 22px; }
        .ms-search{ width: 100%; }
        .ms-grid{ grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .ms-card__body{ padding: 10px 12px; gap: 8px; }
        .ms-card__name{ font-size: 13px; }
        .ms-card__go{ width: 30px; height: 30px; font-size: 12px; }
        .ms-create__head, .ms-create form{ padding-left: 18px; padding-right: 18px; }
    }

    @media (prefers-reduced-motion: reduce){
        .ms-card, .ms-card *{ animation: none !important; transition: none !important; }
    }
</style>

<main class="ms-main">

    <section class="ms-hero">
        <div style="position:relative; z-index:1;">
            <div class="ms-hero__eyebrow">Espace gestionnaire</div>
            <h1>Bonjour, <?= htmlspecialchars($user->name) ?></h1>
            <p><?= $show_form ? 'Créez votre premier point de vente pour commencer.' : 'Choisissez le point de vente que vous souhaitez gérer.' ?></p>

            <?php if(!$show_form){ ?>
            <div class="ms-hero__stats">
                <span class="ms-chip"><i class="fal fa-store"></i> <b><?= count($markets) ?></b> point<?= count($markets) > 1 ? 's' : '' ?> de vente</span>
                <span class="ms-chip"><i class="fal fa-check-circle"></i> <b><?= $nb_actifs ?></b> actif<?= $nb_actifs > 1 ? 's' : '' ?></span>
            </div>
            <?php } ?>
        </div>

        <?php if(count($markets) > 4){ ?>
        <div class="ms-search">
            <i class="fal fa-search"></i>
            <input type="search" id="ms-search" placeholder="Rechercher un point de vente…" autocomplete="off">
        </div>
        <?php } ?>
    </section>

    <?php if(!$show_form){ ?>

        <div class="ms-section-title">
            <h2>Points de vente</h2>
            <span id="ms-count"><?= count($markets) ?> résultat<?= count($markets) > 1 ? 's' : '' ?></span>
        </div>

        <div class="ms-grid" id="ms-grid">
            <?php foreach($markets as $i => $m){ ?>
                <a href="#" class="market ms-card <?= $m['active'] ? '' : 'is-off' ?>"
                   data-id="<?= $m['id'] ?>"
                   data-name="<?= htmlspecialchars(mb_strtolower($m['name'], 'UTF-8')) ?>"
                   title="<?= htmlspecialchars($m['name']) ?>"
                   style="animation-delay: <?= min($i, 12) * 40 ?>ms">
                    <span class="ms-card__recent">Récent</span>
                    <div class="ms-card__logo">
                        <img src="<?= $m['logo'] ?>" alt="<?= htmlspecialchars($m['name']) ?>" loading="lazy">
                    </div>
                    <div class="ms-card__body">
                        <div style="flex:1; min-width:0;">
                            <div class="ms-card__name"><?= htmlspecialchars($m['name']) ?></div>
                            <div class="ms-card__status"><?= $m['active'] ? 'Actif' : 'Désactivé' ?></div>
                        </div>
                        <span class="ms-card__go"><i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            <?php } ?>
        </div>

        <div class="ms-empty" id="ms-empty">
            <i class="fal fa-store-slash d-block"></i>
            Aucun point de vente ne correspond à votre recherche.
        </div>

    <?php } else { ?>

        <div class="ms-create">
            <div class="ms-create__head">
                <div class="ms-create__icon"><i class="fal fa-store"></i></div>
                <div>
                    <h5>Finalisez la création de votre point de vente</h5>
                    <p>Ces informations pourront être modifiées plus tard.</p>
                </div>
            </div>

            <form class="row g-3 needs-validation" method="post" id="market_form" enctype="multipart/form-data">

                <div class="col-12 ms-group-label">Informations</div>

                <div class="col-md-12">
                    <label class="form-label" for="name">Nom du point de vente <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" required data-parsley-trigger="keyup">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="tel">Tél</label>
                    <input type="text" name="tel" id="tel" class="form-control form-control-sm" data-parsley-trigger="keyup" data-parsley-minlength="8" data-parsley-maxlength="13">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm" data-parsley-trigger="keyup">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="address">Adresse</label>
                    <textarea name="address" id="address" class="form-control form-control-sm" rows="2" data-parsley-trigger="keyup"></textarea>
                </div>

                <div class="col-md-12">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" name="logo" id="logo" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.webp" data-parsley-trigger="keyup">
                    <span id="categorie_uploaded_image"></span>
                </div>

                <div class="col-12 ms-group-label">Menu &amp; modules</div>

                <div class="col-md-12">
                    <label for="niveau" class="form-label">Niveau de menu</label>
                    <select class="form-control form-control-sm" name="niveau" id="niveau">
                        <option value="2">2 Niveau ( Catégories / Articles )</option>
                        <option value="3">3 Niveau ( Catégories / Sous Catégories / Articles )</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="multi_langue" class="form-label">Multi Langue</label>
                    <select class="form-control form-control-sm" name="multi_langue" id="multi_langue">
                        <option value="1">Activer</option>
                        <option disabled>Désactiver</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="event" class="form-label">Événement</label>
                    <select class="form-control form-control-sm" name="event" id="event">
                        <option value="2">Activer</option>
                        <option disabled>Désactiver</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="feedback" class="form-label">Feedback</label>
                    <select class="form-control form-control-sm" name="feedback" id="feedback">
                        <option value="2">Activer</option>
                        <option disabled>Désactiver</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="design_qrcode" class="form-label">Design QR Code</label>
                    <select class="form-control form-control-sm" name="design_qrcode" id="design_qrcode">
                        <option value="2">Activer</option>
                        <option disabled>Désactiver</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="wifi" class="form-label">Wi-Fi</label>
                    <select class="form-control form-control-sm" name="wifi" id="wifi">
                        <option value="2">Activer</option>
                        <option disabled>Désactiver</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" name="market_button" id="market_button" class="btn btn-primary w-100">Créer votre point de vente</button>
                </div>
            </form>
        </div>

    <?php } ?>

</main>

<?php include 'footer.php'; ?>

<script>
 $(document).ready(function(){

    var RECENT_KEY = 'mqr_last_market';

    // badge "Récent" sur le dernier point de vente ouvert
    try {
        var last = localStorage.getItem(RECENT_KEY);
        if (last) $('.ms-card[data-id="' + last + '"]').addClass('is-recent');
    } catch (e) {}

    $(document).on('click', '.market', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        try { localStorage.setItem(RECENT_KEY, id); } catch (err) {}
        $.redirect("/", {
            market: id
        }, "POST");
    });

    // recherche instantanée
    $('#ms-search').on('input', function(){
        var q = $(this).val().toLowerCase().trim();
        var visible = 0;
        $('.ms-card').each(function(){
            var match = $(this).data('name').toString().indexOf(q) !== -1;
            $(this).toggle(match);
            if (match) visible++;
        });
        $('#ms-count').text(visible + ' résultat' + (visible > 1 ? 's' : ''));
        $('#ms-empty').toggle(visible === 0);
    });

	$('#market_form').parsley();

	$('#market_form').on('submit', function(event){
		event.preventDefault();
		if($('#market_form').parsley().isValid())
		{
			$.ajax({
				url:"select-action",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
                beforeSend:function()
                {
                    $('#market_button').attr('disabled', 'disabled').text('Création en cours…');
                },
				success:function(data)
				{
                    $('#market_button').attr('disabled', false);
                    if(data.error != '')
                    {
                        $('#market_button').text('Créer votre point de vente');

                        notificationSwal.fire({
                          icon: 'error',
                          title: data.error
                        });
                    }
                    else
                    {
                        window.location.reload();
                    }
				}
			})
		}
	});

});
</script>
