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
      <h1>Point de vente</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <li class="breadcrumb-item active">Point de vente</li>
          
            <li class="breadcrumb-item" style="position: absolute; right: 31px;"> 
             <button class="informations btn btn-sm btn-light  "   ><i class="far fa-info"></i> Informations</button> 
            </li>  
        
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des Point de vente</h6>
                        	</div>
                        	<div class="col" align="right"> 
                        	    <?php if($object->autorisation('c',$user->id,$page_actuel) == 2){?>
                        		    <button type="button" name="add_categorie" id="add_categorie" class="btn btn-primary  btn-sm"  ><i class="fal fa-plus"></i> Ajouter</button>
                        	    <?php }?>
                        	</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bordered"> 
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#fr-tab">Français</button>
                            </li> 
                            <?php 
                                if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                              <li class="nav-item"  >
                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#<?= $row['code']?>-tab"><?= $row['libelle']?></button>
                               </li>
                             <?php } }?>
                            
                            <li class="nav-item">
                                <button class="nav-link  " data-bs-toggle="tab" data-bs-target="#archive-tab"><i class="fa-light fa-box-archive"></i> Archive</button>
                            </li> 
                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active fr-tab" id="fr-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped  table-hover  DataTable" id="categorie_table_fr" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th>Ordre</th>	
                                                <th>Libelle</th>	
                                                <th>Image</th>	
                                                <th>Background</th>	
                                                <th>Statut</th>
                                                <th>Type</th>	
                                                <th>Lien Externe</th>	
                                                <th>Menu Interne</th>	
                                                <th>PDF</th>	
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            
                           <?php 
                           if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                                <div class="tab-pane fade <?= $row['code']?>-tab" id="<?= $row['code']?>-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped  table-hover  DataTable" id="categorie_table_<?= $row['code']?>" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Ordre</th>	
                                                    <th>Libelle</th>	
                                                    <th>Image</th>	
                                                    <th>Background</th>	
                                                    <th>Statut</th>
                                                    <th>Type</th>	
                                                    <th>Lien Externe</th>	
                                                    <th>Menu Interne</th>	
                                                    <th>PDF</th>	
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                             <?php } } ?>
                                <div class="tab-pane fade archive-tab" id="archive-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped  table-hover  DataTable" id="categorie_table_archive" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Ordre</th>	
                                                    <th>Libelle</th>	
                                                    <th>Image</th>	
                                                    <th>Background</th>	
                                                    <th>Statut</th>
                                                    <th>Type</th>	
                                                    <th>Lien Externe</th>	
                                                    <th>Menu Interne</th>	
                                                    <th>PDF</th>	
                                                    <th>Action</th>
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
</main>
   <!--modal-->
    <div class="modal fade" id="categorieModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg  ">
            <form method="post" id="categorie_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout catégorie </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_message"></span>

                        <div class="row">
 
                            
                            <h5 class="text-primary">Libelles</h5>

                            <div class="col-md-6 ">
                                <label for="libelle" class="form-label">Français <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" id="libelle" class="form-control form-control-sm" required placeholder="libelle"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <?php 
                            if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                            
                            <div class="col-md-6 ">
                                <label for="libelle<?= $row['code']?>" class="form-label"><?= $row['libelle']?> <span class="text-danger">*</span></label>
                                <input type="text" name="libelle<?= $row['code']?>" id="libelle<?= $row['code']?>" class="form-control form-control-sm" required placeholder="libelle"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <?php }}?>
                             

                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            
                                                
                            <h5 class="text-primary">Type </h5>

                            <div class="col-md-6">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" class="form-control form-control-sm"   data-parsley-trigger="keyup" > 
                                    <option value="1">Lien Externe</option>
                                    <option value="2">Menu Interne</option>
                                    <option value="3">PDF</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 external_link">
                                <label for="external_link" class="form-label">Lien</label>
                                <input  type="text"  name="external_link" id="external_link" class="form-control form-control-sm"   data-parsley-trigger="keyup" /> 
                            </div> 
                            
                            <div class="col-md-12 market_link">
                                <label for="market_link" class="form-label">Menu</label>
                                <select  name="market_link" id="market_link" class="form-control form-control-sm"   data-parsley-trigger="keyup"  > 
                                    <option value="">Aucun menu</option>
                                    <?= $object->get_markets_type($user->id,$market->id) ?>
                                </select>
                            </div> 
                            
                            <div class="col-md-12 pdf">
                                <label for="pdf" class="form-label">PDF</label>
                                <input type="file" name="pdf" id="pdf" class="form-control form-control-sm"  accept=".pdf"   data-parsley-trigger="keyup">
                                <span id="categorie_uploaded_pdf"></span>   
                            </div> 

                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                             <h5 class="text-primary">Graphiques</h5>

                           
                            <div class="col-md-8 ">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control form-control-sm files"   data-parsley-trigger="keyup">
                                <span id="categorie_uploaded_image"></span>   
                            </div> 
                            
                            <div class="col-md-4 ">
                                <label for="display_image" class="form-label">Afficher l'image</label>
                                <select name="display_image" id="display_image" class="form-control form-control-sm  "   data-parsley-trigger="keyup">
                                    <option value="2">Non</option>
                                    <option value="1">Oui</option>
                                </select> 
                            </div> 
                            
                            <br>
                            
                            <div class="col-md-8 ">
                                <label for="background" class="form-label">Arrière-plan</label>
                                <input type="file" name="background" id="background" class="form-control form-control-sm files"   data-parsley-trigger="keyup">
                                <span id="categorie_uploaded_background"></span>   
                            </div> 
                            <div class="col-md-4 ">
                                <label for="display_background" class="form-label">Afficher l'arrière-plan</label>
                                <select name="display_background" id="display_background" class="form-control form-control-sm  "   data-parsley-trigger="keyup">
                                    <option value="2">Non</option>
                                    <option value="1">Oui</option>
                                </select> 
                            </div>  
                            
                            <br>
                            <div class="col-md-6">
                                <label for="icon" class="form-label">Icône</label>
                                <select  name="icon" id="icon" class="form-control form-control-sm"  data-parsley-trigger="keyup"  > 
								    <?= $object->get_icones(); ?>
								</select>  
								<span class="iconLebel"></span>   
                             </div>  
                             
                             <?php if($market->vip == '2' ){?>
                                <h5 class="text-primary">Accès VIP</h5>
     
                                
                                <div class="col-md-4 ">
                                    <label for="vip" class="form-label">Accès VIP</label>
                                    <select name="vip" id="vip" class="form-control form-control-sm  "   data-parsley-trigger="keyup">
                                         <option value="1"> Non </option>
                                        <option value="2"> Oui </option> 
                                    </select> 
                                </div> 
                                <div class="col-md-4 code_vip">
                                    <label for="code_vip" class="form-label">Code VIP  <span class="text-danger">*</span></label>
                                    <select name="code_vip" id="code_vip" class="form-control form-control-sm  "   data-parsley-trigger="keyup">
                                            <?= $object->get_vip_code_market($market->id) ?>
                                    </select> 
                                </div> 
                            <?php }?>
                             
                             
                        </div>                        
                    </div>
                    <div class="modal-footer"> 
                        <input type="hidden" name="market" id="market"   value="<?= $market->id ?>"/>
                      	<input type="hidden" name="hidden_id" id="hidden_id" />
              			<input type="hidden" name="action" id="action" value="Add" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<button type="submit" name="submit" id="submit_button" class="btn btn-sm  btn-primary" >Add</button>
              			
                    </div>
                  </div>
            </form>
        </div>
    </div>  
 
