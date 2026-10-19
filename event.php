<?php 
    
    include ('header.php'); 
?>
<style>
    .table {
    white-space: initial;
}
</style>
<main id="main" class="main">
      <div class="pagetitle">
      <h1>Événements</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <li class="breadcrumb-item active">Événements</li>
        </ol>
      </nav>
    </div> 
    
    <?php if ($market->event == '2'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 small">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des événements</h6>
                        	</div> 
                    		<div class="col" align="right">
                    		    <?php if($object->autorisation('c',$user->id,$page_actuel) == 2){?>
                        		    <button type="button" name="add_event" id="add_event" class="btn btn-primary  btn-sm"><i class="fas fa-plus"></i> Ajouter</button>
                        		<?php }?>
                        	</div>
                        </div>
                    </div>
                    <div class="card-body"> 
                        <ul class="nav nav-tabs nav-tabs-bordered"> 
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#actif-overview"><i class="fal fa-check"></i> Actif</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#archive-overview"><i class="fal fa-archive"></i> Archive</button>
                            </li> 
                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active actif-overview" id="actif-overview">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-hover  DataTable" id="event_table" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th>Ordre</th>	
                                                <th>Image</th> 
                                                <th>Titre</th> 
                                                <th  width="50%">Texte</th> 
                                                <th>Date Début </th>
                                                <th>Date Fin </th> 
                                                <th>Statut</th>          
                                                <th width="5%">Actions</th> 
                                            </tr>
                                        </thead>
                                        <tbody >
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade  archive-overview" id="archive-overview">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-hover  DataTable" id="event_table2" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr> 	
                                                <th>Image</th> 
                                                <th>Titre</th> 
                                                <th  width="50%">Texte</th> 
                                                <th>Date Début </th>
                                                <th>Date Fin </th> 
                                                <th>Statut</th>          
                                                <th width="5%">Actions</th> 
                                            </tr>
                                        </thead>
                                        <tbody >
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                  <h4>Module - Événements</h4>
                  <h2 class="mb-0">99 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Capture l'attention :</b> Les images et les titres attirent les clients.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Communication efficace : </b>Des informations claires et concises sur chaque événement.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Promotion accrue : </b>Mise en avant d'événements spéciaux et de promotions.</span>
                    </li> 
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Engagement interactif :</b> Possibilité d'interaction et de participation des clients.</span>
                    </li> 
                  </ul> 
                  <button data-key="<?= $object->encrypt('9') ?>" data-type="module"  class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>    
    <?php } ?>  
</main>
 
<?php
include('footer.php');
?> 
    <div class="modal fade" id="eventModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg small">
            <form method="post" id="event_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout Événement </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: max-content;">
                        <span id="form_message"></span>

                        <div class="row"> 
                        
                          <div class="col-md-12 ">
                                <label for="banner" class="form-label">Image</label>
                                <input type="file" name="banner" id="banner" class="form-control form-control-sm files"   data-parsley-trigger="keyup">
                                <span id="banner_uploaded_image"></span>   
                            </div>  

                            <div class="col-md-12 ">
                                <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="titre" id="titre" class="form-control form-control-sm" required placeholder="titre"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>   
                            
                             <div class="col-md-6 ">
                                <label for="debut" class="form-label">Date Début <span class="text-danger">*</span></label>
                                <input type="datetime-local" step="1" name="debut" id="debut" class="form-control form-control-sm" required   data-parsley-trigger="keyup">
                            </div> 
                            
                             <div class="col-md-6 ">
                                <label for="fin" class="form-label">Date Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" step="1" name="fin" id="fin" class="form-control form-control-sm" required   data-parsley-trigger="keyup">
                            </div> 
  
                            
                             <div class="col-md-12" >
                                <label for="texte" class="form-label">Texte </label>
                                <div class="quill-editor-default">
                                </div>
                                <textarea name="texte" id="texte" style='display:none'></textarea> 
                            </div>  
                             
                           
  
                             
                        </div>                        
                    </div>
                    <div class="modal-footer">   
                        <input type="hidden" name="market" id="market"   value="<?= $market->id ?>"/>
                      	<input type="hidden" name="hidden_id" id="hidden_id" />
              			<input type="hidden" name="action" id="action" value="Add" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<input type="submit" name="submit" id="submit_button" class="btn btn-sm  btn-primary" value="Add" />
              			
                    </div>
                  </div>
            </form>
        </div>
    </div>  

<script>

$('.selectpicker').selectpicker();



    var texte = new Quill('.quill-editor-default' , {
          modules: {
            toolbar: [
              [{
                font: []
              }, {
                size: []
              }],
              ["bold", "italic", "underline", "strike"],
              [{
                  color: []
                },
                {
                  background: []
                }
              ],
              [{
                  script: "super"
                },
                {
                  script: "sub"
                }
              ],
              [{
                  list: "ordered"
                },
                {
                  list: "bullet"
                },
                {
                  indent: "-1"
                },
                {
                  indent: "+1"
                }
              ],
              ["direction", {
                align: []
              }],
              ["link", "image", "video"],
              ["clean"]
            ]
          },
          theme: "snow"
        });
    
