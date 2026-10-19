<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style>

            
.emojis {
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.emojis input {
    display: none;
}

.emojis label {
    font-size: 2em;
    margin: 0 5px;
    cursor: pointer;
    opacity: 0.5; /* Start with grey */
    vertical-align: middle; /* Adjust the vertical alignment */
}

.emojis input:checked + label {
    opacity: 1; /* Change to full opacity when selected */
}

.emojis p {
    font-size: 0.6em;
}

.emojis div {
    margin: 1.3px;
}

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
        }
        
        
        .rating > input{ display:none;}
        
        .rating > label {
            position: relative;
            width: 1.1em;
            font-size: 15vw;
            color: <?= $market->background_color1 ?>;
            cursor: pointer;
        }
        
        .rating > label::before{
            content: "\2605";
            position: absolute;
            opacity: 0;
        }
        
        .rating > label:hover:before,
        .rating > label:hover ~ label:before {
            opacity: 1 !important;
        }
        
        .rating > input:checked ~ label:before{
            opacity:1;
        }
        
        .rating:hover > input:checked ~ label:before{ opacity: 0.4; }
        
          <style>
        .feedback-form{
            text-align:left;
            font-family:inherit;
        }

        .feedback-form p{
            text-align:center;
            margin:0 0 20px;
            color:#555;
            line-height:1.5;
        }

        .feedback-group{
            margin-bottom:16px;
        }

        .feedback-label{
            display:block;
            margin-bottom:6px;
            font-size:14px;
            font-weight:600;
            color:#333;
        }

        .feedback-input,
        .feedback-textarea{
            width:100%;
            padding:10px 12px;
            border:1px solid #d9d9d9;
            border-radius:8px;
            font-size:14px;
            outline:none;
            transition:.2s;
            box-sizing:border-box;
            background:#fff;
        }

        .feedback-input:focus,
        .feedback-textarea:focus{
            border-color:#2778c4;
            box-shadow:0 0 0 3px rgba(39,120,196,.15);
        }

        .feedback-textarea{
            resize:vertical;
            min-height:90px;
        }

        .feedback-consent{
            display:flex;
            align-items:flex-start;
            gap:10px;
            margin-bottom:20px;
            font-size:13px;
            line-height:1.5;
            color:#555;
        }

        .feedback-consent input{
            margin-top:3px;
        }

        .feedback-separator{
            border:none;
            border-top:1px solid #eee;
            margin:20px 0;
        }
    </style>
</style>



