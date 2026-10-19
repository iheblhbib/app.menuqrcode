<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://fonts.cdnfonts.com/css/sanva" rel="stylesheet">
                
<script src="https://app.menuqrcode.tn/assets/js/number_format.js"></script>
<style>
    .cart-top {
        position: fixed; 
        left: 10px;
        bottom: 10px;
        z-index: 996;
        width: 55px;
        height: 55px;
        border-radius: 50px;
        transition: all 0.4s;
        border: 1px solid #cda45e;
        background-color: #0c0b09;
        font-size: 28px;
        color: #cda45e;
        line-height: 0;
        cursor:pointer;
    }
    
    .select-article{
        cursor:pointer;
    }
    
    h2#swal2-title, .swal2-styled, div#swal2-html-container{
        font-family: 'Sanva', sans-serif;
    }
    
    #quantite{
        box-sizing: border-box;
        width: auto;
        transition: border-color .1s,box-shadow .1s;
        border: 1px solid #d9d9d9;
        border-radius: 0.1875em;
        background: rgba(0,0,0,0);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.06), 0 0 0 3px rgba(0,0,0,0);
        color: black;
        font-size: 1.5em;
        text-align: center !important;
        width:100px;
    }
    
    .decrease_quantite, .increase_quantite{
        font-size: 1.5em;
    }
    
    .quantite_ligne{ 
        width: auto; 
        border:transparent; 
        background: transparent; 
        color: black;
         font-size: 1.4em;
        text-align: center !important;
        width:50px;
    }
    
    .decrease_quantite_ligne, .increase_quantite_ligne{
         font-size: 1.4em;
    }
    
    .card_counted{ 
        position: fixed;
        left: 50px; 
        bottom: 50px;
        font-weight: normal;
        width: 21px;
        height: 21px;
        text-align: center; 
        z-index: 999;
        font-size: 11px;
        border-radius: 50%;
        padding: 3px;
        color: #000;
        background-color: #c7a15e!important;
    }
 
    .text-minus{
        color: #0c0b09 !important;
    }
    
    .text-plus{
        color: #c7a15e !important;
    }
  
</style>

 <span class="card_counted">
     <?= $object->countPanierTemp($object->getIPAddress(),$market->id) ?>
 </span>
     <i class="fa-light fa-basket-shopping-simple cart-top show_cart d-flex align-items-center justify-content-center" data-market="<?= $market->id ?>"></i> 
