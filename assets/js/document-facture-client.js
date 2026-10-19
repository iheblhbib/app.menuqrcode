getPaitement();
$("#typeremise").selectpicker();
$("#conditionpaiement").selectpicker();

// facturation de facture

$('#facuration_form').parsley();

$('#facuration_form').on('submit', function(event)
{
    event.preventDefault();
    if($('#facuration_form').parsley().isValid())
    {
        $.ajax({
            url:"api/factureClient/facturation.php",
            method:"POST",
            data:new FormData(this),
            dataType:'json',
            processData: false, 
            contentType: false,
            success:function(data)
            {
               $('#facturationModal').modal('hide');
                //  $.redirect("facture-client", {faf_id: data.id}, "POST"); 
                notificationSwal.fire({
                  icon: 'success',
                  title: "Document enregistré avec succès"
                });
            }
        })
    }
});

$("body").on("click", ".facturer", function () {
    $('#facuration_form')[0].reset();
    
    $('#facuration_form').parsley().reset();
    $('#facturationModal').modal('show');
});

var flowerArray = [];
$(".flower").each(function(){
    flowerArray.push($(this).html().toLowerCase());
});

$("#searchFlowerNames").on("input",function(){
    var searchInput = $(this).val().toLowerCase();
    
    var searchInputSplit =  searchInput.split(" ");
    
    if(searchInput!==""){
        $(".flower").hide(); 
        $(".flower").each(function(){

            for(i=0;i<searchInputSplit.length;i++){
                // We have a match.
                if($(this).html().toLowerCase().indexOf(searchInputSplit[i]) !== -1 && searchInputSplit[i]!==""){
                    $(this).show();

           
                }
            }
        });
    }else{
        $(".flower").show(); 
    }
});


$(function () {


    $.ajax({
    
        url:"api/factureClient/getFactures.php",
    
        method:"POST",
    
        dataType:'JSON',
    
        success:function(data)
        {
    
            var html = `<table class="table table-striped table-sm table-hover text-center" > 
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Facture</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Action  </th>       
                                </tr>
                            </thead>
                            <tbody>`;
                        
                 html+= `
                        <tr class="flower" data-id="new">
                        <td colspan="3">Nouvelle facture</td>
                            <td> <input class="form-check-input" type="radio" name="facuration" value="new"  checked></td>
                        </tr>
                        `; 
                for(var count = 0; count < data.length; count++)
                {
                    var date = new Date(data[count].date);
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    html += `
                    <tr class="flower" data-id="`+data[count].id+`">
                        <td>`+date.toLocaleDateString('fr-FR', options)+`</td>
                        <td>`+data[count].reference+`</td>
                        <td>`+data[count].client+`</td>
                        <td><input class="form-check-input selectedfacture" type="radio" name="facuration" data-id="`+data[count].id+`" value="`+data[count].id+`" ></td>
                    </tr>
                    `; 

                }
            
            html += `</tbody>
                </table>
            `;
                
        
            $('#content').html(html);
            } 
    })
});


