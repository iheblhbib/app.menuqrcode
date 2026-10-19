<?php 

    require 'header.php';
   
?>
 
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>QR Code</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">QR Code</li>
        </ol>
      </nav>
    </div> 

    <section class="section profile">
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-body pt-3 text-center">
                    <img src="<?= $market->qrcode ?>" />
            </div>
          </div>

        </div> 
      </div>
    </section>

  </main><!-- End #main -->
  
<?php 

include 'footer.php'; 

?>

 
