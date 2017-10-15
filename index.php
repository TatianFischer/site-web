<?php
// HEADER ------------------------------
$title = 'Développeuse web';
$page = 'Page d\'accueil du site';

require_once('inc/header.inc.php');
?>
<!-- Contenu HTML -->
<main class="row r-m">
    <div id="conteneur" class="col-xs-12">
        <h1>Tatiana FISCHER <br> Développeur-Intégrateur Web Junior</h1>
        <p>
            Actuellement en poste en tant que Développeuse Full-Stack au sein de l'entreprise EXacompta - Clairefontaine jusqu'au 31 mars 2017.<br>
            J'ai suivie une formation certifiante de Développeur-Intégrateur Web à l'école WebForce3.  Et je perfectionne sur les plateformes d'e-learning (OpenClassRoom notamment) et les sites de tutoriels.<br><br>
            Si vous êtes près à accueillir, dès maintenant, une fille <strong>développeuse full-stack</strong> dans votre équipe alors n'hésité pas à  <a href="contact">me contacter</a>.
        </p>
        <div id="bouton">
            <a href="CV.php">
                <img src="assets/images/curriculum.png" alt="vers le CV">
            <p>Vers le CV</p>
            </a>
        </div>
        <div class="col-md-offset-1 col-md-4 col-sm-5" id="contact">
            <h1>Contact</h1>
            <aside id="coordonnees">
                <p>Mon profil vous intéresse ?</p>
                <p>Contactez moi par mail :<br> <a href="mailto:tatiana.fischer@hotmail.fr">tatiana.fischer@hotmail.fr</a>
                </p>
                <p><a href="assets/Fischer-Tatiana-Developpeur-Web.pdf">Lien vers le CV imprimable</a></p>
            </aside>
        </div> 
        <aside class="col-sm-4 col-sm-offset-1" id="mobilite">
            <img src="assets/images/P_Eiffel.png" alt="dessin de la Tour Eiffel sur un fond rond gris"/>
            <p>Mobile sur Paris et sa région.</p>
        </aside>
        <div id="to-top">
            <a href="#top" title="remonter"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></a>
        </div>
    </div><!-- fin du conteneur -->
</main>
<?php

// FOOTER -------------------------------
require_once('inc/footer.inc.php');
?>