// recover if any ligne existe in this facture
$(function () {
  var facture = $("#id_facture").val();

  $.ajax({
    url: "api/factureClient/getLignesFactureClient.php",

    method: "POST",

    data: { facture: facture },

    dataType: "json",

    success: function (data) {
      // if facture contient lignes,else  it will show nothing

      if (data.length !== 0) {
        // preloader before show any data !!!
        setTimeout(function () {
          $("#product_tbody").html("");

          $.each(data, function (index, value) {
            var html =
              `
                            <tr> 
                                <td>
                                    <input type="hidden" class="idLigne" id="idLigne` +
              value.id +
              `"  />
              <textarea name="pname` +
              count +
              `" class="form-control form-control-sm  produit" id="produit` +
              value.id +
              `" rows="1" cols="40" onchange="readLigne(` +
              value.id +
              `)"></textarea>
                                </td> 
                                <td>
                                    <textarea name="description` +
              count +
              `" class="form-control form-control-sm description " id="description` +
              value.id +
              `" rows="1" cols="30" onchange="readLigne(` +
              value.id +
              `)"></textarea>
                                </td>
                                <td>
                                    <input type='text'  name='price` +
              count +
              `' class='form-control form-control-sm price ' id="price` +
              value.id +
              `" onchange="readLigne(` +
              value.id +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='qty` +
              count +
              `' class='form-control form-control-sm qty'  value="1" id="qty` +
              value.id +
              `" onchange="readLigne(` +
              value.id +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='hauteur` +
              count +
              `' class='form-control form-control-sm hauteur' value="1" id="hauteur` +
              value.id +
              `" onchange="readLigne(` +
              value.id +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='largeur` +
              count +
              `' class='form-control form-control-sm largeur' value="1" id="largeur` +
              value.id +
              `" onchange="readLigne(` +
              value.id +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='total` +
              count +
              `'  disabled class='form-control form-control-sm total' id="total` +
              value.id +
              `">
                                </td> 
                                <td>
                                  
                                </td>
                            </tr>
                        `;

            // affect all data into their places
            $("#product_tbody").append(html);
            newproduitList(value.id);
            $("#idLigne" + value.id).val(value.id);
            $("#description" + value.id).val(value.description);
            $("#hauteur" + value.id).val(value.hauteur);
            $("#largeur" + value.id).val(value.largeur);
            $("#qty" + value.id).val(value.quantity);
            $("#total" + value.id).val(value.total_ht);

            // refrech  selectpicker and their new data
            setTimeout(function () {
              $("#produit" + value.id).val(value.produit);
              $("#produit" + value.id).selectpicker("refresh");
            }, 500);

            // calcule total prices
            $("#price" + value.id).val(value.prix_unitaire);
            grand_total();

            // refrech remise after all data recovered
            $("#remise").keyup();

            for (let i = 0; i < tx.length; i++) {
              if (tx[i].value === "") {
                tx[i].setAttribute(
                  "style",
                  "height:" + txHeight + "px;overflow-y:hidden;"
                );
              } else {
                tx[i].setAttribute(
                  "style",
                  "height:" + tx[i].scrollHeight + "px;overflow-y:hidden;"
                );
              }
              tx[i].addEventListener("input", OnInput, false);
            }

            function OnInput(e) {
              this.style.height = "auto";
              this.style.height = this.scrollHeight + "px";
            }
          });
          $("#product_tbody :input").attr("disabled", true);
        }, 2000);

        notificationSwal.fire({
                  icon: 'success',
                  title: "Document enregistré avec succès"
                });
      }
    },
  });
});

// insert or update any new data detected in one ligne
function readLigne(x) {
  var produit = $("#produit" + x + " option:selected").data("id");
  var prix_unitaire = $("#produit" + x + " option:selected").data("price");
  var price = $("#price" + x).val();
  var oldproduit = getCookie("produit" + x);

  //if price field is empty oorrr produit changed ===>>> in that case the price will change to new produit price
  // else it will be the same input from the field
  if (price === null || produit != oldproduit) {
    $("#price" + x).val(number_format(prix_unitaire, "3", ".", ""));
    price = prix_unitaire;
    setCookie("produit" + x, produit, 30);
  }

  //refrech price after changing it ==> recalcule everything
  $("#price" + x).keyup();

  var quantity = $("#qty" + x).val();
  var num_ligne = x;
  var total_ht = $("#total" + x).val();
  var description = $("#description" + x).val();
  var facture = $("#id_facture").val();
  var hauteur = $("#hauteur" + x).val();
  var largeur = $("#largeur" + x).val();
  var totalTTC = $("#totalTTC").val();
  var id = $("#idLigne" + x).val();

  $.ajax({
    url: "api/factureClient/addLigneFactureClient.php",
    method: "POST",
    dataType: "json",
    data: {
      totalTTC: totalTTC,
      id: id,
      facture: facture,
      hauteur: hauteur,
      largeur: largeur,
      num_ligne: num_ligne,
      produit: produit,
      description: description,
      prix_unitaire: price,
      quantity: quantity,
      total_ht: total_ht,
    },
    success: function (data) {
      $("#idLigne" + x).val(data.id);
      notificationSwal.fire({
      icon: 'success',
      title: "Document enregistré avec succès"
    });
    },
  });
}

// detect changes and update any new data inserted
function updateFacture() {
  var conditionpaiement = $("#conditionpaiement").val();
  var typeremise = $("#typeremise").val();
  var remise = $("#remise").val();
  var facture = $("#id_facture").val();
  var reference = $("#reference").val();
  var client = $("#client").val();
  var facdate = $("#facdate").val();
  var tva = $("#tva").val();
  var taxe = $("#taxe").data("id");
  var taxe_supp = $("#taxe_supp").data("id");

  $.ajax({
    url: "api/factureClient/updateFacture.php",
    method: "POST",
    data: {
      conditionpaiement: conditionpaiement,
      remise: remise,
      typeremise: typeremise,
      tva: tva,
      taxe: taxe,
      taxe_supp: taxe_supp,
      facture: facture,
      reference: reference,
      client: client,
      facdate: facdate,
    },
    success: function (data) {
      notificationSwal.fire({
                  icon: 'success',
                  title: "Document enregistré avec succès"
                });
      grand_total();
    },
  });
}

