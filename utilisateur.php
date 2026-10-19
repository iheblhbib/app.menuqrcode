<?php 
    
    include ('header.php');
?>
<style>
    .form-control {
        margin-bottom: 10px;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Utilisateur</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Utilisateur</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<?php if ($market->utilisateur == '2'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 small">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold ">Liste des utilisateur</h6>
                            	</div>
                            	<div class="col" align="right">
                            	    <?php if($object->autorisation('c',$user->id,$page_actuel) == 2){?>
                            		 <button type="button" name="add_utilisateur" id="add_utilisateur" class="btn btn-primary  btn-sm" data-bs-toggle="modal" data-bs-target="#utilisateurModal" ><i class="fal fa-plus"></i> Ajouter</button>
                            	   <?php }?>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-tabs-bordered"> 
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#actif-overview"><i class="fal fa-user-check"></i> Actif</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#archive-overview"><i class="fal fa-archive"></i> Archive</button>
                                </li> 
                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active actif-overview" id="actif-overview">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm table-hover  DataTable" id="utilisateur_table" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr> 
                                                    <th>Nom & Prénom</th>
                                                    <th>téléphone</th>
                                                    <th>Email</th>
                                                    <th>Pseudo</th>  
                                                    <th>Point de vente</th>  
                                                    <th>Permission</th>  
                                                    <th>Statut</th>
                                                    <th>Éditer</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade   archive-overview" id="archive-overview">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm table-hover DataTable" id="utilisateur_archive_table" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr> 
                                                    <th>Nom & Prénom</th>
                                                    <th>téléphone</th>
                                                    <th>Email</th>
                                                    <th>Pseudo</th>  
                                                    <th>Point de vente</th>  
                                                    <th>Permission</th>  
                                                    <th>Statut</th>
                                                    <th>Éditer</th>
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
                  <h4>Module - Utilisateurs</h4>
                  <h2 class="mb-0">299 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Gestion agile : </b> Ajout et retrait d'utilisateurs simplifiés.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Sécurité améliorée : </b> Contrôle d'accès limité.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Collaboration simplifiée : </b>Gestion centralisée pour une collaboration sécurisée.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Suivi des activités : </b>Audit et surveillance des utilisateurs.</span>
                    </li>  
                  </ul> 
                   <button data-key="<?= $object->encrypt('10') ?>"  data-type="module"  class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>    
    <?php } ?> 
  </main>
   <!--modal-->
    <div class="modal fade" id="utilisateurModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg small">
            <form method="post" id="utilisateur_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout utilisateur </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_message"></span>

                        <div class="row"> 
                            
                            <h5 class="text-primary">Informations</h5>
 
                            <div class="col-md-4">
                                <label for="name" class="form-label">Nom & Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" required class="form-control form-control-sm"  placeholder="Nom & Prénom"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>  

                            <div class="col-md-4">
                                <label for="num" class="form-label">Numéro mobile</label>
                                <input type="text" name="num" id="num" class="form-control form-control-sm"  placeholder="+216 22 222 222"  data-parsley-minlength="8" data-parsley-maxlength="13" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm"  placeholder="Email"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <h5 class="text-primary">Information de connexion</h5>

                            <div class="col-md-4">
                                <label for="pseudo" class="form-label">Pseudo</label>
                                <input type="text" name="pseudo" id="pseudo" class="form-control form-control-sm"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-sm"  placeholder="******"   data-parsley-minlength="8" data-parsley-maxlength="18" data-parsley-trigger="keyup">
                            </div> 


                        </div>                        
                    </div>
                    <div class="modal-footer">
                      	<input type="hidden" name="hidden_id" id="hidden_id" />
              			<input type="hidden" name="action" id="action" value="Add" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<input type="submit" name="submit" id="submit_button" class="btn btn-sm  btn-primary" value="Add" />
              			
                    </div>
                  </div>
            </form>
        </div>
    </div>  

    
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalTitle" aria-hidden="true">
    	<div class="modal-dialog modal-xl" role="document" >
            <div class="modal-content">
              	<div class="modal-header">
                	<h5 class="modal-title" id="permissionModalTitle">Permission </h5>
                	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              	</div>
        	  	<div class="modal-body table-responsive-sm " >
                   <form action="">                     
                        <div class="card shadow mb-4  ">
                            <div class="card-header py-3 row">
                            	<div class="col-6" >
                                    <h5 class="text-primary" id="title_permission">Liste des permission</h5> 
                            	</div>
                            	<div class="col-6" align="right">
                            		 <button type="button" style="font-size: x-small;" class="btn btn-secondary btn-sm changeall"  data-what="enable"  >TOUS SÉLECTIONNER</button> / 
                            		 <button type="button" style="font-size: x-small;" class="btn btn-secondary btn-sm changeall"  data-what="disable" >TOUS DÉSÉLECTIONNER</button>
                            		 <input type="hidden" id="permission_user" >
                            	</div>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive  ">
    								<table class="table table-hover table-sm table-striped PermissionDataTable" id="permission_table" width="100%" cellspacing="0"  >
    									<thead>
    										<tr>
                                                <th>Page</th>
                                                <th>Accès à la page</th>
                                                <th>Ajouter</th>
                                                <th>Lire</th>
                                                <th>Modifier</th> 
                                                <th>Activer/Désactiver</th> 
    										</tr>
    									</thead>
    									<tbody   id="tbody_permission_table">
    									</tbody>
    								</table>
    							</div>
    						</div>				
                        </div>				
              	    
                    </form>
              	</div>
                  
              	<div class="modal-footer">
                	<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              	</div>
            </div>
    	</div>
    </div>
    
    <div class="modal fade" id="marketModal" tabindex="-1" role="dialog" aria-labelledby="marketModalTitle" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
              	<div class="modal-header">
                	<h5 class="modal-title" id="marketModalTitle">market </h5>
                	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              	</div>
        	  	<div class="modal-body table-responsive-sm " >
                   <form action="">                     
                        <div class="card shadow mb-4  ">
                            <div class="card-header py-3 row">
                            	<div class="col-6" >
                                    <h5 class="text-primary" id="title_market">Liste des market</h5> 
                            	</div>
                            	<div class="col-6" align="right">
                            		 <button type="button" style="font-size: x-small;" class="btn btn-secondary btn-sm marketAll"  data-what="enable"  >TOUS SÉLECTIONNER</button> / 
                            		 <button type="button" style="font-size: x-small;" class="btn btn-secondary btn-sm marketAll"  data-what="disable" >TOUS DÉSÉLECTIONNER</button>
                            		 <input type="hidden" id="market_user" >
                            	</div>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive  ">
    								<table class="table table-hover table-sm table-striped marketDataTable" id="market_table" width="100%" cellspacing="0"  >
    									<thead>
    										<tr>
                                                <th>Point de vente</th>
                                                <th>Accès</th> 
    										</tr>
    									</thead>
    									<tbody   id="tbody_market_table">
    									</tbody>
    								</table>
    							</div>
    						</div>				
                        </div>				
              	    
                    </form>
              	</div>
                  
              	<div class="modal-footer">
                	<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              	</div>
            </div>
    	</div>
    </div>
  
  <!-- End #main -->
    <?php
        include ('footer.php');
      ?>
      
<script>
  
$(document).ready(function(){ 
 
    
    $(document).on('click', '.market_button', function()
    {
		var id = $(this).data('id'); 
		
		$('#market_user').val($(this).data('id')); 

        $('#market_table').DataTable().destroy();

        $('#tbody_market_table').html('');

        $('#marketModal').modal('show');

        var dataTable = $('#market_table').DataTable({
			"language": { url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
				"processing" : true,
			"serverSide" : true,
			"searching": false,
			"paging": false,
			"info": false,
			"order" : [], 
			"ajax" : {
				url:"utilisateur-action",
				type:"POST",
				data:{
					id:id,   
					action:'market'
				}
			},
			"columnDefs":[
				{
					"targets":['_all'],
					"orderable":false,
				},
			],"drawCallback": function(settings) {
            //   $('.tablefoot').parent().parent().css({    'background-color': '#2e55a1', 'position': 'sticky' , 'bottom': 0});
            //   $('#fac_table th:nth-last-child(2)').attr('style', 'text-align:right !important;'); 
            },
		});
        
	});
    
    $(document).on('click', '.permission_button', function()
    {
		var id = $(this).data('id'); 
		
		$('#permission_user').val($(this).data('id')); 

        $('#permission_table').DataTable().destroy();

        $('#tbody_permission_table').html('');

        $('#permissionModal').modal('show');

        var dataTable = $('#permission_table').DataTable({
			"language": { url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
				"processing" : true,
			"serverSide" : true,
			"searching": false,
			"paging": false,
			"info": false,
			"order" : [], 
			"ajax" : {
				url:"utilisateur-action",
				type:"POST",
				data:{
					id:id,   
					action:'permission'
				}
			},
			"columnDefs":[
				{
					"targets":['_all'],
					"orderable":false,
				},
			],"drawCallback": function(settings) {
            //   $('.tablefoot').parent().parent().css({    'background-color': '#2e55a1', 'position': 'sticky' , 'bottom': 0});
            //   $('#fac_table th:nth-last-child(2)').attr('style', 'text-align:right !important;'); 
            },
		});
        
	});
    
  
	var dataTable = $('#utilisateur_table').DataTable({
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        },
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"utilisateur-action",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[ ],
				"orderable":false,
			},
		],
	});
	var dataTable_archive = $('#utilisateur_archive_table').DataTable({
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        },
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"utilisateur-action",
			type:"POST",
			data:{action:'fetch2'}
		},
		"columnDefs":[
			{
				"targets":[ ],
				"orderable":false,
			},
		],
	});
 

	$('#add_utilisateur').click(function(){
		
		$('#utilisateur_form')[0].reset();

		$('#utilisateur_form').parsley().reset();

    	$('#modal_title').text('Ajout utilisateur');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#utilisateurModal').modal('show');

    	$('#form_message').html('');
    	
    	$('.type').trigger("change");  

	});

 	$(".type").change(function(){
        
        if($("#part").is(":checked"))
        { 
            $('.hiddenrow').hide();
            
            $('#societe').val(null);

            $('#societe').prop('required',false);

            $('#name').prop('required',true);
            
        }else
        {
             $('.hiddenrow').show();

             $('#societe').prop('required',true);

             $('#name').prop('required',true);
        }
    });

	$('#utilisateur_form').parsley();

	$('#utilisateur_form').on('submit', function(event){
		event.preventDefault();
		if($('#utilisateur_form').parsley().isValid())
		{		
			$.ajax({
				url:"utilisateur-action",
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
						$('#submit_button').val('Ajouter');
					}
					else
					{
						$('#utilisateurModal').modal('hide');
						$('.DataTable').DataTable().ajax.reload(); 
                        notificationSwal.fire({
                            icon: 'success',
                            title: data.success,
                        });
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var id = $(this).data('id');
		
		$('#utilisateur_form')[0].reset();

		$('#utilisateur_form').parsley().reset();

		$('#form_message').html('');
        
		$.ajax({

	      	url:"utilisateur-action",

	      	method:"POST",

	      	data:{id:id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{ 
	        	
	        	$('#name').val(data.name); 
	        	
	        	$('#email').val(data.email);  
                
                $('#num').val(data.num);
                
                $('#pseudo').val(data.pseudo); 

	        	$('#modal_title').text('Modifier utilisateur');

	        	$('#action').val('Edit'); 

	        	$('#submit_button').val('Modifier'); 

	        	$('#utilisateurModal').modal('show');

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
                    url:"utilisateur-action",
                    method:"POST",
                    data:{id:id, action:'statut', statut:statut, next_statut:next_statut},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'utilisateur '+next_statut+'!',
                        });
                    }
                });
            }
        });   
        
  	});
  	
  	$(document).on("click", ".button_route", function(){

    	var route = $(this).data("route"); 
        var user = $(this).data("user"); 
        var etat = $(this).data("etat");
 
        var next_etat = "1";
        if(etat == "1")
        {
            next_etat = "2";
        }
        
        $.ajax({
            url:"utilisateur-action",
            method:"POST",
            data:{
                route:route,  
                user:user, 
                etat:etat, 
                next_etat:next_etat,
                action:"edit_route", 
                
            },
            success:function(data)
            { 
             	$('.PermissionDataTable').DataTable().ajax.reload(); 
            }
        });
  	    
  	});
  	
  	$(document).on("click", ".button_market", function(){

    	var market = $(this).data("market"); 
        var user = $(this).data("user"); 
        var etat = $(this).data("etat");
 
        var next_etat = "1";
        if(etat == "1")
        {
            next_etat = "2";
        }
        
        $.ajax({
            url:"utilisateur-action",
            method:"POST",
            data:{
                market:market,  
                user:user, 
                etat:etat, 
                next_etat:next_etat,
                action:"edit_market", 
                
            },
            success:function(data)
            { 
             	$('.marketDataTable').DataTable().ajax.reload(); 
            }
        });
  	    
  	});
  	
  	$(document).on("click", ".button_permission", function(){

    	var route = $(this).data("route");
        var crud = $(this).data("crud");
        var user = $(this).data("user"); 
        var statut = $(this).data("statut");
 
        var next_statut = "1";
        if(statut == "1")
        {
            next_statut = "2";
        }

        $.ajax({
            url:"utilisateur-action",
            method:"POST",
            data:{
                route:route, 
                crud:crud, 
                user:user, 
                statut:statut, 
                next_statut:next_statut,
                action:"edit_permission", 
                
            },
            success:function(data)
            { 
             	$('.PermissionDataTable').DataTable().ajax.reload(); 
            }
        }); 
        
  	});
      	
    $(document).on("click", ".changeall", function(){
        
		var what = $(this).data("what");
		 
		var user = $("#permission_user").val();

        $.ajax({
            url:"utilisateur-action",
            method:"POST",
            data:{
                what:what, 
                user:user, 
                action:"changeall"
                
            },
            success:function(data)
            {
                $('.PermissionDataTable').DataTable().ajax.reload(); 
            }
        });

		
	});
      	
    $(document).on("click", ".marketAll", function(){
        
		var what = $(this).data("what");
		 
		var user = $("#market_user").val();

        $.ajax({
            url:"utilisateur-action",
            method:"POST",
            data:{
                what:what, 
                user:user, 
                action:"marketAll"
                
            },
            success:function(data)
            {
                $('.marketDataTable').DataTable().ajax.reload(); 
            }
        });

		
	});
 

});
 
</script>