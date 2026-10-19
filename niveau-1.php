<?php  
    include ('header.php');
    
   if($market->niveau == '4'){  
    
        if($_POST['categorie_sub_sub']){
            $categorie_sub_sub = json_decode($object->get_categorie($_POST['categorie_sub_sub'],'categorie_sub_sub'));
        }else{
            echo '<script>window.location.href="niveau-4"</script>';
        } 
        
        if($_POST['categorie_sub']){
            $categorie_sub = json_decode($object->get_categorie($_POST['categorie_sub'],'categorie_sub'));
        }else{
            echo '<script>window.location.href="niveau-4"</script>';
        } 
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            echo '<script>window.location.href="niveau-4"</script>';
        }   
   } else if($market->niveau == '3'){   
        
        if($_POST['categorie_sub']){
            $categorie_sub = json_decode($object->get_categorie($_POST['categorie_sub'],'categorie_sub'));
        }else{
            echo '<script>window.location.href="niveau-3"</script>';
        } 
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            echo '<script>window.location.href="niveau-3"</script>';
        }     
   } else if($market->niveau == '2'){   
        
        if($_POST['categorie']){
            $categorie = json_decode($object->get_categorie($_POST['categorie'],'categorie'));
        }else{
            echo '<script>window.location.href="niveau-3"</script>';
        }     
   }   