// change etat of facture ( 0 brouillon , 1 annuler , 2 valider )
function changeEtat(x, redirect = true) {
  var facture = $("#id_facture").val();

  $.ajax({
    url: "api/factureClient/updateFactureEtat.php",
    method: "POST",
    data: {
      facture: facture,
      etat: x,
    },
    success: function (data) {
      notificationSwal.fire({
                  icon: 'success',
                  title: "Document enregistré avec succès"
                });
      if (redirect) window.location.href = "facture-client";
    },
  });
}

$(".selectpicker").selectpicker();
$(".ventediv").removeClass("collapsed");
$(".venteliste").addClass("show");

// 	$(document).ready(function(){

var count = $("#count").val();
clientList();
produitList(count);


// remove timbre fiscal

$("body").on("click", ".btn-row-remove-taxe", function () {
  if (confirm("Es-tu sûr?")) {
    $(this).closest("tr").remove();

    var row = `<tr>
                    <td colspan="6" class="text-right" style="text-align: right;"><b>Timbre Fiscal</b></td>
                    <td>
                        <input disabled data-id="0"  type="text" name="taxe" id="taxe" class="form-control form-control-sm details"  value="0.000"/>											
                    </td>
                    <td>
                        <input type="button" value="+" class="btn btn-primary btn-sm btn-row-add-taxe">											
                    </td>
                </tr>`;

    $("#tablefooter").find("tr:last").prev().prev().after(row);
    grand_total();
    updateFacture();
  }
});

// add timbre fiscal

$("body").on("click", ".btn-row-add-taxe", function () {
  $(this).closest("tr").remove();

  var row = `<tr>
                <td colspan="6" class="text-right" style="text-align: right;"><b>Timbre Fiscal</b></td>
                <td>
                    <input disabled data-id="1"  type="text" name="taxe" id="taxe" class="form-control form-control-sm details"  value="0.600"/>											
                </td>
                <td>
                    <input type="button" value="-" class="btn btn-danger btn-sm btn-row-remove-taxe">											
                </td>
            </tr>`;

  $("#tablefooter").find("tr:last").prev().prev().after(row);
  grand_total();
  updateFacture();
});

// remove FODEC

$("body").on("click", ".btn-row-remove-taxe-supp", function () {
  if (confirm("Es-tu sûr?")) {
    $(this).closest("tr").remove();

    var row = `<tr>
                <td colspan="6" class="text-right" style="text-align: right;"><b>FODEC</b></td>
                <td>
                    <input disabled data-id="0"  type="text" name="taxe_supp" id="taxe_supp" class="form-control form-control-sm details"  value="0%" />												
                </td>
                <td>
                    <input type="button" value="+" class="btn btn-primary btn-sm btn-row-add-taxe-supp">											
                </td>
            </tr>`;

    $("#tablefooter").find("tr:last").prev().after(row);

    grand_total();
    updateFacture();
  }
});

// add FODEC
$("body").on("click", ".btn-row-add-taxe-supp", function () {
  $(this).closest("tr").remove();

  var row = `<tr>
                <td colspan="6" class="text-right" style="text-align: right;"><b>FODEC</b></td>
                <td>
                    <input disabled data-id="2"  type="text" name="taxe_supp" id="taxe_supp" class="form-control form-control-sm details"  value="1%" />												
                </td>
                <td>
                    <input type="button" value="-" class="btn btn-danger btn-sm btn-row-remove-taxe-supp">											
                </td>
            </tr>`;

  $("#tablefooter").find("tr:last").prev().after(row);

  grand_total();

  updateFacture();
});

// add ligne Base Remise

