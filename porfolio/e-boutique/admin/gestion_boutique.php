<?php
require_once('../inc/init.inc.php');

// Redirection si USER pas un ADMIN
if(!userAdmin()){ 
	header('location:'.RACINE_SITE.'connexion.php?action=deconnexion');
}

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************

$page = 'Gestion Boutique';
require_once('../inc/haut.inc.php');




// ******************************************
// **INSERTION / MODIFICATION  D'UN PRODUIT**
// ******************************************

if ($_POST) {
	//debug($_POST);
	//debug($_FILES);

	$nom_photo = 'defaut.jpg';

	if(isset($_POST['photo_actuelle'])){
		$nom_photo = $_POST['photo_actuelle'];
		// Si je suis dans le cadre d'une modification de produit, il existe un champs caché, photo_actuelle. Donc par défault la photo va prendre la valeur de la photo actuelle.
	}

	// Enregistrement de la photo dans le serveur.
	if (!empty($_FILES['photo']['name'])) {
		$nom_photo = $_POST['reference'].'_'.$_FILES['photo']['name'];
		// Je reconstitue le nom de la photo avec la référence du produit et son nom.

		$chemin_photo = $_SERVER['DOCUMENT_ROOT'].RACINE_SITE.'photo/'.$nom_photo;
		// je recompose donc le chemein absolu de la photo, nom compris.

		copy($_FILES['photo']['tmp_name'], $chemin_photo); // la fonction copy(source, destination) me permet de copier un fichier d'un emplacement à un autre.
		// L'empplacement final est représenté par son chemin absolu comprenant le nom de la photo.

		if (isset($_POST['photo_actuelle']) && $_POST['photo_actuelle'] != 'defaut.jpg') {
			$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'].RACINE_SITE.'photo/'.$_POST['photo_actuelle'];
			if (file_exists($chemin_photo_a_supprimer)) {
				unlink($chemin_photo_a_supprimer);
			}
		}
	}

	// ******
	// VERIFICATION DES INFOS DU PRODUIT AVANT INSERTION
	// ******


	// ******
	// INSERTION DES INFOS DU PRODUIT DANS LA BDD
	// ******
	if(empty($msg)){
		if (isset($_GET['action']) && $_GET['action'] == "modification" && !empty($_GET['id'])) {
			// Modification
			$resultat = $pdo -> prepare("REPLACE INTO produit VALUES (:id, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
			$resultat -> bindParam(':id', $_POST['id_produit'], PDO::PARAM_INT);

		} else {
			// Ajout
			$resultat = $pdo -> prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
		}
		
		// STR
		$resultat -> bindParam(':reference', $_POST['reference'], PDO::PARAM_STR);
		$resultat -> bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);
		$resultat -> bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
		$resultat -> bindParam(':description', $_POST['description'], PDO::PARAM_STR);
		$resultat -> bindParam(':couleur', $_POST['couleur'], PDO::PARAM_STR);
		$resultat -> bindParam(':taille', $_POST['taille'], PDO::PARAM_STR);
		$resultat -> bindParam(':public', $_POST['public'], PDO::PARAM_STR);
		$resultat -> bindParam(':photo', $nom_photo, PDO::PARAM_STR);
		// INT
		$resultat -> bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);
		$resultat -> bindParam(':stock', $_POST['stock'], PDO::PARAM_INT);

		if($resultat -> execute()){
			$_GET['action'] = 'affichage'; // retour à l'affichage des produits.
			$id_last_insert = $pdo -> lastInsertId();
			$msg .= '<div class="validation"> Le produit '.$id_last_insert.' a bien été ajouté/modifier </div>';
		} else {
			$msg .= '<div class="erreur"> Erreur SQL, veuillez réessayé plus tard. </div>';
		}
		
	}
	
}







// ******************************************
// ******SUPRESSION D'UN PRODUIT*************
// ******************************************

if (isset($_GET['action']) && $_GET['action'] == 'suppression') {
	// Si une action est demandée et qu'il s'agit d'une suppression
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		// Vérification que l'ID existe bel et bien et qu'il est un entier.
		// On vérifie que le produit existe en BDD en faisant une requête.
		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		if ($resultat -> rowCount() !=0) {
			// Si le produit existe bien dans la BDD ...

			// Je supprime la ou les photos du serveur
			$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
			$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'].RACINE_SITE.'photo/'.$produit['photo']; // je reconstitue le chemin absolu de la photo à supprimer.
			if (!empty($produit['photo']) && file_exists($chemin_photo_a_supprimer) && $produit['photo'] != 'defaut.jpg') { // si la photo existe en BDD et n'est pas celle par défaut alors je peux la supprimer grâce à la fonction unlink().
				unlink($chemin_photo_a_supprimer);
			}

			// Je supprime l'enregistrement
			$resultat = $pdo -> exec("DELETE FROM produit WHERE id_produit = $produit[id_produit]");
			$_GET['action'] = 'affichage';
			header('location:gestion_boutique.php?action=affichage');
			$msg .= '<div class="validation">Le produit N°'.$produit['id_produit'].' a bien été supprimé !</div>';
		}
	}
}






// ******************************************
// *************LIENS DU MENU****************
// ******************************************

$contenu .= '<h1>Gestion des produits</h1>';
$contenu .= '<div class="sousmenu">';
$contenu .= '<h3 class="sousmenu">Sous-menu</h3>';
$contenu .= '<a href="?action=affichage" class="sousmenu">Affichage des produits</a><br>';
$contenu .= '<a href="?action=ajout" class="sousmenu">Ajout d\'un produit</a><br>';
$contenu .= '</div>';