$(document).ready(function(){ 
  
	var dataTable = $('#event_table').DataTable({
	       "language": {
        url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        }, 
		"processing" : true,
		"serverSide" : true, 
    	"rowReorder": 
    	{
            selector: 'td:nth-child(1)'
        },
		"order" : [],
		"ajax" : {
			url:"event-action",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[7],
				"orderable":false,
			},
		],    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
    		}
	});
  
	var dataTable2 = $('#event_table2').DataTable({
	       "language": {
        url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        }, 
		"processing" : true,
		"serverSide" : true,  
		"order" : [],
		"ajax" : {
			url:"event-action",
			type:"POST",
			data:{action:'fetch2'}
		},
		"columnDefs":[
			{
				"targets":[6],
				"orderable":false,
			},
		],    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
    		}
	});
	
	
	dataTable.on( 'row-reordered', function ( e, diff, edit ) {
    
        var id_table = new Array();
        
        var newpos = new Array();
        
        diff.forEach((item, index)=>{ 
            id_table.push($(item.oldData).data('id'));
            newpos.push(item.newPosition);
        });
        
        $.ajax({

      		url:"event-action",

	      	method:"POST",

	      	data:{market:<?= $market->id ?>,id_table:id_table,newpos:newpos, action:'update_order'}, 

	      	success:function(data)
	      	{
	      	    $('.DataTable').DataTable().ajax.reload(); 
						
	      	     notificationSwal.fire({
                    icon: 'success',
                    title: data,
                });
	      	}

	    })
    } );

	$('#add_event').click(function(){
		
		$('#event_form')[0].reset();

		$('#event_form').parsley().reset();

    	$('#modal_title').text('Ajout Événement');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#eventModal').modal('show');

    	$('#form_message').html('');
    	
    	$('#banner').attr('required',true);  

        $('#banner_uploaded_image').html('');
        
        texte.setContents([{ insert: '\n' }]);

	});

    $('#banner').change(function(){
        var extension = $('#banner')
                        .val()
                        .split('.')
                        .pop()
                        .toLowerCase();
                        
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg','webp']) == -1)
            { 
                $('#div_notification').html("Fichier image non valide");
						
      	        show_notifcation();
                $('#banner').val('');
                return false;
            }
        }
    });
  

	$('#event_form').parsley();

	$('#event_form').on('submit', function(event){
		event.preventDefault();
		if($('#event_form').parsley().isValid())
		{		
		    
		    var editor_content = texte.container.innerHTML
            $('#texte').val($(editor_content).html());
            
			$.ajax({
				url:"event-action",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#eventModal').modal('hide');
						notificationSwal.fire({
                            icon: 'success',
                            title: data.data,
                        });

						$('.dataTable').DataTable().ajax.reload();
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var id = $(this).data('id');
		
		$('#event_form')[0].reset();

		$('#event_form').parsley().reset();

		$('#form_message').html('');
		
		texte.setContents([{ insert: '\n' }]);

		$.ajax({

	      	url:"event-action",

	      	method:"POST",

	      	data:{id:id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{  

	        	$('#debut').val(data.debut);

                $('#fin').val(data.fin); 

	        	$('#titre').val(data.titre);  
                
                $('#texte').val(data.texte);
	        	
	        	const value = data.texte;
                const delta = texte.clipboard.convert(value)
                
                texte.setContents(delta, 'silent')
                
                $('#banner').attr('required',false);  
                
                $('#banner_uploaded_image').html('<img src="'+data.banner+'" class="img-fluid img-thumbnail" width="75" height="75" /><input type="hidden" name="hidden_banner" value="'+data.banner+'" />');

	        	$('#modal_title').text('Modifier Événement');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Modifier');

	        	$('#eventModal').modal('show');

	        	$('#hidden_id').val(id);

	      	}

	    })

	});
 
	
 
	
	$(document).on('click', '.statut_button', function(){

    	var id = $(this).data('id');
        var statut = $(this).data('statut');
        var next_statut = 'Activer';
        if(statut == 'Activer')
        {
            next_statut = 'Desactiver';
        }

        Swal.fire({
            title: 'Es-tu sûr?',
            text: "êtes-vous sûr de "+next_statut+"!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, '+next_statut+'!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
            		url:"event-action",
    
            		method:"POST",
    
            		data:{id:id, action:'change_statut', statut:statut, next_statut:next_statut},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'categorie '+next_statut+'!',
                        });
                    }
                });
            }
        });   
        
  	});
	
 
});
</script>