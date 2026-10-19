<?php 
    
    include ('header.php');
?>
 
<main id="main" class="main">
      <div class="pagetitle">
      <h1>Commandes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Commandes</li>
        </ol>
      </nav>
    </div> 
    
<?php if ($market->commande == '2'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des commandes</h6>
                        	</div> 
                        </div>
                    </div>
                    <div class="card-body"> 
                       <ul class="nav nav-tabs nav-tabs-bordered" id="myTab" role="tablist">
                             <li class="nav-item" role="presentation">
                                <button  class="nav-link active" data-table="dataTable" id="tab-tab" data-bs-toggle="tab" data-bs-target="#tabtab" type="button" role="tab" aria-controls="tab" aria-selected="true" >
                                   Tous
                                </button>
                            </li>
                            
                            <?php
                                $nb = 1 ;
                                $object->query="SELECT * FROM commande_etat WHERE statut = 'Activer' order by order_etat";
                                $result = $object->get_result();
                                foreach($result as $row){ 
                            ?>
                            
                                <li class="nav-item" role="presentation">
                                    <button  class="nav-link  " data-table="dataTable<?= $nb ?>" id="tab<?= $nb ?>-tab" data-bs-toggle="tab" data-bs-target="#tab<?= $nb ?>tab" type="button" role="tab" aria-controls="tab<?= $nb ?>" aria-selected="false" >
                                        
                                        <span class="badge rounded-pill bg-<?=  ($row['color']) ?> "><?= ucwords($row['libelle']) ?> ( <?= $object->countEtatTable('commande',$row['id'],$market->id); ?> ) </span> 
                                    </button>
                                </li>
                            <?php  
                                $nb++;
                                }
                            ?> 
                    
                        </ul>
                        <div class="tab-content pt-2" id="myTabContent">
                            <div class="tab-pane fade show active"  id="tabtab" role="tabpanel" aria-labelledby="tab-tab">
                                 
                                <div class="table-responsive">
                                    <table class="table table-striped   table-hover dataTable" id="dataTable" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th width="5%">Date</th>
                                                <th>Réf</th>
                                                <th>N° Table</th>
                                                <th>Total</th>
                                                <th>Nom & Prénom</th>
                                                <th>Tél</th>
                                                <th>E-mail</th>
                                                <th>Articles</th>  
                                                <!--<th>Ticket</th>-->
                                                <th>Etat</th>  
                                                <th>Action</th>  
                                            </tr>
                                        </thead>
                                        <tbody class=" bodytable">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php
                                $nb = 1 ;
                                $object->query="SELECT * FROM commande_etat WHERE statut = 'Activer'  order by order_etat";
                                $result = $object->get_result();
                                foreach($result as $row){ 
                            ?> 
                                <div class="tab-pane fade "  id="tab<?= $nb ?>tab" role="tabpanel" aria-labelledby="tab<?= $nb ?>-tab">
                                     
                                    <div class="table-responsive">
                                        <table class="table table-striped   table-hover   dataTable" id="dataTable<?= $nb ?>" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th width="5%">Date</th>
                                                    <th>Réf</th>
                                                    <th>N° Table</th>
                                                    <th>Total</th>
                                                    <th>Nom & Prénom</th>
                                                    <th>Tél</th>
                                                    <th>E-mail</th>
                                                    <th>Articles</th>  
                                                    <!--<th>Ticket</th>-->
                                                    <th>Etat</th>  
                                                    <th>Action</th>  
                                                </tr>
                                            </thead>
                                            <tbody class=" bodytable">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php 
                                $nb++;
                                }
                            ?> 
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
                  <h4>Module - Commandes</h4>
                  <h2 class="mb-0">399 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Commandes mobiles : </b> Les clients peuvent commander via leur téléphone.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Gestion simplifiée : </b>Facilite la gestion des commandes pour vous.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Automatisation :  </b>Simplifie le processus de commande, réduisant le besoin d'interventions manuelles.</span>
                    </li> 
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Suivi en temps réel :</b> Vous permet de suivre les commandes en temps réel pour une meilleure gestion.</span>
                    </li> 
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Historique des commandes:  </b> Offre un enregistrement des commandes précédentes pour une référence future.</span>
                    </li> 
                  </ul> 
                  <button data-key="<?= $object->encrypt('3') ?>"  data-type="module"  class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>    
    <?php } ?>  
