<?php 

include('header.php'); 

?> 
<style>
    ul {
        list-style-type: none;
    }

</style>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Abonnements</h1>
        <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fad fa-home-lg"></i></a></li> 
            <li class="breadcrumb-item active">Abonnements</li>
        </ol>
        </nav>
    </div>
    
    
    <div class="container">
            <div class="text-center">
                <div class="nav price-tabs" role="tablist"> 
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#starter">Starter</a>
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#business">Business</a> 
                </div>
            </div>
            <div class="tab-content wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
                <div role="tabpanel" class="tab-pane fade show active" id="starter">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4 mb-30">
                            <div class="price-item text-center">
                                <div class="price-top">
                                    <h4>Tasty</h4>
                                    <h2 class="mb-0">99 <sup>DT</sup></h2>
                                    <span class="text-capitalize">1 Mois</span>
                                </div>
                                <div class="price-content">
                                    <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Accès BackOffice</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Maintenance 24/7</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Back-Up</span>
                                        </li>  
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Modules Gratuits</span>
                                        </li> 
                                        <li>
                                            <i class="fa-light fa-xmark text-danger fw-bolder"></i>
                                            <span>Mises à jour</span>
                                        </li> 
                                    </ul>
                                    <button data-key="<?= $object->encrypt('19') ?>" data-type="pack"  class="buy-button btn btn-custom">Acheter maintenant</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-30">
                            <div class="price-item text-center popular">
                                <div class="price-top">
                                    <h4>Juicy</h4>
                                    <h2 class="mb-0">249 <sup>DT</sup></h2>
                                    <span class="text-capitalize">3 Mois</span>
                                </div>
                                <div class="price-content">
                                    <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                                       <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Accès BackOffice</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Maintenance 24/7</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Back-Up</span>
                                        </li> 
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Modules Gratuits</span>
                                        </li> 
                                        <li>
                                            <i class="fa-light fa-xmark text-danger fw-bolder"></i>
                                            <span>Mises à jour</span>
                                        </li> 
                                    </ul>
                                    <button data-key="<?= $object->encrypt('16') ?>" data-type="pack"  class="buy-button btn btn-light">Acheter maintenant</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-30">
                            <div class="price-item text-center">
                                <div class="price-top">
                                    <h4>Appetizing</h4>
                                    <h2 class="mb-0">399 <sup>DT</sup></h2>
                                    <span class="text-capitalize">6 Mois</span>
                                </div>
                                <div class="price-content">
                                    <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Accès BackOffice</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Maintenance 24/7</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Back-Up</span>
                                        </li> 
                                        
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Modules Gratuits</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-xmark text-danger fw-bolder"></i>
                                            <span>Mises à jour</span>
                                        </li> 
                                    </ul>
                                    <button data-key="<?= $object->encrypt('15') ?>" data-type="pack"  class="buy-button btn btn-custom">Acheter maintenant</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="business">
                    <div class="row justify-content-center"> 
                        <div class="col-md-6 col-lg-4 mb-30">
                            <div class="price-item text-center popular">
                                <div class="price-top">
                                    <h4>Delicious</h4>
                                    <h2 class="mb-0">699 <sup>DT</sup></h2>
                                    <span class="text-capitalize">12 Mois</span>
                                </div>
                                <div class="price-content">
                                    <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Accès BackOffice</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Maintenance 24/7</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Back-Up</span>
                                        </li> 
                                        
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Modules Gratuits</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-xmark text-danger fw-bolder"></i>
                                            <span>Mises à jour</span>
                                        </li> 
                                    </ul>
                                    <button data-key="<?= $object->encrypt('17') ?>" data-type="pack"  class="buy-button btn btn-light">Acheter maintenant</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-30">
                            <div class="price-item text-center">
                                <div class="price-top">
                                    <h4>Lifetime</h4>
                                    <h2 class="mb-0">1099 <sup>DT</sup>+ 99 <sup> DT</sup>/an  </h2>
                                    <span class="text-capitalize">à vie</span>
                                </div>
                                <div class="price-content">
                                    <ul class="border-bottom mb-30 mt-md-4 pb-3 text-left">
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Accès BackOffice</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder "></i>
                                            <span class="c-black">Maintenance 24/7 | 1 ère année</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Back-Up | 1 ère année</span>
                                        </li> 
                                        
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Modules Gratuits</span>
                                        </li>
                                        
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Module feed-back</span>
                                        </li>
                                        
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span class="c-black">Module événements</span>
                                        </li>
                                        <li>
                                            <i class="fa-light fa-check text-success fw-bolder"></i>
                                            <span>Mises à jour</span>
                                        </li> 
                                    </ul>
                                    <button data-key="<?= $object->encrypt('14') ?>" data-type="pack"  class="buy-button btn btn-custom">Acheter maintenant</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</main>

<?php include('footer.php'); ?>
 