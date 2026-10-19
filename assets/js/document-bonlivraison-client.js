checkLigne();
$("#typeremise").selectpicker();
$("#conditionpaiement").selectpicker();

    // facturer le bonlivraison
    
    $('#facture_form').parsley();
    
    $('#facture_form').on('submit', function(event)
    {
        event.preventDefault();
        if($('#facture_form').parsley().isValid())
        {
            $.ajax({
                url:"api/bonlivraisonClient/redirection.php",
                method:"POST",
                data:new FormData(this),
                dataType:'json',
                processData: false, 
                contentType: false,
                success:function(data)
                {
                    if (data.error != "") {
                        $("#redirectionModal").modal("hide");
                        checkLigne();
                        notificationSwal.fire({
                              icon: 'error',
                              title: data.error
                            });
                      } else {
                        $("#redirectionModal").modal("hide");
                        notificationSwal.fire({
                          icon: 'success',
                          title: data.success
                        });
                        checkLigne();
                        $("#product_tbody :input").attr("disabled", true);
                        $("#product_tbody tr").addClass("table-success");
                      }
                }
            })
        }
    });
    
    // show modal of redirection
    
    $("body").on("click", ".redirection", function () {
        
        $('#facture-content').html('');
        
        $(function () {
            
                $.ajax({
                
                    url:"api/factureClient/getFactures.php",
                
                    method:"POST",
                
                    dataType:'JSON',
                
                    success:function(data)
                    {
                
                        var html = `<table class="redirectTable table table-striped table-sm table-hover text-center" > 
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
                                    <tr class="rowFacture" data-id="new">
                                    <td colspan="3">Nouvelle facture</td>
                                        <td> <input class="form-check-input" type="radio" name="facturation" value="new"  checked></td>
                                    </tr>
                                    `; 
                            for(var count = 0; count < data.length; count++)
                            {
                                var date = new Date(data[count].date);
                                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                                var datefacture = (data[count].date) ?  ucwords(date.toLocaleDateString('fr-FR', options)) : '--';
                                html += `
                                <tr class="rowFacture" data-id="`+data[count].id+`">
                                    <td>`+datefacture+`</td>
                                    <td>`+data[count].reference+`</td>
                                    <td>`+data[count].client+`</td>
                                    <td><input class="form-check-input selectedfacture" type="radio" name="facturation" data-id="`+data[count].id+`" value="`+data[count].id+`" ></td>
                                </tr>
                                `; 
            
                            }
                        
                        html += `</tbody>
                            </table>
                        `;
                            
                    
                        $('#facture-content').html(html);
                    } 
                })
            });
        
        // facture
        $('#facture_form')[0].reset();
        $('#facture_form').parsley().reset();
        
        
        $('#redirectionModal').modal('show');
    });

    // array of factures
    
    var factureArray = [];
    $(".rowFacture").each(function(){
        factureArray.push($(this).html().toLowerCase());
    });
    
    //filter of factures
    
    $("#rowsFactures").on("input",function(){
        var searchInput3 = $(this).val().toLowerCase();
        
        var searchInputSplit3 =  searchInput3.split(" ");
        
        if(searchInput3!=""){
            $(".rowFacture").hide(); 
            $(".rowFacture").each(function(){
    
                for(i=0;i<searchInputSplit3.length;i++){
                    // We have a match.
                    if($(this).html().toLowerCase().indexOf(searchInputSplit3[i]) != -1 && searchInputSplit3[i]!=""){
                        $(this).show();
                    }
                }
            });
        }else{
            $(".rowFacture").show(); 
        }
    });


