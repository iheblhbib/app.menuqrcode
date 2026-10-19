checkLigne();
$("#typeremise").selectpicker();
// $("#conditionpaiement").selectpicker();

// facturation de commande

$("#facturation_form").parsley();

$("#facturation_form").on("submit", function (event) {
  event.preventDefault();
  if ($("#facturation_form").parsley().isValid()) {
    $.ajax({
      url: "api/commandeFournisseur/facturation.php",
      method: "POST",
      data: new FormData(this),
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (data) {
        $("#facturationModal").modal("hide");
        //  $.redirect("facture-fournisseur", {faf_id: data.id}, "POST");
        
        notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
        checkLigne();
        $("#product_tbody :input").attr("disabled", true);
      },
    });
  }
});

$("body").on("click", ".facturer", function () {
  $("#facturation_form")[0].reset();

  $("#facturation_form").parsley().reset();
  $("#facturationModal").modal("show");
});

var flowerArray = [];
$(".flower").each(function () {
  flowerArray.push($(this).html().toLowerCase());
});

$("#searchFlowerNames").on("input", function () {
  var searchInput = $(this).val().toLowerCase();

  var searchInputSplit = searchInput.split(" ");

  if (searchInput != "") {
    $(".flower").hide();
    $(".flower").each(function () {
      for (i = 0; i < searchInputSplit.length; i++) {
        // We have a match.
        if (
          $(this).html().toLowerCase().indexOf(searchInputSplit[i]) != -1 &&
          searchInputSplit[i] != ""
        ) {
          $(this).show();
        }
      }
    });
  } else {
    $(".flower").show();
  }
});

$(function () {
  $.ajax({
    url: "api/factureFournisseur/getFactures.php",

    method: "POST",

    dataType: "JSON",

    success: function (data) {
      var html = `<table class="table table-striped table-sm table-hover text-center" > 
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Facture</th>
                                    <th scope="col">Fournisseur</th>
                                    <th scope="col">Action  </th>       
                                </tr>
                            </thead>
                            <tbody>`;

      html += `
                        <tr class="flower" data-id="new">
                        <td colspan="3">Nouvelle facture</td>
                            <td> <input class="form-check-input" type="radio" name="facturation" value="new"  checked></td>
                        </tr>
                        `;
      for (var count = 0; count < data.length; count++) {
        var date = new Date(data[count].date);
        const options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        html +=
          `
                    <tr class="flower" data-id="` +
          data[count].id +
          `">
                        <td>` +
          date.toLocaleDateString("fr-FR", options) +
          `</td>
                        <td>` +
          data[count].reference +
          `</td>
                        <td>` +
          data[count].fournisseur +
          `</td>
                        <td><input class="form-check-input selectedfacture" type="radio" name="facturation" data-id="` +
          data[count].id +
          `" value="` +
          data[count].id +
          `" ></td>
                    </tr>
                    `;
      }

      html += `</tbody>
                </table>
            `;

      $("#content").html(html);
    },
  });
});

