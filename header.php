<?php 

include('../config.menuqrcode/config.php'); 
    
    $object = new application(); 
     
    if(!$object->is_login())
    {
        header("location:".$object->client_url."login");
    }
    
     
    if(!$object->is_user_actif($_SESSION['id']))
    {
        header("location:".$object->client_url."logout");
    }
    
    $page_actuel = $object->get_page_url();

    $title = $object->get_page_libelle($page_actuel);
    
    if($_SERVER["HTTPS"] != "on")
    {
        header("Location: ".$object->client_url.$page_actuel);
        
        exit();
    }  
     
    
    // information d'utilisateur
    $user = json_decode($object->get_user($_SESSION['id'])); 
    
    if($_POST['market']){
        $_SESSION['market'] = $_POST['market'] ;
    } 
      
    if(empty($_SESSION['market']))
    {
        header("location:".$object->client_url."select");
    }
    
    $market = json_decode($object->get_market($_SESSION['market'])); 
    
    if($object->route($user->id, $page_actuel) == '1' )
    {
        header("Location: ".$object->client_url);
    }

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <title>Menu QR Code <?= $title ?> </title>
    
  <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png"> 
    <link rel="manifest" href="assets/img/site.webmanifest">
      <meta content="Boostez votre restaurant ou café en Tunisie avec des menus digitalisés par QR code ! Simplifiez les commandes et impressionnez vos clients grâce à notre solution innovante. Découvrez la révolution numérique des menus dès maintenant !" name="description">
        <meta content="digital menu, dynamic QR code, restaurant, online ordering, food, drinks, meals, menu, scanning, technology,menu numérique, QR code dynamique, restaurant, commande en ligne, alimentation, nourriture, boissons, repas, carte, numérisation, technologie" name="keywords">
        
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
    <!--<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>-->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
 
    
    <?php
    $css_file = 'assets/css/new-style.css';
    $css_timestamp = filemtime($css_file);
    ?>
    <link rel="stylesheet" href="<?= $css_file . '?v=' . $css_timestamp; ?>">
  
    <!-- Latest BS-Select compiled and minified CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

            <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-FD9CWFEKGK"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date()); 
            gtag('config', 'G-FD9CWFEKGK');
        </script> 
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4637955071361573" crossorigin="anonymous"></script>
        
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-211033766-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());  
            gtag('config', 'UA-211033766-1');
        </script>

  <!-- =======================================================
        Aditionnal CSS
  ======================================================== -->
    <style> 
        .page<?=  $page_actuel ?> {
            color: #ffffff !important;
            background: #2c5199 !important; 
            border-radius: 5px !important;
        }
        
        .dt-button {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff !important;
        }
        
        .dashboard .day-card .card-icon {
            color: #0dcaf0;
            background: #0dcaf02b;
        }
        
        .dashboard .month-card .card-icon {
            color: #0d6efd;
            background: #0d6efd2b;
        }
        
        .dashboard .total-card .card-icon {
            color: #4154f1;
            background: #4154f12b;
        }
        
        .ql-editor , .ql-container {
            height: max-content !important;
        }
        .credits a{ 
            font-size: 18px;
            display: inline-block;
            background: #2c5199;
            color: #ffffff;
            line-height: 1;
            padding: 8px 0;
            margin-right: 4px;
            border-radius: 50%;
            text-align: center;
            width: 36px;
            height: 36px;
            transition: 0.3s;
        }
        .credits a:hover{  
            background: #ffffff;
            color: #2c5199; 
        }
        .nav-link{
            cursor: pointer; 
        }
         
        .btn-custom{
            color: #fff;
            background-color: #2c5199;
            border-color: #2c5199; 
        }
        
        .btn-custom:hover{
            color: #2c5199;
            background-color: #f1f4fa;
            border-color: #f5f8fe; 
        }
        
        .cart-konnect {
            position: fixed;
            right: 15px;
            bottom: 115px;
            z-index: 996;
            width: 55px;
            height: 55px;
            border-radius: 50px;
            transition: all 0.4s;
            border: 1px solid #2c5199;
            background-color: #2c5199;
            font-size: 28px;
            color: #ffffff;
            line-height: 0;
            cursor: pointer;
        }
        
            
    .card_counted{ 
        position: fixed;
        right: 50px;
        bottom: 155px;
        font-weight: normal;
        width: 21px;
        height: 21px;
        text-align: center;
        z-index: 999;
        font-size: 11px;
        border-radius: 50%;
        padding: 3px;
        color: #000;
        background-color: #f6f9ff!important;
        border: 1px solid #2c5199;
    }
    
    .table {
        white-space: initial;
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
      <i class="bi bi-list toggle-sidebar-btn"></i>
      <i class="bi bi-arrows-fullscreen fullscreen_button" style="font-size: 27px; padding-left: 10px; cursor: pointer; color: #012970;"></i>
     
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
              <span>Client</span>
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
                <i class="fa-light fa-key-skeleton"></i>
                <span>Mot de passe</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
 
            <li>
              <a class="dropdown-item d-flex align-items-center" <?= ($object->route($user->id, 'settings') == '1') ? ' ' : 'href="settings"' ?>>
                <i class="fa-light fa-store"></i>
                <span>Point de vente</span>
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

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->


  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        
        <li class="nav-heading">Statistiques</li>

        <li class="nav-item">
            <a class="nav-link collapsed pageindex" href="/">
                <img src="img/dashboard.png" class="image-header" alt="profile-image"> 
                <span>Dashboard</span> 
            </a>
        </li>


        
        <?php   if($market->varie == 'single'){  ?>
        
            <li class="nav-heading">Menu </li>
            <li class="nav-item">
                <a class="nav-link collapsed pagemenu" href="menu">
                    <img src="img/menu.png" class="image-header" alt="profile-image"> 
                    <span>Mon menu <span class="badge bg-success ms-1" style="font-size:10px;">Nouveau</span></span> 
                </a>
            </li>
            <?php   if($market->niveau == '4'){  ?>
                   <li class="nav-item">
                    <a class="nav-link collapsed pageniveau-1 pageniveau-2  pageniveau-3 pageniveau-4"  <?= ($object->route($user->id, 'niveau-4') == '1') ? 'style="background: #dee2e6;"' : 'href="niveau-4"' ?>>
                        <img src="img/menu.png" class="image-header" alt="profile-image"> 
                        <span>Liste des points de vente</span> 
                    </a>
                  </li>
            <?php } if($market->niveau == '3'){  ?>
                   <li class="nav-item">
                    <a class="nav-link collapsed  pageniveau-1 pageniveau-2  pageniveau-3 "  <?= ($object->route($user->id, 'niveau-3') == '1') ? 'style="background: #dee2e6;"' : 'href="niveau-3"' ?>>
                        <img src="img/menu.png" class="image-header" alt="profile-image"> 
                        <span>Liste des catégories</span> 
                    </a>
                  </li>
            <?php } if($market->niveau == '2'){  ?>
                   <li class="nav-item">
                    <a class="nav-link collapsed pageniveau-2 pageniveau-1"  <?= ($object->route($user->id, 'niveau-2') == '1') ? 'style="background: #dee2e6;"' : 'href="niveau-2"' ?>>
                        <img src="img/menu.png" class="image-header" alt="profile-image"> 
                        <span>Liste des catégories</span> 
                    </a>
                  </li>
            <?php } if($market->niveau == '1'){  ?>
                   <li class="nav-item">
                    <a class="nav-link collapsedpageniveau-1"  <?= ($object->route($user->id, 'niveau-1') == '1') ? 'style="background: #dee2e6;"' : 'href="niveau-1"' ?>>
                        <img src="img/menu.png" class="image-header" alt="profile-image"> 
                        <span>Liste des articles</span> 
                    </a>
                  </li>
            <?php }  ?>
            <?php }else{  ?>
            
                 <li class="nav-item">
                    <a class="nav-link collapsed pageniveau-5"  <?= ($object->route($user->id, 'niveau-5') == '1') ? 'style="background: #dee2e6;"' : 'href="niveau-5"' ?>>
                        <img src="img/menu.png" class="image-header" alt="profile-image"> 
                        <span>Liste des point de vente</span> 
                    </a>
                  </li>
                  
            <?php }  ?>
        
            <?php if($market->pro == '0'){  ?>
            
             <li class="nav-heading">Apèrçu </li>
             
            <li class="nav-item">
                <a class="nav-link collapsed menu-link"  data-link="https://menuqrcode.tn/qrcode?data=<?= $object->encrypt($market->id) ?>"  href="#" >
                    <img src="img/menu-qr-code.png" class="image-header" alt="profile-image"> 
                    <span>Afficher le menu</span> 
                </a>
            </li> 


            
            <?php } ?> 
            

            
            <?php if($market->pro == '1'){  ?>
            
            <li class="nav-item">
                <a class="nav-link collapsed menu-link"  data-link="https://menuqrcode.tn/qrcode?data=<?= $object->encrypt($market->id) ?>"  href="#" >
                    <img src="img/menu-qr-code.png" class="image-header" alt="profile-image"> 
                    <span>Prévisualiser le menu</span> 
                </a>
            </li> 
            
             <li class="nav-heading">QR Code </li>
                <li class="nav-item">
                    <a class="nav-link collapsed pageqr-code"  <?= ($object->route($user->id, 'qr-codes') == '1') ? 'style="background: #dee2e6;"' : 'href="qr-codes"' ?>>
                        <img src="img/qr-code-2.png" class="image-header" alt="profile-image"> 
                        <span>Liste QR Code</span> 
                    </a>
                </li>
            <?php } ?> 
            
            <li class="nav-heading">QR Code </li>
             
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link collapsed pagetheme"  <?= ($object->route($user->id, 'theme') == '1') ? 'style="background: #dee2e6;"' : 'href="theme"' ?>>-->
            <!--        <img src="img/modele.png" class="image-header" alt="profile-image"> -->
            <!--        <span>Personnaliser le menu</span> -->
            <!--    </a>-->
            <!--</li> -->
            
                        
            <li class="nav-item">
                <a class="nav-link collapsed pageqr-code"  <?= ($object->route($user->id, 'qr-code') == '1') ? 'style="background: #dee2e6;"' : 'href="qr-code"' ?>>
                    <img src="img/qr-code-2.png" class="image-header" alt="profile-image"> 
                    <span>Standard</span> 
                </a>
            </li>
            
           <?php if ($market->design_qrcode == '2'){ ?>           
            <li class="nav-item">
                <a class="nav-link collapsed pageqrcode"  <?= ($object->route($user->id, 'qrcode') == '1') ? 'style="background: #dee2e6;"' : 'href="qrcode"' ?>>
                    <img src="img/qr-code.png" class="image-header rounded-circle" alt="profile-image"> 
                    <span>Custom</span> 
                </a>
            </li> 
            
            <?php } ?> 
            
            <li class="nav-heading">Modules </li>
            
            <?php if ($market->commande == '2'){ ?>
            
               <li class="nav-item">
                <a class="nav-link collapsed pagecommande"  <?= ($object->route($user->id, 'commande') == '1') ? 'style="background: #dee2e6;"' : 'href="commande"' ?>>
                    <img src="img/commande.png" class="image-header" alt="profile-image"> 
                    <span>Commandes</span> 
                </a>
              </li> 
              
           <?php } ?> 
            
             <?php if ($market->multi_langue == '1'){ ?>
               <li class="nav-item">
                <a class="nav-link collapsed pagelangue"  <?= ($object->route($user->id, 'langue') == '1') ? 'style="background: #dee2e6;"' : 'href="langue"' ?>>
                    <img src="img/arabe.png" class="image-header" alt="profile-image"> 
                    <span>Langues</span> 
                </a>
              </li> 
           <?php } ?> 
             <?php if ($market->feedback == '2'){ ?>

            <li class="nav-item">
                <a class="nav-link collapsed pagefeedback"  <?= ($object->route($user->id, 'feedback') == '1') ? 'style="background: #dee2e6;"' : 'href="feedback"' ?>>
                    <img src="img/notation.png" class="image-header" alt="profile-image"> 
                    <span>Feedback</span> 
                </a>
            </li> 
             <?php } ?> 
             <?php if ($market->event == '2'){ ?>
            <li class="nav-item">
                <a class="nav-link collapsed pageevent"  <?= ($object->route($user->id, 'event') == '1') ? 'style="background: #dee2e6;"' : 'href="event"' ?>>
                    <img src="img/defi.png" class="image-header" alt="profile-image"> 
                    <span>Événement</span> 
                </a>
            </li> 
             <?php } ?> 

            <?php if ($market->vip == '2'){ ?>
            <li class="nav-item">
                <a class="nav-link collapsed pagevip"  <?= ($object->route($user->id, 'vip') == '1') ? 'style="background: #dee2e6;"' : 'href="vip"' ?>>
                    <img src="img/vip.png" class="image-header" alt="profile-image"> 
                    <span>Accès VIP</span> 
                </a>
            </li> 
            
             <?php } ?> 

            
            <li class="nav-heading">Paramètres</li>
            
            <li class="nav-item">
                <a class="nav-link collapsed pagesettings"  <?= ($object->route($user->id, 'settings') == '1') ? 'style="background: #dee2e6;"' : 'href="settings"' ?>>
                    <img src="img/reglages.png" class="image-header" alt="profile-image"> 
                    <span>Paramètres</span> 
                </a>
            </li>
            <?php if ($market->wifi == '2'){ ?>
            <li class="nav-item">
                <a class="nav-link collapsed pagewifi"  <?= ($object->route($user->id, 'wifi') == '1') ? 'style="background: #dee2e6;"' : 'href="wifi"' ?>>
                    <img src="img/wifi.png" class="image-header" alt="profile-image" > 
                    <span>Wi-Fi</span> 
                </a>
            </li> 
             <?php } ?> 
            
            <li class="nav-item">
                <a class="nav-link collapsed pagereseaux-sociaux"  <?= ($object->route($user->id, 'reseaux-sociaux') == '1') ? 'style="background: #dee2e6;"' : 'href="reseaux-sociaux"' ?>>
                    <img src="img/facebook.png" class="image-header" alt="profile-image"> 
                    <span>Réseaux Sociaux</span> 
                </a>
            </li> 
         <?php if ($market->utilisateur == '2'){ ?>
            <li class="nav-item">
                <a class="nav-link collapsed pageutilisateur"  <?= ($object->route($user->id, 'utilisateur') == '1') ? 'style="background: #dee2e6;"' : 'href="utilisateur"' ?>>
                    <img src="img/serveur.png" class="image-header" alt="profile-image"> 
                    <span>Utilisateurs</span> 
                </a>
            </li> 
             <?php } ?> 
        <?php   if($user->markets > '1'){  ?>
        
        <li class="nav-heading">Changement point de vente</li>
        
        <li class="nav-item">
            <a class="nav-link collapsed pageselect" href="select">
                <img src="img/select.png" class="image-header" alt="profile-image"> 
                <span>Points de vente</span> 
            </a>
        </li>
        <?php }    ?>
        
        
        <?php if($user->lifetime == '1'){ ?>
        
        <!--<li class="nav-heading">Paiement</li>-->
        
        <!--<li class="nav-item">-->
        <!--    <a class="nav-link collapsed pageabonnements" href="abonnements">-->
        <!--        <img src="img/abonnements.png" class="image-header" alt="profile-image"> -->
        <!--        <span>Acheter un abonnement</span> -->
        <!--    </a>-->
        <!--</li>-->
        <?php }  ?>
     </ul>

  </aside>