?>
<style>
    .form-control {
        margin-bottom: 10px;
    }
    
    .breadcrumb-item span {
        color: #899bbd;
        font-size: 14px;
        font-weight: 400;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Articles</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="/"><?= $market->market ?></a></li>  
          <?php if($market->niveau == '4'){ ?>
            <li class="breadcrumb-item"><a href="/niveau-4">Point de vente  </a></li> 
            <li class="breadcrumb-item categorie-sub-page" data-id="<?= $_POST['categorie_sub'] ?>"><a href="#">Catégories  </a></li> 
            <li class="breadcrumb-item categorie-page" data-id="<?= $_POST['categorie'] ?>"><a href="#">Sous Catégories </a></li> 
          <?php }  ?>
          
          <?php if($market->niveau == '3'){ ?>
            <li class="breadcrumb-item" ><a href="niveau-3">Catégories  </a></li> 
            <li class="breadcrumb-item categorie-page" data-id="<?= $_POST['categorie'] ?>"><a href="#">Sous Catégories </a></li> 
          <?php }?>
          
          <?php if($market->niveau == '2'){ ?>
            <li class="breadcrumb-item" ><a href="niveau-2">Catégories </a></li> 
          <?php }?>
          
            <li class="breadcrumb-item active">Articles</li>
                  <li class="breadcrumb-item" style="position: absolute; right: 31px;"> 
             <button class="informations btn btn-sm btn-light  "   ><i class="far fa-info"></i> Informations</button> 
            </li>  
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                 <div class="card shadow mb-4 ">
                    <div class="card-header py-3">
                    	<div class="row">
                        	<div class="col">
                        		<h5 class=" "><?= ucwords($categorie->libelle) ?></h5>
                        	</div>
                        	<div class="col" align="right"> 
                        		<?php if($object->autorisation('c',$user->id,$page_actuel) == 2){?>
                        		    <button type="button" name="add_categorie" id="add_categorie" class="btn btn-primary  btn-sm"  ><i class="fal fa-plus"></i> Ajouter</button>
                        	    <?php }?>
                        	</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-bordered"> 
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#fr-tab">Français</button>
                            </li> 
                            <?php 
                                if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                              <li class="nav-item"  >
                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#<?= $row['code']?>-tab"><?= $row['libelle']?></button>
                               </li>
                             <?php } }?>
                             <li class="nav-item">
                                <button class="nav-link  " data-bs-toggle="tab" data-bs-target="#archive-tab"><i class="fa-light fa-box-archive"></i> Archive</button>
                            </li> 
                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active fr-tab" id="fr-tab">
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm table-hover  DataTable" id="categorie_table_fr" width="100%" cellspacing="0" >
                                        <thead>
                                            <tr>
                                                <th>Ordre</th>	
                                                <th>Libelle</th>
                                                <th>Prix</th>
                                                <th>Description</th>
                                                <th>Allergènes</th>
                                                <th>Image</th>	 
                                                <?= ($market->ingredient == '2' ) ? '<th>Ingrédients </th>'  : '' ?>
                                                <th>Statut</th> 
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            
                           <?php 
                           if($market->multi_langue == '1'){ 
                                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                                $result = $object->get_result();
                                foreach($result as $row){
                            ?>
                                <div class="tab-pane fade <?= $row['code']?>-tab" id="<?= $row['code']?>-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm table-hover  DataTable" id="categorie_table_<?= $row['code']?>" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Ordre</th>	
                                                    <th>Libelle</th> 
                                                    <th>Prix</th>
                                                    <th>Description</th> 
                                                    <th>Allergènes</th>
                                                    <th>Image</th>	 
                                                    <?= ($market->ingredient == '2' ) ? '<th>Ingrédients </th>'  : '' ?>
                                                    <th>Statut</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                             <?php } } ?>
                                <div class="tab-pane fade archive-tab" id="archive-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm table-hover  DataTable" id="categorie_table_archive" width="100%" cellspacing="0" >
                                            <thead>
                                                <tr>
                                                    <th>Ordre</th>	
                                                    <th>Libelle</th> 
                                                    <th>Prix</th>
                                                    <th>Description</th> 
                                                    <th>Allergènes</th>
                                                    <th>Image</th>	 
                                                    <?= ($market->ingredient == '2' ) ? '<th>Ingrédients </th>'  : '' ?>
                                                    <th>Statut</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
   <!--modal-->
    <div class="modal fade" id="categorieModal"  data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl  ">
            <form method="post" id="categorie_form" enctype="multipart/form-data">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal_title"> Ajout catégorie </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 70vh;    overflow-y: scroll;">
                        <span id="form_message"></span>

                        <div class="row">
                            
                        
                            
                            <?php  if($market->niveau != '1'){  ?>
                             
                            
                            <h5 class="text-primary">Catégories</h5>

                            <div class="col-md-3 ">
                                <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select name="categorie" id="categorie" class="form-control form-control-sm" required  data-parsley-trigger="keyup">
                                    <?php  if($market->niveau > 2){  ?>
                                        <?= $object->get_categories($market->id,'categorie' , $_POST['categorie_sub'] ); ?>
                                    <?php }else{   ?>
                                     <?= $object->get_categories($market->id,'categorie' ); ?>
                                    <?php } ?>
                                </select>
                            </div> 
                             
                            <?php }   ?>
                            
                           <h5 class="text-primary">Allergènes</h5>
                            
                              <div class="col-md-3">
                                <label for="allergens" class="form-label">Allergènes</label>
                                <select  name="allergens[]" id="allergens" class="form-control form-control-sm selectpicker" data-size="5" data-live-search="true" title="Sélectionnez "  data-parsley-trigger="keyup"  multiple> 
								    <?= $object->get_allergens(); ?>
								</select>    
                            </div>  
                            
                            
                            <h5 class="text-primary">Libelles</h5>
                            
                            <div class="col-md-4 ">
                                <label for="libelle" class="form-label">Français <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" id="libelle" class="form-control form-control-sm" required placeholder="libelle" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                            
                            <div class="col-md-2 ">
                                <label class="form-label">Traduire</label>
                                <button type="button" id="btn-traduire-libelle" class="btn btn-sm btn-primary" title="Traduire automatiquement vers les autres langues">
                                    <i class="fa fa-language"></i> Traduire
                                </button>
                            </div>
                            
                            <?php
                            if ($market->multi_langue == '1') {
                                $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
                                $result = $object->get_result();
                                foreach ($result as $row) {
                            ?>
                            
                            <div class="col-md-4 ">
                                <label for="libelle<?= $row['code'] ?>" class="form-label"><?= $row['libelle'] ?> <span class="text-danger">*</span></label>
                                <input type="text" name="libelle<?= $row['code'] ?>" id="libelle<?= $row['code'] ?>" class="form-control form-control-sm" required placeholder="libelle" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                            
                            <?php
                                }
                            }
                            ?>
                            
                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            <h5 class="text-primary">Tarifs</h5>
                            
                            <div class="col-md-3 ">
                                <label for="prix" class="form-label">Français <span class="text-danger">*</span></label>
                                <input type="text" name="prix" id="prix" class="form-control form-control-sm" required placeholder="Prix" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                            
                            <div class="col-md-2 ">
                                <label class="form-label">Dupliquer</label>
                                <button type="button" id="btn-traduire-prix" class="btn btn-sm btn-primary" title="Copier le prix vers les autres langues">
                                    <i class="fa fa-copy"></i> Dupliquer
                                </button>
                            </div>
                            
                            <?php
                            if ($market->multi_langue == '1') {
                                $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
                                $result = $object->get_result();
                                foreach ($result as $row) {
                            ?>
                            
                            <div class="col-md-3 ">
                                <label for="prix<?= $row['code'] ?>" class="form-label"><?= $row['libelle'] ?> <span class="text-danger">*</span></label>
                                <input type="text" name="prix<?= $row['code'] ?>" id="prix<?= $row['code'] ?>" class="form-control form-control-sm" required placeholder="Prix" data-parsley-maxlength="150" data-parsley-trigger="keyup">
                            </div>
                            
                            <?php
                                }
                            }
                            ?>
                            
                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            <h5 class="text-primary">Descriptions</h5>
                            
                            <div class="col-md-6" style="margin-bottom: 80px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="description" class="form-label">Français</label>
                                    <button type="button" id="btn-traduire-description" class="btn btn-sm btn-primary" title="Traduire automatiquement vers les autres langues">
                                        <i class="fa fa-language"></i> Traduire
                                    </button>
                                </div>
                                <div class="quill-editor-default">
                                </div>
                                <textarea name="description" id="description" style='display:none'></textarea>
                            </div>
                            
                            <?php
                            if ($market->multi_langue == '1') {
                                $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
                                $result = $object->get_result();
                                foreach ($result as $row) {
                            ?>
                            
                            <div class="col-md-6" style="margin-bottom: 80px;">
                                <label for="description<?= $row['code'] ?>" class="form-label"><?= $row['libelle'] ?></label>
                                <div class="quill-editor-default<?= $row['code'] ?>">
                                </div>
                                <textarea name="description<?= $row['code'] ?>" id="description<?= $row['code'] ?>" style='display:none'></textarea>
                            </div>
                            
                            <?php
                                }
                            }
                            ?>
                            
                            

                            <div class="col-md-12"  >
                                <hr>
                            </div>
                            
                             <h5 class="text-primary">Graphiques</h5>

                           
                            <div class="col-md-6 ">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control form-control-sm files"   data-parsley-trigger="keyup">
                                <span id="categorie_uploaded_image"></span>   
                            </div> 
                            
                            <div class="col-md-3 ">
                                <label for="display_image" class="form-label">Afficher l'image</label>
                                <select name="display_image" id="display_image" class="form-control form-control-sm  "   data-parsley-trigger="keyup">
                                    <option value="2">Non</option>
                                    <option value="1">Oui</option>
                                </select> 
                            </div> 
                

                            <?php include('planning-form.php'); ?> 
                             
                          
                             
                        </div>                        
                    </div>
                    <div class="modal-footer">   
                        <input type="hidden" name="market" id="market"   value="<?= $market->id ?>"/>
                      	<input type="hidden" name="hidden_id" id="hidden_id" />
              			<input type="hidden" name="action" id="action" value="Add" />
              			<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
              			<input type="submit" name="submit" id="submit_button" class="btn btn-sm  btn-primary" value="Add" />
              			
                    </div>
                  </div>
            </form>
        </div>
    </div>  
 
