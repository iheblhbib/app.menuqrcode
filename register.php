<?php
include('../config.menuqrcode/config.php');

$object = new application();

header("location:".$object->client_url."login");

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

  <title> Menu QR Code | Register</title>
        <meta content="Discover our digital menu with dynamic QR code for online ordering in our restaurant. Browse through our selection of delicious food and drinks, and enjoy a quick and easy meal. Our digitized menu offers a convenient and modern dining experience through technology., Découvrez notre menu numérique avec QR code dynamique pour commander en ligne dans notre restaurant. Parcourez notre sélection d'aliments et de boissons délicieux, et profitez d'un repas facile et rapide. Notre carte numérisée offre une expérience culinaire pratique et moderne grâce à la technologie." name="description">
        <meta content="digital menu, dynamic QR code, restaurant, online ordering, food, drinks, meals, menu, scanning, technology,menu numérique, QR code dynamique, restaurant, commande en ligne, alimentation, nourriture, boissons, repas, carte, numérisation, technologie" name="keywords">
        

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
     <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>
  
  <!-- Template Main CSS File -->
  <link href="assets/css/new-style.css" rel="stylesheet">

</head>

<body>
    <div class="container">
      <section class="section register d-flex flex-column align-items-center justify-content-center py-4" style="    margin-top: 70px;">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4"> 
              </div><!-- End Logo -->

              <div class="card mb-3">
                <div class="card-body">
                    
 
  
                    <div class="pt-4 pb-2 text-center">
                        <img src="img/logo-menu-qr-code.png" alt="menu qr code" style= "width:30%;"> 
                    
                        <h5 class="card-title text-center pb-0 fs-4">Bienvenue dans le futur de la gestion de menu</h5>
                        <p class="text-center small">Rejoignez-nous pour une gestion de menu simplifiée</p>
                    </div>

                  <form class="row g-3 needs-validation" method="post" id="login_form">
                      

                    <div class="col-md-12">
                      <label class="form-label" for="name">Nom & Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Menu QR Code" class="form-control" required data-parsley-trigger="keyup">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" placeholder="exemple@menuqrcode.tn" class="form-control" required data-parsley-trigger="keyup">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="num">Tél <span class="text-danger">*</span></label>
                        <input type="text" name="num" id="num" placeholder="71581836" class="form-control" required data-parsley-trigger="keyup" data-parsley-minlength="8"  data-parsley-maxlength="8" >
                    </div>
                    
                    <div class="col-md-6">
                      <label class="form-label"  for="pseudo">Nom d'utilisateur <span class="text-danger">*</span></label>
                        <input type="text" name="pseudo" id="pseudo"  placeholder="menuqrcodetunisie" class="form-control" required data-parsley-trigger="keyup">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label"  for="password">Mot de Passe <span class="text-danger">*</span></label>
                      <input type="password" name="password" id="password" placeholder="********" class="form-control was-validated" required data-parsley-trigger="keyup"  data-parsley-minlength="8" data-parsley-maxlength="16" > 
                    </div> 
                    
                    <div class="col-md-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox"  id="acceptTerms" required  data-parsley-trigger="keyup"> 
                        <label class="form-check-label" for="acceptTerms">Je suis d'accord et j'accepte les <a target="_blank" href="https://menuqrcode.tn/conditions-generales-dutilisation">termes et conditions</a></label> 
                      </div>
                    </div>

                    <div class="col-md-12">
                      <button type="submit" name="register_button" id="register_button"  class="btn btn-primary w-100">Créer un compte professionnel</button>
                    </div> 
                   <div class="col-md-12">
                      <p class="small mb-0">Vous avez déjà un compte ? <a href="login">Log in</a></p>
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
                url:"register-action",
                method:"POST",
                data:$(this).serialize(),
                dataType:'json',
                beforeSend:function()
                {
                    $('#register_button').attr('disabled', 'disabled');
                    $('#register_button').val('Loading ...');
                },
                success:function(data)
                {
                    $('#register_button').attr('disabled', false);
                    if(data.error != '')
                    {
                        $('#register_button').val('Compte créé');
                        
                         notificationSwal.fire({
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
                        
                        $.redirect("verification-OTP", {otp:'validation',key:data.key}, "POST");  
                        
     
                    }
                }
            })
        }
    });

}); 

        </script>