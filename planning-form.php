<!-- Bloc « Disponibilité » à inclure dans le .row du modal d'un niveau (niveau-1 ... niveau-4) -->
<div class="col-md-12">
    <hr>
</div>

<h5 class="text-primary"><i class="fal fa-clock"></i> Disponibilité</h5>

<div class="col-md-12">
    <div class="form-check form-check-inline">
        <input class="form-check-input planning_mode" type="radio" name="planning_mode" id="planning_mode_toujours" value="toujours" checked>
        <label class="form-check-label" for="planning_mode_toujours">Toujours disponible</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input planning_mode" type="radio" name="planning_mode" id="planning_mode_horaire" value="horaire">
        <label class="form-check-label" for="planning_mode_horaire">Selon un horaire</label>
    </div>

    <div id="planning_bloc" class="mt-2" style="display:none;">
        <div id="planning_creneaux"></div>

        <button type="button" id="planning_add" class="btn btn-sm btn-light"><i class="fal fa-plus"></i> Ajouter un créneau</button>

        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="planning_pause" id="planning_pause" value="1">
            <label class="form-check-label" for="planning_pause">Mettre le planning en pause (le statut ne change plus automatiquement)</label>
        </div>

        <small class="text-muted d-block mt-1">
            Heure de Tunisie. En dehors des créneaux, l'élément est masqué du menu.
            Si l'heure de fin est avant l'heure de début, le créneau finit le lendemain (ex. 20:00 → 02:00).
        </small>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var jours = {1: 'Lun', 2: 'Mar', 3: 'Mer', 4: 'Jeu', 5: 'Ven', 6: 'Sam', 7: 'Dim'};
    var idx = 0;

    function addCreneau(c) {
        c = c || {jours: [1, 2, 3, 4, 5], debut: '12:00', fin: '20:00'};
        var i = idx++;
        var html = '<div class="planning_creneau border rounded p-2 mb-2">'
                 + '<div class="btn-group btn-group-sm flex-wrap mb-2" role="group">';
        $.each(jours, function (j, label) {
            var checked = c.jours.indexOf(parseInt(j, 10)) !== -1 ? ' checked' : '';
            html += '<input type="checkbox" class="btn-check" autocomplete="off" id="pj_' + i + '_' + j + '" name="planning[' + i + '][jours][]" value="' + j + '"' + checked + '>'
                  + '<label class="btn btn-outline-primary" for="pj_' + i + '_' + j + '">' + label + '</label>';
        });
        html += '</div>'
              + '<div class="d-flex align-items-center gap-2">'
              + '<span>De</span><input type="time" class="form-control form-control-sm mb-0" style="width:120px" name="planning[' + i + '][debut]" value="' + c.debut + '">'
              + '<span>à</span><input type="time" class="form-control form-control-sm mb-0" style="width:120px" name="planning[' + i + '][fin]" value="' + c.fin + '">'
              + '<button type="button" class="btn btn-sm btn-outline-danger ms-auto planning_remove" title="Supprimer ce créneau"><i class="fal fa-trash"></i></button>'
              + '</div></div>';
        $('#planning_creneaux').append(html);
    }

    function toggleBloc() {
        var horaire = $('#planning_mode_horaire').is(':checked');
        $('#planning_bloc').toggle(horaire);
        $('#planning_creneaux input[type=time]').prop('disabled', !horaire);   // heures non envoyées en mode « toujours »
        if (horaire && $('#planning_creneaux .planning_creneau').length === 0) {
            addCreneau();
        }
    }

    $(document).on('change', '.planning_mode', toggleBloc);
    $('#planning_add').on('click', function () { addCreneau(); });
    $(document).on('click', '.planning_remove', function () {
        $(this).closest('.planning_creneau').remove();
    });

    /* Appelé par le bouton « Ajouter » de la page */
    window.planningReset = function () {
        $('#planning_creneaux').empty();
        $('#planning_pause').prop('checked', false);
        $('#planning_mode_toujours').prop('checked', true);
        toggleBloc();
    };

    /* Appelé par fetch_single (data.planning) */
    window.planningFill = function (p) {
        window.planningReset();
        if (p && p.creneaux && p.creneaux.length) {
            $.each(p.creneaux, function (_, c) { addCreneau(c); });
            $('#planning_pause').prop('checked', !p.actif);
            $('#planning_mode_horaire').prop('checked', true);
            toggleBloc();
        }
    };
});
</script>
