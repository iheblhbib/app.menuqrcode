<?php 

include('header.php'); 
 
?>


<style>
  .dq-wrap{ font-family:'Inter',sans-serif; color:#14110C; }
 
  @media (prefers-reduced-motion: reduce){
    .dq-wrap *{ animation:none !important; transition:none !important; }
  }
 
  /* ---- toolbar / period pills ---- */
  .dq-toolbar{ display:flex; justify-content:flex-end; margin-bottom:22px; }
  .dq-pills{ display:inline-flex; gap:4px; background:#F5EFE2; padding:5px; border-radius:999px; }
  .dq-pills button{
    border:none; background:transparent; cursor:pointer;
    padding:8px 16px; border-radius:999px;
    font-family:'Inter',sans-serif; font-weight:600; font-size:.78rem;
    color:#8A7B63; transition:.2s ease;
  }
  .dq-pills button:hover{ color:#14110C; }
  .dq-pills button.active{
    background:linear-gradient(135deg,#D9B872,#B8860B);
    color:#14110C;
    box-shadow:0 8px 16px -6px rgba(184,134,11,.5);
  }
  .dq-pills button:focus-visible{ outline:2px solid #B8860B; outline-offset:2px; }
  .dq-pills.disabled{ opacity:.6; pointer-events:none; }
 
  /* ---- loading progress bar ---- */
  .dq-progress{
    position:relative; height:3px; border-radius:999px; overflow:hidden;
    background:#F5EFE2; margin-bottom:18px; opacity:0; transition:opacity .25s ease;
  }
  .dq-progress.active{ opacity:1; }
  .dq-progress::after{
    content:""; position:absolute; top:0; left:-40%; width:40%; height:100%;
    border-radius:999px;
    background:linear-gradient(90deg,#14110C,#B8860B,#F0DFB8,#B8860B,#14110C);
    animation:dq-progress-slide 1.1s ease-in-out infinite;
  }
  @keyframes dq-progress-slide{ 0%{ left:-40%; } 50%{ left:60%; } 100%{ left:100%; } }
  @media (prefers-reduced-motion: reduce){
    .dq-progress::after{ animation:none; left:0; width:100%; }
  }
 
  /* ---- dimmed state while loading ---- */
  .dq-wrap.dq-loading .dq-kpi,
  .dq-wrap.dq-loading .dq-panel{ opacity:.55; filter:saturate(.65); pointer-events:none; }
 
  /* ---- KPI gradient cards ---- */
  .dq-kpi{
    position:relative; overflow:hidden;
    border-radius:20px; padding:24px; color:#fff;
    min-height:150px;
    box-shadow:0 16px 30px -12px var(--glow, rgba(0,0,0,.3));
    opacity:0; transform:translateY(14px);
    animation:dq-rise .55s ease forwards;
    transition:transform .25s ease, box-shadow .25s ease, opacity .25s ease, filter .25s ease;
  }
  .dq-kpi:hover{ transform:translateY(-5px); box-shadow:0 22px 40px -12px var(--glow, rgba(0,0,0,.4)); }
  .dq-kpi:nth-child(1) .dq-kpi, .row .col-xxl-3:nth-child(1) .dq-kpi{ animation-delay:.02s; }
  .row .col-xxl-3:nth-child(2) .dq-kpi{ animation-delay:.10s; }
  .row .col-xxl-3:nth-child(3) .dq-kpi{ animation-delay:.18s; }
  .row .col-xxl-3:nth-child(4) .dq-kpi{ animation-delay:.26s; }
  @keyframes dq-rise{ to{ opacity:1; transform:translateY(0); } }
 
  .dq-kpi::before{
    content:""; position:absolute; width:150px; height:150px; border-radius:50%;
    background:rgba(255,255,255,.16); filter:blur(6px);
    top:-50px; right:-40px;
    animation:dq-float 9s ease-in-out infinite alternate;
  }
  @keyframes dq-float{ to{ transform:translate(-10px,10px); } }
 
  .dq-kpi.g-violet{ background:linear-gradient(135deg,#D9B872,#B8860B); --glow:rgba(184,134,11,.45); }
  .dq-kpi.g-pink{   background:linear-gradient(135deg,#2B2620,#14110C); --glow:rgba(0,0,0,.35); }
  .dq-kpi.g-amber{  background:linear-gradient(135deg,#B8860B,#8C6A3B); --glow:rgba(140,106,59,.45); }
  .dq-kpi.g-teal{   background:linear-gradient(135deg,#3A2E1A,#14110C); --glow:rgba(0,0,0,.4); }
 
  .dq-icon{
    position:relative; width:44px;height:44px;border-radius:12px;
    background:rgba(255,255,255,.22); backdrop-filter:blur(4px);
    display:flex; align-items:center; justify-content:center;
    font-size:19px; margin-bottom:16px;
  }
  .dq-label{ position:relative; font-size:.72rem; text-transform:uppercase; letter-spacing:.07em; font-weight:600; opacity:.9; }
  .dq-value{ position:relative; font-family:'Poppins',sans-serif; font-weight:700; font-size:2.1rem; line-height:1; margin-top:8px; }
 
  /* ---- chart panels ---- */
  .dq-panel{
    background:#fff; border-radius:20px; padding:22px 22px 12px;
    box-shadow:0 10px 26px -16px rgba(20,17,12,.18);
    border:1px solid rgba(20,17,12,.07);
    height:100%;
    transition:opacity .25s ease, filter .25s ease;
  }
  .dq-panel-head{ display:flex; align-items:center; gap:9px; margin-bottom:6px; }
  .dq-panel-head h3{
    font-family:'Poppins',sans-serif; font-weight:600; font-size:.92rem;
    color:#14110C; margin:0;
  }
  .dq-dot{ width:9px;height:9px;border-radius:50%; flex-shrink:0; }
  .dq-dot.violet{ background:#B8860B; }
  .dq-dot.pink{   background:#14110C; }
  .dq-dot.amber{  background:#B8860B; }
  .dq-dot.teal{   background:#14110C; }
  .dq-empty{ color:#A9987C; font-size:.85rem; text-align:center; padding:38px 0; }
 
  /* ---- feedback list ---- */
  .dq-fb-list{ margin-top:8px; }
  .dq-fb-item{ display:flex; gap:14px; padding:14px 0; border-bottom:1px dashed rgba(20,17,12,.08); }
  .dq-fb-item:last-child{ border-bottom:none; }
  .dq-fb-avatar{
    flex:0 0 auto; width:38px;height:38px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    color:#fff; font-family:'Poppins',sans-serif; font-weight:700; font-size:13px;
  }
  .dq-fb-avatar.grad-0{ background:linear-gradient(135deg,#D9B872,#B8860B); }
  .dq-fb-avatar.grad-1{ background:linear-gradient(135deg,#2B2620,#14110C); }
  .dq-fb-avatar.grad-2{ background:linear-gradient(135deg,#B8860B,#8C6A3B); }
  .dq-fb-avatar.grad-3{ background:linear-gradient(135deg,#3A2E1A,#14110C); }
  .dq-fb-body{ flex:1; min-width:0; }
  .dq-fb-top{ display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }
  .dq-fb-name{ font-weight:600; font-size:.9rem; }
  .dq-fb-rating{
    font-family:'Poppins',sans-serif; font-weight:600; font-size:.72rem;
    background:linear-gradient(135deg,#D9B872,#B8860B); color:#fff;
    padding:3px 10px; border-radius:999px; white-space:nowrap;
  }
  .dq-fb-comment{ font-size:.85rem; color:#55493A; margin:5px 0 3px; line-height:1.45; }
  .dq-fb-comment:empty::before{ content:"— sans commentaire —"; color:#A9987C; font-style:italic; }
  .dq-fb-date{ font-size:.72rem; color:#A9987C; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
 
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard </h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
            <li class="breadcrumb-item active"><?= $market->market ?></li>
        </ol>
        </nav>
    </div>
 
    <section class="section dashboard">
      <div class="dq-wrap">
 
        <div class="dq-progress" id="dqProgress"></div>
 
        <div class="dq-toolbar">
            <div class="dq-pills" id="periodSwitch">
                <button type="button" data-days="1">Aujourd'hui</button>
                <button type="button" data-days="7">7 jours</button>
                <button type="button" class="active" data-days="30">30 jours</button>
                <button type="button" data-days="90">90 jours</button>
            </div>
        </div>
 
        <div class="row g-4 mb-1">
            <div class="col-xxl-3 col-md-6">
                <div class="dq-kpi g-violet">
                    <div class="dq-icon"><i class="bi bi-qr-code"></i></div>
                    <div class="dq-label">Accès QR</div>
                    <div class="dq-value" id="kpiAcces">0</div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dq-kpi g-pink">
                    <div class="dq-icon"><i class="bi bi-chat-square-text"></i></div>
                    <div class="dq-label">Feedback reçus</div>
                    <div class="dq-value" id="kpiFeedback">0</div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dq-kpi g-amber">
                    <div class="dq-icon"><i class="bi bi-star-fill"></i></div>
                    <div class="dq-label">Note moyenne</div>
                    <div class="dq-value" id="kpiNote">0.0</div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-6">
                <div class="dq-kpi g-teal">
                    <div class="dq-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                    <div class="dq-label">Connexions</div>
                    <div class="dq-value" id="kpiConnexions">0</div>
                </div>
            </div>
        </div>
 
        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="dq-panel">
                    <div class="dq-panel-head"><span class="dq-dot violet"></span><h3>Accès par jour</h3></div>
                    <div id="chart-access"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dq-panel">
                    <div class="dq-panel-head"><span class="dq-dot amber"></span><h3>Répartition des notes</h3></div>
                    <div id="chart-ratings"></div>
                </div>
            </div>
        </div>
 
        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="dq-panel">
                    <div class="dq-panel-head"><span class="dq-dot teal"></span><h3>Connexions par jour</h3></div>
                    <div id="chart-logins"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dq-panel">
                    <div class="dq-panel-head"><span class="dq-dot pink"></span><h3>Navigateurs</h3></div>
                    <div id="chart-browsers"></div>
                </div>
            </div>
        </div>
 
        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="dq-panel">
                    <div class="dq-panel-head"><span class="dq-dot violet"></span><h3>Derniers avis clients</h3></div>
                    <div id="feedbackList" class="dq-fb-list"></div>
                </div>
            </div>
        </div>
 
      </div>
    </section>
</main>
 

<?php include('footer.php'); ?>



  
<script>
$(function(){
    // ⚠️ adapte si $market->id existe séparément de $market->market
    const MARKET = "<?= htmlspecialchars($market->id, ENT_QUOTES) ?>";
    const API_URL = 'api/api.php';
    let DAYS = 30;
    let charts = {};
    const PALETTE = ['#B8860B','#14110C','#8C6A3B','#3A2E1A'];
 
    function animateNumber($el, end, decimals){
        decimals = decimals || 0;
        const duration = 700;
        const startTime = performance.now();
        function step(now){
            const p = Math.min(1, (now - startTime) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            const val = end * eased;
            $el.text(decimals ? val.toFixed(decimals) : Math.round(val));
            if(p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
 
    function initials(name){
        if(!name) return '?';
        const parts = name.trim().split(/\s+/);
        return (parts[0][0] + (parts[1] ? parts[1][0] : '')).toUpperCase();
    }
 
    function formatDate(iso){
        const d = new Date(iso.replace(' ', 'T'));
        if(isNaN(d)) return iso;
        return d.toLocaleDateString('fr-FR', { day:'2-digit', month:'short' });
    }
 
    const chartBaseOptions = {
        chart:{ toolbar:{ show:false }, background:'transparent', fontFamily:'Inter, sans-serif' },
        grid:{ borderColor:'#F3EEE3', strokeDashArray:4 },
        dataLabels:{ enabled:false },
        tooltip:{ theme:'light' }
    };
 
    let pendingRequests = 0;
    function beginLoad(){
        pendingRequests++;
        $('#dqProgress').addClass('active');
        $('.dq-wrap').addClass('dq-loading');
        $('#periodSwitch').addClass('disabled');
    }
    function endLoad(){
        pendingRequests = Math.max(0, pendingRequests - 1);
        if(pendingRequests === 0){
            $('#dqProgress').removeClass('active');
            $('.dq-wrap').removeClass('dq-loading');
            $('#periodSwitch').removeClass('disabled');
        }
    }
 
    function api(action, extra){
        beginLoad();
        return $.getJSON(API_URL, Object.assign({ action, market: MARKET, days: DAYS }, extra || {}))
            .always(endLoad);
    }
 
    function loadSummary(){
        api('summary').done(function(res){
            if(!res.success) return;
            animateNumber($('#kpiAcces'), res.acces, 0);
            animateNumber($('#kpiFeedback'), res.feedback, 0);
            animateNumber($('#kpiNote'), res.note_moyenne, 1);
            animateNumber($('#kpiConnexions'), res.connexions, 0);
        });
    }
 
    function loadAccessChart(){
        api('access_series').done(function(res){
            const rows = (res && res.data) || [];
            const categories = rows.map(r => formatDate(r.jour));
            const data = rows.map(r => parseInt(r.total, 10));
            if(charts.access){
                charts.access.updateOptions({ xaxis:{ categories } });
                charts.access.updateSeries([{ name:'Accès', data }]);
                return;
            }
            charts.access = new ApexCharts(document.querySelector('#chart-access'), Object.assign({}, chartBaseOptions, {
                chart: Object.assign({}, chartBaseOptions.chart, { type:'area', height:220 }),
                series:[{ name:'Accès', data }],
                xaxis:{ categories },
                colors:['#B8860B'],
                stroke:{ curve:'smooth', width:3 },
                fill:{ type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:.45, opacityTo:0, stops:[0,90,100] } }
            }));
            charts.access.render();
        });
    }
 
    function loadLoginsChart(){
        api('login_series').done(function(res){
            const rows = (res && res.data) || [];
            const categories = rows.map(r => formatDate(r.jour));
            const data = rows.map(r => parseInt(r.total, 10));
            if(charts.logins){
                charts.logins.updateOptions({ xaxis:{ categories } });
                charts.logins.updateSeries([{ name:'Connexions', data }]);
                return;
            }
            charts.logins = new ApexCharts(document.querySelector('#chart-logins'), Object.assign({}, chartBaseOptions, {
                chart: Object.assign({}, chartBaseOptions.chart, { type:'area', height:220 }),
                series:[{ name:'Connexions', data }],
                xaxis:{ categories },
                colors:['#14110C'],
                stroke:{ curve:'smooth', width:3 },
                fill:{ type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:.45, opacityTo:0, stops:[0,90,100] } }
            }));
            charts.logins.render();
        });
    }
 
    function loadRatingsChart(){
        api('feedback_ratings').done(function(res){
            const rows = (res && res.data) || [];
            const byRating = {1:0,2:0,3:0,4:0,5:0};
            rows.forEach(r => { byRating[r.rating] = parseInt(r.total, 10); });
            const series = [1,2,3,4,5].map(n => byRating[n] || 0);
            const total = series.reduce((a,b) => a+b, 0);
            const $target = $('#chart-ratings');
            if(total === 0){
                if(charts.ratings){ charts.ratings.destroy(); charts.ratings = null; }
                $target.html('<div class="dq-empty">Aucune note sur cette période</div>');
                return;
            }
            if($target.find('.dq-empty').length) $target.empty();
            if(charts.ratings){
                charts.ratings.updateSeries(series);
                return;
            }
            charts.ratings = new ApexCharts($target[0], Object.assign({}, chartBaseOptions, {
                chart: Object.assign({}, chartBaseOptions.chart, { type:'donut', height:220 }),
                series,
                labels:['1 ★','2 ★','3 ★','4 ★','5 ★'],
                colors:['#14110C','#3A2E1A','#8C6A3B','#B8860B','#D9B872'],
                legend:{ position:'bottom', fontSize:'12px' },
                dataLabels:{ enabled:true }
            }));
            charts.ratings.render();
        });
    }
 
    function loadBrowsersChart(){
        api('browser_stats').done(function(res){
            const rows = (res && res.data) || [];
            const categories = rows.map(r => r.browser || 'Inconnu');
            const data = rows.map(r => parseInt(r.total, 10));
            const $target = $('#chart-browsers');
            if(rows.length === 0){
                if(charts.browsers){ charts.browsers.destroy(); charts.browsers = null; }
                $target.html('<div class="dq-empty">Aucune donnée sur cette période</div>');
                return;
            }
            if($target.find('.dq-empty').length) $target.empty();
            if(charts.browsers){
                charts.browsers.updateOptions({ xaxis:{ categories } });
                charts.browsers.updateSeries([{ name:'Accès', data }]);
                return;
            }
            charts.browsers = new ApexCharts($target[0], Object.assign({}, chartBaseOptions, {
                chart: Object.assign({}, chartBaseOptions.chart, { type:'bar', height:220 }),
                series:[{ name:'Accès', data }],
                plotOptions:{ bar:{ horizontal:true, borderRadius:5, barHeight:'55%', distributed:true } },
                legend:{ show:false },
                xaxis:{ categories },
                colors:['#14110C','#B8860B','#3A2E1A','#D9B872','#8C6A3B','#2B2620']
            }));
            charts.browsers.render();
        });
    }
 
    function loadFeedbackList(){
        api('latest_feedback', { limit: 8 }).done(function(res){
            const rows = (res && res.data) || [];
            const $list = $('#feedbackList');
            $list.empty();
            if(rows.length === 0){
                $list.html('<div class="dq-empty">Aucun avis pour le moment</div>');
                return;
            }
            rows.forEach(function(fb, i){
                const $item = $('<div class="dq-fb-item">');
                $item.append('<div class="dq-fb-avatar grad-' + (i % 4) + '">' + initials(fb.name) + '</div>');
                const $body = $('<div class="dq-fb-body">');
                $body.append(
                    '<div class="dq-fb-top"><span class="dq-fb-name"></span><span class="dq-fb-rating"></span></div>' +
                    '<div class="dq-fb-comment"></div>' +
                    '<div class="dq-fb-date"></div>'
                );
                $body.find('.dq-fb-name').text(fb.name || 'Client anonyme');
                $body.find('.dq-fb-rating').html(parseFloat(fb.rating).toFixed(1) + ' <i class="bi bi-star-fill"></i>');
                $body.find('.dq-fb-comment').text(fb.commentaire || '');
                $body.find('.dq-fb-date').text(formatDate(fb.created));
                $item.append($body);
                $list.append($item);
            });
        });
    }
 
    function loadAll(){
        loadSummary();
        loadAccessChart();
        loadLoginsChart();
        loadRatingsChart();
        loadBrowsersChart();
        loadFeedbackList();
    }
 
    $('#periodSwitch button').on('click', function(){
        $('#periodSwitch button').removeClass('active');
        $(this).addClass('active');
        DAYS = parseInt($(this).data('days'), 10);
        loadAll();
    });
 
    loadAll();
});
</script>
 