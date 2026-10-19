<?php
include('../config.menuqrcode/config.php');

$object = new application();

 if($_SERVER["HTTPS"] != "on")
    {
        header("Location: ".$object->client_url);
        exit();
    } 

if($object->is_login())
{
    header("location:".$object->client_url."");
}
 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> Menu QR Code | Login</title>
        <meta content="Boostez votre restaurant ou café en Tunisie avec des menus digitalisés par QR code ! Simplifiez les commandes et impressionnez vos clients grâce à notre solution innovante. Découvrez la révolution numérique des menus dès maintenant !" name="description">
        <meta content="digital menu, dynamic QR code, restaurant, online ordering, food, drinks, meals, menu, scanning, technology,menu numérique, QR code dynamique, restaurant, commande en ligne, alimentation, nourriture, boissons, repas, carte, numérisation, technologie" name="keywords">
        

  <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png"> 
    <link rel="manifest" href="assets/img/site.webmanifest">

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
     <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>
  
  <!-- Template Main CSS File -->
  <link href="assets/css/new-style.css" rel="stylesheet">
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
</head>

<body>
    <div class="container">
      <section class="section register d-flex flex-column align-items-center justify-content-center py-4" style="    margin-top: 70px;">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="#" class="logo d-flex align-items-center w-auto"> 
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <div class="card-body">
                    <div class="pt-4 pb-2 text-center">
                        <img src="img/logo-menu-qr-code.png" alt="menu qr code" style= "width:60%;"> 
                  </div>

                  <form class="row g-3 needs-validation" method="post" id="login_form">

                    <div class="col-12">
                      <label class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="pseudo" id="pseudo"  class="form-control" required>
                    </div>

                    <div class="col-12">
                      <label class="form-label">Mot de Passe</label>
                      <input type="password" name="password" id="password"  class="form-control was-validated" required>
                    
                    </div>

                    <div class="col-12">
                      <button type="submit" name="login_button" id="login_button"  class="btn btn-primary w-100">Connexion</button>
                    </div> 
                    
                       <div class="col-12">
                          <!--<p class="small mb-0">Vous n'avez pas de compte ?<a href="register"> Créer un compte</a></p>-->
                        </div>
                       <div class="col-12">
                          <p class="small mb-0">← Aller sur <a href="https://menuqrcode.tn/">Menu QR Code</a></p>
                        </div>
                    
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
 
    <footer class="footer">
                <div class="copyright">
                &copy; <?= date('Y') ?> Tous les droits sont réservés
                </div>
                <div class="credits">
               <strong><span>Menu QR Code</span></strong> 3.1.1</a>
                </div>
            </footer>
  <div id="message"></div>
          <style>
            #message {
                visibility: hidden;
                min-width: 250px;
                margin-left: -125px;
                background-color: #e51722;
                color: #fff;
                text-align: center;
                border-radius: 2px;
                padding: 16px;
                position: fixed;
                z-index: 1;
                left: 50%;
                bottom: 30px;
                font-size: 17px;
                border-radius: 5px;
                box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);
            }
            
            #message.show {
                visibility: visible;
                -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
                animation: fadein 0.5s, fadeout 0.5s 2.5s;
            }
            
            @-webkit-keyframes fadein {
                from {bottom: 0; opacity: 0;} 
                to {bottom: 30px; opacity: 1;}
            }
            
            @keyframes fadein {
                from {bottom: 0; opacity: 0;}
                to {bottom: 30px; opacity: 1;}
            }
            
            @-webkit-keyframes fadeout {
                from {bottom: 30px; opacity: 1;} 
                to {bottom: 0; opacity: 0;}
            }
            
            @keyframes fadeout {
                from {bottom: 30px; opacity: 1;}
                to {bottom: 0; opacity: 0;}
            }
        </style>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <!-- Vendor JS Files -->
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>
        <script src="assets/vendor/quill/quill.min.js"></script>
        <script src="assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="assets/vendor/chart.js/chart.min.js"></script>
        <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="assets/vendor/echarts/echarts.min.js"></script>
        
        
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="assets/js/sb-admin-2.min.js"></script>
        <script type="text/javascript" src="assets/vendor/parsley/dist/parsley.min.js"></script>
        
        <script type="text/javascript" src="vendor/parsley/dist/parsley.min.js"></script>
            
        <!--/*******************************************-->
        <!--                   parsley  français               -->
        <!--********************************************/-->
        <script type="text/javascript" src="vendor/parsley/dist/i18n/fr.js"></script>
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
 <script src="vendor/jquery/jquery.redirect/jquery.redirect.js"></script>        
</body>

</html>

<script>
 

$(document).ready(function(){

    $('#login_form').parsley();

    $('#login_form').on('submit', function(event){
        event.preventDefault();
        if($('#login_form').parsley().isValid())
        {       
            $.ajax({
                url:"login-action",
                method:"POST",
                data:$(this).serialize(),
                dataType:'json',
                beforeSend:function()
                {
                    $('#login_button').attr('disabled', 'disabled');
                    $('#login_button').val('wait...');
                },
                success:function(data)
                {
                    $('#login_button').attr('disabled', false);
                    
                    if(!data.verified){
                        
                        Swal.fire({
                          icon: 'error',
                          title: data.error
                        }); 
                        setTimeout(function(){ 
                            $.redirect("verification-OTP", {otp:'validation',key:data.key }, "POST");  
                        }, 2000);
                        
                    }else{
                        if(data.error != '')
                        {
                            $('#login_button').val('Login');
                            
                             Swal.fire({
                              icon: 'error',
                              title: data.error
                            }); 
                        }
                        else
                        {   
                            notificationSwal.fire({
                              icon: 'success',
                              title: data.success
                            });
                            window.location.href = "<?= $object->client_url; ?>";
                        }
                    }
                    
                     
                }
            })
        }
    });

}); 

        </script>