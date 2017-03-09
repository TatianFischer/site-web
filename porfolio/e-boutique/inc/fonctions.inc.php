<?php 

// ----- FONCTION DEBUG
// - Pour afficher des print-r ou des var-dump :
function debug($arg){
	echo '<div style="color: white; font-weight: bold; padding: 10px; background:#'.rand('111111','999999').'">';
	$trace = debug_backtrace(); // debug_backtrace() est une fonction qui permet de récupérer toutes les infos et notamment le fichier on fait appel à la fonction. (tableau multidimmensionnel)
	echo 'Le debug a été demandé dans le fichier : '.$trace[0]['file'].' à la ligne : '.$trace[0]['line'].'<hr>';

	echo '<pre>';
	print_r($arg);
	echo '<pre>';
	
	echo '</div>';
}

// ----- FONCTION USERCONNECTE
// - pour voir si l'utilisateur est connecté
function userConnecte(){
	if(isset($_SESSION['membre'])){
		return TRUE;
	} else {
		return FALSE;
	}
}

// ----- FONCTION USERADMIN
// - Pour voir si l'utilisateur est connecté et admin
function userAdmin(){
	if(userConnecte() && $_SESSION['membre']['statut'] == 1){
		return TRUE;
	} else {
		return FALSE;
	}
}

// ----- FONCTION REGEX
function verif_regex($choix, $variable){
	if($choix == 'carac_nbr'){
		return preg_match('#^[a-zA-Z0-9.\' _-]+$#',$variable);
	} else if($choix == 'caractere') {
		return preg_match('#^[a-zA-Z.\' _-]+$#',$variable);
	} else if($choix == 'nombre') {
		return preg_match('#^[0-9]+$#',$variable);
	} else {
		echo 'erreur dans la fonction verif_regex()';
	}
}
//  La fonction preg_match() permet de vérifier si les caractères contenu dans une chaine de caractères sont conformes à ce que l'on attend.
	// Argument :
		//1. regex (expression régulière)
		//2. la chaine de caractère
	// Valeurs de retour :
		// 1 <=> true (ok)
		// 0 <=> false

// ----- FONCTION CREATIONpANIER
function creationPanier(){
	if(!isset($_SESSION['panier'])){
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_produit'] = array();
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['prix'] = array();
		$_SESSION['panier']['photo'] = array();
	}
	return true;
}

// ----- FONCTION AJOUTpANIER
function ajoutPanier($id_produit, $quantite, $titre, $prix, $photo){
	// Création du panier s'il n'existe pas déjà
	creationPanier();

	// Vérification que le produit en cours d'ajout n'existe pas déjà dans le panier.
	$positionPdt = array_search($id_produit, $_SESSION['panier']['id_produit']);
	// Array_search() permet de chercher un élément dans les valeurs d'un array. S'il trouve l'élément, il noous retourne son emplacement sinon, il nous retourne FALSE.
	// Arg1 : l'élément à chercher
	// Arg2 : l'array où l'on cherche

	if($positionPdt !== FALSE){
		// Le produit est déjà dans le panier
		$_SESSION['panier']['quantite'][$positionPdt] += $quantite;
	} else {
		// Sinon on le crée
		$_SESSION['panier']['id_produit'][] = $id_produit;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['prix'][] = $prix;
		$_SESSION['panier']['photo'][] = $photo;
	}
}

// ----- FONCTION QUANTITEpANIER
function quantitePanier(){
	$quantite = 0;
	if(isset($_SESSION['panier']) && !empty($_SESSION['panier']['quantite'])){
		for ($i=0; $i < sizeof($_SESSION['panier']['quantite']) ; $i++) { 
			$quantite += $_SESSION['panier']['quantite'][$i];
		}
	}
	if ($quantite != 0) {
		return $quantite;
	}
}

// ----- FONCTION MONTANTtOTAL
function montantTotal(){
	$total = 0;
	if(isset($_SESSION['panier']) && !empty($_SESSION['panier']['prix'])){
		for ($i=0; $i < sizeof($_SESSION['panier']['prix']) ; $i++) { 
			$total += $_SESSION['panier']['prix'][$i]*$_SESSION['panier']['quantite'][$i];
			// Pour chaque produit dans le panier je multiplie son prix et sa quantié demandée
		}
	}
	if ($total != 0) {
		return round($total, 2);
	}
}

// ----- FONCTION RETIRERpRODUIT
function retirerProduit($id_produit_a_supprimer){
	$position_produit_a_supprimer = array_search($id_produit_a_supprimer, $_SESSION['panier']['id_produit']);

	if($position_produit_a_supprimer !== FALSE){
		array_splice($_SESSION['panier']['id_produit'], $position_produit_a_supprimer, 1);
		array_splice($_SESSION['panier']['quantite'], $position_produit_a_supprimer, 1);
		array_splice($_SESSION['panier']['titre'], $position_produit_a_supprimer, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit_a_supprimer, 1);
		array_splice($_SESSION['panier']['photo'], $position_produit_a_supprimer, 1);

		// arry_splice() me permet de supprimer une ligne dans un array et de réaffecter les indices de manière à ce que qu'il n'y ait pas d'indice manquant.
	}
}	
?>