// recover if any ligne existe in this commande
$(function () {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeFournisseur/getLignesCommandeFournisseur.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
      // if commande contient lignes,else  it will show nothing

      if (data.length !== 0) {
        // preloader before show any data !!!
        setTimeout(function () {
          $("#product_tbody").html("");

          $.each(data, function (index, value) {
              var color = '';
              if(value.factured === '1')
                color = 'class="table-info"';
              
            var html =
              `
                            <tr `+color+`> 
                                <td>
                                    <input type="hidden" class="idLigne" id="idLigne` +
              value.num_ligne +
              `"  />
                                    <select  data-width="250px"  data-container="body"  name="pname` +
              count +
              `" class="form-control form-control-sm matiere" id="matiere` +
              value.num_ligne +
              `" data-live-search="true" title="Sélectionnez une matiere" data-size="5" onchange="readLigne(` +
              value.num_ligne +
              `)">
                                    </select>
                                </td> 
                                <td>
                                    <textarea name="description` +
              count +
              `" class="form-control form-control-sm description " id="description` +
              value.num_ligne +
              `" rows="1" cols="30" onchange="readLigne(` +
              value.num_ligne +
              `)"></textarea>
                                </td>
                                <td>
                                    <input type='text'  name='price` +
              count +
              `' class='form-control form-control-sm price ' id="price` +
              value.num_ligne +
              `" onchange="readLigne(` +
              value.num_ligne +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='qty` +
              count +
              `' class='form-control form-control-sm qty'  value="1" id="qty` +
              value.num_ligne +
              `" onchange="readLigne(` +
              value.num_ligne +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='hauteur` +
              count +
              `' class='form-control form-control-sm hauteur' value="1" id="hauteur` +
              value.num_ligne +
              `" onchange="readLigne(` +
              value.num_ligne +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='largeur` +
              count +
              `' class='form-control form-control-sm largeur' value="1" id="largeur` +
              value.num_ligne +
              `" onchange="readLigne(` +
              value.num_ligne +
              `)">
                                </td> 
                                <td>
                                    <input type='text'  name='total` +
              count +
              `'  disabled class='form-control form-control-sm total' id="total` +
              value.num_ligne +
              `">
                                </td> 
                                <td>
                                    <button class='btn btn-danger btn-sm btn-row-remove'> x </button>
                                </td>
                            </tr>
                        `;

            // affect all data into their places
            $("#product_tbody").append(html);
            newmatiereList(value.num_ligne);
            $("#idLigne" + value.num_ligne).val(value.id);
            $("#description" + value.num_ligne).val(value.description);
            $("#hauteur" + value.num_ligne).val(value.hauteur);
            $("#largeur" + value.num_ligne).val(value.largeur);
            $("#qty" + value.num_ligne).val(value.quantity);
            $("#total" + value.num_ligne).val(value.total_ht);

            // refrech  selectpicker and their new data
            setTimeout(function () {
              $("#matiere" + value.num_ligne).val(value.matiere);
              $("#matiere" + value.num_ligne).selectpicker("refresh");
            }, 500);

            // calcule total prices
            $("#price" + value.num_ligne).val(value.prix_unitaire);
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
          
          $(".table-info :input").attr("disabled", true);
          
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
  var matiere = $("#matiere" + x + " option:selected").data("id");
  var prix_unitaire = $("#matiere" + x + " option:selected").data("price");
  var price = $("#price" + x).val();
  var oldmatiere = getCookie("matiere" + x);

  //if price field is empty oorrr matiere changed ===>>> in that case the price will change to new matiere price
  // else it will be the same input from the field
  if (price === null || matiere != oldmatiere) {
    $("#price" + x).val(number_format(prix_unitaire, "3", ".", ""));
    price = prix_unitaire;
    setCookie("matiere" + x, matiere, 30);
  }
  
    if($("#price" + x).val() == 0)
  {
      $("#price" + x).val(prix_unitaire);
  }

  //refrech price after changing it ==> recalcule everything
  $("#price" + x).keyup();

  var quantity = $("#qty" + x).val();
  var num_ligne = x;
  var total_ht = $("#total" + x).val();
  var description = $("#description" + x).val();
  var commande = $("#id_commande").val();
  var hauteur = $("#hauteur" + x).val();
  var largeur = $("#largeur" + x).val();
  var totalTTC = $("#totalTTC").val();
  var id = $("#idLigne" + x).val();

  $.ajax({
    url: "api/commandeFournisseur/addLigneCommandeFournisseur.php",
    method: "POST",
    dataType: "json",
    data: {
      totalTTC: totalTTC,
      id: id,
      commande: commande,
      hauteur: hauteur,
      largeur: largeur,
      num_ligne: num_ligne,
      matiere: matiere,
      description: description,
      prix_unitaire: price,
      quantity: quantity,
      total_ht: total_ht,
    },
    success: function (data) {
        
      checkLigne();
      $("#idLigne" + x).val(data.id);
      notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
      
    },
  });
}

// detect changes and update any new data inserted
function updateCommande() {
  // var conditionpaiement = $("#conditionpaiement").val();
  var typeremise = $("#typeremise").val();
  var remise = $("#remise").val();
  var commande = $("#id_commande").val();
  var reference = $("#reference").val();
  var fournisseur = $("#fournisseur").val();
  var cdfdate = $("#cdfdate").val();
  var tva = $("#tva").val();
  var taxe = $("#taxe").data("id");
  var taxe_supp = $("#taxe_supp").data("id");

  $.ajax({
    url: "api/commandeFournisseur/updateCommande.php",
    method: "POST",
    data: {
      // conditionpaiement: conditionpaiement,
      remise: remise,
      typeremise: typeremise,
      tva: tva,
      taxe: taxe,
      taxe_supp: taxe_supp,
      commande: commande,
      reference: reference,
      fournisseur: fournisseur,
      cdfdate: cdfdate,
    },
    success: function (data) {
      notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
      grand_total();
      checkLigne();
    },
  });
}

// change etat of commande ( 0 brouillon , 1 annuler , 2 valider )
function changeEtat(x, redirect = true) {
  var commande = $("#id_commande").val();
  var question = '';
  switch(x) {
    case 1:
      question =  'Voulez-vous vraiment l\'annuler ?';
      break;
    case 2:
      question =  'Tu l\'as complété ?';
      break;
  }
  var content = confirm(question);
  if (content === true) {
    $.ajax({
      url: "api/commandeFournisseur/updateCommandeEtat.php",
      method: "POST",
      data: {
        commande: commande,
        etat: x,
      },
      success: function (data) {
        notificationSwal.fire({
              icon: 'success',
              title: "Document enregistré avec succès"
            });
        if (redirect) window.location.href = "commande-fournisseur";
      },
    });
  }

}

$(".selectpicker").selectpicker();
$(".achatdiv").removeClass("collapsed");
$(".achatliste").addClass("show");

// 	$(document).ready(function(){

var count = $("#count").val();
fournisseurList();
matiereList(count);

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
                    <select  data-width="250px"  data-container="body" name="pname` +
    count +
    `" class="form-control form-control-sm matiere" id="matiere` +
    count +
    `" data-live-search="true" title="Sélectionnez une matiere" data-size="5" onchange="readLigne(` +
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
                    <button class='btn btn-danger btn-sm btn-row-remove'> x </button>
                </td>
            </tr>`;

  $("#product_tbody").append(row);
  count = $("#count").val();
  newmatiereList(count);
});

// remove and delete ligne from this commande

$("body").on("click", ".btn-row-remove", function () {
  if (confirm("Es-tu sûr?")) {
    console.log("deleted");
    $(this).closest("tr").remove();

    var ligneCommande = $(this).closest("tr").find(".idLigne").val();

    $.ajax({
      url: "api/commandeFournisseur/deleteLigneCommandeFournisseur.php",
      method: "POST",
      dataType: "json",
      data: {
        ligneCommande: ligneCommande,
      },
      success: function (data) {
        grand_total();
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
    updateCommande();
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
  updateCommande();
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
    updateCommande();
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

  updateCommande();
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

    if(typeremise){
         tot = (typeremise == "2") ? (tot - (tot * remise) / 100) : tot - remise;
    }
       


  if (taxe_supp != 0) tot += tot * taxe_supp;
      

  var baseTva = tot * tva;

  if(tva) tot += tot * tva;

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
      var html = "<option value='0' selected disabled>Sélectionnez TVA</option>";;

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
// $(function () {
//   $.ajax({
//     url: "api/getConditionPaiement.php",

//     method: "POST",

//     dataType: "json",

//     success: function (data) {
//       var html = "";

//       $.each(data, function (index, value) {
//         html +=
//           '<option value="' +
//           value.id +
//           '" data-id="' +
//           value.id +
//           '" >' +
//           value.libelle +
//           "</option>";
//       });

//       $("#conditionpaiement").empty();

//       $("#conditionpaiement").append($.parseHTML(html));

//       $("#conditionpaiement").selectpicker("refresh");
//     },
//   });
// });

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
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeFournisseur/getOneCommande.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
      setTimeout(function () {
        $("#fournisseur").val(data.fournisseur);

        $("#fournisseur").selectpicker("refresh");

        // $("#conditionpaiement").val(data.conditionpaiement);

        // $("#conditionpaiement").selectpicker("refresh");

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
  var commande = $("#id_commande").val();
  $.ajax({
    url: "api/commandeFournisseur/dupliquerCommande.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
      window.location.href = "commande-fournisseur";
    },
  });
});


function checkLigne() {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeFournisseur/checkLigne.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
        
        console.log(data);

        if(data.all_factured)
        {
            $( "#save" ).prop( "disabled", true );
        }
        
        if(!data.all_factured)
        {
            $( "#save" ).prop( "disabled", false );
        }
        
        if(data.one_factured)
        {
            $( "#annule" ).prop( "disabled", true );
        }
        
        if(!data.one_factured)
        {
            $( "#annule" ).prop( "disabled", false );
        }

    },
  });
}



