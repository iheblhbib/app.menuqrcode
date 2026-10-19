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
      <h1>Paramètres</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Paramètres</li>
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
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit"><i class="fal fa-cogs"></i> Paramètres</button>
                     <input type="hidden" name="market" id="market" value="<?= $market->id ?>" />
                </li> 

                <li class="nav-item">
                    <button class="nav-link  " data-bs-toggle="tab" data-bs-target="#profile-historie"><i class="fa-light fa-badge-dollar"></i> Historique de Paiement</button> 
                </li> 
  
              </ul>
              
              <?php if($object->autorisation('r',$user->id,$page_actuel) == 2){?>
              
              <div class="tab-content pt-2">
 
                
                <div class="tab-pane fade  show active profile-edit pt-3" id="profile-edit">
                
                    <form method="post" id="market_form" enctype="multipart/form-data">
                       
                        <div class="row mb-3">
                            <label for="logo" class="col-md-4 col-lg-3 col-form-label">Logo</label>
                            <div class="col-md-8 col-lg-9">
                                <span id="uploaded_logo">
                                    <img src="<?= $market->logo ?>" id="logo_image" alt="logo" class="img-fluid img-thumbnail">
                                    <input type="hidden" name="hidden_logo" id="hidden_logo" value="<?= $market->logo ?>" /> 
                                </span>
                                <div class="pt-2">
                                    <input type="file" name="logo" id="logo" class="form-control form-control-sm"/>  
                                </div> 
                            </div>
                        </div> 
    
                        <div class="row mb-3">
                            <label for="libelle" class="col-md-4 col-lg-3 col-form-label">Nom du Point de vente</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="libelle" type="text" class="form-control form-control-sm" id="libelle" value="<?= $market->market ?>" placeholder="Nom du Point de vente">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label">E-mail</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" type="email" class="form-control form-control-sm" id="email" value="<?= $market->email ?>" placeholder="E-mail">
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="siteweb" class="col-md-4 col-lg-3 col-form-label">Site Web</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="siteweb" type="text" class="form-control form-control-sm" id="siteweb" value="<?= $market->siteweb ?>" placeholder="www.menuqrcode.tn">
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-lg-3 col-form-label">Adresse</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="address" type="text" class="form-control form-control-sm" id="address" value="<?= $market->address ?>" placeholder="A108 Adam Street, New York, NY 535022">
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="tel" class="col-md-4 col-lg-3 col-form-label">Téléphone</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="tel" type="text" class="form-control form-control-sm" id="tel" value="<?= $market->tel ?>" placeholder="Téléphone">
                            </div>
                        </div>    
                        
                        <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Afficher le menu sur la page d'accueil</label> 
                            <div class="col-md-1 col-lg-1">
                                <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="display" name="display"  <?= ($market->display == '1') ? 'checked' : '' ?>> 
                                </div> 
                            </div>
                        </div>
    
                        <div class="row mb-3">
                            <label for="tel" class="col-md-4 col-lg-3 col-form-label">Description</label>
                            <div class="col-md-8 col-lg-9"> 
                                <div class="quill-editor-default">
                                    <?= $market->description ?>
                                </div>
                                <textarea name="description" id="description" style='display:none'></textarea> 
                            </div>
                        </div>    
                        
                        <!--<div class="row mb-3">-->
                        <!--    <label class="col-md-4 col-lg-3 col-form-label">Statut QR Code</label> -->
                        <!--    <div class="col-md-1 col-lg-1">-->
                        <!--        <div class="form-check form-switch">-->
                        <!--          <input class="form-check-input" type="checkbox" id="statut" name="statut"  <?= ($market->statut == 'Activer') ? 'checked' : '' ?>> -->
                        <!--        </div> -->
                        <!--    </div>-->
                        <!--</div>-->
    
                        <?php if($object->autorisation('u',$user->id,$page_actuel) == 2){?>
                            <div class="text-center">
                              <button type="submit" class="btn btn-primary"  name="market_button" id="market_button">Sauvegarder les modifications</button>
                            </div>
                        <?php }?>
                    </form>  
                </div>
                
                <div class="tab-pane fade   profile-historie pt-3" id="profile-historie">
                    
                            
                            <?php if($user->lifetime == '1'){?>
                                <h4 class="card-title">Date d'écheance : <?= date('d/m/Y',strtotime($user->echeance)) ?> </h4>
                            <?php }?>
                             
                            <h5 class="card-title">Dernière Paiements </h5>
        
                            <div class="activity p-4">
                                    <?php
                                        $Paiements = json_decode($object->lastPaiements($market->id));
                                        foreach ($Paiements as $Paiement => $item) 
                                        {
                                    ?>
                                        <div class="activity-item d-flex">
                                        <div class="activite-label"><?= $item->time ?></div>
                                        <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                        <div class="activity-content">
                                            <h6><b>Pack <?= $item->libelle ?> ( <?= $item->duree ?> Mois )  <span>| <?= $item->montant ?> TND</span></b></h6>
                                            <p>Le <?= $item->date ?></p>
                                        </div>
                                        </div>
                
        
                                    <?php
                                        } 
                                    ?>
        
                            </div> 
                
                </div>  
              </div>  
              <?php }?>
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
    
        
    var description = new Quill('.quill-editor-default', {
      theme: 'snow',
    
      placeholder: 'Écrire une description détaillée...',
    
      debug: 'warn',
    
      modules: {
        toolbar: {
          container: [
            [{ font: [] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
    
            ['bold', 'italic', 'underline', 'strike'],
    
            [{ color: [] }, { background: [] }],
    
            [{ script: 'sub' }, { script: 'super' }],
    
            [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
    
            [{ indent: '-1' }, { indent: '+1' }],
            [{ align: [] }],
    
            ['blockquote', 'code-block'],
    
            ['link', 'image'],
    
            ['clean']
          ], 
        },
    
        clipboard: {
          matchVisual: false
        },
    
        history: {
          delay: 1000,
          maxStack: 100,
          userOnly: true
        }
      },
    
      formats: [
        'font', 'header',
        'bold', 'italic', 'underline', 'strike',
        'color', 'background',
        'script',
        'list', 'bullet', 'check',
        'indent',
        'align',
        'blockquote', 'code-block',
        'link', 'image', 'video'
      ]
    });
    
  	$('#market_form').parsley();

	$('#market_form').on('submit', function(event){
	     
		event.preventDefault();
		if($('#market_form').parsley().isValid())
		{	
		    
		    var editor_content = description.container.innerHTML
            
            $('#description').val($(editor_content).html());
            
		    var formData = new FormData(this);
		    formData.append('action', 'update_market'); 
		    formData.append('market', $('#market').val()); 
		    
            var display = $('#display').is(':checked');
            // var statut = $('#statut').is(':checked');
            
            formData.append('market', $('#market').val()); 
            // formData.append('statut', statut); 
            formData.append('display', display); 
        
			$.ajax({
				url:"settings-action",
				method:"POST",
				data:formData,
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#market_button').attr('disabled', 'disabled');
					$('#market_button').html('Attendre...');
				}, 
				success:function(data)
				{
					$('#market_button').attr('disabled', false);
					
                    $('#market_button').html('Sauvegarder les modifications');

                    if(data.logo != '')
                    {
                        $('#uploaded_logo').html('<img src="'+data.logo+'" id="logo_image" alt="logo"><input type="hidden" name="hidden_logo" id="hidden_logo" value="'+data.logo+'" />');
                    } 
						
                    notificationSwal.fire({
                        icon: 'success',
                        title: data.success,
                    });
				}
			})
		}
	});
      
    $('#logo').change(function(){
        var extension = $('#logo').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Fichier image non valide");
                $('#logo').val('');
                return false;
            }
        }
    });
});
</script>
