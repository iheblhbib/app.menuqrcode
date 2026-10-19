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
      <h1>Langue</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <li class="breadcrumb-item active">Langue</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <?php if ($market->multi_langue == '1'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4 small">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des langue</h6>
                        	</div> 
                        </div>
                    </div>
                    <div class="card-body">  
                        <div class="table-responsive">
                            <table class="table table-striped table-sm table-hover  DataTable" id="langue_table" width="100%" cellspacing="0" >
                                <thead>
                                    <tr>
                                        <th>Langue</th>
                                        <th>Code</th> 
                						<th width="10%">Etat</th> 
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
    </section>
<?php }else{ ?>  
    
 

      <section class="section" > 
        <div role="tabpanel" class="tab-pane" id="monthly">
          <div class="row justify-content-center">
            <div class="col-md-4 ">
              <div class="price-item text-center">
                <div class="price-top">
                  <h4>Module - Multi Langue</h4>
                  <h2 class="mb-0">299 DT <sup> TTC</sup></h2>
                  <span class="text-capitalize">à vie</span>
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Accessibilité internationale : </b>Permet de servir les clients de différentes langues et nationalités.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Élimination des barrières linguistiques :</b> Facilite la compréhension des plats et des options.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Précision des informations : </b>Assure des traductions précises pour une meilleure compréhension.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-close mr-2"></i>
                      <span><b>Mise à jour facile : </b>Permet de mettre à jour rapidement les informations du menu dans différentes langues.</span>
                    </li> 
                  </ul> 
                  <button data-key="<?= $object->encrypt('4') ?>" data-type="module" class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>      
    <?php } ?>    
</main>
  
<?php
    include ('footer.php');
  ?>
      
<?php if ($market->multi_langue == '1'){ ?>
    <script> 
        $(document).ready(function(){ 
      
    	var dataTable = $('#langue_table').DataTable({
            "language": {
                url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
            },
    		"processing" : true,
    		"serverSide" : true,
    		"order" : [],
    		"ajax" : {
    			url:"langue-action",
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
     
      
    
    	$(document).on('click', '.etat_button', function(){
    
        	var id = $(this).data('id');
            var etat = $(this).data('etat');
            var next_etat = '1';
            var next_etat_text = 'Activer';
            if(etat == '1')
            {
                next_etat = '2';
                next_etat_text = 'Désactiver';
            }
    
            Swal.fire({
                title: 'Es-tu sûr?',
                text: "êtes-vous sûr de "+next_etat_text+"!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Fermer',
                confirmButtonText: 'Oui, '+next_etat_text+'!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"langue-action",
                        method:"POST",
                        data:{id:id, action:'etat', etat:etat, next_etat:next_etat},
                        success:function(data){
                            $('.DataTable').DataTable().ajax.reload();
                            notificationSwal.fire({
                                icon: 'success',
                                title: 'langue '+next_etat_text+'!',
                            });
                        }
                    });
                }
            });   
            
      	});
     
    
    }); 
    </script> 
 <?php } ?>    