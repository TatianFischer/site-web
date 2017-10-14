$(function(){
    /*-----------LABEL FLOTTANT--------------*/
	function floatLabel(inputType){
		$(inputType).each(function(){
			var $this = $(this);
            console.log('hello');
			// on focus add class active to label
			$this.focus(function(){
				$this.next().addClass("active");
			});
			//on blur check field and remove class if needed
			$this.blur(function(){
				if($this.val() === '' || $this.val() === 'blank'){
					$this.next().removeClass();
				}
			});
		});
	}
	// just add a class of "floatLabel to the input field!"
	floatLabel(".form-control");
    
    /*-----------VALIDATION DE FORMULAIRE--------------*/
    /*A la soumission de mon formulaire #formulaire, je souhaite vérifier les champs qui ont été remplis par mon visiteur.*/
    
    // 1. -- A la soumission du Formulaire
    $('#formulaire').on('submit', function(e){
        
        // 2. -- J'arrête le processus normal du submit
        e.preventDefault();
        
        // 2b -- À chaque soumission du formulaire je réinitialise les erreurs et je vérifie à nouveau celles qui restent ...
        $('#formulaire .has-error').removeClass('has-error');
        $('#formulaire p.text-danger').remove();
        $('#formulaire .alert-danger').remove();
        
        // 3. -- je récupère les champs à vérifier :

        var nomprenom   = $('#nomprenom');
        var email       = $('#email');
        var tel         = $('#telephone');
        var sujet       = $('#sujet');
        var message     = $('#message');
        
        // 4. -- je défini un booléen qui prendra pour valeur TRUE si le formulaire est valide et FALSE s'il est invalide.
        var isForValid = true;
        
        // -- Vérifier le nom et le prénom
        // -- si le champ a été laissé vide ...
        if(nomprenom.val().length == 0){
            // -- notre formulaire n'est plus valide.
            isForValid = false;
            // -- Je rajoute une class has-error au parent du champ input. Le contour de celui-ci sera donc en rouge...
            nomprenom.parent().addClass("has-error");
            $("<p>").text("N'oubliez pas de saisir votre nom complet").addClass('text-danger').appendTo(nomprenom.parent());
        }
        
        // -- Vérifier le numéro de teléphone
        // -- si le champ a été laissé vide ou pas de chiffre
        function validateTel(tel){

            var telReg = new RegExp(/^0[1-68]([-. ]?[0-9]{2}){4}$/i);
            var valid = telReg.test(tel);

            if(!valid) {
                return false;
            } else {
                return true;
            }
        }
        
        
        if(validateTel(tel)){
            // -- notre formulaire n'est plus valide.
            isForValid = false;
            // -- Je rajoute une class has-error au parent du champ input. Le contour de celui-ci sera donc en rouge...
            tel.parent().addClass("has-error");
            $("<p>").text("Vérifiez votre numéro de téléphone").addClass('text-danger').appendTo(tel.parent());
        }
        
        // -- Vérification du mail
        /*  https://paulund.co.uk/regular-expression-to-validate-email-address    */
        function validateEmail(email){
	        var emailReg = new RegExp(/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})/i);
            var valid = emailReg.test(email);

            if(!valid) {
                return false;
            } else {
                return true;
            }
        }
        

        if(!validateEmail(email.val())){
            isForValid = false;
            
            email.parent().addClass('has-error');
            $("<p>").text("Vérifier votre adresse email").addClass('text-danger').appendTo(email.parent());
        }
        
        // -- A la fin du traitement.
        if(isForValid){
            $(this).replaceWith("<div class='alert alert-success'>Votre demande a bien été envoyée ! Nous vous répondrons dans les meilleurs délais.</div>")
        }else{
            $('legend').after("<div class='alert alert-danger'>Nous ne sommes pas en mesure de valider vos informations.</div>")
        }
    });
});