<?php include ('footer.php'); ?>
      
<script>
 

$(document).ready(function(){ 
    
    var lightbox = GLightbox({
        selector: '.glightbox'
    });
        
    // --- Initialisation Quill (français + langues) : inchangé, fourni pour référence ---

    var description = new Quill('.quill-editor-default', {
        theme: 'snow',
        placeholder: 'Ecrire une description detaillee...',
        debug: 'warn',
        modules: {
            toolbar: {
                container: [
                    [{ font: [] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ], 
            },
            clipboard: { matchVisual: false },
            history: { delay: 1000, maxStack: 100, userOnly: true }
        },
        formats: [
            'font', 'header', 'bold', 'italic', 'underline', 'strike',
            'color', 'background', 'script', 'list', 'bullet', 'check',
            'indent', 'align', 'blockquote', 'code-block', 'link', 'image', 'video'
        ]
    });
    
    <?php
    if ($market->multi_langue == '1') {
        $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
        $result = $object->get_result();
        foreach ($result as $row) {
    ?>
    
    var description<?= $row['code'] ?> = new Quill('.quill-editor-default<?= $row['code'] ?>', {
        theme: 'snow',
        placeholder: 'Ecrire une description detaillee...',
        debug: 'warn',
        modules: {
            toolbar: {
                container: [
                    [{ font: [] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ],
            },
            clipboard: { matchVisual: false },
            history: { delay: 1000, maxStack: 100, userOnly: true }
        },
        formats: [
            'font', 'header', 'bold', 'italic', 'underline', 'strike',
            'color', 'background', 'script', 'list', 'bullet', 'check',
            'indent', 'align', 'blockquote', 'code-block', 'link', 'image', 'video'
        ]
    });
    
    <?php
        }
    }
    ?>
    
    // --- Traduction automatique ---
    
    /**
     * Repère tous les champs dont l'id commence par `prefix`, et renvoie
     * la liste des suffixes (codes langue), en excluant le champ français lui-même.
     * ex: getTargetCodes('input[id^="libelle"]', 'libelle') -> ['en','ar',...]
     */
    function getTargetCodes(selector, prefix) {
        var codes = [];
        $(selector).each(function () {
            var id = $(this).attr('id');
            if (id && id !== prefix) {
                codes.push(id.replace(prefix, ''));
            }
        });
        return codes;
    }
    
    /**
     * Appelle l'API de traduction et remplit les inputs #{fieldPrefix}{code}.
     */
    function translateAndFillInputs(texte, targets, fieldPrefix, $btn, originalHtml) {
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Traduction...');
    
        $.ajax({
            url: 'api/translate-article.php',
            method: 'POST',
            dataType: 'json',
            data: {
                text: texte,
                targets: targets
            },
            success: function (response) {
                if (response.success) {
                    $.each(response.translations, function (code, valeur) {
                        $('#' + fieldPrefix + code).val(valeur);
                    });
                }
                if (response.errors && Object.keys(response.errors).length > 0) {
                    console.warn('Langues non traduites automatiquement (' + fieldPrefix + ') :', response.errors);
                }
            },
            error: function () {
                alert('Erreur lors de la traduction automatique. Veuillez saisir les champs manuellement.');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }
    
    // Libelles
    $(document).on('click', '#btn-traduire-libelle', function () {
        var $btn = $(this);
        var originalHtml = $btn.html();
        var texte = $.trim($('#libelle').val());
    
        if (texte === '') {
            alert('Veuillez saisir le libellé en français avant de traduire.');
            return;
        }
    
        var targets = getTargetCodes('input[id^="libelle"]', 'libelle');
        if (targets.length === 0) {
            return;
        }
    
        translateAndFillInputs(texte, targets, 'libelle', $btn, originalHtml);
    });
    
    // Tarifs : un prix ne se "traduit" pas, on duplique simplement la valeur (pas d'appel API)
    $(document).on('click', '#btn-traduire-prix', function () {
        var valeur = $.trim($('#prix').val());
    
        if (valeur === '') {
            alert('Veuillez saisir le prix en français avant de le dupliquer.');
            return;
        }
    
        var targets = getTargetCodes('input[id^="prix"]', 'prix');
        targets.forEach(function (code) {
            $('#prix' + code).val(valeur);
        });
    });
    
    // Descriptions (Quill) : on traduit le texte brut (sans mise en forme),
    // puis on l'injecte dans chaque éditeur Quill de langue avec ta méthode habituelle
    // (clipboard.convert + setContents(delta, 'silent')), en référençant chaque
    // instance directement par son nom (comme ton code de chargement) plutôt que
    // via un lookup dynamique.
    $(document).on('click', '#btn-traduire-description', function () {
        var $btn = $(this);
        var originalHtml = $btn.html();
        var texte = $.trim(description.getText());
    
        if (texte === '') {
            alert('Veuillez saisir la description en français avant de traduire.');
            return;
        }
    
        var targets = getTargetCodes('textarea[id^="description"]', 'description');
        if (targets.length === 0) {
            return;
        }
    
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Traduction...');
    
        $.ajax({
            url: 'api/translate-article.php',
            method: 'POST',
            dataType: 'json',
            data: {
                text: texte,
                targets: targets
            },
            success: function (response) {
                if (response.success) {
                    var translations = response.translations;
    
    <?php
    if ($market->multi_langue == '1') {
        $object->query = "select * from langue where statut = 'Activer' and id in ( SELECT langue from market_langue where market = '" . $market->id . "' and etat = '1')";
        $result = $object->get_result();
        foreach ($result as $row) {
    ?>
                    if (translations['<?= $row['code'] ?>'] !== undefined) {
                        var deltaTraduction<?= $row['code'] ?> = description<?= $row['code'] ?>.clipboard.convert(translations['<?= $row['code'] ?>']);
                        description<?= $row['code'] ?>.setContents(deltaTraduction<?= $row['code'] ?>, 'silent');
                    }
    <?php
        }
    }
    ?>
                }
                if (response.errors && Object.keys(response.errors).length > 0) {
                    console.warn('Langues non traduites automatiquement (description) :', response.errors);
                }
            },
            error: function () {
                alert('Erreur lors de la traduction automatique. Veuillez saisir les descriptions manuellement.');
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
    
    $(document).on('click', '.nav-link', function(){
        $('.DataTable').DataTable().ajax.reload();
    });
  
    var dataTable_fr = $('#categorie_table_fr').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : true,
    	"serverSide" : true,
        "dom": 'Blfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 
        ],
    	"paging":false, 
    	"select" : true,
    	"rowReorder": 
    	{
            selector: 'td:nth-child(1)'
        },
    	"order" : [],
    	"ajax" : {
    		url:"niveau-1-action",
    		type:"POST",
    		data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>action:'fetch'}
    	},
    	"columnDefs":[
    		{
    			"targets":['_all'],
    			"orderable":false, 
    		},
    	],  
    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                
                $('#categorie_table_fr td:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_fr th:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_fr td:nth-last-child(2)').attr('style', 'width: 5% !important;');
                $('#categorie_table_fr th:nth-last-child(2)').attr('style', 'width: 5% !important;');
    		}
    });

	dataTable_fr.on( 'row-reordered', function ( e, diff, edit ) {
    
        var id_table = new Array();
        
        var newpos = new Array();
        
        diff.forEach((item, index)=>{ 
            id_table.push($(item.oldData).data('id'));
            newpos.push(item.newPosition);
        });
        
        $.ajax({

      		url:"niveau-1-action",

	      	method:"POST",

	      	data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>id_table:id_table,newpos:newpos, action:'update_order'}, 

	      	success:function(data)
	      	{
	      	    $('.DataTable').DataTable().ajax.reload(); 
						
	      	     notificationSwal.fire({
                    icon: 'success',
                    title: data,
                });
	      	}

	    })
    } );
  
    var dataTable_archive = $('#categorie_table_archive').DataTable({
       "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    	"processing" : true,
    	"serverSide" : true,
    	"paging":false, 
        "dom": 'Blfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 
        ],
    	"select" : true,
    	"rowReorder": 
    	{
            selector: 'td:nth-child(1)'
        },
    	"order" : [],
    	"ajax" : {
    		url:"niveau-1-action",
    		type:"POST",
    		data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>action:'fetch_archive'}
    	},
    	"columnDefs":[
    		{
    			"targets":['_all'],
    			"orderable":false, 
    		},
    	],  
    	"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                $('#categorie_table_archive td:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_archive th:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_archive td:nth-last-child(2)').attr('style', 'width: 5% !important;');
                $('#categorie_table_archive th:nth-last-child(2)').attr('style', 'width: 5% !important;');
    		}
    });

	dataTable_archive.on( 'row-reordered', function ( e, diff, edit ) {
    
        var id_table = new Array();
        
        var newpos = new Array();
        
        diff.forEach((item, index)=>{ 
            id_table.push($(item.oldData).data('id'));
            newpos.push(item.newPosition);
        });
        
        $.ajax({

      		url:"niveau-1-action",

	      	method:"POST",

	      	data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>id_table:id_table,newpos:newpos, action:'update_order'}, 

	      	success:function(data)
	      	{
	      	    $('.DataTable').DataTable().ajax.reload(); 
						
	      	     notificationSwal.fire({
                    icon: 'success',
                    title: data,
                });
	      	}

	    })
    } );
	
    <?php 
        if($market->multi_langue == '1'){ 
            $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
            $result = $object->get_result();
            foreach($result as $row){
    ?>
    
        var dataTable_<?= $row['code']?> = $('#categorie_table_<?= $row['code']?>').DataTable({
    	   "language": {  url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/French.json' }, 
    		"processing" : true,
    		"serverSide" : true,
	        "dom": 'Blfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print', 
            ],
    		"paging":false, 
        	"select" : true,
    		"rowReorder": 
    		{
                selector: 'td:nth-child(1)'
            },
    		"order" : [],
    		"ajax" : {
    			url:"niveau-1-action",
    			type:"POST",
    			data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>action:'fetch<?= $row['code']?>'}
    		},
    		"columnDefs":[
    			{
    				"targets":['_all'],
    				"orderable":false, 
    			},
    		], 
    		"drawCallback": function(settings) {
                var lightbox = GLightbox({
                  selector: '.glightbox'
                });
                
                $('#categorie_table_<?= $row['code']?> td:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_<?= $row['code']?> th:nth-last-child(1)').attr('style', 'width: 5% !important;');
                $('#categorie_table_<?= $row['code']?> td:nth-last-child(2)').attr('style', 'width: 5% !important;');
                $('#categorie_table_<?= $row['code']?> th:nth-last-child(2)').attr('style', 'width: 5% !important;');
    		}
    	});
    	
    	dataTable_<?= $row['code']?>.on( 'row-reordered', function ( e, diff, edit ) {
            
            var id_table = new Array();
            var newpos = new Array();
            
            diff.forEach((item, index)=>{
                id_table.push($(item.oldData).data('id'));
                newpos.push(item.newPosition);
            });
            
            $.ajax({
    
          		url:"niveau-1-action",
    
    	      	method:"POST",
    
    	      	data:{market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?>id_table:id_table,newpos:newpos, action:'update_order'}, 
    
    	      	success:function(data)
    	      	{
    	      	    $('.DataTable').DataTable().ajax.reload();
    	      	    
    	      	    notificationSwal.fire({
                        icon: 'success',
                        title: data,
                    });
    	      	}
    
    	    })
        } );
        
   <?php } }?>
      
    $('.files').on('change', function() {
        var fileInput = $(this);
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.webp)$/i;
        var files = fileInput.get(0).files;
    
        for (var i = 0; i < files.length; i++) {
          var file = files[i];
          var fileName = file.name;
          if (!allowedExtensions.test(fileName)) {
              Swal.fire({
                  icon: 'error',
                  title: 'Extension de fichier invalide.',
                  text: 'Seuls les fichiers JPG, JPEG, PNG et WebP sont autorisés.',
                }); 
            fileInput.val('');
            return;
          }
        }
      });
   
	$('#add_categorie').click(function(){
		
		$('#categorie_form')[0].reset();

		$('#categorie_form').parsley().reset();

    	$('#modal_title').text('Ajout Article');

    	planningReset();

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#categorieModal').modal('show');
    	
    	$('#allergens').val();
    	
    	$('#allergens').selectpicker('refresh'); 

    	$('#form_message').html(''); 
		
		$('#categorie_uploaded_image').html(''); 
		 
        $('#categorie').val(<?= $_POST['categorie'] ?>);
        
        description.setContents([{ insert: '\n' }]);
        
        <?php 
            if($market->multi_langue == '1'){ 
                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                $result = $object->get_result();
                foreach($result as $row){
        ?>
        description<?= $row['code']?>.setContents([{ insert: '\n' }]);
        <?php  }}?>
  
	});
 

	$('#categorie_form').parsley();

	$('#categorie_form').on('submit', function(event){
		event.preventDefault();
		if($('#categorie_form').parsley().isValid())
		{		
		    var editor_content = description.container.innerHTML
            
            $('#description').val($(editor_content).html());
            
            <?php 
                    if($market->multi_langue == '1'){ 
                        $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                        $result = $object->get_result();
                        foreach($result as $row){
            ?>
               var editor_content<?= $row['code']?> = description<?= $row['code']?>.container.innerHTML
                
                $('#description<?= $row['code']?>').val($(editor_content<?= $row['code']?>).html());
            <?php  }}?>
		    
			$.ajax({
				url:"niveau-1-action",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false, 
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Ajouter');
					}
					else
					{
						$('#categorieModal').modal('hide');
						$('.DataTable').DataTable().ajax.reload(); 
                        notificationSwal.fire({
                            icon: 'success',
                            title: data.success,
                        });
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var id = $(this).data('id');
		
		$('#categorie_form')[0].reset();

		$('#categorie_form').parsley().reset();

		$('#form_message').html(''); 
		
		$('#categorie_uploaded_image').html('');
		 
    	$('#allergens').val();
    	
    	$('#allergens').selectpicker('refresh'); 
		
        description.setContents([{ insert: '\n' }]);
        
        <?php 
            if($market->multi_langue == '1'){ 
                $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                $result = $object->get_result();
                foreach($result as $row){
        ?>
        description<?= $row['code']?>.setContents([{ insert: '\n' }]);
        
        <?php  }}?>
        
		$.ajax({

	      	url:"niveau-1-action",

	      	method:"POST",

	      	data:{id:id,market:<?= $market->id ?>,<?= ($_POST['categorie']) ? 'categorie:'.$_POST['categorie'].',' :'' ?> action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
	        	 
	        	
	        	$('#display_image').val(data.display_image); 
	        	
	        	$('#type').val(data.type);
	        	 	
            	$('#allergens').selectpicker('val', data.allergens);
            	
            	$('#allergens').selectpicker('refresh'); 
            	
	        	$('#libelle').val(data.libelle);
	        	
	        	$('#prix').val(data.prix); 
	        	
	        	$('#description').val(data.description);
	        	
	        	const value  = data.description;
                
                const delta  = description.clipboard.convert(value)
                    
                description.setContents(delta, 'silent')
	        	
	        	
               <?php 
                if($market->multi_langue == '1'){ 
                    $object->query="select * from langue where statut = 'Activer' and id in  ( SELECT langue from market_langue where market = '".$market->id."' and etat = '1')";
                    $result = $object->get_result();
                    foreach($result as $row){
                ?>
                
                $('#libelle<?= $row['code']?>').val(data.libelle<?= $row['code']?>);
                
                $('#prix<?= $row['code']?>').val(data.prix<?= $row['code']?>);
    	        	
	        	$('#description<?= $row['code']?>').val(data.description<?= $row['code']?>);
	        	
	        	const value<?= $row['code']?> = data.description<?= $row['code']?>;
                
                const delta<?= $row['code']?> = description<?= $row['code']?>.clipboard.convert(value<?= $row['code']?>)
                    
                description<?= $row['code']?>.setContents(delta<?= $row['code']?>, 'silent')
    	        	 
                <?php } } ?>
                  
                $('#categorie').val(data.categorie);
            
	        	$('#categorie_uploaded_image').html('<img src="'+data.image+'" class="img-fluid img-thumbnail glightbox" width="75" height="75" /><input type="hidden" name="hidden_categorie_image" value="'+data.image+'" />');
	        	
	            $('#modal_title').text('Modifier categorie');

                planningFill(data.planning);

	        	$('#action').val('Edit');
 

	        	$('#submit_button').val('Modifier'); 

	        	$('#categorieModal').modal('show');

	        	$('#hidden_id').val(id);

	      	}

	    })

	});

	$(document).on('click', '.duplicate_button', function(){

    	var id = $(this).data('id'); 

        Swal.fire({
            title: 'Es-tu sûr?',
            text: "êtes-vous sûr de dupliquer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui !'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"niveau-1-action",
                    method:"POST",
                    dataType:'json',
                    data:{id:id,market:<?= $market->id ?>, action:'duplicate', },
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'Article dupliquer!',
                        });
                    }
                });
            }
        });    
  	});

	$(document).on('click', '.statut_button', function(){

    	var id = $(this).data('id');
        var statut = $(this).data('statut');
        var next_statut = 'Activer';
        if(statut == 'Activer')
        {
            next_statut = 'Desactiver';
        }

        Swal.fire({
            title: 'Es-tu sûr?',
            text: "êtes-vous sûr de "+next_statut+"! (si un planning existe, il sera mis en pause)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, '+next_statut+'!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"niveau-1-action",
                    method:"POST",
                    data:{id:id,market:<?= $market->id ?>, action:'statut', statut:statut, next_statut:next_statut},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'article '+next_statut+'!',
                        });
                    }
                });
            }
        });    
  	});
  	
  	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');
        var etat = $(this).data('etat');
        var next_etat = '0';
        var affiche = 'reactivé' ;
        if(etat == '0')
        {
            affiche = 'supprimé ' ;
            next_etat = '1';
        }

        Swal.fire({
            title: 'Es-tu sûr?',
            text: "êtes-vous sûr de "+affiche+"!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Fermer',
            confirmButtonText: 'Oui, '+affiche+'!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"niveau-1-action",
                    method:"POST",
                    data:{id:id,market:<?= $market->id ?>, action:'etat', etat:etat, next_etat:next_etat},
                    success:function(data){
                        $('.DataTable').DataTable().ajax.reload();
                        notificationSwal.fire({
                            icon: 'success',
                            title: 'article '+affiche+'!',
                        });
                    }
                });
            }
        });   
        
  	});
  	
 	$(document).on('click', '.article_liste', function(){
        $.redirect("niveau-0", {
            article:$(this).data('id') 
            <?= ($_POST['categorie']) ? ',categorie:'.$_POST['categorie'] : '' ?>
            <?= ($_POST['categorie_sub']) ? ',categorie_sub:'.$_POST['categorie_sub'] : '' ?>
            <?= ($_POST['categorie_sub_sub']) ? ',categorie_sub_sub:'.$_POST['categorie_sub_sub'] : '' ?>
        }, "POST");  
    });
  	
   	$(document).on('click', '.categorie-page', function(){
        $.redirect("niveau-2", {
            categorie:$(this).data('id')
             <?= ($_POST['categorie_sub']) ? ',categorie:'.$_POST['categorie_sub'] : '' ?>
             <?= ($_POST['categorie_sub_sub']) ? ',categorie_sub:'.$_POST['categorie_sub_sub'] : '' ?>
        }, "POST");  
    });
  	
   	$(document).on('click', '.categorie-sub-page', function(){
        $.redirect("niveau-3", {
            categorie:$(this).data('id') 
             <?= ($_POST['categorie_sub_sub']) ? ',categorie_sub:'.$_POST['categorie_sub_sub'] : '' ?>
        }, "POST");  
    });
 
  	
	$(document).on("click", ".informations", function () {
     Swal.fire({
        title: 'Informations',
        html: `
          <div style="text-align:left;">
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-primary btn-sm me-2">
                <i class="fal fa-plus"></i> Ajouter
              </button>
              <span>Ajouter un nouveau</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-info btn-sm me-2">
                <i class="fa-duotone fa-list-tree"></i>
              </button>
              <span>Voir la liste des sous-éléments</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-success btn-sm me-2">Activer</button>
              <button type="button" class="btn btn-danger btn-sm me-2">Désactiver</button>
              <span>Changer le statut </span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-info btn-sm me-2">
                <i class="fa-duotone fa-copy"></i>
              </button>
              <span>Dupliquer (et son contenu)</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-warning btn-sm me-2">
                <i class="fa-duotone fa-sliders"></i>
              </button>
              <span>Modifier</span>
            </div>
    
            <div class="d-flex align-items-center mb-2">
              <button type="button" class="btn btn-success btn-sm me-2">
                <i class="fa-light fa-trash-undo"></i>
              </button>
              <button type="button" class="btn btn-danger btn-sm me-2">
                <i class="fa-light fa-trash-xmark"></i>
              </button>
              <span>Archiver ou désarchiver</span>
            </div>
          </div>
        `,
        showConfirmButton: false,
        width: 600
      });
  });

});
 
</script>