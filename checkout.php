<?php 

include('header.php'); 

 
?> 
<style>
    ul {
        list-style-type: none;
    }
    
    .btn-custom{
        color: #fff;
        background-color: #2c5199;
        border-color: #2c5199; 
    }
    
    .btn-custom:hover{
        color: #2c5199;
        background-color: #f1f4fa;
        border-color: #f5f8fe; 
    }
</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Abonnements</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li> 
            <li class="breadcrumb-item active">Abonnements</li>
        </ol>
        </nav>
    </div>
    
    <?php if($object->countKonnect($user->id)){ ?> 
        
        <div class="container"> 
          <form method="post" id="checkout_form" enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-8">
                <div class="accordion" id="accordionPayment">
                  <!-- Credit card -->
                  <div class="accordion-item mb-3">
                    <h2 class="h5 px-4 py-3 accordion-header d-flex justify-content-between align-items-center">
                      <div class=" w-100  "  > 
                        Détails de facturation <i class="fa-light fa-circle-info"></i>
                      </div> 
                    </h2>
                    <div  >
                      <div class="accordion-body">  
                        <div class="row">
                          <div class="col-lg-6">
                            <div class="mb-3">
                              <label class="form-label">Nom<span class="text-danger">*</span></label>
                              <input type="text" id="nom"  name="nom" class="form-control" required placeholder="Nom" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                          </div> 
                          <div class="col-lg-6">
                            <div class="mb-3">
                              <label class="form-label">Prénom <span class="text-danger">*</span></label>
                              <input type="text"  id="prenom"  name="prenom" class="form-control" required placeholder="Prénom" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                          </div> 
                          <div class="col-lg-12">
                            <div class="mb-3">
                              <label class="form-label">Nom de l’entreprise (facultatif)</label>
                              <input type="text"  id="societe"  name="societe" class="form-control"   placeholder="Menu QR Code" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                          </div> 
                          <div class="col-lg-12">
                            <div class="mb-3">
                              <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                              <input type="text"  id="tel"  name="tel"  class="form-control" required placeholder="+216 31 560 289" data-parsley-maxlength="8" data-parsley-trigger="keyup">
                            </div>
                          </div> 
                          <div class="col-lg-12">
                            <div class="mb-3">
                              <label class="form-label">Email <span class="text-danger">*</span></label>
                              <input type="email"  id="email"  name="email"  class="form-control" required placeholder="contact@menuqrcode.tn" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                          </div>  
                          <div class="col-lg-12">
                            <div class="mb-3">
                              <label class="form-label">Address <span class="text-danger">*</span></label>
                              <textarea  class="form-control"  id="address"  name="address"  placeholder="Bardo, Tunis" required rows="2" data-parsley-maxlength="150" data-parsley-trigger="keyup"></textarea >
                            </div>
                          </div>  
                        </div>
                      </div>
                    </div>
                  </div>
                   
                </div>
              </div>
              <!-- Right -->
              <div class="col-lg-4">
                <div class="card position-sticky top-0">
                  <div class="p-3 bg-light bg-opacity-10">
                    <h6 class="card-title mb-3">Votre commande</h6>
                    <div class=" justify-content-between mb-1 small">
                        <div class="table-responsive">
                           <table class="table table-sm    DataTable" id="checkout_table" width="100%" cellspacing="0" >
                                <thead>
                                    <tr>
                                        <th>Produit</th> 	
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    
                                </tbody>
                            </table>
                        </div> 
                    </div>  
                    <hr>
                    <div class="form-check mb-1 small">
                      <input class="form-check-input" type="radio" value="1" id="enligne" required checked>
                      <label class="form-check-label" for="enligne">
                        Paiement en ligne <img src="img/konnect-logo.png" width="100"/>
                      </label>
                    </div>
                    <hr>
                    <div class="form-check mb-1 small">
                      <input class="form-check-input" type="checkbox" value="1" id="tnc" required checked>
                      <label class="form-check-label" for="tnc">
                        J’ai lu et j’accepte les <a target="_blank" href="https://menuqrcode.tn/conditions-generales-dutilisation">Conditions générales d'utilisation</a>
                      </label>
                    </div>
                    <div class="form-check mb-3 small">
                      <input class="form-check-input" type="checkbox" value="1" id="subscribe" required checked>
                      <label class="form-check-label" for="subscribe">
                        Vos données personnelles seront utilisées pour le traitement de votre commande, vous accompagner au cours de votre paiement, et pour d’autres raisons décrites dans notre <a target="_blank" href="https://menuqrcode.tn/politique-de-confidentialite">politique de confidentialité.</a>
                      </label>
                    </div> 
                    <button class="btn btn-primary w-100 mt-2" id="submit_button" >Commander</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
      </div>
    <?php }else{?> 
        
        <div class="row justify-content-md-center"> 
    
            <div class="col-lg-6">
    
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Votre panier est actuellement vide.</h5>
                  <p>Impossible de commander si votre panier est vide.</p>
                </div>
              </div>
    
            </div>
          </div>
        
    <?php }?> 
      
      
