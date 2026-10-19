<?php  
    include ('header.php');
    
    if($market->niveau == '4'){  
    
        if($_POST['categorie_sub_sub']){
            $categorie_sub_sub = json_decode($object->get_categorie($_POST['categorie_sub_sub'],'categorie_sub_sub'));
        }else{
            // echo '<script>window.location.href="niveau-4"</script>';
        } 
        
        if($_POST['categorie_sub']){
            $categorie_sub = json_decode($object->get_categorie($_POST['categorie_sub'],'categorie_sub'));
        }else{
            // echo '<script>window.location.href="niveau-4"</script>';
        } 
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            // echo '<script>window.location.href="niveau-4"</script>';
        }   
    } else if($market->niveau == '3'){   
        
        if($_POST['categorie_sub']){
            $categorie_sub = json_decode($object->get_categorie($_POST['categorie_sub'],'categorie_sub'));
        }else{
            // echo '<script>window.location.href="niveau-3"</script>';
        } 
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            // echo '<script>window.location.href="niveau-3"</script>';
        }     
    } else if($market->niveau == '2'){   
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            // echo '<script>window.location.href="niveau-3"</script>';
        }     
    }   

    $article = json_decode($object->get_categorie($_POST['article'],'article'));
?>
<style>
    .form-control {
        margin-bottom: 10px;
    }
    
    .breadcrumb-item span {
        color: #899bbd;
        font-size: 14px;
        font-weight: 400;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Ingredients</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <?php /*$if($market->niveau == '4'){ ?>
            <li class="breadcrumb-item"><a href="niveau-4">Point de vente </a></li> 
            <li class="breadcrumb-item categorie-sub-page" data-id="<?= $_POST['categorie_sub_sub'] ?>"><a href="#">Catégories </a></li> 
            <li class="breadcrumb-item categorie-page" data-id="<?= $_POST['categorie_sub'] ?>" data-categorie="<?= $_POST['categorie_sub_sub'] ?>"><a href="#">Sous Catégories </a></li>   
            <li class="breadcrumb-item article-page"   data-id="<?= $_POST['article'] ?>"><a href="#">Articles </a></li> 
          <?php }  ?>
          
          <?php if($market->niveau == '3'){ ?>
            <li class="breadcrumb-item" ><a href="niveau-3">Catégories </a></li> 
            <li class="breadcrumb-item categorie-page" data-id="<?= $_POST['categorie_sub'] ?>"><a href="#">Sous Catégories </a></li> 
          <?php }?>
          
          <?php if($market->niveau == '2'){ ?>
             <li class="breadcrumb-item " ><a href="niveau-2">Catégories </a></li> 
          <?php } */?>
             
          <li class="breadcrumb-item article-page"   data-id="<?= $_POST['categorie'] ?>"><a href="#">Articles </a></li> 
             <li class="breadcrumb-item active">Ingredients</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 small">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h6 class="m-0 font-weight-bold ">Liste des ingredient</h6>
                        	</div>
                        	<div class="col" align="right"> 
                        		 <button type="button" name="add_ingredient" id="add_ingredient" class="btn btn-primary  btn-sm"  ><i class="fal fa-plus"></i> Ajouter</button>
                         
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
                            
                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active fr-tab" id="fr-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-hover  DataTable" id="ingredient_table_fr" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th>Ordre</th>	
                                                <th>Libelle</th>
                                                <th>Prix</th>   
                                                <th>Statut</th> 
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
                                        <table class="table table-striped table-sm table-hover  DataTable" id="ingredient_table_<?= $row['code']?>" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Ordre</th>	
                                                    <th>Libelle</th> 
                                                    <th>Prix</th>    
                                                    <th>Statut</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                             <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
   <!--modal-->
    <div class="modal fade" id="ingredientModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg  small">
            <form method="post" id="ingredient_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout catégorie </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"  >
                        <span id="form_message"></span>

                        <div class="row">
                             
                            
                            <h5 class="text-primary">Article</h5>

                            <div class="col-md-4 ">
                                <label for="categorie" class="form-label">Article <span class="text-danger">*</span></label>
                                <select name="categorie" id="categorie" class="form-control form-control-sm" required  data-parsley-trigger="keyup">
                                    <?= $object->get_categories($market->id,'article' ); ?>
                                </select>
                            </div> 
                            
                            <div class="col-md-12">
                                <hr>
                            </div>
                             
                            <h5 class="text-primary">Français</h5>

                            <div class="col-md-6 ">
                                <label for="libelle" class="form-label">Libelle <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" id="libelle" class="form-control form-control-sm" required placeholder="libelle"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-6 ">
                                <label for="prix" class="form-label">Prix <span class="text-danger">*</span></label>
                                <input type="text" name="prix" id="prix" class="form-control form-control-sm" required placeholder="Prix"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            <?php 
                            if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                            
                            <h5 class="text-primary"><?= ucwords($row['libelle']) ?> </h5>
                            
                            <div class="col-md-6">
                                <label for="libelle<?= $row['code']?>" class="form-label">Libelle <span class="text-danger">*</span></label>
                                <input type="text" name="libelle<?= $row['code']?>" id="libelle<?= $row['code']?>" class="form-control form-control-sm" required placeholder="libelle"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-6">
                                <label for="prix<?= $row['code']?>" class="form-label">Prix <span class="text-danger">*</span></label>
                                <input type="text" name="prix<?= $row['code']?>" id="prix<?= $row['code']?>" class="form-control form-control-sm" required placeholder="Prix"  data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            <?php }}?>
                             
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
 