<script>

    setTimeout(function(){
         feedback(); 
    }, 360000);
      
    function feedback(){
        Swal.fire({
            title: 'Bienvenue 👋',
            html: `
                 <div class="feedback-form">
            
                    <p>
                        Merci de votre visite. Afin de personnaliser votre expérience,
                        nous vous invitons à renseigner les informations suivantes.
                    </p>
            
                    <div class="feedback-group">
                        <label class="feedback-label" for="name">
                            Nom & Prénom (Optionnel)
                        </label>
                        <input
                            type="text"
                            id="name"
                            class="feedback-input"
                            placeholder="Votre nom et prénom">
                    </div>
            
                    <div class="feedback-group">
                        <label class="feedback-label" for="email">
                            E-mail (Optionnel)
                        </label>
                        <input
                            type="email"
                            id="email"
                            class="feedback-input"
                            placeholder="votre@email.com">
                    </div>
            
                    <label class="feedback-consent">
                        <input type="checkbox" id="consent">
                        <span>
                            J'accepte que mes informations (nom, prénom et e-mail)
                            soient collectées et utilisées dans le cadre du suivi client
                            et de l'amélioration des services.
                        </span>
                    </label>
            
                    <hr class="feedback-separator">
            
                    <div class="emojis">
                        <div>
                            <input type="radio" name="emoji" id="emoji1" value="1">
                            <label for="emoji1">😔</label>
                        </div>
            
                        <div>
                            <input type="radio" name="emoji" id="emoji2" value="2">
                            <label for="emoji2">😟</label>
                        </div>
            
                        <div>
                            <input type="radio" name="emoji" id="emoji3" value="3">
                            <label for="emoji3">😐</label>
                        </div>
            
                        <div>
                            <input type="radio" name="emoji" id="emoji4" value="4">
                            <label for="emoji4">😊</label>
                        </div>
            
                        <div>
                            <input type="radio" name="emoji" id="emoji5" value="5">
                            <label for="emoji5">😀</label>
                        </div>
                    </div>
            
                    <div class="feedback-group" style="margin-top:18px;">
                        <label class="feedback-label" for="commentaire">
                            Merci de partager votre avis
                        </label>
            
                        <textarea
                            id="commentaire"
                            class="feedback-textarea"
                            placeholder="Votre commentaire"></textarea>
                    </div>
            
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Envoyer',
            confirmButtonColor: '#2778c4',
            cancelButtonText: 'Fermer',
            cancelButtonColor: '#6c757d',
        
            preConfirm: () => {
        
                const name = $('#name').val().trim(); 
                const email = $('#email').val().trim();
                const consent = $('#consent').is(':checked');
                const commentaire = $('#commentaire').val().trim();
                const rating = $('input[name="emoji"]:checked').val();
        
                if (!consent) {
                    Swal.showValidationMessage(
                        'Vous devez accepter la collecte de vos données.'
                    );
                    return false;
                }
        
                if (!rating) {
                    Swal.showValidationMessage(
                        'Veuillez sélectionner une note.'
                    );
                    return false;
                }
        
                return {
                    name, 
                    email,
                    commentaire,
                    rating
                };
            }
        }).then((result) => {
        
            if (result.isConfirmed) {
        
                var market = '<?= $market->id ?>';
        
                $.ajax({
                    url: "../module-feedback-action",
                    method: "POST",
                    data: { 
                        name: result.value.name,
                        email: result.value.email,
                        commentaire: result.value.commentaire,
                        rating: result.value.rating,
                        consent: 1,
                        market: market,
                        action: 'feedback'
                    },
                    dataType: 'JSON',
                    success: function(data) {
        
                        Swal.fire(
                            'Merci',
                            'Votre avis a été envoyé avec succès.',
                            'success'
                        );
        
                    }
                }); 
            }
        });
    }
    
    
   $('#feedbackbtn').click(function(){
        feedback();
    })
    
//     setTimeout(function(){
//          feedback(); 
//     }, 360000);
      
//     function feedback(){
//         Swal.fire({
//             title: 'Feedback', 
//             html: ` <div class="rating"> 
//                         <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
//                         <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
//                         <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
//                         <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
//                         <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
//                     </div>
//                     <div class="col-md-12">
//                         <label for="commentaire" class="form-label">Please leave your feedback below</label>
//                         <textarea rows="3" name="commentaire" id="commentaire" class="form-control form-control-sm" placeholder="..." data-parsley-trigger="keyup"></textarea>
//                     </div> `, 
//             showCancelButton:true,
//             confirmButtonText: 'Send',
//             confirmButtonColor:'#2778c4',
//             cancelButtonText: 'Cancel',
//             cancelButtonColor:'#6c757d', 
//               inputValidator: (value) => {
//                 return !value && 'You must enter a message!'
//               },
//               preConfirm: () => {
//                 const message = document.getElementById('commentaire').value
//                 if (message.length < 1) {
//                   Swal.showValidationMessage('You must enter a message!')
//                 }
//                 return { message: message }
//               }
//         }).then((result) => {
//             if (result.isConfirmed) { 
//                 var commentaire = $('#commentaire').val();
//                 var rating = $('input[name="rating"]:checked').val(); 
//                 var market = '<?= $market->id ?>';
//         		$.ajax({ 
//         	      	url:"../module-feedback-action",
//         	      	method:"POST",
//         	      	data:{
//         	      	        commentaire:commentaire, 
//         	      	        rating:rating, 
//         	      	        market:market, 
//         	      	        action:'feedback'},
//         	      	dataType:'JSON',
//         	      	success:function(data) {  
//                         Swal.fire(
//                           'Thank you',
//                           'Thank you for submitting your feedback!',
//                           'success'
//                         )
//         	      	} 
//         	    }) 
//             }
//         })
//     }
    
    
//   $('#feedbackbtn').click(function(){
//         feedback();
//     })
</script>