</main>

<?php include('footer.php'); ?>

<script>

$(document).ready(function(){ 
    
    var dataTable_checkout = $('#checkout_table').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : false,
    	"serverSide" : true,
    	"paging":false,  
    	"info":false,  
    	"searching":false,  
    	"order" : [],
    	"ajax" : {
    		url:"abonnements-action",
    		type:"POST",
    		data:{ action:'fetch'}
    	},
    	"columnDefs":[
    		{
    			"targets":['_all'],
    			"orderable":false, 
    		},
    	],  
    	"drawCallback": function(settings) { 
            $('#checkout_table th:nth-last-child(1)').attr('style', 'text-align:right !important;');
            $('#checkout_table td:nth-last-child(1)').attr('style', 'text-align:right !important;');
		} 
    });
    
    $(document).on('click','.delete-checkout',function(){
        
        var id = $(this).data('id');
        
        Swal.fire({
            title: "Confirmation de suppression",
            text: "Êtes-vous sûr ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Non',
            confirmButtonText: 'Oui'
        }).then((result) => {
            if (result.isConfirmed) {
                
               $.ajax({ 
        	      	url:"abonnements-action",
        	      	method:"POST",
        	      	dataType:'JSON',
        	      	data:{ action:'delete' , id:id },
        	      	success:function(data)
        	      	{ 
        	      	    if(data.error != ''){
            	      	    Swal.fire({
                                icon: 'error',
                                title: data.error,
                            });
        	      	        
        	      	    }else{
            	      	    Swal.fire({
                                icon: 'success',
                                title: data.success,
                            });
        	      	        $('.card_counted').html(data.counted);
        	      	        $('.DataTable').DataTable().ajax.reload(); 
        	      	    }
        	      	}
                    
               });

            }
        });
        
    });
    
	$('#checkout_form').parsley();

	$('#checkout_form').on('submit', function(event){
		event.preventDefault();
		if($('#checkout_form').parsley().isValid())
		{		
		    var formData = new FormData(this);
		    
		    formData.append('action', 'paiement'); 
		    
			$.ajax({
			    url:"abonnements-action",
				method:"POST",
				data:formData,
				dataType:'json',
                contentType:false,
                processData:false, 
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').html('<i class="fa-duotone fa-spinner fa-spin"></i>');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
					    Swal.fire({
                            icon: 'error',
                            title: data.error,
                        });
						$('#submit_button').html('Commander');
					}
					else
					{ 
                        Swal.fire({
                            icon: 'success',
                            title: data.success,
                        });
                        
                         window.open(data.lien, '_blank'); 
                         
                        setTimeout(function(){ 
                            window.location.href="checkout"
                        }, 1000); 
					}
				}
			})
		}
	});
	
});
</script>