// recover if any ligne existe in this bonlivraison
$(function () {
  var bonlivraison = $("#id_bonlivraison").val();

  $.ajax({
    url: "api/bonlivraisonClient/getLignesBonlivraisonClient.php",

    method: "POST",

    data: { bonlivraison: bonlivraison },

    dataType: "json",

    success: function (data) {
      // if bonlivraison contient lignes,else  it will show nothing

      if (data.length !== 0) {
        // preloader before show any data !!!
        setTimeout(function () {
          $("#product_tbody").html("");

          $.each(data, function (index, value) {
            var color = "";
            if (value.factured === "1") color = 'class="table-success"';

            var html =
              `
                            <tr ` +
              color +
              `> 
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
            $("#produit" + value.id).val(value.produit);
             
      

            // calcule total prices
            $("#price" + value.id).val(value.prix_unitaire);
            grand_total();

            // refrech remise after all data recovered
            $("#remise").keyup();

            for (let i = 0; i < tx.length; i++) {
              if (tx[i].value == "") {
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
          checkLigne();
        }, 2000);

        notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
      }
    },
  });
});

// detect changes and update any new data inserted
function updateBonlivraison() {
  var conditionpaiement = $("#conditionpaiement").val();
  var typeremise = $("#typeremise").val();
  var remise = $("#remise").val();
  var bonlivraison = $("#id_bonlivraison").val();
  var reference = $("#reference").val();
  var client = $("#client").val();
  var blcdate = $("#blcdate").val();
  var tva = $("#tva").val();
  var taxe = $("#taxe").data("id");
  var taxe_supp = $("#taxe_supp").data("id");

  $.ajax({
    url: "api/bonlivraisonClient/updateBonlivraison.php",
    method: "POST",
    data: {
      conditionpaiement: conditionpaiement,
      remise: remise,
      typeremise: typeremise,
      tva: tva,
      taxe: taxe,
      taxe_supp: taxe_supp,
      bonlivraison: bonlivraison,
      reference: reference,
      client: client,
      blcdate: blcdate,
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

// change etat of bonlivraison ( 0 brouillon , 1 annuler , 2 valider )
function changeEtat(x, redirect = true) {
  var bonlivraison = $("#id_bonlivraison").val();

  $.ajax({
    url: "api/bonlivraisonClient/updateBonlivraisonEtat.php",
    method: "POST",
    data: {
      bonlivraison: bonlivraison,
      etat: x,
    },
    success: function (data) {
      $("#message").html();
     notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
      if (redirect) window.location.href = "bonlivraison-client";
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

// add new ligne

$("#btn-add-row").click(function () {
  var count = $("#count").val();
  count++;
  $("#count").val(count);

  var row =
    `<tr> 
                <td>
                    <input type="hidden" class="idLigne" id="idLigne` +
    count +
    `"  />
                    <select  data-width="250px"  name="pname` +
    count +
    `" class="form-control form-control-sm produit" id="produit` +
    count +
    `" data-live-search="true" title="Sélectionnez une produit" data-size="5" onchange="readLigne(` +
    count +
    `)">
                    </select>
                </td> 
                <td>
                    <textarea  name="description` +
    count +
    `" class="form-control form-control-sm description " id="description` +
    count +
    `" rows="1" cols="30" onchange="readLigne(` +
    count +
    `)"></textarea>
                </td>
                <td>
                    <input type='text'  name='price` +
    count +
    `' class='form-control form-control-sm price ' id="price` +
    count +
    `" onchange="readLigne(` +
    count +
    `)">
                </td> 
                <td>
                    <input type='text'  name='qty` +
    count +
    `' class='form-control form-control-sm qty'  value="1" id="qty` +
    count +
    `" onchange="readLigne(` +
    count +
    `)">
                </td> 
                <td>
                    <input type='text'  name='hauteur` +
    count +
    `' class='form-control form-control-sm hauteur' value="1" id="hauteur` +
    count +
    `" onchange="readLigne(` +
    count +
    `)">
                </td> 
                <td>
                    <input type='text'  name='largeur` +
    count +
    `' class='form-control form-control-sm largeur' value="1" id="largeur` +
    count +
    `" onchange="readLigne(` +
    count +
    `)">
                </td> 
                <td>
                    <input type='text'  name='total` +
    count +
    `'  disabled class='form-control form-control-sm total' id="total` +
    count +
    `">
                </td> 
                <td>
                    <input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'> 
                </td>
            </tr>`;

  $("#product_tbody").append(row);
  count = $("#count").val();
  newproduitList(count);
});

// remove and delete ligne from this bonlivraison

$("body").on("click", ".btn-row-remove", function () {
  if (confirm("Es-tu sûr?")) {
    console.log("deleted");
    $(this).closest("tr").remove();

    var ligneBonlivraison = $(this).closest("tr").find(".idLigne").val();

    $.ajax({
      url: "api/bonlivraisonClient/deleteLigneBonlivraisonClient.php",
      method: "POST",
      dataType: "json",
      data: {
        ligneBonlivraison: ligneBonlivraison,
      },
      success: function (data) {
        grand_total();
        $("#message").html();
        notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
      },
    });
  }
});

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
    updateBonlivraison();
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
  updateBonlivraison();
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
    updateBonlivraison();
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

  updateBonlivraison();
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

if(typeremise)
  tot = typeremise == "2" ? tot - (tot * remise) / 100 : tot - remise;

  if (taxe_supp != 0) tot += tot * taxe_supp;

  var baseTva = tot * tva;

if(tva)
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
  var bonlivraison = $("#id_bonlivraison").val();

  $.ajax({
    url: "api/bonlivraisonClient/getOneBonlivraison.php",

    method: "POST",

    data: { bonlivraison: bonlivraison },

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
  var bonlivraison = $("#id_bonlivraison").val();
  $.ajax({
    url: "api/bonlivraisonClient/dupliquerBonlivraison.php",

    method: "POST",

    data: { bonlivraison: bonlivraison },

    dataType: "json",

    success: function (data) {
      window.location.href = "bonlivraison-client";
    },
  });
});

function checkLigne() {
    var bonlivraison = $("#id_bonlivraison").val();
  
    $.ajax({
      url: "api/bonlivraisonClient/checkLigne.php",
  
      method: "POST",
  
      data: { bonlivraison: bonlivraison },
  
      dataType: "json",
  
      success: function (data) {

        console.log(data);
        $("#save").prop("disabled", data.all);
        $("#annule").prop("disabled", data.one);
        $(".table-success :input").attr("disabled", true);
      },
    });
  }