<?php include ('footer.php'); ?>
      
<script>
 

$(document).ready(function(){
 
    
    var lightbox = GLightbox({
        selector: '.glightbox'
    });
    
    $(document).on('click', '.nav-link', function(){
        $('.DataTable').DataTable().ajax.reload();
    });
  
    var dataTable_fr = $('#categorie_table_fr').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : true,
    	"serverSide" : true,
    	"paging":false, 
    	"rowReorder": 
    	{
            selector: 'td:nth-child(1)'
        },
    	"order" : [],
    	"ajax" : {
    		url:"niveau-5-action",
    		type:"POST",
    		data:{market:<?= $market->id ?>,action:'fetch'}
    	},
    	"columnDefs":[
    		{
    			"targets":['_all'],
    			"orderable":false, 
    		},
    	],  
    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                
                $('.vip_line').parent().parent().addClass('table-primary');
    		}
    });

	dataTable_fr.on( 'row-reordered', function ( e, diff, edit ) {
    
        var id_table = new Array();
        
        var newpos = new Array();
        
        diff.forEach((item, index)=>{ 
            id_table.push($(item.oldData).data('id'));
            newpos.push(item.newPosition);
        });
        
        $.ajax({

      		url:"niveau-5-action",

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
  
    var dataTable_archive = $('#categorie_table_archive').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : true,
    	"serverSide" : true,
    	"paging":false, 
    	"rowReorder": 
    	{
            selector: 'td:nth-child(1)'
        },
    	"order" : [],
    	"ajax" : {
    		url:"niveau-5-action",
    		type:"POST",
    		data:{market:<?= $market->id ?>,action:'fetch_archive'}
    	},
    	"columnDefs":[
    		{
    			"targets":['_all'],
    			"orderable":false, 
    		},
    	],  
    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                
                $('.vip_line').parent().parent().addClass('table-primary');
    		}
    });

	dataTable_archive.on( 'row-reordered', function ( e, diff, edit ) {
    
        var id_table = new Array();
        
        var newpos = new Array();
        
        diff.forEach((item, index)=>{ 
            id_table.push($(item.oldData).data('id'));
            newpos.push(item.newPosition);
        });
        
        $.ajax({

      		url:"niveau-5-action",

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
	
    <?php 
        if($market->multi_langue == '1'){ 
            $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
            $result = $object->get_result();
            foreach($result as $row){
    ?>
    
        var dataTable_<?= $row['code']?> = $('#categorie_table_<?= $row['code']?>').DataTable({
    	   "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    		"processing" : true,
    		"serverSide" : true,
    		"paging":false, 
    		"rowReorder": 
    		{
                selector: 'td:nth-child(1)'
            },
    		"order" : [],
    		"ajax" : {
    			url:"niveau-5-action",
    			type:"POST",
    			data:{market:<?= $market->id ?>,action:'fetch<?= $row['code']?>'}
    		},
    		"columnDefs":[
    			{
    				"targets":['_all'],
    				"orderable":false, 
    			},
    		], 
    		"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                 $('.vip_line').parent().parent().addClass('table-primary');
    		}
    	});
    	
    	dataTable_<?= $row['code']?>.on( 'row-reordered', function ( e, diff, edit ) {
            
            var id_table = new Array();
            var newpos = new Array();
            
            diff.forEach((item, index)=>{
                id_table.push($(item.oldData).data('id'));
                newpos.push(item.newPosition);
            });
            
            $.ajax({
    
          		url:"niveau-5-action",
    
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
        
   <?php } }?>
      
    $('.files').on('change', function() {
        var fileInput = $(this);
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp)$/i;
        var files = fileInput.get(0).files;
    
        for (var i = 0; i < files.length; i++) {
          var file = files[i];
          var fileName = file.name;
          if (!allowedExtensions.test(fileName)) {
              Swal.fire({
                  icon: 'error',
                  title: 'Extension de fichier invalide.',
                  text: 'Seuls les fichiers JPG, JPEG, PNG et WebP sont autorisés.',
                }); 
            fileInput.val('');
            return;
          }
        }
      });
  
	$('#add_categorie').click(function(){
		
		$('#categorie_form')[0].reset();

		$('#categorie_form').parsley().reset();

    	$('#modal_title').text('Ajout categorie');

    	$('#action').val('Add');

    	$('#submit_button').html('Ajouter');

    	$('#categorieModal').modal('show');
    	
    	$('#vip').trigger("change");
    	
    	$('#type').trigger("change");

    	$('#form_message').html('');
    	
		$('#categorie_uploaded_pdf').html('');
    	
		$('#categorie_uploaded_background').html('');
		
		$('#categorie_uploaded_image').html(''); 
	});

    $('#icon').on('change',function(){
          var icon = $('#icon option:selected').data('icon') ; 
          
          if(icon){
            $('.iconLebel').html('<i style="font-size: xxx-large;" class="fa-lg fal fa-'+icon+'"></i>')
          }else{
              $('.iconLebel').html('')
          }
    })

    $(document).on('change','#vip',function(){ 
          
        if($(this).val() == '1' )
        { 
            $('.code_vip').hide();  
            $('#code_vip').attr('required',false);  
            
        }else
        {
            $('.code_vip').show(); 
            $('#code_vip').attr('required',true);  
        }
    });
    
    
 	$("#type").change(function(){
         
        if($(this).val() == '1' )
        { 
            $('.external_link').show(); 
            $('.market_link').hide(); 
            $('.pdf').hide(); 
        }
        else if($(this).val() == '2' )
        { 
            $('.external_link').hide(); 
            $('.market_link').show(); 
            $('.pdf').hide(); 
        }else if($(this).val() == '3' )
        {
            $('.external_link').hide(); 
            $('.market_link').hide(); 
            $('.pdf').show(); 
        }
    });

	$('#categorie_form').parsley();

	$('#categorie_form').on('submit', function(event){
		event.preventDefault();
		if($('#categorie_form').parsley().isValid())
		{		
			$.ajax({
				url:"niveau-5-action",
				method:"POST",
				data:new FormData(this),
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
						$('#form_message').html(data.error);
						$('#submit_button').html('Ajouter');
					}
					else
					{
						$('#categorieModal').modal('hide');
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
		
		$('#categorie_form')[0].reset();

		$('#categorie_form').parsley().reset();

		$('#form_message').html('');
		
		$('#categorie_uploaded_pdf').html('');
		
		$('#categorie_uploaded_background').html('');
		
		$('#categorie_uploaded_image').html('');
        
		$.ajax({

	      	url:"niveau-5-action",

	      	method:"POST",

	      	data:{id:id,market:<?= $market->id ?>, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
	        	$('#libelle').val(data.libelle);
	        	
	        	$('#code_vip').val(data.code_vip);
	        	
	        	$('#vip').val(data.vip);
	        	
	        	$('#type').val(data.type); 
	        	
	        	$('#external_link').val(data.external_link); 
	        	
	        	$('#market_link').val(data.market_link); 
	        	
	        	$('#display_image').val(data.display_image);  
	        	
	        	$('#display_background').val(data.display_background);  
	        	
	        	$('#icon').val(data.icon);
	        	
               <?php 
                if($market->multi_langue == '1'){ 
                    $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                    $result = $object->get_result();
                    foreach($result as $row){
                ?>
                $('#libelle<?= $row['code']?>').val(data.libelle<?= $row['code']?>);
                
                <?php } } ?>
                
                if(data.pdf) 
	        	    $('#categorie_uploaded_pdf').html('<a target="_blank" href="'+data.pdf+'" >Télécharger</a><input type="hidden" name="hidden_categorie_pdf" value="'+data.pdf+'" />');
	        	
	        	$('#categorie_uploaded_image').html('<img src="'+data.image+'" class="img-fluid img-thumbnail glightbox" width="75" height="75" /><input type="hidden" name="hidden_categorie_image" value="'+data.image+'" />');
	        	
	        	$('#categorie_uploaded_background').html('<img src="'+data.background+'" class="img-fluid img-thumbnail glightbox" width="75" height="75" /><input type="hidden" name="hidden_categorie_background" value="'+data.background+'" />');

	        	$('#modal_title').text('Modifier Catégorie');

	        	$('#action').val('Edit');

                $('#icon').trigger("change");

                $('#vip').trigger("change");
                
                $('#type').trigger("change");

	        	$('#submit_button').html('Modifier'); 

	        	$('#categorieModal').modal('show');

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
                    url:"niveau-5-action",
                    method:"POST",
                    data:{id:id,market:<?= $market->id ?>, action:'statut', statut:statut, next_statut:next_statut},
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

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');
        var etat = $(this).data('etat');
        var next_etat = '0';
        var affiche = 'reactivé' ;
        if(etat == '0')
        {
            affiche = 'supprimé ' ;
            next_etat = '1';
        }

        Swal.fire({
            title: 'Es-tu sûr?',
            text: "êtes-vous sûr de "+affiche+"!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, '+affiche+'!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"niveau-5-action",
                    method:"POST",
                    data:{id:id,market:<?= $market->id ?>, action:'etat', etat:etat, next_etat:next_etat},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'categorie '+affiche+'!',
                        });
                    }
                });
            }
        });   
        
  	});
  	
   	$(document).on('click', '.categorie_liste', function(){
        $.redirect("niveau-3", {categorie:$(this).data('id')}, "POST");  
    });
    
  	$(document).on("click", ".informations", function () {
     Swal.fire({
        title: 'Informations',
        html: `
          <div style="text-align:left;">
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-primary btn-sm me-2">
                <i class="fal fa-plus"></i> Ajouter
              </button>
              <span>Ajouter un nouveau</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-info btn-sm me-2">
                <i class="fa-duotone fa-list-tree"></i>
              </button>
              <span>Voir la liste des sous-éléments</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-success btn-sm me-2">Activer</button>
              <button type="button" class="btn btn-danger btn-sm me-2">Désactiver</button>
              <span>Changer le statut </span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-info btn-sm me-2">
                <i class="fa-duotone fa-copy"></i>
              </button>
              <span>Dupliquer (et son contenu)</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-warning btn-sm me-2">
                <i class="fa-duotone fa-sliders"></i>
              </button>
              <span>Modifier</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-success btn-sm me-2">
                <i class="fa-light fa-trash-undo"></i>
              </button>
              <button type="button" class="btn btn-danger btn-sm me-2">
                <i class="fa-light fa-trash-xmark"></i>
              </button>
              <span>Archiver ou désarchiver</span>
            </div>
          </div>
        `,
        showConfirmButton: false,
        width: 600
      });
  });


});
 
</script>