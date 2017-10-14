<?php
require_once('../inc/init.inc.php');

if(isset($_GET['search'])){
	trim($_GET['search']); // On retire les espaces
	if($_GET['search'] != ""){
		$query = "SELECT * FROM produit 
					WHERE stock != 0
					AND (
					public LIKE :search 
					OR categorie LIKE :search 
					OR titre LIKE :search
					OR description LIKE :search
					) ORDER BY RAND() LIMIT 0,5";
		$search = '%'.$_GET['search'].'%';
		$resultat = $pdo -> prepare($query);
		$resultat -> bindParam(':search', $search, PDO::PARAM_STR);
		$resultat -> execute();
		$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($produits);
	}
}