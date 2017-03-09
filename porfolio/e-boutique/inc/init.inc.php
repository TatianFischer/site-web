<?php 
// CONNECTION A LA BASE DE DONNEES
$pdo = new PDO('mysql:host=db672303829.db.1and1.com;dbname=db672303829', 'dbo672303829', '$Fitz123');
// SESSION
session_start();

//CHEMIN
define('RACINE_SITE','/porfolio/e-boutique/');


// VARIABLES
$msg = '';
$page = '';
$contenu = '';
$sousmenu = '';



// AUTRES INCLUSIONS
require_once('fonctions.inc.php');


?>