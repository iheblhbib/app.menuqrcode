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
      <h1>Réseaux Sociaux</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Réseaux Sociaux</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">

        <div class="col-md-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
 

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#social-edit"><i class="fab fa-facebook"></i> Réseaux Sociaux</button>
                    <input type="hidden" name="market" id="market" value="<?= $market->id ?>" />
                </li> 
               
              </ul>
              
              <?php if($object->autorisation('r',$user->id,$page_actuel) == 2){?>
              
              <div class="tab-content pt-2">
 
                 
                <div class="tab-pane fade show active social-edit pt-3" id="social-edit"> 
                    <form method="post" id="social_form" enctype="multipart/form-data">
                        

                    <div class="row mb-3">
                      <label for="facebook" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-facebook"></i> Facebook</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="facebook" type="text" class="form-control form-control-sm" id="facebook" value="<?= $market->facebook ?>" placeholder="Facebook">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="instagram" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-instagram"></i> Instagram</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="instagram" type="text" class="form-control form-control-sm" id="instagram" value="<?= $market->instagram ?>" placeholder="Instagram">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="tiktok" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-tiktok"></i> Tiktok</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="tiktok" type="text" class="form-control form-control-sm" id="tiktok" value="<?= $market->tiktok ?>" placeholder="Tiktok">
                      </div>
                    </div>    
                    <div class="row mb-3">
                      <label for="linkedin" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-linkedin"></i> Linkedin</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="linkedin" type="text" class="form-control form-control-sm" id="linkedin" value="<?= $market->linkedin ?>" placeholder="Linkedin">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="youtube" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-youtube"></i> Youtube</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="youtube" type="text" class="form-control form-control-sm" id="youtube" value="<?= $market->youtube ?>" placeholder="Youtube">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="twitter" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-twitter"></i> Twitter</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="twitter" type="text" class="form-control form-control-sm" id="twitter" value="<?= $market->twitter ?>" placeholder="Twitter">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="whatsapp" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-whatsapp"></i> WhatsApp</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="whatsapp" type="text" class="form-control form-control-sm" id="whatsapp" value="<?= $market->whatsapp ?>" placeholder="WhatsApp">
                      </div>
                    </div>  
                    
                    <div class="row mb-3">
                      <label for="google" class="col-md-4 col-lg-3 col-form-label"><i class="fab fa-google"></i> Google Avis</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="google" type="text" class="form-control form-control-sm" id="google" value="<?= $market->google ?>" placeholder="Google Avis">
                      </div>
                    </div>    

                    <div class="row mb-3">
                      <label for="tripadvisor" class="col-md-4 col-lg-3 col-form-label"><img width="24" src="./assets/img/tripadvisor.svg" alt="TripAdvisor"> TripAdvisor </label>
                      <div class="col-md-8 col-lg-9">
                        <input name="tripadvisor" type="text" class="form-control form-control-sm" id="tripadvisor" value="<?= $market->tripadvisor ?>" placeholder="TripAdvisor">
                      </div>
                    </div>    

 
                    <div class="text-center">
                        <?php if($object->autorisation('u',$user->id,$page_actuel) == 2){?>
                            <button type="submit" class="btn btn-primary"  name="social_button" id="social_button">Sauvegarder les modifications</button>
                        <?php } ?>
                    </div>
                  </form><!-- End Profile Edit Form --> 
                </div>
        
                
              </div>  
               <?php } ?>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
  
<?php 

include 'footer.php'; 

?>

<script>
$(document).ready(function(){ 
 
    
  	$('#social_form').parsley();

	$('#social_form').on('submit', function(event){
	     
		event.preventDefault();
		if($('#social_form').parsley().isValid())
		{	
		    var formData = new FormData(this);
		    formData.append('action', 'update_social'); 
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
					$('#social_button').attr('disabled', 'disabled');
					$('#social_button').html('Attendre...');
				}, 
				success:function(data)
				{
					$('#social_button').attr('disabled', false);
					
                    $('#social_button').html('Sauvegarder les modifications'); 
                    
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