</main>
     <div class="modal fade" id="etatModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog text-center ">
            <form method="post" id="etat_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="etatModaltitle" >Ajout etat </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_message_etat"></span>
                        
                        <?php 
                            $object->query="SELECT * FROM commande_etat WHERE statut = 'Activer'  order by order_etat";
                            $result = $object->get_result();
                            foreach($result as $row){ 
                        ?>
                        <fieldset class="row mb-3 justify-content-center  ">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="etat" id="etat<?= $row['id']; ?>" value="<?= $row['id']; ?>" >
                                    <label class="form-check-label" for="etat<?= $row['id']; ?>"><?= ucwords( $row['libelle']) ?></label>
                                </div>
                            </div>
                        </fieldset>
                        <?php }?> 

                    </div>
                    <div class="modal-footer">
                      	<input type="hidden" name="hidden_id_etat" id="hidden_id_etat" />
              			<input type="hidden" name="action_etat" id="action_etat" value="Add" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<input type="submit" name="submit" id="submit_button_etat" class="btn btn-sm  btn-primary" value="Add" />

                    </div>
                  </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="commandesModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <form method="post" id="commandes_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout commandes </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_message"></span>
                        <div class="row">
                            <h5 class="text-primary">Informations du Client</h5>
                            <div class="col-md-3">
                                <label for="name" class="form-label">Nom & Prénom  </label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm" disabled  data-parsley-trigger="keyup">
                            </div>
                            <div class="col-md-3 ">
                                <label for="email" class="form-label">E-mail </label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm" disabled  data-parsley-trigger="keyup">
                            </div>
                            <div class="col-md-3 ">
                                <label for="tel" class="form-label">Télephone  </label>
                                <input type="text" name="tel" id="tel" class="form-control form-control-sm" disabled  data-parsley-trigger="keyup">
                            </div>
                            <div class="col-md-3 ">
                                <label for="num_table" class="form-label">N° Table  </label>
                                <input type="text" name="num_table" id="num_table" class="form-control form-control-sm" disabled  data-parsley-trigger="keyup"> 
                            </div> 
                            <div class="col-md-12">
                                <hr>
                            </div> 
                            <h5 class="text-primary">Informations du commande <button style="position: absolute;    right: 30px;" type="button" name="add_more" id="add_more" class="btn btn-primary btn-sm"><i class="fal fa-plus"></i></button></h5> 
                            <span id="span_product_details"></span>
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
<?php
include('footer.php');
?>
<script>
$(document).ready(function(){
    
    $(document).on('click', ".viewTicket",function(){
         
        var id = $(this).data('id');
        $.redirect("ticket", {
            id: id
        }, "POST", "_blank");
         
    });
  
    $(document).on('click', '.nav-link', function(){
 
	    $('#'+$(this).data('table')).DataTable().ajax.reload(); 
      
    });

	var dataTable = $('#dataTable').DataTable({
        "language": { url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' },
		"processing" : true,
		"serverSide" : true,  
		"order" : [],
		"ajax" : {
			url:"commande-action",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[ ],
				"orderable":false,
			},
		] 
        
	});



    <?php 
        $nb = 1 ;
        $object->query="SELECT * FROM commande_etat WHERE statut = 'Activer'  order by order_etat";
        $result = $object->get_result();
        foreach($result as $row){ 
    ?>

	var dataTable<?= $nb ?> = $('#dataTable<?= $nb ?>').DataTable({
        "language": { url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' },
		"processing" : true,
		"serverSide" : true,  
		"order" : [],
		"ajax" : {
			url:"commande-action",
			type:"POST",
			data:{id:<?= $row['id'] ?>,action:'fetch<?= $nb ?>'}
		},
		"columnDefs":[
			{
				"targets":[ ],
				"orderable":false,
			},
		] 
        
	});
	
	<?php   
        $nb++;
        }
    ?> 
    
    $(document).on('click', '.view_etat', function(){
        var id = $(this).data("id");
        var etat = $(this).data("etat");

        
		$('#etat_form')[0].reset();
        
		$('#etat_form').parsley().reset();
        
        $('#hidden_id_etat').val(id);

        if(etat){
            $(`#etat${etat}`).prop("checked", true);
        }

    	$('#etatModaltitle').text('Modifier état');

    	$('#action_etat').val('edit_etat');

    	$('#submit_button_etat').val('Modifier');

    	$('#etatModal').modal('show');
    	
    	$('#form_message_etat').html('');
        
    });
    
    $('#etat_form').parsley();

    $('#etat_form').on('submit', function(event){
        event.preventDefault();
        if($('#etat_form').parsley().isValid())
        {
            $.ajax({
                url:"commande-action",
                method:"POST",
                data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
                success:function(data)
                { 
                    
					$('#submit_button_etat').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message_etat').html(data.error);
						$('#submit_button_etat').val('Modifier');
					}
					else
					{
						$('#etatModal').modal('hide');
						$('.dataTable').DataTable().ajax.reload(); 
                        notificationSwal.fire({
                            icon: 'success',
                            title: data.success,
                        }); 
					}
                }
            })
        }
    });
    
    	
	var count = 1;
	
    $(document).on('click', '#add_more', function(){
        count = count + 1;
        add_product_row(count);
    });
    
    $(document).on('click', '.remove', function(){
        var row_no = $(this).data("id");
        $('#row'+row_no).remove();
    });
	
    function add_product_row(count = '')
    {
        var html = `
            
            <span id="row`+count+`" class="row"> 
            
                <div class="col-md-8 ">
                    <label for="article" class="form-label">Article <span class="text-danger">*</span></label>
                    <select   name="article[]" id="article`+count+`"  class="form-control form-control-sm" required  data-parsley-trigger="keyup">
                    <option value="" disabled selected >Choisissez</option>
                        <?= $object->get_categories($market->id,'article') ?>
                    </select>
                </div>
                 
                <div class="col-md-3 ">
                    <label for="quantite" class="form-label">Quantite <span class="text-danger">*</span></label>
                    <input type="text" name="quantite[]" id="quantite`+count+`" class="form-control form-control-sm" required  data-parsley-trigger="keyup">
                </div> 
                <div class="col-md-1 ">
                    <label for="remove`+count+`" class="form-label">Action</label><br> 
                    <button type="button" name="remove" id="remove`+count+`"  data-id="`+count+`" class="btn btn-danger btn-sm remove"><i class="fal fa-minus"></i></button>
                </div>
            </span>`; 
            
            $('#span_product_details').append(html);

    }
    
    
	$('#commandes_form').parsley();

	$('#commandes_form').on('submit', function(event){
		event.preventDefault();
		if($('#commandes_form').parsley().isValid())
		{		
			$.ajax({
				url:"commande-action",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,  
				success:function(data)
				{ 
					$('#commandesModal').modal('hide');
					$('.dataTable').DataTable().ajax.reload(); 
                    notificationSwal.fire({
                        icon: 'success',
                        title: data.success,
                    }); 
				}
			})
		}
	});
    
	$(document).on('click', '.edit_button', function(){

		var id = $(this).data('id');
		
		$('#commandes_form')[0].reset();

		$('#commandes_form').parsley().reset();

		$('#form_message').html('');
		
		$('#span_product_details').html('');
        
		$.ajax({

	      	url:"commande-action",

	      	method:"POST",

	      	data:{id:id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{ 
	      	    
	        	$('#modal_title').text('Modifier Commande');

	        	$('#action').val('Edit');
	        	
	        	$('#name').val(data.name);
	        	
	        	$('#tel').val(data.tel);  
	        	
	        	$('#email').val(data.email);  
	        	
	        	$('#num_table').val(data.num_table);     
	        	
	        	$.each(data.ligne, function (index, value) { 
	        	    add_product_row(value.id);
	        	    $('#article'+value.id).val(value.article); 
	        	    $('#quantite'+value.id).val(value.quantite);
                });
	        	
 
	        	$('#submit_button').val('Modifier'); 
	        	
	        	$('#commandesModal').modal('show');

	        	$('#hidden_id').val(id);

	      	}

	    })

	});
        
        
	$(document).on('click', '.viewArticles', function(){
	    
		var id = $(this).data('id');

		$.ajax({

	      	url:"commande-action",

	      	method:"POST",

	      	data:{id:id, action:'fetch_swal'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
	      	    var ligne = '';
	      	    if(data.ligne){
    	      	     ligne =`<h3 class="text-primary">Informations de Commande</h3>
    	      	                <table class="table table-bordered table-sm table-hover table-striped">
    	      	                    <thead>
    	      	                        <tr>
    	      	                            <th>Article</th>
    	      	                            <th>Note</th>
    	      	                            <th>Quantité</th>
    	      	                            <th>Prix Unitaire </th>
    	      	                            <th>Total </th>
    	      	                        </tr>
    	      	                    </thead>
    	      	                    <tbody>
    	      	                    `;
    	      	    $.each(data.ligne, function (index, value) { 
    	      	        ligne += `
    	      	            <tr>
    	      	                 <td>`+value.article+`</td> 
    	      	                 <td>`+value.note+`</td> 
    	      	                 <td>`+value.quantite+`</td> 
    	      	                 <td>`+value.prix+`</td> 
    	      	                 <td>`+value.total+`</td> 
          	                </tr>
    	      	        ` ;
    	      	    });
    	      	    ligne += `</tbody>
    	      	            </table>
    	      	            `;
	      	    }
	      	    Swal.fire({
                    html:
                    '<h3 class="text-primary">Informations du Client</h3>'+
                    '<table class="  table table-bordered table-sm table-hover table-striped">'+
                    '<tr><th width="30%">Réference</th><td>' + data.reference + '</td></tr>' +
                    '<tr><th width="30%">Nom & Prénom</th><td>'+data.name+'</td></tr>'+
                    '<tr><th width="30%">N° Table</th><td>' + data.num_table + '</td></tr>' +
                    '<tr><th width="30%">Télephone</th><td>' + data.tel + '</td></tr>' + 
                    '<tr><th width="30%">E-mail</th><td>' + data.email + '</td></tr>' +
                    '</table>' +  
                    '<h3 class="text-primary">Informations de Paiement</h3>'+
                    '<table class="table table-bordered table-sm table-hover table-striped">'+
                    '<tr><th width="30%">Total Commande</th><td>'+data.total+'</td></tr>'+ 
                    '</table>'+ligne,
                    confirmButtonText: 'Fermer',
                    confirmButtonColor: '#6c757d',
                })
                setTimeout(function(){
                    $('.swal2-popup.swal2-modal.swal2-show').width("1000px");
                }, 10);
	      	}
      	});
	});  
        
});
</script>