$("body").on("keyup", "#remise", function () {
  var remise = $("#remise").val();
  var total = $("#grand_total").val();
  var html = `<tr class="baseRemise">
                    <td colspan="6" class="text-right" style="text-align: right;"><b>Remise</b></td>
                    <td>
                        <input type="text" name="baseRemise" id="baseRemise" class="form-control form-control-sm details"  disabled />
                    </td>
                </tr>`;
  $("#tablefooter").find(".baseRemise").remove();
  if (remise > 0) {
    var typeremise = $("#typeremise").val();

    total = typeremise == "2" ? (total * remise) / 100 : remise;

    $("#tablefooter")
      .find("tr:last")
      .prev()
      .prev()
      .prev()
      .prev()
      .prev()
      .after(html);
    $("#baseRemise").val(number_format(total, "3", ".", ""));
  }
});

// add ligne Base Remise

$("body").on("change", "#typeremise", function () {
  var remise = $("#remise").val();
  var total = $("#grand_total").val();
  var html = `<tr class="baseRemise">
                    <td colspan="6" class="text-right" style="text-align: right;"><b>Remise</b></td>
                    <td>
                        <input type="text" name="baseRemise" id="baseRemise" class="form-control form-control-sm details"  disabled />
                    </td>
                </tr>`;
  $("#tablefooter").find(".baseRemise").remove();
  if (remise > 0) {
    var typeremise = $("#typeremise").val();

    total = typeremise == "2" ? (total * remise) / 100 : remise;

    $("#tablefooter")
      .find("tr:last")
      .prev()
      .prev()
      .prev()
      .prev()
      .prev()
      .after(html);
    $("#baseRemise").val(number_format(total, "3", ".", ""));
  }
});

$("body").on("keyup", ".price", function () {
  var price = Number($(this).val());
  var qty = Number($(this).closest("tr").find(".qty").val());
  var hauteur = Number($(this).closest("tr").find(".hauteur").val());
  var largeur = Number($(this).closest("tr").find(".largeur").val());
  $(this)
    .closest("tr")
    .find(".total")
    .val(number_format(price * qty * hauteur * largeur, "3", ".", ""));
  grand_total();
});

$("body").on("keyup", ".qty", function () {
  var qty = Number($(this).val());
  var price = Number($(this).closest("tr").find(".price").val());
  var hauteur = Number($(this).closest("tr").find(".hauteur").val());
  var largeur = Number($(this).closest("tr").find(".largeur").val());
  $(this)
    .closest("tr")
    .find(".total")
    .val(number_format(price * qty * hauteur * largeur, "3", ".", ""));
  grand_total();
});

$("body").on("keyup", ".hauteur", function () {
  var hauteur = Number($(this).val());
  var qty = Number($(this).closest("tr").find(".qty").val());
  var largeur = Number($(this).closest("tr").find(".largeur").val());
  var price = Number($(this).closest("tr").find(".price").val());
  $(this)
    .closest("tr")
    .find(".total")
    .val(number_format(price * qty * hauteur * largeur, "3", ".", ""));
  grand_total();
});

$("body").on("keyup", ".largeur", function () {
  var largeur = Number($(this).val());
  var qty = Number($(this).closest("tr").find(".qty").val());
  var hauteur = Number($(this).closest("tr").find(".hauteur").val());
  var price = Number($(this).closest("tr").find(".price").val());
  $(this)
    .closest("tr")
    .find(".total")
    .val(number_format(price * qty * hauteur * largeur, "3", ".", ""));
  grand_total();
});

function grand_total() {
  var tot = 0;

  $(".total").each(function () {
    tot += Number($(this).val());
  });

  $("#grand_total").val(number_format(tot, "3", ".", ""));
  totalTTC();
}

function totalTTC() {
  var remise = Number($("#remise").val());

  var tot = Number($("#grand_total").val());

  var tva = Number($("#tva option:selected").data("tva")) / 100;

  var taxe = Number($("#taxe").val());

  var taxe_supp = $("#taxe_supp").val();

  taxe_supp = Number(taxe_supp.substr(0, 1)) / 100;

  var typeremise = $("#typeremise").val();

  tot = typeremise == "2" ? tot - (tot * remise) / 100 : tot - remise;

  if (taxe_supp != 0) tot += tot * taxe_supp;

  var baseTva = tot * tva;

  tot += tot * tva;

  tot += taxe;

  $("#baseTva").val(number_format(baseTva, "3", ".", ""));

  $("#totalTTC").val(number_format(tot, "3", ".", ""));
}
// 	});