<script>

    $(document).on('click','.select-article',function(){
      var libelle = $(this).data('libelle');
      var prix = $(this).data('prix');
        prix = (isNaN(prix) || typeof prix === 'undefined') ? 0 : prix ;
      var id = $(this).data('id');
       
      showProductDetails(libelle, prix, id);
    });
    
    
    function showProductDetails(libelle, prix, id) {
      Swal.fire({ 
        html: ` 
            <div class="text-center align-items-center justify-content-center" style="font-family: 'Sanva', sans-serif;">
                <h3 style="font-family: 'Sanva', sans-serif;" >${libelle}</h3>
                <p class="prix_quantite fs-4" data-prix="${ prix }">Prix :  ${prix} DT</p>
                <i class="decrease_quantite text-minus fa-duotone fa-circle-minus"></i> <input type="number" disabled id="quantite" min="1" value="1" required   /> <i class="increase_quantite text-plus fa-duotone fa-circle-plus"></i>
                <br><br>
                
                <textarea id="note" rows="2" class="form-control" placeholder="Note"></textarea>
            </div>`,
        showCancelButton: true,
        confirmButtonColor: '#c7a15e',
        cancelButtonColor: '#0c0b09',
        confirmButtonText: 'Ajouter au panier',
        cancelButtonText: 'Fermer', 
      }).then((result) => {
        if (result.isConfirmed) {
            
            var quantite = $('#quantite').val(); 
            var prix_unitaire = $('.prix_quantite').data('prix'); 
            var note = $('#note').val(); 
            var market = $('.show_cart').data('market');
            $.ajax({ 
    	      	url:"../commande",
    	      	method:"POST",
    	      	data:{
    	      	        market:market, 
    	      	        id:id,
    	      	        quantite:quantite,
    	      	        prix:prix,
    	      	        prix_unitaire:prix_unitaire,
    	      	        note:note, 
    	      	        action:'add_temp'},
    	      	dataType:'JSON',
    	      	success:function(data)
    	      	{ 
    	      	    if(data.error != '')
					{
					    Swal.fire({
                            icon: 'error',
                            title: data.error, 
                            confirmButtonColor: '#c7a15e',
                            confirmButtonText: 'Fermer'
                        });
					}
					else
					{
        	            Swal.fire({
                            icon: 'success',
                            title: data.success, 
                            confirmButtonColor: '#c7a15e',
                            confirmButtonText: 'Fermer'
                        });	
					}
                    
                    $('.card_counted').html(data.counted);
    	      	}
    	    })
          
        
        }
      });
    }
    
    
     
    $(document).on('click','.decrease_quantite',function(){
      const quantiteInput = document.getElementById('quantite');
      let quantite = parseFloat(quantiteInput.value, 10);
      if (quantite > 1) {
        quantite--;
        quantiteInput.value = quantite;
      }
      var prix_quantite = parseFloat($('.prix_quantite').data('prix'));
      $('.prix_quantite').html('Prix : '+(prix_quantite*quantite)+' DT' );
      
    });

    $(document).on('click','.increase_quantite',function(){
      const quantiteInput = document.getElementById('quantite');
      let quantite = parseFloat(quantiteInput.value, 10);
      quantite++;
      quantiteInput.value = quantite;
      
     var prix_quantite = parseFloat($('.prix_quantite').data('prix'));
      $('.prix_quantite').html('Prix : '+(prix_quantite*quantite)+' DT' );
    });
     
    $(document).on('click','.decrease_quantite_ligne',function(){
     
        var id = $(this).data('id'); 
        let quantite = parseFloat($('#quantite_ligne'+id).val());
      if (quantite > 0) {
        quantite--;
        $('#quantite_ligne'+id).val(quantite);
      }
      var prix_quantite = parseFloat($('#quantite_ligne'+id).data('prix'));
      $('#prix_ligne'+id).html( prix_quantite*quantite );
      totalPrix();
    });

    $(document).on('click','.increase_quantite_ligne',function(){
        var id = $(this).data('id'); 
        let quantite = parseFloat($('#quantite_ligne'+id).val());
      quantite++;
      $('#quantite_ligne'+id).val(quantite);
      
      var prix_quantite = parseFloat($('#quantite_ligne'+id).data('prix'));
      $('#prix_ligne'+id).html( prix_quantite*quantite );
      totalPrix();
    });
    
    
    $(document).on('click','.show_cart',function(){
        var market = $('.show_cart').data('market');
        $.ajax({ 
	      	url:"../commande",
	      	method:"POST",
	      	dataType:'JSON',
	      	data:{ action:'fetch_temp', market:market, },
	      	success:function(data)
	      	{ 
                Swal.fire({ 
                    html: generateTable(data),
                    showDenyButton: true,
                    showCancelButton: true,
                    showCancelButton: true,
                    showCloseButton: true,
                    denyButtonColor: '#c7a15e',
                    confirmButtonColor: '#c7a15e',
                    cancelButtonColor: '#0c0b09',
                    confirmButtonText: 'Valider <i class="fa-light fa-paper-plane"></i>',
                    cancelButtonText: 'Fermer <i class="fa-light fa-xmark"></i> ',
                    denyButtonText: 'Sauvegarder <i class="fa-light fa-floppy-disk"></i>',
                }).then((result) => { 
                    if (result.isConfirmed) {
                        
                        Swal.fire({
                            title: 'Confirmation',
                            html :          
                                        '<div style="text-align: left;" >' +
                                            '<div class="form-group">' +
                                            '   <label class="p-2" for="name">Nom & Prénom <span class="text-danger" >*</span> :</label>' +
                                            '   <input id="name" class="form-control" placeholder="Nom & Prénom">' +
                                            '</div>' +
                                            '<div class="form-group">' +
                                            '   <label class="p-2" for="tel">Téléphone :</label>' +
                                            '   <input id="tel" class="form-control" placeholder="Téléphone">' +
                                            '</div>' +
                                            '<div class="form-group">' +
                                            '   <label class="p-2" for="email">E-mail :</label>' +
                                            '   <input id="email" type="email" class="form-control" placeholder="E-mail">' +
                                            '</div>' +
                                            '<div class="form-group">' +
                                            '   <label class="p-2" for="table">N° Table :</label>' +
                                            '   <input id="table" class="form-control" placeholder="N° Table">' +
                                            '</div>'+
                                        '</div>',
                            showCancelButton: true,
                            confirmButtonColor: '#c7a15e',
                            cancelButtonColor: '#0c0b09',
                            confirmButtonText: 'Valider <i class="fa-light fa-paper-plane"></i>',
                            cancelButtonText: 'Fermer <i class="fa-light fa-xmark"></i> ',
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#name').value;
                                const tel = Swal.getPopup().querySelector('#tel').value;
                                const email = Swal.getPopup().querySelector('#email').value;
                                const table = Swal.getPopup().querySelector('#table').value;
                                   
                                if (name.trim() === '' ) {
                                    Swal.showValidationMessage('Nom & Prénom sont obligatoires');
                                    return false; // Prevent dialog from closing
                                }
                
                                return { name: name, tel: tel, email: email, table: table };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) { 
                                
                               var idArray = []; 
                                var quantityArray = [];
                                
                                $('.quantite_ligne').each(function() {  
                                    // Extract the required attributes 
                                    var id = $(this).data('id');
                                    var value = $(this).val();
                            
                                    // Push the data into respective arrays
                                    idArray.push(id); 
                                    quantityArray.push(value);
                                    
                                });
                                
                                $.ajax({ 
                        	      	url:"../commande",
                        	      	method:"POST",
                        	      	dataType:'JSON',
                        	      	data:{ 
                        	      	    name: result.value.name ,
                        	      	    tel: result.value.tel ,
                        	      	    email: result.value.email ,
                        	      	    table: result.value.table ,
                        	      	    market:market, 
                        	      	    idArray:idArray, 
                            	      	quantityArray:quantityArray, 
                            	      	action:'add_commande'
                        	      	},
                        	      	success:function(data)
                        	      	{ 
                        	      	    Swal.fire({
                                            icon: 'success',
                                            title: data.success, 
                                            text : 'Réf : '+data.reference,
                                            confirmButtonColor: '#c7a15e',
                                            confirmButtonText: 'Fermer'
                                        });	
                                        $('.card_counted').html(data.counted);
                        	      	}
                                });
                            }
                        });
                     
              
                    } else if (result.isDenied) {
                                                 
                        var idArray = []; 
                        var prixArray = []; 
                        var quantityArray = [];
                        
                        $('.quantite_ligne').each(function() {  
                            // Extract the required attributes 
                            var id = $(this).data('id');
                            var prix = $(this).data('prix');
                            var value = $(this).val();
                    
                            // Push the data into respective arrays
                            idArray.push(id); 
                            prixArray.push(prix); 
                            quantityArray.push(value);
                            
                        });
                        
                        $.ajax({ 
                	      	url:"../commande",
                	      	method:"POST",
                	      	dataType:'JSON',
                	      	data:{ 
                	      	    market:market, 
                	      	    idArray:idArray, 
                	      	    prixArray:prixArray, 
                    	      	quantityArray:quantityArray, 
                    	      	action:'update_temp'
                	      	},
                	      	success:function(data)
                	      	{ 
                	      	    Swal.fire({
                                    icon: 'success',
                                    title: data.success,  
                                    confirmButtonColor: '#c7a15e',
                                    confirmButtonText: 'Fermer'
                                });
                                $('.card_counted').html(data.counted);
                	      	}
                        });
                    }
                });	  
	      	}
	    })
    });
    
    function totalPrix(){
        var total = 0 ;
        $('.prix_ligne').each(function() {  
            var price = parseFloat($(this).html());
            total += price; 
        });
        $('#prix_total').html(total);
    }
    
    function generateTable(data) {
      let table = ` <h3 style="font-family: 'Sanva', sans-serif;" >Mon Panier</h3>
                    <div >
                    <table class="table table-sm table-striped  " style="font-size:0.8em;">
                        <thead>
                            <tr>  
                                <th width="50%" style="text-align: left;">Libelle</th>
                                <th width="25%" >Quantite</th>
                                <th width="25%" style="text-align: right;">Prix</th>  
                            </tr>
                        </thead>
                        <tbody>
                        `;
      var total = 0 ;               
      data.forEach(item => {
        table += `  <tr>  
                        <td style="text-align: left;">${item.libelle}</td>
                        <td style="white-space:nowrap;">
                            <i      data-id="${item.id}"  class="decrease_quantite_ligne text-minus fa-duotone fa-circle-minus"></i> 
                            <input  data-article="${item.article}" data-id="${item.id}"  data-prix="${item.prix_unitaire}"   id="quantite_ligne${item.id}"   type="number" disabled class="quantite_ligne" min="1" value="${item.quantite}" required   /> 
                            <i      data-id="${item.id}" class="increase_quantite_ligne text-plus fa-duotone fa-circle-plus"></i></td>
                        <td style="text-align: right;"> <span class="prix_ligne" id="prix_ligne${item.id}" data-id="${item.id}">${item.prix}</span> DT</td>  
                    </tr>`;
        total += parseFloat(item.prix) ;
      });
      
      total = number_format(total,2,'.','');
      
        table += `  </tbody>
                    <tfoot>  
                        <tr>  
                            <td> </td>
                            <th style="text-align: right;">Total :</th>
                            <th style="text-align: right;white-space:nowrap;"><span id="prix_total">${total}</span> DT</th>  
                        </tr>
                    </tfoot>
                 </table>
                 </div>`;
      return table;
    }

    
 
</script>
 