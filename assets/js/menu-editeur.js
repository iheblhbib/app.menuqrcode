/*
 * Éditeur de menu — espace client (menu.php)
 * Dépendances déjà chargées par footer.php : jQuery, Bootstrap 5, jQuery UI (sortable), Quill,
 * bootstrap-select, SweetAlert2. Lecture via menu-action, écritures via niveau-N-action (inchangés).
 */
(function ($) {
    'use strict';

    var CFG = window.ME_CONFIG;
    var N = CFG.niveau;
    var ACTION = { 4: 'niveau-4-action', 3: 'niveau-3-action', 2: 'niveau-2-action', 1: 'niveau-1-action' };
    var NOMS = {
        4: ['Point de vente', 'Points de vente', 'un point de vente'],
        3: ['Catégorie', 'Catégories', 'une catégorie'],
        2: N >= 3 ? ['Sous-catégorie', 'Sous-catégories', 'une sous-catégorie'] : ['Catégorie', 'Catégories', 'une catégorie'],
        1: ['Article', 'Articles', 'un article']
    };
    var LANGS = ['fr'].concat(CFG.langues.map(function (l) { return l.code; }));

    var state = {
        nodes: {}, children: { root: [] }, perms: {},
        key: 'root', etat: '0', mode: 'list', search: '',
        items: [], open: {}
    };
    var ed = { level: null, id: null, parent: null };
    var quills = {};
    var editor, listReq, searchTimer;
    var toast = window.notificationSwal || Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });

    /* ---------------------------------------------------------------- utilitaires */
    function esc(s) {
        return $('<div>').text(s == null ? '' : String(s)).html();
    }
    function store(k, v) {
        try {
            if (v === undefined) { return JSON.parse(localStorage.getItem(k)); }
            localStorage.setItem(k, JSON.stringify(v));
        } catch (e) { /* stockage indisponible : on ignore */ }
        return null;
    }
    function api(data) {
        return $.ajax({ url: 'menu-action', method: 'POST', data: data, dataType: 'json' });
    }
    function act(level, data, json) {
        data.market = CFG.market;
        return $.ajax({ url: ACTION[level], method: 'POST', data: data, dataType: json ? 'json' : 'text' });
    }
    function fail(xhr) {
        if (xhr && xhr.statusText === 'abort') { return; }
        var m = (xhr && xhr.responseJSON && xhr.responseJSON.error) || 'Une erreur est survenue. Réessayez.';
        toast.fire({ icon: 'error', title: m });
    }
    function can(level, right) {
        return !!(state.perms[level] && state.perms[level][right]);
    }
    function nom(level, i) {
        return NOMS[level][i || 0];
    }
    function prix(p) {
        if (p === null || p === undefined || p === '') { return ''; }
        return /^\d+([.,]\d+)?$/.test(String(p).trim()) ? String(p).trim() + ' DT' : String(p);
    }
    function confirmBox(title, text, ok) {
        return Swal.fire({
            title: title, text: text, icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#2c5199', cancelButtonText: 'Annuler', confirmButtonText: ok
        });
    }
    function listLevel() {
        return state.key === 'root' ? N : state.nodes[state.key].level - 1;
    }
    function pathOf(key) {
        var path = [];
        while (key && state.nodes[key]) {
            path.unshift(state.nodes[key]);
            key = state.nodes[key].parent;
        }
        return path;
    }

    /* ---------------------------------------------------------------- arbre */
    function loadTree() {
        return api({ action: 'tree' }).done(function (d) {
            state.perms = d.perms;
            state.nodes = {};
            state.children = { root: [] };
            d.nodes.forEach(function (n) {
                state.nodes[n.key] = n;
                state.children[n.key] = state.children[n.key] || [];
                var p = n.parent || 'root';
                (state.children[p] = state.children[p] || []).push(n.key);
            });
            if (state.key !== 'root' && !state.nodes[state.key]) { state.key = 'root'; }
            renderTree();
            renderHead();
        }).fail(fail);
    }

    function highlight(text, q) {
        if (!q) { return esc(text); }
        var i = text.toLowerCase().indexOf(q);
        if (i < 0) { return esc(text); }
        return esc(text.slice(0, i)) + '<mark>' + esc(text.slice(i, i + q.length)) + '</mark>' + esc(text.slice(i + q.length));
    }

    function branch(parentKey, visible, q) {
        return (state.children[parentKey] || []).map(function (k) {
            if (visible && !visible[k]) { return ''; }
            var n = state.nodes[k];
            var hasKids = (state.children[k] || []).length > 0;
            var open = q ? true : !!state.open[k];
            var cls = 'me-node' + (k === state.key ? ' active' : '') + (n.statut !== 'Activer' ? ' off' : '');
            return '<li><div class="' + cls + '" data-key="' + k + '" title="' + esc(n.libelle) + '">'
                + '<i class="fa-solid fa-chevron-right me-caret' + (hasKids ? (open ? ' open' : '') : ' empty') + '" data-toggle="' + k + '"></i>'
                + '<span class="me-node-label">' + highlight(n.libelle, q) + '</span>'
                + (n.planning ? '<i class="fa-light fa-clock me-clock" title="' + esc(n.planning.resume) + '"></i>' : '')
                + '<span class="me-count">' + n.count + '</span></div>'
                + (hasKids && open ? '<ul>' + branch(k, visible, q) + '</ul>' : '')
                + '</li>';
        }).join('');
    }

    function renderTree() {
        var q = state.search.toLowerCase();
        var visible = null;
        if (q) {
            visible = {};
            Object.keys(state.nodes).forEach(function (k) {
                if (state.nodes[k].libelle.toLowerCase().indexOf(q) > -1) {
                    for (var p = k; p; p = state.nodes[p].parent) { visible[p] = true; }
                }
            });
        }
        var root = '<div class="me-node root' + (state.key === 'root' && state.mode === 'list' ? ' active' : '') + '" data-key="root">'
            + '<i class="fa-light fa-book-open me-caret"></i><span class="me-node-label">Tout le menu</span>'
            + (N > 1 ? '<span class="me-count">' + state.children.root.length + '</span>' : '') + '</div>';
        var body = branch('root', visible, q);
        if (q && !body) {
            body = '<li class="me-tree-empty">Aucune catégorie ne correspond.</li>';
        }
        $('#meTreeBody').html(root + '<ul>' + body + '</ul>');
    }

    /* ---------------------------------------------------------------- navigation */
    function select(key, fromHash) {
        if (key !== 'root' && !state.nodes[key]) { key = 'root'; }
        state.key = key;
        state.mode = 'list';
        state.etat = '0';
        if (state.search) {
            state.search = '';
            $('#meSearch').val('');
        }
        for (var p = state.nodes[key] && state.nodes[key].parent; p; p = state.nodes[p].parent) { state.open[p] = true; }
        if (key !== 'root') { state.open[key] = true; }
        store('me-open-' + CFG.market, state.open);
        if (!fromHash && decodeURIComponent(location.hash.slice(1)) !== key) {
            location.hash = key;
        }
        renderTree();
        renderHead();
        loadList();
        closeTree();
    }

    function renderHead() {
        var $c = $('#meCrumbs');
        if (state.mode === 'search') {
            $c.html('<a data-key="root">Tout le menu</a><span class="sep">›</span><span>Recherche</span>');
            return;
        }
        var path = pathOf(state.key === 'root' ? null : state.key);
        var crumbs = ['<a data-key="root">Tout le menu</a>'];
        path.forEach(function (n, i) {
            crumbs.push(i === path.length - 1 ? '<span>' + esc(n.libelle) + '</span>' : '<a data-key="' + n.key + '">' + esc(n.libelle) + '</a>');
        });
        $c.html(path.length ? crumbs.join('<span class="sep">›</span>') : '');

        var lvl = listLevel();
        var node = state.nodes[state.key];
        $('#meTitle').text(node ? node.libelle : 'Tout le menu');
        var sub = '';
        if (node) {
            sub = nom(node.level);
            if (node.statut !== 'Activer') { sub += '<span class="badge-off">Masqué du menu</span>'; }
            if (node.planning) {
                sub += ' <span class="me-tag' + (node.planning.actif ? '' : ' paused') + '"><i class="fa-light fa-clock"></i> '
                    + esc(node.planning.resume) + (node.planning.actif ? '' : ' (en pause)') + '</span>';
            }
        } else {
            sub = esc(nom(lvl, 1)) + ' de premier niveau';
        }
        $('#meSubtitle').html(sub);
        $('#meEditParent').prop('hidden', !(node && can(node.level, 'u')));
        $('#meAdd').prop('hidden', !can(lvl, 'c')).find('span').text('Ajouter ' + nom(lvl, 2));
    }

    /* ---------------------------------------------------------------- liste */
    function skeleton(n) {
        return new Array(n + 1).join('<div class="me-skeleton"></div>');
    }

    function loadList() {
        var lvl = listLevel();
        var parent = state.key === 'root' ? 0 : state.nodes[state.key].id;
        $('#meFilters').show();
        $('#meList').html(skeleton(4));
        if (listReq) { listReq.abort(); }
        listReq = api({ action: 'list', level: lvl, parent: parent, etat: state.etat }).done(function (d) {
            state.items = d.items;
            $('#meFilters .me-chip[data-etat="0"] span').text(d.counts[0]);
            $('#meFilters .me-chip[data-etat="1"] span').text(d.counts[1]);
            renderList();
        }).fail(function (x) {
            fail(x);
            if (x.statusText !== 'abort') { $('#meList').html(emptyState('Impossible de charger la liste.')); }
        });
    }

    function emptyState(text, withAdd) {
        return '<div class="me-empty"><i class="fa-light fa-bowl-food"></i>' + esc(text)
            + (withAdd ? '<div class="mt-3"><button type="button" class="btn btn-primary btn-sm me-empty-add"><i class="fa-light fa-plus"></i> Ajouter ' + esc(nom(listLevel(), 2)) + '</button></div>' : '')
            + '</div>';
    }

    function rowHtml(it, extraMeta) {
        var lvl = it.level;
        var archived = it.etat === '1';
        var on = it.statut === 'Activer';
        var canU = can(lvl, 'u');
        var sortable = canU && !archived && state.mode === 'list';
        var meta = [];
        if (extraMeta) { meta.push(extraMeta); }
        if (lvl > 1) {
            var label = it.count + ' ' + nom(lvl - 1, 1).toLowerCase();
            if (it.count && it.actifs !== it.count) { label += ' · ' + it.actifs + ' visibles'; }
            meta.push('<span><i class="fa-light fa-layer-group"></i> ' + esc(label) + '</span>');
        }
        if (lvl === 1 && it.type === 'text') { meta.push('<span class="me-tag paused">Texte seul</span>'); }
        if (it.planning) {
            meta.push('<span class="me-tag' + (it.planning.actif ? '' : ' paused') + '"><i class="fa-light fa-clock"></i> '
                + esc(it.planning.resume) + (it.planning.actif ? '' : ' · en pause') + '</span>');
        }
        if (lvl === 1 && it.allergenes && it.allergenes.length) {
            meta.push('<span title="' + esc(it.allergenes.join(', ')) + '">' + esc(it.allergenes.map(function (a) { return a.split(' ')[0]; }).join(' ')) + '</span>');
        }
        if (lvl === 1 && it.description) { meta.push('<span class="desc">' + esc(it.description) + '</span>'); }

        var menu = [];
        if (canU) { menu.push('<li><a class="dropdown-item" data-act="edit"><i class="fa-light fa-pen"></i> Modifier</a></li>'); }
        if (lvl > 1 && !archived) { menu.push('<li><a class="dropdown-item" data-act="open"><i class="fa-light fa-folder-open"></i> Ouvrir</a></li>'); }
        if (canU && !archived) { menu.push('<li><a class="dropdown-item" data-act="duplicate"><i class="fa-light fa-copy"></i> Dupliquer</a></li>'); }
        if (canU) {
            menu.push('<li><hr class="dropdown-divider"></li>');
            menu.push(archived
                ? '<li><a class="dropdown-item" data-act="etat"><i class="fa-light fa-box-open"></i> Restaurer</a></li>'
                : '<li><a class="dropdown-item text-danger" data-act="etat"><i class="fa-light fa-box-archive"></i> Archiver</a></li>');
        }

        return '<div class="me-row' + (on ? '' : ' off') + '" data-id="' + it.id + '" data-level="' + lvl + '">'
            + (sortable ? '<span class="me-handle" title="Glisser pour réordonner"><i class="fa-solid fa-grip-dots-vertical"></i></span>' : '')
            + '<div class="me-thumb' + (it.image && !it.show_img ? ' hidden-img' : '') + '"' + (it.image ? ' data-img="' + esc(it.image) + '"' : '') + '>'
            + (it.image ? '' : '<i class="fa-light fa-image"></i>') + '</div>'
            + '<div class="me-row-main" data-act="main">'
            + '<div class="me-row-title"><span class="txt">' + esc(it.libelle) + '</span></div>'
            + (meta.length ? '<div class="me-row-meta">' + meta.join('') + '</div>' : '')
            + '</div>'
            + (it.prix ? '<div class="me-price">' + esc(prix(it.prix)) + '</div>' : '')
            + '<div class="me-row-actions">'
            + (archived ? '' : '<button type="button" class="me-switch' + (on ? ' on' : '') + '" data-act="statut" title="' + (on ? 'Visible sur le menu' : 'Masqué du menu') + '"' + (can(lvl, 'd') ? '' : ' disabled') + '></button>')
            + (menu.length ? '<div class="dropdown"><button type="button" class="me-icon-btn" data-bs-toggle="dropdown" aria-label="Actions"><i class="fa-regular fa-ellipsis-vertical"></i></button>'
                + '<ul class="dropdown-menu dropdown-menu-end">' + menu.join('') + '</ul></div>' : '')
            + '</div></div>';
    }

    function paintThumbs($scope) {
        $scope.find('.me-thumb[data-img]').each(function () {
            this.style.backgroundImage = 'url("' + $(this).data('img').replace(/["\\]/g, '') + '")';
        });
    }

    function renderList() {
        var lvl = listLevel();
        $('#meFilters .me-chip').removeClass('active').filter('[data-etat="' + state.etat + '"]').addClass('active');
        $('#meHint').toggle(can(lvl, 'u') && state.etat === '0' && state.items.length > 1);
        if (!state.items.length) {
            $('#meList').html(state.etat === '1'
                ? emptyState('Aucun élément archivé ici.')
                : emptyState('Aucun élément pour le moment.', can(lvl, 'c')));
            return;
        }
        $('#meList').html(state.items.map(function (it) { return rowHtml(it); }).join(''));
        paintThumbs($('#meList'));
        initSortable(lvl);
    }

    /* footer.php charge aussi vendor/jquery/jquery-sortable.js, qui écrase $.fn.sortable de jQuery UI.
       On reprend donc le widget jQuery UI sous un autre nom pour ne dépendre que de lui. */
    function uiSortable() {
        if (!$.ui || !$.ui.sortable || !$.widget || !$.widget.bridge) { return false; }
        if (!$.fn.meSortable) { $.widget.bridge('meSortable', $.ui.sortable); }
        return true;
    }

    function initSortable(lvl) {
        var $l = $('#meList');
        if (!uiSortable()) { return; }
        if ($l.data('ui-sortable')) { $l.meSortable('destroy'); }
        if (!can(lvl, 'u') || state.etat !== '0' || state.mode !== 'list') { return; }
        $l.meSortable({
            handle: '.me-handle', items: '> .me-row', axis: 'y', tolerance: 'pointer', placeholder: 'me-row-placeholder',
            update: function () {
                var ids = $l.children('.me-row').map(function () { return $(this).data('id'); }).get();
                act(lvl, { action: 'update_order', id_table: ids, newpos: ids.map(function (_, i) { return i; }) })
                    .done(function () {
                        toast.fire({ icon: 'success', title: 'Ordre enregistré' });
                        loadTree();
                    })
                    .fail(fail);
            }
        });
    }

    function findItem($row) {
        var id = $row.data('id');
        for (var i = 0; i < state.items.length; i++) {
            if (state.items[i].id === id) { return state.items[i]; }
        }
        return null;
    }

    function redrawRow(it) {
        var $row = $('#meList .me-row[data-id="' + it.id + '"]');
        var $new = $(rowHtml(it, $row.data('extra') ? it._extra : null));
        $row.replaceWith($new);
        paintThumbs($new);
    }

    /* ---------------------------------------------------------------- actions sur une ligne */
    function toggleStatut(it) {
        var next = it.statut === 'Activer' ? 'Desactiver' : 'Activer';
        var go = function () {
            act(it.level, { action: 'statut', id: it.id, statut: it.statut, next_statut: next })
                .done(function () {
                    it.statut = next;
                    if (it.planning) { it.planning.actif = false; }
                    redrawRow(it);
                    toast.fire({ icon: 'success', title: next === 'Activer' ? 'Visible sur le menu' : 'Masqué du menu' });
                    loadTree();
                })
                .fail(fail);
        };
        if (it.planning && it.planning.actif) {
            confirmBox('Planning actif', 'Cet élément suit un horaire (' + it.planning.resume + '). Le changer à la main met le planning en pause.', 'Changer quand même')
                .then(function (r) { if (r.isConfirmed) { go(); } });
        } else {
            go();
        }
    }

    function toggleEtat(it) {
        var archive = it.etat === '0';
        var txt = archive
            ? (it.level > 1 ? 'Il disparaît du menu avec tout son contenu. Vous pourrez le restaurer depuis « Archivés ».' : 'Il disparaît du menu. Vous pourrez le restaurer depuis « Archivés ».')
            : 'Il réapparaîtra dans la liste.';
        confirmBox(archive ? 'Archiver « ' + it.libelle + ' » ?' : 'Restaurer « ' + it.libelle + ' » ?', txt, archive ? 'Archiver' : 'Restaurer')
            .then(function (r) {
                if (!r.isConfirmed) { return; }
                act(it.level, { action: 'etat', id: it.id, etat: it.etat, next_etat: archive ? '1' : '0' })
                    .done(function () {
                        toast.fire({ icon: 'success', title: archive ? 'Archivé' : 'Restauré' });
                        refresh();
                    })
                    .fail(fail);
            });
    }

    function duplicate(it) {
        confirmBox('Dupliquer « ' + it.libelle + ' » ?', it.level > 1 ? 'Tout son contenu sera copié aussi.' : '', 'Dupliquer')
            .then(function (r) {
                if (!r.isConfirmed) { return; }
                act(it.level, { action: 'duplicate', id: it.id }, true)
                    .done(function (d) {
                        if (d && d.error) { toast.fire({ icon: 'error', title: d.error }); return; }
                        toast.fire({ icon: 'success', title: 'Dupliqué' });
                        refresh();
                    })
                    .fail(fail);
            });
    }

    function refresh() {
        loadTree();
        if (state.mode === 'search') { runSearch(); } else { loadList(); }
    }

    /* ---------------------------------------------------------------- recherche */
    function runSearch() {
        var q = state.search;
        state.mode = 'search';
        renderTree();
        renderHead();
        $('#meTitle').text('Résultats pour « ' + q + ' »');
        $('#meSubtitle').text('Articles');
        $('#meAdd, #meEditParent').prop('hidden', true);
        $('#meFilters').hide();
        $('#meList').html(skeleton(3));
        if (listReq) { listReq.abort(); }
        listReq = api({ action: 'search', q: q }).done(function (d) {
            state.items = d.items;
            if (!d.items.length) {
                $('#meList').html(emptyState('Aucun article ne correspond à « ' + q + ' ».'));
                return;
            }
            $('#meList').html(d.items.map(function (it) {
                var path = pathOf(it.parent).map(function (n) { return n.libelle; }).join(' › ');
                it._extra = path ? '<span><i class="fa-light fa-folder"></i> ' + esc(path) + '</span>' : '';
                return rowHtml(it, it._extra);
            }).join(''));
            $('#meList .me-row').attr('data-extra', '1');
            paintThumbs($('#meList'));
        }).fail(fail);
    }

    /* ---------------------------------------------------------------- arbre mobile */
    function openTree() { $('#meTree, #meTreeBackdrop').addClass('open'); }
    function closeTree() { $('#meTree, #meTreeBackdrop').removeClass('open'); }

    /* ---------------------------------------------------------------- éditeur */
    function setupQuills() {
        if (!window.Quill) { return; }
        LANGS.forEach(function (c) {
            if (!document.getElementById('meQuill_' + c)) { return; }
            quills[c] = new Quill('#meQuill_' + c, {
                theme: 'snow',
                placeholder: 'Ingr\u00e9dients, pr\u00e9paration\u2026',
                modules: { toolbar: [['bold', 'italic', 'underline'], [{ list: 'ordered' }, { list: 'bullet' }], ['clean']] }
            });
        });
    }
    function setQuill(c, html) {
        if (!quills[c]) { return; }
        if (!html) {
            quills[c].setContents([{ insert: '\n' }], 'silent');
        } else {
            quills[c].setContents(quills[c].clipboard.convert(html), 'silent');
        }
    }

    function toggleSection(sel, on) {
        var $s = $(sel);
        $s.toggle(on).find('input, select, textarea').prop('disabled', !on);
        $s.find('.selectpicker').each(function () {
            if ($.fn.selectpicker) { $(this).selectpicker('refresh'); }
        });
    }

    function showLang(c) {
        $('#meForm .me-lang').removeClass('active').filter('[data-lang="' + c + '"]').addClass('active');
        $('#meForm .me-lang-pane').removeClass('active').filter('[data-lang="' + c + '"]').addClass('active');
    }

    /* Le prix ne dépend pas de la langue : celui de l'onglet FR est recopié dans toutes les autres. */
    function syncPrix() {
        if (ed.level !== 1) { return; }
        var prixFr = $.trim($('#prix').val());
        CFG.langues.forEach(function (l) {
            $('#prix' + l.code).val(prixFr).removeClass('me-field-error');
        });
    }

    function langComplete(c) {
        var suffix = c === 'fr' ? '' : c;
        if (!$.trim($('#libelle' + suffix).val())) { return false; }
        if (ed.level === 1 && !$.trim($('#prix' + suffix).val())) { return false; }
        return true;
    }
    function updateDots() {
        LANGS.forEach(function (c) {
            $('#meForm .me-lang[data-lang="' + c + '"] .me-dot').toggleClass('ok', langComplete(c));
        });
    }

    function setPreview(url) {
        var $img = $('#mePreview');
        if (url) { $img.attr('src', url).prop('hidden', false); } else { $img.removeAttr('src').prop('hidden', true); }
    }

    function fillParents(level, parentId) {
        var $p = $('#meParent').empty();
        if (level >= N) { return; }
        $('#meParentLabel').text('Dans ' + nom(level + 1).toLowerCase());
        Object.keys(state.nodes).forEach(function (k) {
            var n = state.nodes[k];
            if (n.level !== level + 1) { return; }
            var label = pathOf(k).map(function (x) { return x.libelle; }).join(' › ');
            $p.append($('<option>').val(n.id).text(label));
        });
        if (parentId) { $p.val(String(parentId)); }
    }

    function parentOfList() {
        return state.key === 'root' ? null : state.nodes[state.key].id;
    }

    function openEditor(level, id, parentId) {
        ed = { level: level, id: id || null, parent: parentId || null };
        var form = $('#meForm')[0];
        form.reset();
        $('#meFormError').empty();
        $('#meForm .me-field-error').removeClass('me-field-error');
        $('#meAction').val(id ? 'Edit' : 'Add');
        $('#hidden_id').val(id || '');
        $('#hidden_categorie_image').val('');
        $('#meEditorKicker').text(nom(level));
        $('#meEditorTitle').text(id ? 'Modifier' : 'Ajouter ' + nom(level, 2));

        $('#meForm .me-only[data-levels]').each(function () {
            toggleSection(this, String($(this).data('levels')).split(',').indexOf(String(level)) > -1);
        });
        toggleSection('#meForm [data-parent]', level < N);
        toggleSection('#meForm [data-vip]', CFG.vip && level === N);
        fillParents(level, parentId);
        // Icône, VIP et prix/note ne sont plus affichés, mais restent actifs : leur valeur actuelle
        // repart telle quelle à l'enregistrement (sinon Edit les remettrait à vide).
        $('#meForm .me-keep').addClass('d-none');
        $('#meGeneral').toggle(level < N || level === 1);

        setPreview('');
        $('#meDisplayImage').prop('checked', true);
        $('#display_image').val('1');
        LANGS.forEach(function (c) { setQuill(c, ''); });
        if ($.fn.selectpicker) { $('#allergens').selectpicker('val', []); }
        $('#etat_prix').trigger('change');
        $('#vip').trigger('change');
        showLang('fr');
        if (window.planningReset) { window.planningReset(); }
        updateDots();
        editor.show();

        if (!id) {
            setTimeout(function () { $('#libelle').trigger('focus'); }, 300);
            return;
        }
        $('#meSave').prop('disabled', true);
        act(level, { action: 'fetch_single', id: id, categorie: parentId || '' }, true)
            .done(fillEditor)
            .fail(fail)
            .always(function () { $('#meSave').prop('disabled', false); });
    }

    function fillEditor(d) {
        var level = ed.level;
        $('#libelle').val(d.libelle || '');
        CFG.langues.forEach(function (l) {
            $('#libelle' + l.code).val(d['libelle' + l.code] || '');
            if (level === 1) {
                $('#prix' + l.code).val(d['prix' + l.code] || '');
                setQuill(l.code, d['description' + l.code]);
            }
        });
        if (level === 1) {
            $('#prix').val(d.prix || '');
            setQuill('fr', d.description);
            syncPrix();
            $('#type').val(d.type || 'product');
            if ($.fn.selectpicker) { $('#allergens').selectpicker('val', (d.allergens || []).map(String)); }
        }
        if (level === 2) {
            $('#etat_prix').val(d.etat_prix === '2' ? '2' : '1').trigger('change');
            $('#meNote').val(d.prix || '');
        }
        if (level === 2 || level === 3) { $('#icon').val(d.icon || ''); }
        if (CFG.vip && level === N) {
            $('#vip').val(d.vip === '2' ? '2' : '1').trigger('change');
            $('#code_vip').val(d.code_vip || '');
        }
        setPreview(d.image || '');
        $('#hidden_categorie_image').val(d.image || '');
        $('#meDisplayImage').prop('checked', d.display_image !== '2');
        $('#display_image').val(d.display_image === '2' ? '2' : '1');
        if (window.planningFill) { window.planningFill(d.planning); }
        updateDots();
    }

    function validate() {
        var firstBad = null;
        syncPrix();
        $('#meForm .me-field-error').removeClass('me-field-error');
        LANGS.forEach(function (c) {
            var suffix = c === 'fr' ? '' : c;
            var fields = ['#libelle' + suffix];
            if (ed.level === 1) { fields.push('#prix' + suffix); }
            fields.forEach(function (f) {
                if (!$.trim($(f).val())) {
                    $(f).addClass('me-field-error');
                    if (!firstBad) { firstBad = c; }
                }
            });
        });
        if (ed.level < N && !$('#meParent').val()) {
            $('#meParent').addClass('me-field-error');
            firstBad = firstBad || 'fr';
        }
        if (firstBad) {
            showLang(firstBad);
            $('#meFormError').html('<div class="alert alert-danger py-2">Complétez les champs en rouge'
                + (firstBad !== 'fr' ? ' (onglet ' + firstBad.toUpperCase() + ')' : '') + '.</div>');
            $('#meEditor .offcanvas-body').scrollTop(0);
            return false;
        }
        return true;
    }

    function save(e) {
        e.preventDefault();
        if (!validate()) { return; }
        if (ed.level === 1) {
            LANGS.forEach(function (c) {
                if (!quills[c]) { return; }
                var html = quills[c].getText().trim() === '' ? '' : quills[c].root.innerHTML;
                $(c === 'fr' ? '#description' : '#description' + c).val(html);
            });
        }
        var $btn = $('#meSave').prop('disabled', true).html('<i class="fa-light fa-spinner fa-spin"></i> Enregistrement…');
        $.ajax({
            url: ACTION[ed.level], method: 'POST', data: new FormData($('#meForm')[0]),
            processData: false, contentType: false, dataType: 'json'
        }).done(function (d) {
            if (d && d.error) {
                $('#meFormError').html(d.error);
                $('#meEditor .offcanvas-body').scrollTop(0);
                return;
            }
            editor.hide();
            toast.fire({ icon: 'success', title: (d && d.success) || 'Enregistré' });
            refresh();
        }).fail(fail).always(function () {
            $btn.prop('disabled', false).html('<i class="fa-light fa-check"></i> Enregistrer');
        });
    }

    /* Un seul bouton (onglet FR) : traduit le nom, et pour un article la description, vers toutes les langues.
       Le prix FR est recopié dans toutes les langues (le prix ne dépend pas de la langue). */
    function translateAll() {
        var codes = CFG.langues.map(function (l) { return l.code; });
        var src = $.trim($('#libelle').val());
        if (!codes.length) { return; }
        if (!src) {
            $('#libelle').addClass('me-field-error').trigger('focus');
            toast.fire({ icon: 'info', title: "Saisissez d'abord le nom en français." });
            return;
        }
        var url = ed.level === 1 ? 'api/translate-article.php' : 'api/translate.php';
        var $b = $('#meTranslateAll');
        var html = $b.html();
        var manquantes = [];
        $b.prop('disabled', true).html('<i class="fa-light fa-spinner fa-spin"></i> Traduction…');

        var calls = [$.ajax({ url: url, method: 'POST', dataType: 'json', data: { text: src, targets: codes } }).done(function (r) {
            codes.forEach(function (c) {
                if (r && r.translations && r.translations[c]) {
                    $('#libelle' + c).val(r.translations[c]).removeClass('me-field-error');
                } else {
                    manquantes.push(c.toUpperCase());
                }
            });
        })];
        if (ed.level === 1) {
            syncPrix();
            var desc = quills.fr ? quills.fr.getText().trim() : '';
            if (desc) {
                calls.push($.ajax({ url: url, method: 'POST', dataType: 'json', data: { text: desc, targets: codes } }).done(function (r) {
                    codes.forEach(function (c) {
                        if (r && r.translations && r.translations[c]) { setQuill(c, r.translations[c]); }
                    });
                }));
            }
        }

        $.when.apply($, calls)
            .done(function () {
                if (manquantes.length) {
                    toast.fire({ icon: 'warning', title: 'Non traduit : ' + manquantes.join(', ') + '. Complétez à la main.' });
                } else {
                    toast.fire({ icon: 'success', title: 'Traduit dans toutes les langues' });
                }
            })
            .fail(function () { toast.fire({ icon: 'error', title: 'Traduction indisponible, saisissez-la à la main.' }); })
            .always(function () {
                $b.prop('disabled', false).html(html);
                updateDots();
            });
    }

    function setFile(file) {
        if (!file) { return; }
        if (!/\.(jpe?g|png|webp)$/i.test(file.name)) {
            toast.fire({ icon: 'error', title: 'Seuls les fichiers JPG, PNG et WebP sont acceptés.' });
            $('#image').val('');
            return;
        }
        setPreview(URL.createObjectURL(file));
    }

    /* ---------------------------------------------------------------- événements */
    function bind() {
        $('#meTreeBody')
            .on('click', '.me-caret[data-toggle]', function (e) {
                e.stopPropagation();
                var k = $(this).data('toggle');
                if ($(this).hasClass('empty')) { select(k); return; }
                state.open[k] = !state.open[k];
                store('me-open-' + CFG.market, state.open);
                renderTree();
            })
            .on('click', '.me-node', function () { select($(this).data('key')); });

        $('#meCrumbs').on('click', 'a[data-key]', function () { select($(this).data('key')); });

        $('#meSearch').on('input', function () {
            var q = $.trim(this.value);
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                state.search = q;
                if (q.length >= 2) {
                    runSearch();
                } else if (state.mode === 'search') {
                    select(state.key, true);
                } else {
                    renderTree();
                }
            }, 250);
        });

        $('#meFilters').on('click', '.me-chip', function () {
            state.etat = String($(this).data('etat'));
            loadList();
        });

        $('#meAdd').on('click', function () { openEditor(listLevel(), null, parentOfList()); });
        $('#meList').on('click', '.me-empty-add', function () { openEditor(listLevel(), null, parentOfList()); });
        $('#meEditParent').on('click', function () {
            var n = state.nodes[state.key];
            if (n) { openEditor(n.level, n.id, n.parent ? state.nodes[n.parent].id : null); }
        });

        $('#meList').on('click', '[data-act]', function (e) {
            var $row = $(this).closest('.me-row');
            var it = findItem($row);
            if (!it) { return; }
            var a = $(this).data('act');
            var parentId = it.parent ? (state.nodes[it.parent] ? state.nodes[it.parent].id : null) : parentOfList();
            if (a === 'main') {
                a = (it.level > 1 && it.etat === '0' && state.nodes[it.level + '-' + it.id]) ? 'open' : (can(it.level, 'u') ? 'edit' : null);
            }
            if (a === 'open') { select(it.level + '-' + it.id); }
            if (a === 'edit') { openEditor(it.level, it.id, parentId); }
            if (a === 'statut') { toggleStatut(it); }
            if (a === 'etat') { toggleEtat(it); }
            if (a === 'duplicate') { duplicate(it); }
            e.preventDefault();
        });

        $('#meTreeOpen').on('click', openTree);
        $('#meTreeClose, #meTreeBackdrop').on('click', closeTree);

        $(window).on('hashchange', function () {
            var k = decodeURIComponent(location.hash.slice(1)) || 'root';
            if (k !== state.key || state.mode !== 'list') { select(k, true); }
        });

        // éditeur
        $('#meForm').on('submit', save);
        $('#meForm').on('click', '.me-lang', function () { showLang($(this).data('lang')); });
        $('#meForm').on('click', '#meTranslateAll', translateAll);
        $('#meForm').on('input', 'input[data-fr], input[data-tr]', function () {
            $(this).removeClass('me-field-error');
            if (this.id === 'prix') { syncPrix(); }
            updateDots();
        });
        $('#etat_prix').on('change', function () { $('#meNoteWrap').toggle(ed.level === 2 && this.value === '2'); });
        $('#vip').on('change', function () { $('#meCodeVipWrap').toggle(CFG.vip && ed.level === N && this.value === '2'); });
        $('#meDisplayImage').on('change', function () { $('#display_image').val(this.checked ? '1' : '2'); });
        $('#image').on('change', function () { setFile(this.files[0]); });
        $('#meDrop')
            .on('dragover dragenter', function (e) { e.preventDefault(); $(this).addClass('drag'); })
            .on('dragleave drop', function () { $(this).removeClass('drag'); })
            .on('drop', function (e) {
                e.preventDefault();
                var files = e.originalEvent.dataTransfer.files;
                if (!files.length) { return; }
                try { $('#image')[0].files = files; } catch (err) { /* navigateur ancien : le clic reste possible */ }
                setFile(files[0]);
            });
    }

    $(function () {
        state.open = store('me-open-' + CFG.market) || {};
        setupQuills();
        // Options par défaut : compatible Bootstrap 5.0 → 5.3 (« backdrop: 'static' » n'existe qu'à partir de 5.2)
        editor = new bootstrap.Offcanvas(document.getElementById('meEditor'));
        bind();
        loadTree().done(function () {
            select(decodeURIComponent(location.hash.slice(1)) || 'root', true);
        });
    });
})(jQuery);