$(function () {
  $.ajax({
    url: "api/tva.php",

    method: "POST",

    dataType: "json",

    success: function (data) {
      var html = "<option value='0' selected disabled>Sélectionnez TVA</option>";

      $.each(data, function (index, value) {
        html +=
          '<option value="' +
          value.id +
          '" data-id="' +
          value.id +
          '" data-signe="' +
          value.signe +
          '" data-tva="' +
          value.tva +
          '">' +
          value.tva +
          "%</option>";
      });

      $("#tva").empty();

      $("#tva").append($.parseHTML(html));

      $("#tva").selectpicker("refresh");
    },
  });
});

// liste des conditions de paiemenets
$(function () {
  $.ajax({
    url: "api/getConditionPaiement.php",

    method: "POST",

    dataType: "json",

    success: function (data) {
      var html = "";

      $.each(data, function (index, value) {
        html +=
          '<option value="' +
          value.id +
          '" data-id="' +
          value.id +
          '" >' +
          value.libelle +
          "</option>";
      });

      $("#conditionpaiement").empty();

      $("#conditionpaiement").append($.parseHTML(html));

      $("#conditionpaiement").selectpicker("refresh");
    },
  });
});

// refrech liste de types de remise

$(function () {
  $.ajax({
    url: "api/getTypeRemise.php",

    method: "POST",

    dataType: "json",

    success: function (data) {
      var html = "";

      $.each(data, function (index, value) {
        html +=
          '<option value="' +
          value.id +
          '" data-id="' +
          value.id +
          '" >' +
          value.libelle +
          "</option>";
      });

      $("#typeremise").empty();

      $("#typeremise").append($.parseHTML(html));

      $("#typeremise").selectpicker("refresh");
    },
  });
});

$(function () {
  var facture = $("#id_facture").val();

  $.ajax({
    url: "api/factureClient/getOneFacture.php",

    method: "POST",

    data: { facture: facture },

    dataType: "json",

    success: function (data) {
      setTimeout(function () {
        $("#client").val(data.client);

        $("#client").selectpicker("refresh");

        $("#conditionpaiement").val(data.conditionpaiement);

        $("#conditionpaiement").selectpicker("refresh");

        $("#typeremise").val(data.typeremise);

        $("#typeremise").selectpicker("refresh");

        $("#tva").val(data.tva);

        $("#tva").selectpicker("refresh");

        $("#remise").val(data.remise);
        
        $("#remise").keyup();
      }, 2000);
    },
  });
});

// duplicator
$("body").on("click", "#duplique", function () {
  var facture = $("#id_facture").val();
  $.ajax({
    url: "api/factureClient/dupliquerFacture.php",

    method: "POST",

    data: { facture: facture },

    dataType: "json",

    success: function (data) {
      window.location.href = "facture-client";
    },
  });
});


