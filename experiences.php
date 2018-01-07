<?php
// HEADER ------------------------------
$title = 'Expériences';
$page = 'Tatiana FISCHER : Compétences et expériences';
$css = 'style-CV.css';

require_once('inc/header.inc.php');
?>
<!-- Contenu HTML -->
<main class="row r-m">
	<div id="page" class="col-xs-12">
	    <h1>Curriculum Vitae</h1>
	    
<!-- COMPETENCES -->
	    <section id="competence" class="row r-m">
			<div class="col-md-4">
				<h2>Langages</h2>
				<ul class="row r-m">
					<li class="col-md-12 col-xs-6">HTML5 & CSS3</li>
					<li class="barre col-md-12 col-xs-6" data-length="80"></li>
					<li class="col-md-12 col-xs-6">Javascript & jQuery</li>
					<li class="barre col-md-12 col-xs-6" data-length="70"></li>
					<li class="col-md-12 col-xs-6">PHP procédural et POO</li>
					<li class="barre col-md-12 col-xs-6" data-length="70"></li>
                    <li class="col-md-12 col-xs-6">SQL (MySQL & MariaDB)</li>
                    <li class="barre col-md-12 col-xs-6" data-length="70"></li>
				</ul>
			</div>
			<div class="col-md-4">
			    <h2>Frameworks</h2>
			    <ul class="row r-m">
			        <li class="col-md-12 col-xs-6">Bootstrap</li>
			        <li class="barre col-md-12 col-xs-6" data-length="70"></li>
                    <li class="col-md-12 col-xs-6">Laravel 5.4</li>
                    <li class="barre col-md-12 col-xs-6" data-length="60"></li>
                    <li class="col-md-12 col-xs-6">Symfony 2.9 et 3</li>
                    <li class="barre col-md-12 col-xs-6" data-length="60"></li>
                    <li class="col-md-12 col-xs-6">Code Igniter</li>
                    <li class="barre col-md-12 col-xs-6" data-length="20"></li>
			    </ul>
			</div>
			<div class="col-md-4">
			    <h2>CMS</h2>
			    <ul class="row r-m">
                    <li class="col-md-12 col-xs-6">Magento 1.9 et 2.1</li>
                    <li class="barre col-md-12 col-xs-6" data-length="60"></li>
                    <li class="col-md-12 col-xs-6">Wordpress</li>
                    <li class="barre col-md-12 col-xs-6" data-length="20"></li>
			    </ul>
			    <h2>Outils</h2>
			    <ul class="row r-m">
			    	<li class="col-md-12 col-xs-6">Linux/GNU</li>
					<li class="barre col-md-12 col-xs-6" data-length="50"></li>
					<li class="col-md-12 col-xs-6">Git & GitHub</li>
					<li class="barre col-md-12 col-xs-6" data-length="50"></li>
			    </ul>
			</div>
		</section>
	</div>
</main>

<?php

// FOOTER -------------------------------
require_once('inc/footer.inc.php');
?>