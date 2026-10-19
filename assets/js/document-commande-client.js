checkLigne();
$("#typeremise").selectpicker();
$("#conditionpaiement").selectpicker();

// livrer le commande

$("#livraison_form").parsley();

$("#livraison_form").on("submit", function (event) {
  event.preventDefault();
  if ($("#livraison_form").parsley().isValid()) {
    $.ajax({
      url: "api/commandeClient/redirection.php",
      method: "POST",
      data: new FormData(this),
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (data) {
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
      },
    });
  }
});

// show modal of redirection

$("body").on("click", "#save", function () {
  $("#livraison-content").html("");

  $(function () {
    $.ajax({
      url: "api/bonlivraisonClient/getBonlivraisons.php",

      method: "POST",

      dataType: "JSON",

      success: function (data) {
        var html = `<table class="redirectTable table table-striped table-sm table-hover text-center" > 
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">BL</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Action  </th>       
                                        </tr>
                                    </thead>
                                    <tbody>`;

        html += `
                                <tr class="rowBonlivraison" data-id="new">
                                <td colspan="3">Nouvelle bon de livraison</td>
                                    <td> <input class="form-check-input" type="radio" name="livration" value="new"  checked></td>
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
          var datefacture = data[count].date
            ? ucwords(date.toLocaleDateString("fr-FR", options))
            : "--";
          html +=
            `
                            <tr class="rowBonlivraison" data-id="` +
            data[count].id +
            `">
                                <td>` +
            datefacture +
            `</td>
                                <td>` +
            data[count].reference +
            `</td>
                                <td>` +
            data[count].client +
            `</td>
                                <td><input class="form-check-input selectedbonlivraison" type="radio" name="livration" data-id="` +
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

        $("#livraison-content").html(html);
      },
    });
  });

  // bonlivraison
  $("#livraison_form")[0].reset();
  $("#livraison_form").parsley().reset();

  $("#redirectionModal").modal("show");
});

// array of bon livraison

var bonlivraisonArray = [];
$(".rowBonlivraison").each(function () {
  bonlivraisonArray.push($(this).html().toLowerCase());
});

//filter of bon livraison

$("#rowsBonlivraisons").on("input", function () {
  var searchInput2 = $(this).val().toLowerCase();

  var searchInputSplit2 = searchInput2.split(" ");

  if (searchInput2 != "") {
    $(".rowBonlivraison").hide();
    $(".rowBonlivraison").each(function () {
      for (i = 0; i < searchInputSplit2.length; i++) {
        // We have a match.
        if (
          $(this).html().toLowerCase().indexOf(searchInputSplit2[i]) != -1 &&
          searchInputSplit2[i] != ""
        ) {
          $(this).show();
        }
      }
    });
  } else {
    $(".rowBonlivraison").show();
  }
});

// recover if any ligne existe in this commande
$(function () {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeClient/getLignesCommandeClient.php",

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
            var color = "";
            if (value.livred === "1") color = 'class="table-success"';

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
            $("#price" + value.id).val(value.prix_unitaire);
            $("#description" + value.id).val(value.description);
            $("#hauteur" + value.id).val(value.hauteur);
            $("#largeur" + value.id).val(value.largeur);
            $("#qty" + value.id).val(value.quantity);
            $("#total" + value.id).val(value.total_ht);
            $("#produit" + value.id).val(value.produit);

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
function updateCommande() {
  var commande = $("#id_commande").val();
  var reference = $("#reference").val();
  var client = $("#client").val();
  var cdcdate = $("#cdcdate").val();
  var echeance = $("#echeance").val();

  $.ajax({
    url: "api/commandeClient/updateCommande.php",
    method: "POST",
    data: {
      commande: commande,
      reference: reference,
      client: client,
      cdcdate: cdcdate,
      echeance: echeance,
    },
    success: function (data) {
      notificationSwal.fire({
          icon: 'success',
          title: "Document enregistré avec succès"
        });
    },
  });
}

// change etat of commande ( 0 brouillon , 1 annuler , 2 valider )
function changeEtat(x, redirect = true) {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeClient/updateCommandeEtat.php",
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
      if (redirect) window.location.href = "commande-client";
    },
  });
}

$(".selectpicker").selectpicker();
$(".ventediv").removeClass("collapsed");
$(".venteliste").addClass("show");

var count = $("#count").val();
clientList();
produitList(count);

// remove and delete ligne from this commande

$("body").on("click", ".btn-row-remove", function () {
  if (confirm("Es-tu sûr?")) {
    console.log("deleted");
    $(this).closest("tr").remove();

    var ligneCommande = $(this).closest("tr").find(".idLigne").val();

    $.ajax({
      url: "api/commandeClient/deleteLigneCommandeClient.php",
      method: "POST",
      dataType: "json",
      data: {
        ligneCommande: ligneCommande,
      },
      success: function (data) {
        notificationSwal.fire({
          icon: 'success',
          title: "Document enregistré avec succès"
        });
      },
    });
  }
});

$(function () {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeClient/getOneCommande.php",

    method: "POST",

    data: { commande: commande },

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
  var commande = $("#id_commande").val();
  $.ajax({
    url: "api/commandeClient/dupliquerCommande.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
      window.location.href = "commande-client";
    },
  });
});

function checkLigne() {
  var commande = $("#id_commande").val();

  $.ajax({
    url: "api/commandeClient/checkLigne.php",

    method: "POST",

    data: { commande: commande },

    dataType: "json",

    success: function (data) {
      $("#save").prop("disabled", data.all);
      $("#annule").prop("disabled", data.one);
      $(".table-success :input").attr("disabled", true);
    },
  });
}