$("body").on("click", ".addPaiement", function () {
    
    $('#paiementbyespece')[0].reset();
    
    $('#paiementbyespece').parsley().reset();

    $('#paiementbycheque')[0].reset();
    
    $('#paiementbycheque').parsley().reset();
    
    $('#paiementbyvirement')[0].reset();
    
    $('#paiementbyvirement').parsley().reset();
    
    $('#paiementbytraite')[0].reset();
    
    $('#paiementbytraite').parsley().reset();

    grand_total();
  var facture = $("#id_facture").val();

  $.ajax({
    url: "api/factureClient/getResteMontant.php",

    method: "POST",

    data: { facture: facture },

    dataType: "json",

    success: function (data) {
        

      $(".facture_client").val(facture);
      $(".totalmontant").val(data.totalmontant);
      $(".totalmontant2").val(data.totalmontant2 + ' TND');
      $(".payeddate").val(data.payeddate);
      $("#verticalycentered").modal("show");
      
      $('.form_message').html('');
    },
  }); 
});

	$('#paiementbyespece').parsley();

	$('#paiementbyespece').on('submit', function(event){
		event.preventDefault();
		if($('#paiementbyespece').parsley().isValid())
		{		
			$.ajax({
				url:"api/factureClient/addPaiement.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_buttonbyespece').attr('disabled', 'disabled');
					$('#submit_buttonbyespece').val('Attendre...');
				},
				success:function(data)
				{
					$('#submit_buttonbyespece').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_messagebyespece').html(data.error);
						$('#submit_buttonbyespece').val('Valider');
					}
					else
					{
						$('#verticalycentered').modal('hide');
						notificationSwal.fire({
                          icon: 'success',
                          title: data.success
                        });
                        
                        getPaitement();

					}
				}
			})
		}
	});
	
	$('#paiementbycheque').parsley();

	$('#paiementbycheque').on('submit', function(event){
		event.preventDefault();
		if($('#paiementbycheque').parsley().isValid())
		{		
			$.ajax({
				url:"api/factureClient/addPaiement.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_buttonbycheque').attr('disabled', 'disabled');
					$('#submit_buttonbycheque').val('Attendre...');
				},
				success:function(data)
				{
					$('#submit_buttonbycheque').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_messagebycheque').html(data.error);
						$('#submit_buttonbycheque').val('Valider');
					}
					else
					{
						$('#verticalycentered').modal('hide');
						notificationSwal.fire({
                          icon: 'success',
                          title: data.success
                        });
                        
                        getPaitement();
					}
				}
			})
		}
	});
	$('#paiementbyvirement').parsley();

	$('#paiementbyvirement').on('submit', function(event){
		event.preventDefault();
		if($('#paiementbyvirement').parsley().isValid())
		{		
			$.ajax({
				url:"api/factureClient/addPaiement.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_buttonbyvirement').attr('disabled', 'disabled');
					$('#submit_buttonbyvirement').val('Attendre...');
				},
				success:function(data)
				{
					$('#submit_buttonbyvirement').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_messagebyvirement').html(data.error);
						$('#submit_buttonbyvirement').val('Valider');
					}
					else
					{
						$('#verticalycentered').modal('hide');
						notificationSwal.fire({
                          icon: 'success',
                          title: data.success
                        });
                        
                        getPaitement();
					}
				}
			})
		}
	});
	
	$('#paiementbytraite').parsley();

	$('#paiementbytraite').on('submit', function(event){
		event.preventDefault();
		if($('#paiementbytraite').parsley().isValid())
		{		
			$.ajax({
				url:"api/factureClient/addPaiement.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_buttonbytraite').attr('disabled', 'disabled');
					$('#submit_buttonbytraite').val('Attendre...');
				},
				success:function(data)
				{
					$('#submit_buttonbytraite').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_messagebytraite').html(data.error);
						$('#submit_buttonbytraite').val('Valider');
					}
					else
					{
						$('#verticalycentered').modal('hide');
						notificationSwal.fire({
                          icon: 'success',
                          title: data.success
                        });
                        
                        getPaitement();
					}
				}
			})
		}
	});
	
function getPaitement() {
  var facture = $("#id_facture").val();

  $.ajax({
    url: "api/factureClient/getPaiement.php",

    method: "POST",

    data: { facture: facture },

    dataType: "json",

    success: function (data) {
    
        if(data.total_payed > 0)
        {
            $( "#annule" ).css("display","none");
        }
        
        if(data.difference == 0 )
        {
            $( "#save" ).css("display","none");
        }


    },
  });
}



// Banques

$(function () {
    $('#banque-bycheque').selectpicker();
    $.ajax({
      url: "api/banque.php",
  
      method: "POST",
  
      dataType: "json",
  
      success: function (data) {
        var html = "";
  
        $.each(data, function (index, value) {
          html +=
            '<option value="' +
            value.id +
            '" data-id="' +
            value.id +
            '" >' +
            value.libelle +
            "</option>";
        });
  
        $("#banque-bycheque").empty();
  
        $("#banque-bycheque").append($.parseHTML(html));
  
        $("#banque-bycheque").selectpicker("refresh");
      },
    });
  });
  
  $(function () {
    $('#banque-byvirement').selectpicker();
    $.ajax({
      url: "api/banque.php",
  
      method: "POST",
  
      dataType: "json",
  
      success: function (data) {
        var html = "";
  
        $.each(data, function (index, value) {
          html +=
            '<option value="' +
            value.id +
            '" data-id="' +
            value.id +
            '" >' +
            value.libelle +
            "</option>";
        });
  
        $("#banque-byvirement").empty();
  
        $("#banque-byvirement").append($.parseHTML(html));
  
        $("#banque-byvirement").selectpicker("refresh");
      },
    });
  });
  $(function () {
    $('#banque-bytraite').selectpicker();
    $.ajax({
      url: "api/banque.php",
  
      method: "POST",
  
      dataType: "json",
  
      success: function (data) {
        var html = "";
  
        $.each(data, function (index, value) {
          html +=
            '<option value="' +
            value.id +
            '" data-id="' +
            value.id +
            '" >' +
            value.libelle +
            "</option>";
        });
  
        $("#banque-bytraite").empty();
  
        $("#banque-bytraite").append($.parseHTML(html));
  
        $("#banque-bytraite").selectpicker("refresh");
      },
    });
  });


