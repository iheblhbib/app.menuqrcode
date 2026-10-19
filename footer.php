 
         <!--<span class="card_counted">-->
         <!--    <?= $object->countPanierKonnect($_SESSION['id'],$_SESSION['market']) ?>-->
         <!--</span>-->
         
         <!--<i class="fa-light fa-basket-shopping-simple cart-konnect show_cart d-flex align-items-center justify-content-center"  ></i>    -->
           
           <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color:#ffffffd4;color: black;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Prêt à partir?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                
                        </div>
                        <div class="modal-body">Sélectionnez «Déconnexion» ci-dessous si vous êtes prêt à mettre fin à votre session en cours.</div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-secondary" type="button" data-bs-dismiss="modal">Annuler</button>
                            <a class="btn  btn-sm btn-primary" href="logout">Déconnexion</a>
                        </div>
                    </div>
                </div>
            </div>
            
      <audio id="alert-sound" src="assets/mp3/ring_1.mp3" preload="auto"></audio>
      
           
    <div class="modal fade" id="profilModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  small">
            <form method="post" id="profil_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" > Profil </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_message"></span>

                        <div class="row"> 

                            <div class="col-md-12 ">
                                <label for="name_profil" class="form-label">Nom et prénom <span class="text-danger">*</span></label>
                                <input type="text" name="name_profil" id="name_profil" class="form-control form-control-sm" required value="<?= $user->name ?>" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div> 
                            
                             <div class="col-md-12">
                                <br>
                            </div>

                            <div class="col-md-6 ">
                                <label for="num_profil" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="num_profil" id="num_profil" class="form-control form-control-sm" required value="<?= $user->num ?>" data-parsley-minlength="8" data-parsley-maxlength="13" data-parsley-trigger="keyup">
                            </div> 

                            <div class="col-md-6">
                                <label for="email_profil" class="form-label">E-mail <span class="text-danger">*</span></label>
                                <input type="email" name="email_profil" id="email_profil" class="form-control form-control-sm" required value="<?= $user->email ?>" d  data-parsley-trigger="keyup">
                            </div>   
                            
                            
                        </div>                        
                    </div>
                    <div class="modal-footer">   
              			<input type="hidden" name="action_profil" id="action_profil" value="update" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<button type="submit" name="submit" id="submit_button_profil" class="btn btn-sm  btn-primary" >Modifier</button>
              			
                    </div>
                  </div>
            </form>
        </div>
    </div>  
           
    <div class="modal fade" id="passwordModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  small">
            <form method="post" id="password_form" enctype="y/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" > Mot de passe </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form-message-password"></span>

                        <div class="row">  
                            <div class="col-md-12">
                                <label for="old-password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="old-password" name="old-password" required data-parsley-trigger="keyup">
                            </div> 
                            
                            <div class="col-md-12">
                                <label for="new-password" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="new-password" name="new-password" required data-parsley-trigger="keyup">
                            </div> 

                            <div class="col-md-12">
                                <label for="renewpassword_profil" class="form-label">Confirmez le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required data-parsley-trigger="keyup" data-parsley-equalto="#new-password"> 
                            </div>  
                        </div>                        
                    </div>
                    <div class="modal-footer">   
              			<input type="hidden" name="action_password" id="action_password" value="update" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<button type="submit" name="submit" id="submit_button_password" class="btn btn-sm  btn-primary" >Modifier</button> 
                    </div>
                  </div>
            </form>
        </div>
    </div>  
            
      <footer  class="footer">
        <div class="copyright">
          &copy; Copyright <strong><span><a  href="https://www.menuqrcode.tn" target="_blank">Menu QR Code</a></span></strong>. All Rights Reserved
        </div>
        <div class="credits"> 
          <a href="https://www.facebook.com/menuqrcodetun" target="_blank"><i class="fab fa-facebook"></i></a>
          <a href="https://www.instagram.com/menu_qr_code/" target="_blank"><i class="fab fa-instagram"></i></a> 
        </div>
      </footer><!-- End Footer -->
        
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        
        <div id="message"></div>
        <div id="messagefailed"></div>
        
        <script src="vendor/jquery/jquery-3.6.0.min.js" ></script>
        
        <!-- Vendor JS Files -->
        
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>
        <script src="assets/vendor/quill/quill.min.js"></script>
        <script src="assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="assets/vendor/chart.js/chart.min.js"></script>
        <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="assets/vendor/echarts/echarts.min.js"></script>

        
        <script src='vendor/fullcalendar/packages/core/main.js'></script>
        <script src='vendor/fullcalendar/packages/daygrid/main.js'></script>
        <script src='vendor/fullcalendar/packages/timegrid/main.js'></script>
        <script src='vendor/fullcalendar/packages/list/main.js'></script>
        <script src='vendor/fullcalendar/packages/interaction/main.js'></script>
        <script src='vendor/fullcalendar/packages/core/locales-all.js'></script>
        
        <!--data table bootstrap 5--> 
        <script type="text/javascript" src="vendor/datatables/datatables/1.13.2/jquery.dataTables.js"></script>
        <script type="text/javascript" src="vendor/datatables/datatables/1.13.2/dataTables.bootstrap5.js"></script>
        <script type="text/javascript" src="vendor/datatables/buttons/2.2.2/dataTables.buttons.js"></script>
        <script type="text/javascript" src="vendor/datatables/buttons/2.2.2/buttons.html5.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="vendor/datatables/fixedheader/3.2.1/dataTables.fixedHeader.js"></script>
        <script type="text/javascript" src="vendor/datatables/rowgroup/1.2.0/dataTables.rowGroup.js"></script>
        <script type="text/javascript" src="vendor/datatables/rowreorder/1.2.7/dataTables.rowReorder.js"></script>
        <script type="text/javascript" src="vendor/datatables/responsive/2.2.9/js/dataTables.responsive.js"></script>
        <script type="text/javascript" src="vendor/datatables/select/1.6.0/js/dataTables.select.js"></script>
        <script type="text/javascript" src="vendor/datatables/plug-ins/1.13.2/pagination/scrolling.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        
        <script type="text/javascript" src="vendor/parsley/dist/parsley.min.js"></script>
            
        <!--/*******************************************-->
        <!--                     français               -->
        <!--********************************************/-->
        <script type="text/javascript" src="vendor/parsley/dist/i18n/fr.js"></script>
            
        
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
        
    	<script src="https://code.jquery.com/ui/1.13.0-rc.3/jquery-ui.min.js" integrity="sha256-R6eRO29lbCyPGfninb/kjIXeRjMOqY3VWPVk6gMhREk=" crossorigin="anonymous"></script>
    	<script src="vendor/jquery/jquery.redirect/jquery.redirect.js"></script>        
        <script src="assets/js/number_format.js"></script>  
        <script src="vendor/jquery/jquery-sortable.js"></script>  
        <!-- Template Main JS File -->

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <script src="assets/js/main2.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.1/fabric.min.js"></script>
 
 
        <script>
            $(document).ready(function(){ 
                
                $(document).on('click','#go-profil',function(){ 
                    
            		$('#profil_form')[0].reset();
            
            		$('#profil_form').parsley().reset();   
            
                	$('#profilModal').modal('show');
                });
                
                $(document).on('click','#go-password',function(){ 
                    
            		$('#password_form')[0].reset();
            
            		$('#password_form').parsley().reset(); 
            		
            		$('#form-message-password').html( '');
            		
            	    $('#submit_button_password').html('Modifier');
            		 
            	    $('#submit_button_password').attr('disabled', false);
            
                	$('#passwordModal').modal('show');
                });
                
            	$('#profil_form').parsley();
    
            	$('#profil_form').on('submit', function(event){
            		event.preventDefault();
            		if($('#profil_form').parsley().isValid())
            		{		
            			$.ajax({
            				url:"profil-action",
            				method:"POST",
            				data:new FormData(this),
            				dataType:'json',
                            contentType:false,
                            processData:false, 
            				beforeSend:function()
            				{
            					$('#submit_button_profil').attr('disabled', 'disabled');
            					$('#submit_button_profil').html('<i class="fa-duotone fa-spinner fa-spin"></i>');
            				},
            				success:function(data)
            				{
            				    $('#submit_button_profil').html('Modifier');
            					$('#submit_button_profil').attr('disabled', false);
            					if(data.error != '')
            					{
            						notificationSwal.fire({
                                        icon: 'error',
                                        title: data.error,
                                    });
            						
            					}
            					else
            					{
            						$('#profilModal').modal('hide'); 
                                    notificationSwal.fire({
                                        icon: 'success',
                                        title: data.success,
                                    });
            					}
            				}
            			})
            		}
            	});
                
            	$('#password_form').parsley();
    
            	$('#password_form').on('submit', function(event){
            		event.preventDefault();
            		if($('#password_form').parsley().isValid())
            		{		
            			$.ajax({
            				url:"profil-action",
            				method:"POST",
            				data:new FormData(this),
            				dataType:'json',
                            contentType:false,
                            processData:false, 
            				beforeSend:function()
            				{
            					$('#submit_button_password').attr('disabled', 'disabled');
            					$('#submit_button_password').html('<i class="fa-duotone fa-spinner fa-spin"></i>');
            				},
            				success:function(data)
            				{ 
            				    $('#submit_button_password').html('Modifier');
            					$('#submit_button_password').attr('disabled', false);
            					if(data.error != '')
            					{ 
                                    $('#form-message-password').html(`<div class="alert alert-danger " >${data.error}</div> `);
            					}
            					else
            					{
            						$('#passwordModal').modal('hide'); 
                                    notificationSwal.fire({
                                        icon: 'success',
                                        title: data.success,
                                    });
            					}
            				}
            			})
            		}
            	});
        	});
        	
        	$(document).on('click','.menu-link',function(){
        	    
        	    var iframe = ' <iframe height="800" style="border: 2px solid #cda45e;border-radius: 7px;" src="'+$(this).data('link')+'"></iframe>';
        	    
                $('#menuModalContent').html('');
                
                $('#menuModalContent').append(iframe);
                
                $('#menuModal').modal('show'); 
        	});
        	
        	
        	
                	    
            function generateTable(data) {
              let table = ` <h3 style="font-family: 'Sanva', sans-serif;" >Mon Panier</h3>
                            <div >
                            <table class="table table-sm table-striped  " style="font-size:0.8em;">
                                <thead>
                                    <tr>  
                                        <th width="75%" style="text-align: left;">Produit</th> 
                                        <th width="25%" style="text-align: right;">Prix</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                `;
              var total = 0 ;               
              data.forEach(item => {
                table += `  <tr>  
                                <td style="text-align: left;">${item.produit}</td> 
                                <td style="text-align: right;"> ${item.tarif}</span> </td>  
                            </tr>`;
                total += parseInt(item.prix) ;
              });
              
                table += `  </tbody>
                            <tfoot>  
                                <tr>   
                                    <th style="text-align: right;">Total :</th>
                                    <th style="text-align: right;"><span id="prix_total">${total}</span> DT</th>  
                                </tr>
                            </tfoot>
                         </table>
                         </div>`;
              return table;
            }
            
     $(document).on('click','.show_cart',function(){ 
        $.ajax({ 
	      	url:"abonnements-action",
	      	method:"POST",
	      	dataType:'JSON',
	      	data:{ action:'fetch_konnect'  },
	      	success:function(data)
	      	{ 
                Swal.fire({ 
                    html: generateTable(data),
                    showDenyButton: true,
                    showCancelButton: true,
                    showCancelButton: true,
                    showCloseButton: true,
                    denyButtonColor: '#2c5199',
                    confirmButtonColor: '#2c5199',
                    cancelButtonColor: '#d1e0fd',
                    confirmButtonText: 'Commander',
                    cancelButtonText: 'Fermer',
                    denyButtonText: 'Poursuivre les achats',
                }).then((result) => { 
                    if (result.isConfirmed) {
                         window.location.href="checkout";
                    } else if (result.isDenied) {
                         swal.close() 
                    }
                });	  
	      	}
	    })
    });
    
    $(document).on('click','.delete-panier',function(){
        
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
        	      	        
        	      	        setTimeout(function(){  
        	      	            $('.show_cart').trigger('click');
                            }, 1000);
        	      	    }
        	      	}
                    
               });

            }
        });
        
    });
        $(document).on('click','.buy-button',function(){
            
            var key = $(this).data('key');
            
            var type = $(this).data('type');
            
            
            
            Swal.fire({
                title: "Confirmation d'achat",
                text: "Êtes-vous sûr d'acheter ce "+type+" ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Fermer',
                confirmButtonText: 'Oui !'
                }).then((result) => {
                if (result.isConfirmed) {
                    
                   $.ajax({ 
            	      	url:"abonnements-action",
            	      	method:"POST",
            	      	dataType:'JSON',
            	      	data:{ action:'add_produit' , type:type , key:key },
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
            	      	        
            	      	        setTimeout(function(){  
            	      	            $('.show_cart').trigger('click');
                                }, 1000);
            	      	    }
            	      	}
                        
                   });

                }
            });  
	      	    
        });
        
                                		
        $.ajax({
    		url:"api/alert",
    		method:"POST",
    		data: {client : <?= $_SESSION['id'] ?> },
    		dataType:'json',      
    		success:function(reponse)
    		{ 
    		   if(reponse.status){
    		       if(reponse.data.forced == '1'){
        		        Swal.fire({
                            icon: 'warning',
                            html: reponse.data.texte,
                            confirmButtonText : 'Fermer',
                            confirmButtonColor : '#dc3545',
                        }); 
    		        }else{
    		            Swal.fire({ 
                            icon: 'warning',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            html: reponse.data.texte,
                        }); 
    		       }
    		   }
    		}
    	});
            
        </script>
        
        <div class="modal fade" id="menuModal"  >
            <div class="modal-dialog   small"> 
                  <div class="modal-content" id="menuModalContent">
          		</div> 
          	</div>
        </div>
        
        
        <?php if ($market->commande == '2'){ ?>
            <script>
                 
                setInterval(function() {
                    displayReminder();
                    
                          	
                  }, 1000 * 30 );
                  
                  
                function displayReminder() {
              		$.ajax({
    
            	      	url:"api/rapelle",
            
            	      	method:"POST", 
            
            	      	dataType:'JSON',
            	      	
            	      	data:{market:<?= $_SESSION['market'] ?>},
            
            	      	success:function(response)
            	      	{
            	      	    
            	      	    var audio = document.getElementById('alert-sound');
            	      	    
            	      	    if(response.length > 0 ){
            	      	    
                              var tableContent = '<div class="table-responsive"><table class="table table-sm table-striped table-hover" style="white-space:nowrap;">' +
                                '<thead>' +
                                '<tr>' + 
                                '<th>Temps</th>' +
                                '<th>Date</th>' +
                                '<th>Réf </th>' +
                                '<th>Nom & Prénom</th>' +
                                '<th>N° Table</th>' +
                                '<th>Total</th>' +
                                '<th>Status</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>';
                        
                              for (var i = 0; i < response.length; i++) {
                                var row = response[i];
                                tableContent += '<tr class="rappel_button" data-id="' + row.id + '">' + 
                                  '<td>' + row.time + '</td>' +
                                  '<td>' + row.date + '</td>' +
                                  '<td>' + row.reference + '</td>' +
                                  '<td>' + row.name + '</td>' +
                                  '<td>' + row.num_table + '</td>' +
                                  '<td>' + row.total + ' </td>' +
                                  '<td>' + row.etat + '</td>' +
                                  '</tr>';
                              }
                        
                              tableContent += '</tbody></table></div>';
                              
                                audio.play();
                        
                                Swal.fire({
                                    title: "Commandes en attentes",
                                    html: tableContent,  
                                    allowEscapeKey: false, 
                                    confirmButtonText : 'Consulter les commandes',
                                }).then((result) => { 
                                    if (result.isConfirmed) {
                                      
                                      window.location.href = "/commande" ;
                                    } 
                                })
                                
                                 setTimeout(function(){
                                    $('.swal2-popup.swal2-modal.swal2-show').width("900px");
                                }, 10);
            	      	    }
                        }
              		});
                }
              
            </script>
        <?php }?> 
    </body>

</html>