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
      <h1>VIP</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <li class="breadcrumb-item active">VIP</li>
        </ol>
      </nav>
    </div> 
    
    <?php if ($market->vip == '2'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 small">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des accès VIP</h6>
                        	</div> 
                    		<div class="col" align="right">
                    		    <?php if($object->autorisation('c',$user->id,$page_actuel) == 2){?>
                        		<button type="button" name="add_vip" id="add_vip" class="btn btn-primary  btn-sm"><i class="fas fa-plus"></i> Ajouter</button>
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
                                    <table class="table table-striped table-sm table-hover  DataTable" id="vip_table" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr> 	
                                                <th>Code</th> 
                                                <th>Expiration</th> 
                                                <th>Date d'Expiration</th>  
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
                                    <table class="table table-striped table-sm table-hover  DataTable" id="vip_table2" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr> 	
                                                <th>Code</th> 
                                                <th>Expiration</th> 
                                                <th>Date d'Expiration</th>  
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
                  <h4>Module - VIP</h4>
                  <h2 class="mb-0">299 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Exclusivité pour les membres VIP : </b> Offre un contenu spécial et caché uniquement pour les membres privilégiés.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Incitation à l'adhésion : </b>Encourage les clients à devenir des membres VIP pour accéder à du contenu exclusif.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Marketing ciblé : </b>Vous permet de cibler directement vos membres VIP avec des promotions et des offres spéciales.</span>
                    </li> 
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Communauté engagée : </b> Favorise une communauté VIP active et engagée autour de votre marque.</span>
                    </li> 
                  </ul> 
                  <button data-key="<?= $object->encrypt('12') ?>"   data-type="module"  class="buy-button btn btn-custom">Acheter maintenant</button>
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
    <div class="modal fade" id="vipModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  small">
            <form method="post" id="vip_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout VIP </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" >
                        <span id="form_message"></span>

                        <div class="row">  

                            <div class="col-md-12">
                                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control form-control-sm" required placeholder="Code"  data-parsley-maxlength="10" data-parsley-trigger="keyup">
                                
                            </div>   

                            <div class="col-md-12 text-right" >  
                                <button class="btn-sm btn btn-dark " type="button" id="generate">Générer Code</button>
                            </div>   
                            
                            <div class="col-md-6 ">
                                <label for="expire" class="form-label">Expire ?<span class="text-danger">*</span></label>
                                <select name="expire" id="expire" class="form-control form-control-sm" required   data-parsley-trigger="keyup">
                                    <option value="1">Non</option>
                                    <option value="2">Oui</option>
                                </select>
                            </div> 
                            
                            <div class="col-md-6 expire_date">
                                <label for="expire_date" class="form-label">Date d'expiration <span class="text-danger">*</span></label>
                                <input type="date"  name="expire_date" id="expire_date" class="form-control form-control-sm"     data-parsley-trigger="keyup">
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

 
    
$(document).ready(function(){ 
  
	var dataTable = $('#vip_table').DataTable({
	       "language": {
        url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        }, 
		"processing" : true,
		"serverSide" : true,  
		"order" : [],
		"ajax" : {
			url:"vip-action",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[],
				"orderable":false,
			},
		], 
	});
  
	var dataTable2 = $('#vip_table2').DataTable({
	       "language": {
        url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        }, 
		"processing" : true,
		"serverSide" : true,  
		"order" : [],
		"ajax" : {
			url:"vip-action",
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
	
	$(document).on('change','#expire',function(){
	    
	    if($(this).val() == '1'){
	        $('.expire_date').hide();
	        $('#expire_date').attr('required',false);  
	    } else{
	        $('.expire_date').show();
	        $('#expire_date').attr('required',true);  
	    }
	});
	 

	$('#add_vip').click(function(){
		
		$('#vip_form')[0].reset();

		$('#vip_form').parsley().reset();

    	$('#modal_title').text('Ajout Accès VIP');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#vipModal').modal('show');

    	$('#form_message').html('');  
    	
    	$('#expire').trigger('change');

	}); 
  

	$('#vip_form').parsley();

	$('#vip_form').on('submit', function(event){
		event.preventDefault();
		if($('#vip_form').parsley().isValid())
		{		
		     
			$.ajax({
				url:"vip-action",
				method:"POST",
				data:new FormData(this),
				dataType:'JSON',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('Loading...');
				},
				success:function(data)
				{
				    console.log(data)
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Ajouter'); 
					}
					else
					{
						$('#vipModal').modal('hide');
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
		
		$('#vip_form')[0].reset();

		$('#vip_form').parsley().reset();

		$('#form_message').html(''); 

		$.ajax({

	      	url:"vip-action",

	      	method:"POST",

	      	data:{id:id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{   
	        	$('#code').val(data.code);

                $('#expire').val(data.expire); 

	        	$('#expire_date').val(data.expire_date);   
                
                $('#expire').trigger('change');

	        	$('#modal_title').text('Modifier Accès VIP');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Modifier');

	        	$('#vipModal').modal('show');

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
            		url:"vip-action",
    
            		method:"POST",
    
            		data:{id:id, action:'change_statut', statut:statut, next_statut:next_statut},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'Code '+next_statut+'!',
                        });
                    }
                });
            }
        });   
        
  	});
	
  	$(document).on('click','#generate',function() {
        const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const digits = '0123456789';
        const randomAlphabet = alphabet.split('').sort(() => Math.random() - 0.5).slice(0, 3).join('');
        const randomDigits = digits.split('').sort(() => Math.random() - 0.5).slice(0, 3).join('');
        const reference = randomAlphabet + randomDigits;
        $('#code').val(reference);
    });
 
});
  	
</script>