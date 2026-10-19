<?php 

    require 'header.php';
   
?>
<style>
  label {
    font-weight: 600;
    color: rgba(1, 41, 112, 0.6);
}

.iframe-container {
  position: relative;
  width: 500px; /* Set initial width as desired */
  height: 800px; /* Set initial height as desired */
  resize: both;
  overflow: auto;
}

.resize-handle {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 20px;
  height: 20px;
  background-color: #2c5199;
  cursor: se-resize;
}

.iframe-container iframe {
  width: 100%;
  height: 100%;
}

</style>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Design Menu</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Design Menu</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">

        <div class="col-md-4">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered"> 
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#theme-edit"><i class="fa-light fa-utensils"></i> Design Menu </button>
                    <input type="hidden" name="market" id="market" value="<?= $market->id ?>" />
                </li> 
              </ul>
              
              <div class="tab-content pt-2">
 
                 <?php if($object->autorisation('r',$user->id,$page_actuel) == 2){?>
                <div class="tab-pane fade show active  theme-edit pt-3" id="theme-edit"> 
                    <form method="post" id="theme_form" enctype="multipart/form-data">
                        <div class="row mb-3">
                          <label for="background" class="col-md-6  col-form-label">Arrière-plan</label>
                          <div class="col-md-6 ">
                            <span id="uploaded_background">
                                <img src="<?= $market->background ?>" id="background_image" alt="background" class="img-fluid img-thumbnail" width="150">
                                <input type="hidden" name="hidden_background" id="hidden_background" value="<?= $market->background ?>" />
                                
                            </span>
                            <div class="pt-2">
                                <input type="file" name="background" id="background" class="form-control form-control-sm"/>  
                            </div>
                            
                          </div>
                        </div>
    
                        <?php if($market->niveau == '4'){ ?>
    
                        <div class="row mb-3">
                          <label for="text_color0" class="col-md-6  col-form-label">Texte Point de vente</label>
                          <div class="col-md-6 ">
                            <input name="text_color0" type="color" style="width:150px;"  class="form-control form-control-sm" id="text_color0" value="<?= $market->text_color0 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="background_color0" class="col-md-6  col-form-label">Arrière-plan Point de vente</label>
                          <div class="col-md-6 ">
                            <input name="background_color0" type="color" style="width:150px;"  class="form-control form-control-sm" id="background_color0" value="<?= $market->background_color0 ?>" placeholder="Entreprise">
                          </div>
                        </div>  
                        
                        <?php } ?>
                        
                        <div class="row mb-3">
                          <label for="text_color1" class="col-md-6  col-form-label">Texte Catégories</label>
                          <div class="col-md-6 ">
                            <input name="text_color1" type="color" style="width:150px;"  class="form-control form-control-sm" id="text_color1" value="<?= $market->text_color1 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="background_color1" class="col-md-6  col-form-label">Arrière-plan Catégories</label>
                          <div class="col-md-6 ">
                            <input name="background_color1" type="color" style="width:150px;"  class="form-control form-control-sm" id="background_color1" value="<?= $market->background_color1 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <?php if($market->niveau == '3' || $market->niveau == '4'){ ?>
                        
                        <div class="row mb-3">
                          <label for="text_color2" class="col-md-6  col-form-label">Texte Sous Catégories</label>
                          <div class="col-md-6 ">
                            <input name="text_color2" type="color" style="width:150px;"  class="form-control form-control-sm" id="text_color2" value="<?= $market->text_color2 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="background_color2" class="col-md-6  col-form-label">Arrière-plan Sous Catégories</label>
                          <div class="col-md-6 ">
                            <input name="background_color2" type="color" style="width:150px;"  class="form-control form-control-sm" id="background_color2" value="<?= $market->background_color2 ?>" placeholder="Entreprise">
                          </div>
                        </div> 
                        
                        <?php } ?>
                        
                        <div class="row mb-3">
                          <label for="text_color3" class="col-md-6  col-form-label">Texte Article</label>
                          <div class="col-md-6 ">
                            <input name="text_color3" type="color" style="width:150px;"  class="form-control form-control-sm" id="text_color3" value="<?= $market->text_color3 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="background_color3" class="col-md-6  col-form-label">Arrière-plan Article</label>
                          <div class="col-md-6 ">
                            <input name="background_color3" type="color" style="width:150px;"  class="form-control form-control-sm" id="background_color3" value="<?= $market->background_color3 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="text_color4" class="col-md-6  col-form-label">Texte Description</label>
                          <div class="col-md-6 ">
                            <input name="text_color4" type="color" style="width:150px;"  class="form-control form-control-sm" id="text_color4" value="<?= $market->text_color4 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                        <div class="row mb-3">
                          <label for="background_color4" class="col-md-6  col-form-label">Arrière-plan Description</label>
                          <div class="col-md-6 ">
                            <input name="background_color4" type="color" style="width:150px;"  class="form-control form-control-sm" id="background_color4" value="<?= $market->background_color4 ?>" placeholder="Entreprise">
                          </div>
                        </div>   
                        
                      

                        <?php if($object->autorisation('u',$user->id,$page_actuel) == 2){?>
                            <div class="text-center">
                              <button type="submit" class="btn btn-primary"  name="theme_button" id="theme_button">Sauvegarder les modifications</button>
                            </div>
                        <?php }?>
                  </form><!-- End Profile Edit Form --> 
                </div>
                 
                  <?php }?>
                
              </div>  
            </div>
          </div>

        </div>
        <div class="col-md-4">
            <div class="card menu-container iframe-container">
                  <div class="resize-handle"></div>

                <iFrame  id="my-iframe" src="<?= $object->get_url_menu_selected($market->id) ?>?data=<?= $object->encrypt($market->id) ?>" height="700"></iFrame>
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
    
var container = document.querySelector('.iframe-container');
var handle = document.querySelector('.resize-handle');

handle.addEventListener('mousedown', initializeResize, false);

function initializeResize(e) {
  e.preventDefault();
  window.addEventListener('mousemove', startResize, false);
  window.addEventListener('mouseup', stopResize, false);
}

function startResize(e) {
  container.style.width = (e.clientX - container.offsetLeft) + 'px';
  container.style.height = (e.clientY - container.offsetTop) + 'px';
}

function stopResize() {
  window.removeEventListener('mousemove', startResize, false);
  window.removeEventListener('mouseup', stopResize, false);
}
 
    
  	$('#theme_form').parsley();

	$('#theme_form').on('submit', function(event){
	     
		event.preventDefault();
		if($('#theme_form').parsley().isValid())
		{	
		    var formData = new FormData(this);
		    formData.append('action', 'update_theme'); 
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
					$('#theme_button').attr('disabled', 'disabled');
					$('#theme_button').html('Attendre...');
				}, 
				success:function(data)
				{
					$('#theme_button').attr('disabled', false);
					
                    $('#theme_button').html('Sauvegarder les modifications'); 
                    
                    notificationSwal.fire({
                        icon: 'success',
                        title: data.success,
                    });
                         
                     if(data.background != '')
                    {
                        $('#uploaded_background').html('<img src="'+data.background+'" id="background_image" alt="background"  class="img-fluid img-thumbnail"  width="150"><input type="hidden" name="hidden_background" id="hidden_background" value="'+data.background+'" />');
                    } 
                    
                    $('.menu-container').html('<iFrame src="'+data.lien+'" height="700"></iFrame>');
				}
			})
		}
	});
 
 
});
</script>