<?php include ('footer.php'); ?>
      
<script>
 

$(document).ready(function(){ 
    
    var lightbox = GLightbox({
        selector: '.glightbox'
    });
    
 
    
    $(document).on('click', '.nav-link', function(){
        $('.DataTable').DataTable().ajax.reload();
    });
  
    var dataTable_fr = $('#ingredient_table_fr').DataTable({
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
    		url:"niveau-0-action",
    		type:"POST",
    		data:{market:<?= $market->id ?>,categorie:<?= ($_POST['article']) ?>,action:'fetch'}
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

      		url:"niveau-0-action",

	      	method:"POST",

	      	data:{market:<?= $market->id ?>,categorie:<?= ($_POST['article']) ?>,id_table:id_table,newpos:newpos, action:'update_order'}, 

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
    
        var dataTable_<?= $row['code']?> = $('#ingredient_table_<?= $row['code']?>').DataTable({
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
    			url:"niveau-0-action",
    			type:"POST",
    			data:{market:<?= $market->id ?>,categorie:<?= ($_POST['article']) ?>,action:'fetch<?= $row['code']?>'}
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
    
          		url:"niveau-0-action",
    
    	      	method:"POST",
    
    	      	data:{market:<?= $market->id ?>,categorie:<?= ($_POST['article']) ?>,id_table:id_table,newpos:newpos, action:'update_order'}, 
    
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
  
	$('#add_ingredient').click(function(){
		
		$('#ingredient_form')[0].reset();

		$('#ingredient_form').parsley().reset();

    	$('#modal_title').text('Ajout ingredient');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#ingredientModal').modal('show');

    	$('#form_message').html('');   
		 
        $('#categorie').val(<?= $_POST['article'] ?>);
 
  
	});

    $('#icon').on('change',function(){
          var icon = $('#icon option:selected').data('icon') ; 
          
          if(icon){
            $('.iconLebel').html('<i style="font-size: xxx-large;" class="fa-lg fal fa-'+icon+'"></i>')
          }else{
              $('.iconLebel').html('')
          }
    })

	$('#ingredient_form').parsley();

	$('#ingredient_form').on('submit', function(event){
		event.preventDefault();
		if($('#ingredient_form').parsley().isValid())
		{		
 
		    
			$.ajax({
				url:"niveau-0-action",
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
						$('#ingredientModal').modal('hide');
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
		
		$('#ingredient_form')[0].reset();

		$('#ingredient_form').parsley().reset();

		$('#form_message').html('');  
 
        
		$.ajax({

	      	url:"niveau-0-action",

	      	method:"POST",

	      	data:{id:id,market:<?= $market->id ?>,categorie:<?= ($_POST['article']) ?>, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
	        	$('#libelle').val(data.libelle);  
	        	
	        	$('#prix').val(data.prix); 
	        	
               <?php 
                if($market->multi_langue == '1'){ 
                    $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                    $result = $object->get_result();
                    foreach($result as $row){
                ?>
                
                $('#libelle<?= $row['code']?>').val(data.libelle<?= $row['code']?>);
                
                $('#prix<?= $row['code']?>').val(data.prix<?= $row['code']?>); 
    	        	 
                <?php } } ?>
                  
                $('#categorie').val(data.categorie);
             
	            $('#modal_title').text('Modifier categorie');

	        	$('#action').val('Edit');

                $('#icon').trigger("change");

	        	$('#submit_button').val('Modifier'); 

	        	$('#ingredientModal').modal('show');

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
                    url:"niveau-0-action",
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
  	 
  	
           	$(document).on('click', '.article-page', function(){
                $.redirect("niveau-1", {
                     categorie:$(this).data('id')   
                }, "POST");  
            });
            
 
 
 
  	


});
 
</script>