// ******************************************
// *******AFFICHAGE DES PRODUITS*************
// ******************************************

if (isset($_GET['action']) && $_GET['action'] == 'affichage') {
	// Récupérer les infos de tous les produits dans la BDD
	$resultat = $pdo -> query("SELECT * FROM produit") ;

// Faire des boucles pour afficher le tableau
	$contenu .= '<table border="1"><tr>';
	for($i = 0 ; $i < $resultat -> columnCount() ; $i++){
		$meta = $resultat -> getColumnMeta($i) ;
		$contenu .= '<th>' . $meta['name'] . '</th>' ;
	}
	$contenu .= '<th colspan="2">Actions</th>';
	$contenu .= '</tr>';
	$lignes = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	foreach ($lignes as $value){
		$contenu .= '<tr>' ;
		foreach ($value as $key2 => $valeur) {
			if($key2 == 'photo'){ // affichage de la photo
				$contenu .= '<td><img src="'.RACINE_SITE.'photo/'.$valeur.'" height="80"/></td>';
			}else{
				$contenu .= '<td>' . $valeur . '</td>' ;
			}
		}
		$contenu .= '<td><a href="?action=modification&id='.$value['id_produit'].'"><img src="'.RACINE_SITE.'img/edit.png"></a></td>';
		$contenu .= '<td><a href="?action=suppression&id='.$value['id_produit'].'"><img src="'.RACINE_SITE.'img/delete.png"></a></td>';
		$contenu .= '</tr>' ;
	}
	$contenu .= '</table>';
}

echo $msg;
echo $contenu;




// ******************************************
// *******AFFICHAGE DU FORMULAIRE************
// ******************************************

//  (action = ajout, modification)
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) {
	// On me demande soit d'ajouter ,soit de modifier un produit. Je peux donc afficher le formulaire

	// ******
	// MODIFICATION D'UN PRODUIT : formulaire
	// ******
	if (isset($_GET['id'])) {// signifie qu'on est dans une action de modification
		$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		$produit_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	}

	$reference = (isset($produit_actuel)) ? $produit_actuel['reference'] : '';
	// Affectation en même temps que condition...
	$categorie = (isset($produit_actuel)) ? $produit_actuel['categorie'] : '';
	$titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';
	$description = (isset($produit_actuel)) ? $produit_actuel['description'] : '';
	$couleur = (isset($produit_actuel)) ? $produit_actuel['couleur'] : '';
	$taille = (isset($produit_actuel)) ? $produit_actuel['taille'] : '';
	$public = (isset($produit_actuel)) ? $produit_actuel['public'] : '';
	$photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';
	$prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
	$stock = (isset($produit_actuel)) ? $produit_actuel['stock'] : '';
	//---
	$id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';
	$submit = (isset($produit_actuel)) ? 'Modifier' : 'Enregistrer';


	

// je ne ferme pas mon if donc tous le HTML ci-dessous sera afficher si j'entre dans le IF.
?>
<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<form action="" method="post" enctype="multipart/form-data">
<!-- multipart/form-data : Important dès que l'on a l'upload d'un fichier . Onn gére ensuite lles fichier grâce à la superglobale $_FILES -->
	<fieldset>
		<input type="hidden" name="id_produit" value="<?= $id_produit; ?>">

		<label for="reference">Reference :</label>
		<input type="text" name="reference" id="reference"
		value="<?= $reference; ?>"><br>

		<label for="categorie">Catégorie :</label>
		<input type="text" name="categorie" id="categorie" value="<?= $categorie; ?>"><br>

		<label for="titre">Titre :</label>
		<input type="text" name="titre" id="titre"
		value="<?= $titre; ?>"><br>

		<label for="description">Description :</label>
		<textarea name="description" id="description" cols="40" rows="10"><?= $description; ?></textarea>

		<label for="couleur">Couleur :</label>
		<input type="text" name="couleur" id="couleur"
		value="<?= $couleur; ?>"><br>

		<label for="taille">Taille :</label>
		<input type="text" name="taille" id="taille"
		value="<?= $taille; ?>"><br>

		<label for="public">Public :</label>
		<select name="public" id="public">
			<option value="m" <?= (isset($produit_actuel) && $public == 'm') ? 'selected' : ''; ?>>Homme</option>
			<option value="f" <?= (isset($produit_actuel) && $public == 'f') ? 'selected' : ''; ?>>Femme</option>
			<option value="unisexe" <?= (isset($produit_actuel) && $public == 'unisexe') ? 'selected' : ''; ?>>Unisexe</option>
		</select><br>

		<?php 
			if(isset($produit_actuel)){
				echo '<label for="photo_actuelle">Photo actuelle :</label>';
				echo ' <img src="'.RACINE_SITE.'photo/'.$photo.'" width="150"><br>';
				echo '<input type="hidden" name="photo_actuelle" value="'.$photo.'"/>';
			}
		?>

		<label for="photo">Photo :</label>
		<input type="file" name="photo" id="photo" value="<?= $photo ?>"><br>

		<label for="prix">Prix :</label>
		<input type="text" name="prix" id="prix" value="<?= $prix; ?>"><br>

		<label for="stock">Stock :</label>
		<input type="text" name="stock" id="stock" value="<?= $stock; ?>"><br>

		<input type="submit" value="<?= $submit; ?>">
	</fieldset>


<?php
// Fermeture du if
}

// ******************************************
// **************BAS DE PAGE*****************
// ****************************************** 
require_once('../inc/bas.inc.php');
?>