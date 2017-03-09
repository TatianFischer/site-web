$(function(){
    // DOM is ready to eat candies
    /*------------------------------------------------------------------------------*/
    //                    Validation de formulaire
    /*------------------------------------------------------------------------------*/
    $("#formContact").on("submit", function(event){
        // 1. Stopper l'envoi du formulaire
        event.preventDefault();
        
        // 2. Reset des actions
        $(".has-error").removeClass();
        $(".text-warning").remove();
        $('#formContact .alert-warning').remove();
        
        // 3; Vérification de l'email
        var email = $("#email");
        var isForValid = true;
        
        // -- Vérification du mail
        /*  https://paulund.co.uk/regular-expression-to-validate-email-address    */
        function validateEmail(email){
            var emailReg = new RegExp(/([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/i);
            var valid = emailReg.test(email);

            if(!valid) {
                return true;
            } else {
                return false;
            }
        }
        
        if(validateEmail(email.val()) || email.val().length == 0){
            isForValid = false;
            
            email.parent().addClass('has-error');
            $("<p>").text("Vérifier votre adresse email").addClass('text-warning').appendTo(email.parent());
        }
        
        // -- A la fin du traitement.
        if(isForValid){
            $(this).replaceWith("<div class='alert alert-success'>Votre demande a bien été envoyée ! Nous vous répondrons dans les meilleurs délais.</div>")
        }else{
            $('legend').after("<div class='alert alert-warning'>Nous ne sommes pas en mesure de valider vos informations.</div>")
        }
        
    });
    
    
    /*------------------------------------------------------------------------------*/
    //                    BONBONS Messages
    /*------------------------------------------------------------------------------*/
    
    
    // je créé une variable contenant mes citations
    var mesCitations = ["Les meilleurs bonbons de Paris près du Canal",
            "Du sucre doux pour vos papilles endormies",
            "Parce que quand même, il faut se faire plaisir."];

    // je crée un indice
    var j = 0;

    function bonbonMessage() {
        $('.message').fadeIn(1500);
        j++;
        if (j == 3){
            j = 0;
        }

        $('.message').html('<i class="fa fa-quote-left" aria-hidden="true"></i> '+mesCitations[j]+' <i class="fa fa-quote-right" aria-hidden="true"></i>').fadeOut(1500);
    }
        
    setInterval(bonbonMessage, 3000);
        
    
    
});

