<?php 

    require 'header.php';
   
?>
<style>
  label {
    font-weight: 600;
    color: rgba(1, 41, 112, 0.6);
}
</style>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Wi-Fi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Wi-Fi</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <?php if ($market->wifi == '2'){ ?>
    <section class="section profile">
      <div class="row">

        <div class="col-md-6">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
 
 
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#wifi-edit"><i class="fa-regular fa-wifi"></i> Wi-Fi</button>
                    <input type="hidden" name="market" id="market" value="<?= $market->id ?>" />
                </li>  
 
              </ul>
              
              <?php if($object->autorisation('r',$user->id,$page_actuel) == 2){?>
              
              <div class="tab-content pt-2">

  
                  
                <div class="tab-pane fade  show active  wifi-edit pt-3" id="wifi-edit"> 
                    <form method="post" id="wifi_form" enctype="multipart/form-data">
                        

                    <div class="row mb-3">
                      <label for="wifi_ssid" class="col-md-4 col-lg-3 col-form-label"><i class="fa-light fa-router"></i> SSID</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="wifi_ssid" type="text" class="form-control form-control-sm" id="wifi_ssid" value="<?= $market->wifi_ssid ?>" placeholder="Entreprise">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="wifi_password" class="col-md-4 col-lg-3 col-form-label"><i class="fa-light fa-key"></i> Mot de Passe</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="wifi_password" type="text" class="form-control form-control-sm" id="wifi_password" value="<?= $market->wifi_password ?>" placeholder="Entreprise">
                      </div>
                    </div>     

                    <?php if($object->autorisation('u',$user->id,$page_actuel) == 2){?>
                        <div class="text-center">
                          <button type="submit" class="btn btn-primary"  name="wifi_button" id="wifi_button">Sauvegarder les modifications</button>
                        </div>
                    <?php }?>
                  </form> 
                </div> 
                
              </div>  
            <?php }?>
            </div>
          </div>

        </div>
      </div>
    </section>
    
    <?php }else{ ?>  
    
 

      <section class="section" > 
        <div role="tabpanel" class="tab-pane" id="monthly">
          <div class="row justify-content-center">
            <div class="col-md-4 ">
              <div class="price-item text-center">
                <div class="price-top">
                  <h4>Module - Wi-Fi</h4>
                  <h2 class="mb-0">49 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Flexibilité des données de connexion :</b> Vous pouvez facilement modifier les informations de connexion Wi-Fi en fonction de vos besoins changeants, offrant ainsi une flexibilité dans la gestion de votre réseau.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Gestion simplifiée : </b>La capacité de modifier les données de connexion vous permet de gérer efficacement les problèmes éventuels liés au Wi-Fi, assurant ainsi une connectivité fluide et une expérience utilisateur optimale.</span>
                    </li> 
                  </ul> 
                  <button data-key="<?= $object->encrypt('5') ?>"  data-type="module" class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>      
    <?php } ?> 

  </main><!-- End #main -->
  
<?php 

include 'footer.php'; 

?>

<script>
$(document).ready(function(){ 
 
 
    
  	$('#wifi_form').parsley();

	$('#wifi_form').on('submit', function(event){
	     
		event.preventDefault();
		if($('#wifi_form').parsley().isValid())
		{	
		    var formData = new FormData(this);
		    formData.append('action', 'update_wifi'); 
		    formData.append('market', $('#market').val()); 
			$.ajax({
				url:"settings-action",
				method:"POST",
				data:formData,
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#wifi_button').attr('disabled', 'disabled');
					$('#wifi_button').html('Attendre...');
				}, 
				success:function(data)
				{
					$('#wifi_button').attr('disabled', false);
					
                    $('#wifi_button').html('Sauvegarder les modifications'); 
                    
                    notificationSwal.fire({
                        icon: 'success',
                        title: data.success,
                    });
				}
			})
		}
	});
 
});
</script>
