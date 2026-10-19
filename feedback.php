<?php 
    
    include ('header.php');
?>
 
<style>
.fb-stat-card { border-left: 4px solid #4e73df; border-radius: .5rem; }
.fb-stat-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #858796; font-weight: 700; }
.fb-stat-value { font-size: 1.6rem; font-weight: 700; color: #2e2f3e; line-height: 1.2; }
.fb-stars { color: #f6c23e; font-size: .95rem; letter-spacing: 1px; }

.fb-dist-row { display: flex; align-items: center; gap: .5rem; font-size: .8rem; margin-bottom: 3px; }
.fb-dist-row .fb-dist-label { width: 34px; color: #6e707e; flex-shrink: 0; }
.fb-dist-row .fb-dist-bar-bg { flex: 1; background: #eaecf4; border-radius: 6px; height: 8px; overflow: hidden; }
.fb-dist-row .fb-dist-bar-fill { background: #f6c23e; height: 100%; border-radius: 6px; }
.fb-dist-row .fb-dist-count { width: 28px; text-align: right; color: #858796; flex-shrink: 0; }

.fb-search-input { min-width: 220px; }
.fb-select { width: auto; }

.fb-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
.fb-chip {
    border: 1px solid #d1d3e2; background: #fff; color: #5a5c69;
    border-radius: 20px; padding: .25rem .8rem; font-size: .8rem; cursor: pointer;
    transition: all .15s ease;
}
.fb-chip:hover { background: #f8f9fc; }
.fb-chip.active { background: #4e73df; border-color: #4e73df; color: #fff; }

.fb-card {
    border: 1px solid #e3e6f0; border-radius: .6rem; padding: 1rem;
    height: 100%; background: #fff; transition: box-shadow .15s ease, transform .15s ease;
    border-left: 4px solid #d1d3e2;
}
.fb-card:hover { box-shadow: 0 .3rem .9rem rgba(0,0,0,.08); transform: translateY(-2px); }
.fb-card.fb-r5, .fb-card.fb-r4 { border-left-color: #1cc88a; }
.fb-card.fb-r3 { border-left-color: #f6c23e; }
.fb-card.fb-r2, .fb-card.fb-r1 { border-left-color: #e74a3b; }
.fb-card.fb-r0 { border-left-color: #d1d3e2; }

.fb-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; margin-bottom: .6rem; }
.fb-avatar {
    width: 38px; height: 38px; border-radius: 50%; background: #4e73df; color: #fff;
    display: flex; align-items: center; justify-content: center; font-weight: 700;
    font-size: .95rem; flex-shrink: 0;
}
.fb-identity { display: flex; gap: .6rem; align-items: center; min-width: 0; }
.fb-name { font-weight: 700; font-size: .9rem; color: #2e2f3e; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.fb-email { font-size: .75rem; color: #858796; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 180px; }
.fb-date { font-size: .72rem; color: #b7b9cc; white-space: nowrap; }

.fb-badge {
    display: inline-block; font-size: .78rem; font-weight: 600; padding: .15rem .55rem;
    border-radius: 12px; margin-bottom: .5rem;
}
.fb-badge.fb-r5, .fb-badge.fb-r4 { background: #e3fbf0; color: #17a673; }
.fb-badge.fb-r3 { background: #fff8e6; color: #dda20a; }
.fb-badge.fb-r2, .fb-badge.fb-r1 { background: #fbe9e7; color: #e02d1b; }
.fb-badge.fb-r0 { background: #eaecf4; color: #858796; }

.fb-comment { font-size: .85rem; color: #4a4b5c; line-height: 1.45; margin-bottom: .6rem; word-break: break-word; }
.fb-comment.fb-empty-comment { color: #b7b9cc; font-style: italic; }

.fb-card-footer { font-size: .72rem; color: #b7b9cc; display: flex; align-items: center; gap: .3rem; border-top: 1px dashed #eee; padding-top: .5rem; }
</style>
<main id="main" class="main">
      <div class="pagetitle">
      <h1>Feedback</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li> 
          <li class="breadcrumb-item active">Feedback</li>
        </ol>
      </nav>
    </div> 
    
<?php if ($market->feedback == '2'){ ?>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Stats bar -->
                <div class="row" id="fb-stats-row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm fb-stat-card">
                            <div class="card-body py-3">
                                <div class="fb-stat-label">Total avis</div>
                                <div class="fb-stat-value" id="fb-stat-total">--</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="card shadow-sm fb-stat-card">
                            <div class="card-body py-3">
                                <div class="fb-stat-label">Note moyenne</div>
                                <div class="fb-stat-value" id="fb-stat-average">--</div>
                                <div class="fb-stars" id="fb-stat-stars"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm fb-stat-card">
                            <div class="card-body py-3">
                                <div class="fb-stat-label mb-2">Répartition des notes</div>
                                <div id="fb-stat-distribution"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4 small">
                    <div class="card-header py-3">
                        <div class="row align-items-center g-2">
                            <div class="col-md-4">
                                <h6 class="m-0 font-weight-bold">Liste des feedback</h6>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                    <input type="text" id="fb-search" class="form-control form-control-sm fb-search-input" placeholder="Rechercher nom, email, commentaire...">
                                    <select id="fb-sort" class="form-select form-select-sm fb-select">
                                        <option value="date_desc">Plus récents</option>
                                        <option value="date_asc">Plus anciens</option>
                                        <option value="rating_desc">Meilleure note</option>
                                        <option value="rating_asc">Note la plus basse</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="fb-chips mt-3" id="fb-rating-chips">
                            <button type="button" class="fb-chip active" data-rating="">Tous</button>
                            <button type="button" class="fb-chip" data-rating="5">5 😀</button>
                            <button type="button" class="fb-chip" data-rating="4">4 😊</button>
                            <button type="button" class="fb-chip" data-rating="3">3 😐</button>
                            <button type="button" class="fb-chip" data-rating="2">2 😟</button>
                            <button type="button" class="fb-chip" data-rating="1">1 😔</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div id="fb-loading" class="fb-loading text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <div class="mt-2 text-muted">Chargement des avis...</div>
                        </div>

                        <div id="fb-empty" class="fb-empty text-center py-5" style="display:none;">
                            <i class="fad fa-comment-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucun avis ne correspond à votre recherche.</p>
                        </div>

                        <div class="row" id="fb-grid" style="display:none;"></div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4" id="fb-pagination-wrap" style="display:none;">
                            <div class="text-muted small" id="fb-pagination-info"></div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="fb-pagination"></ul>
                            </nav>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
        <?php }else{ ?>  
    
 
      <section class="section" > 
        <div role="tabpanel" class="tab-pane" id="monthly">
          <div class="row justify-content-center">
            <div class="col-md-4 ">
              <div class="price-item text-center">
                <div class="price-top">
                  <h4>Module - Feedback</h4>
                  <h2 class="mb-0">99 DT <sup> TTC</sup></h2> 
                </div>
                <div class="price-content">
                  <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Amélioration de l'expérience client :</b> Permet aux clients de partager leurs commentaires pour améliorer leur expérience.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Collecte de données et analyse :</b> Fournit des informations précieuses pour analyser les préférences des clients et améliorer votre stratégie.</span>
                    </li>
                    <li>
                      <i class="zmdi zmdi-check mr-2"></i>
                      <span class="c-black"><b>Renforcement de la fidélité et de l'engagement :</b> Favorise la fidélité des clients en leur offrant un moyen de s'exprimer et de se sentir écoutés.</span>
                    </li> 
                  </ul> 
                   <button data-key="<?= $object->encrypt('8') ?>"  data-type="module" class="buy-button btn btn-custom">Acheter maintenant</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    </section>    
    <?php } ?>  
</main>
 

<?php
include('footer.php');
?> 


<script>
$(document).ready(function(){

    var fb = {
        page: 0,
        length: 9,
        search: '',
        sort: 'date_desc',
        rating: '',
        draw: 0
    };

    var searchTimer = null;

    function escapeHtml(str){
        if(str === null || str === undefined) return '';
        return String(str)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function initials(name){
        if(!name || name === 'Anonyme') return '?';
        var parts = name.trim().split(/\s+/);
        var letters = parts[0].charAt(0) + (parts.length > 1 ? parts[parts.length - 1].charAt(0) : '');
        return letters.toUpperCase();
    }

    function deviceIcon(device){
        if(!device) return '<i class="fad fa-question-circle"></i>';
        var d = device.toLowerCase();
        if(d.indexOf('iphone') !== -1 || d.indexOf('android') !== -1 || d.indexOf('mobi') !== -1){
            return '<i class="fad fa-mobile-alt"></i>';
        }
        if(d.indexOf('ipad') !== -1 || d.indexOf('tablet') !== -1){
            return '<i class="fad fa-tablet-alt"></i>';
        }
        return '<i class="fad fa-desktop"></i>';
    }

    function loadStats(){
        $.post('feedback-action', { action: 'stats' }, function(res){
            $('#fb-stat-total').text(res.total);
            $('#fb-stat-average').text(res.total > 0 ? res.average + ' / 5' : '--');

            var full = Math.round(res.average);
            var stars = '';
            for(var i = 1; i <= 5; i++){
                stars += (i <= full) ? '★' : '☆';
            }
            $('#fb-stat-stars').text(res.total > 0 ? stars : '');

            var distHtml = '';
            for(var r = 5; r >= 1; r--){
                var count = res.distribution[r] || 0;
                var pct = res.total > 0 ? Math.round((count / res.total) * 100) : 0;
                distHtml += '<div class="fb-dist-row">' +
                    '<div class="fb-dist-label">' + r + ' ★</div>' +
                    '<div class="fb-dist-bar-bg"><div class="fb-dist-bar-fill" style="width:' + pct + '%;"></div></div>' +
                    '<div class="fb-dist-count">' + count + '</div>' +
                    '</div>';
            }
            $('#fb-stat-distribution').html(distHtml);
        }, 'json');
    }

    function renderCard(item){
        var r = item.rating || 0;

        var commentHtml = item.commentaire
            ? '<div class="fb-comment">' + escapeHtml(item.commentaire) + '</div>'
            : '<div class="fb-comment fb-empty-comment">Aucun commentaire</div>';

        var emailHtml = item.email
            ? '<div class="fb-email">' + escapeHtml(item.email) + '</div>'
            : '';

        return '' +
        '<div class="col-md-6 col-xl-4 mb-3">' +
            '<div class="fb-card fb-r' + r + '">' +
                '<div class="fb-card-header">' +
                    '<div class="fb-identity">' +
                        '<div class="fb-avatar">' + initials(item.name) + '</div>' +
                        '<div>' +
                            '<div class="fb-name">' + escapeHtml(item.name) + '</div>' +
                            emailHtml +
                        '</div>' +
                    '</div>' +
                    '<div class="fb-date">' + escapeHtml(item.date_full) + '</div>' +
                '</div>' +
                '<div class="fb-badge fb-r' + r + '">' + escapeHtml(item.rating_label) + '</div>' +
                commentHtml +
                '<div class="fb-card-footer">' + deviceIcon(item.device) + ' ' + escapeHtml(item.device) + '</div>' +
            '</div>' +
        '</div>';
    }

    function renderPagination(total, filtered){
        var totalPages = Math.max(1, Math.ceil(filtered / fb.length));
        var currentPage = fb.page + 1;

        var from = filtered === 0 ? 0 : (fb.page * fb.length) + 1;
        var to = Math.min(filtered, (fb.page + 1) * fb.length);
        $('#fb-pagination-info').text('Affichage de ' + from + ' à ' + to + ' sur ' + filtered + ' avis');

        var html = '';
        html += '<li class="page-item' + (currentPage === 1 ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (fb.page - 1) + '">&laquo;</a></li>';

        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);

        for(var p = start; p <= end; p++){
            html += '<li class="page-item' + (p === currentPage ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + (p - 1) + '">' + p + '</a></li>';
        }

        html += '<li class="page-item' + (currentPage === totalPages ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (fb.page + 1) + '">&raquo;</a></li>';

        $('#fb-pagination').html(html);
        $('#fb-pagination-wrap').show();
    }

    function loadFeedbacks(){
        fb.draw++;
        var currentDraw = fb.draw;

        $('#fb-loading').show();
        $('#fb-empty').hide();
        $('#fb-grid').hide();
        $('#fb-pagination-wrap').hide();

        $.post('feedback-action', {
            action: 'fetch',
            start: fb.page * fb.length,
            length: fb.length,
            search: fb.search,
            sort: fb.sort,
            rating_filter: fb.rating,
            draw: fb.draw
        }, function(res){
            if(res.draw !== currentDraw) return; // stale response, ignore

            $('#fb-loading').hide();

            if(!res.data || res.data.length === 0){
                $('#fb-empty').show();
                $('#fb-pagination-wrap').hide();
                return;
            }

            var html = '';
            res.data.forEach(function(item){
                html += renderCard(item);
            });

            $('#fb-grid').html(html).show();
            renderPagination(res.recordsTotal, res.recordsFiltered);
        }, 'json');
    }

    // Search (debounced)
    $('#fb-search').on('input', function(){
        var val = $(this).val();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function(){
            fb.search = val;
            fb.page = 0;
            loadFeedbacks();
        }, 400);
    });

    // Sort
    $('#fb-sort').on('change', function(){
        fb.sort = $(this).val();
        fb.page = 0;
        loadFeedbacks();
    });

    // Rating chips
    $('#fb-rating-chips').on('click', '.fb-chip', function(){
        $('.fb-chip').removeClass('active');
        $(this).addClass('active');
        fb.rating = $(this).data('rating');
        fb.page = 0;
        loadFeedbacks();
    });

    // Pagination clicks
    $('#fb-pagination').on('click', 'a.page-link', function(e){
        e.preventDefault();
        var page = parseInt($(this).data('page'));
        if(isNaN(page) || page < 0) return;
        fb.page = page;
        loadFeedbacks();
        $('html, body').animate({ scrollTop: $('#fb-grid').offset().top - 100 }, 200);
    });

    loadStats();
    loadFeedbacks();

});
</script>