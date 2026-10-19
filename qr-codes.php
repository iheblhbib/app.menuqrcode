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
      <h1>Liste QR Code</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <li class="breadcrumb-item active">Liste QR Code</li>
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
                        		<h6 class="m-0 font-weight-bold ">Liste des QR Code</h6>
                        	</div> 
                        </div>
                    </div>
                    <div class="card-body"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-sm table-hover  DataTable" id="qrcode_table" width="100%" cellspacing="0" >
                                <thead>
                                    <tr>
                                        <th>Libelle</th>	
                                        <th>URL</th>	
                                        <th>QR Code</th>	
                                        <th>Point de vente</th>	
                                        <th>N° Accès</th> 
                                        <th>Statut</th>
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
</main> 

<?php include ('footer.php'); ?>
      
<script>
 

$(document).ready(function(){ 
    
    var lightbox = GLightbox({
        selector: '.glightbox'
    });
 
  
    var dataTable_fr = $('#qrcode_table').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : true,
    	"serverSide" : true, 
    	"order" : [],
    	"ajax" : {
    		url:"qr-codes-action",
    		type:"POST",
    		data:{action:'fetch'}
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
    
    $(document).on('click','.markets',function(){
        
        var id = $(this).data('id');
        
        $.ajax({
            url:"qr-codes-action",
            method:"POST", 
            data:{
                id:id,  
                action:"fetch_market"
            },
            success:function(data)
            { 
                  var  html = '<select data-container="body" id="list-markets" name="list-markets[]"  class="form-control   " multiple>'+data+'</select>';
                 
                
                   Swal.fire({
                        title: 'Point de vente',
                        html: html,
                        icon: 'question',
                        confirmButtonColor: '#0d6efd',
                        showCancelButton: true,
                        cancelButtonColor: '#6c757d',
                        cancelButtonText: 'Fermer',
                        confirmButtonText: 'Sauvegarder',

                    }).then((result) => {
                        if (result.isConfirmed) {
                            
                            var markets = $('#list-markets').val(); 

                            $.ajax({

                                url:"qr-codes-action",

                                method:"POST",

                                data:{id:id,markets:markets, action:'update'},

                                dataType:'JSON',

                                success:function(data)
                                { 
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.success+'!'
                                    })
                                }
                            })
                        }
                    });
                        
                    setTimeout(function(){ 
                        $('#list-markets').selectpicker();
                    }, 10);
            }
        });

        
    });
 
 
  	
  	


});
 
</script>