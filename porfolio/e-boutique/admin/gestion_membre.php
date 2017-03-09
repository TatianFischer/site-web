<?php
require_once('../inc/init.inc.php');
// Redirection  si pas un admin
if(!userAdmin()){ 
	header('location:'.RACINE_SITE.'connexion.php?action=deconnexion"');
}

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Gestion Membre';
require_once('../inc/haut.inc.php');




// ******************************************
// ***INSERTION / MODIFICATION D'UN MEMBRE***
// ******************************************
if ($_POST) {
	debug($_POST);

	// INSERTION DES INFOS DES MEMBRES DANS LA BDD
		// ************ Vérication des champs ********************
	if (!empty($_POST['pseudo'])) {
		if (verif_regex('carac_nbr', $_POST['pseudo'])) {
			if (strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 20) {
				$msg .= '<div class="erreur">Veuillez renseigner un pseudo de 3 à 20 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Pseudo : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}	
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un pseudo !</div>';
	}
	
	// VERIFICATION DU MOT DE PASSE
	if (!empty($_POST['mdp'])) {
		if (verif_regex('carac_nbr', $_POST['mdp'])) {
			if (strlen($_POST['mdp']) < 3 || strlen($_POST['mdp']) > 20) {
				$msg .= '<div class="erreur">Veuillez renseigner un mot de passe de 3 à 20 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Mot de passe : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un mot de passe !</div>';
	}
	

	// VERIFICATION DU NOM
	if (!empty($_POST['nom'])) {
		if (verif_regex('carac_nbr', $_POST['nom'])){
			if (strlen($_POST['nom']) < 3 || strlen($_POST['nom']) > 20) {
				$msg .= '<div class="erreur">Veuillez renseigner un nom de 3 à 20 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Nom : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un nom !</div>';
	}

		

	// VERIFICATION DU PRENOM
	if (!empty($_POST['prenom'])) {
		if (verif_regex('carac_nbr', $_POST['nom'])) {
			if (strlen($_POST['prenom']) < 3 || strlen($_POST['prenom']) > 20) {
				$msg .= '<div class="erreur">Veuillez renseigner un prenom de 3 à 20 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Prenom : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un prenom !</div>';
	}
		

	// VERIFICATION DE L'EMAIL
	if (!empty($_POST['email'])) {
		$regex_email = '/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/';
		if(!preg_match($regex_email, $_POST['email'])) {
			$msg .= '<div class="erreur">Veuillez renseigner un email valide !</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un email !</div>';
	}
		

	// VERIFICATION DE LA CIVIVITE
	if (empty($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')) {
		$msg .= '<div class="erreur">Petit malin vous ne m\'aurez pas !</div>';
	}
		

	// VERIFICATION DE LA VILLE
	if (!empty($_POST['ville'])) {
		if (verif_regex('caractere', $_POST['ville'])) {
			if(strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50){
				$msg .= '<div class="erreur">Veuillez renseigner une adresse de 5 à 50 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Adresse : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner une ville !</div>';
	}
		

	// VERIFICATION DU CODE POSTAL
	if (!empty($_POST['code_postal'])) {
		if (!verif_regex('nombre', $_POST['code_postal']) && strlen($_POST['code_postal']) != 5) {
			$msg .= '<div class="erreur">Veuillez renseigner un code postal valide !</div>';	
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner un code postal !</div>';
	}
		

	// VERIFICATION DE L'ADRESSE
	if (!empty($_POST['adresse'])) {
		if (verif_regex('carac_nbr', $_POST['adresse'])) {
			if(strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 50){
				$msg .= '<div class="erreur">Veuillez renseigner une adresse de 5 à 50 caractères.<br> Seuls les caratères non accentués, les chiffres, "-", "_" et "." sont acceptés.</div>';
			}
		} else {
			$msg .= '<div class="erreur">Adresse : caractères non accentués, chiffres, "-", "_" et ".".</div>';
		}
	} else {
		$msg .= '<div class="erreur">Veuillez renseigner une adresse !</div>';
	}

	// VERIFICATION DU STATUT
	if(!empty($_POST['statut'])){
		if($_POST['statut'] != 0 || $_POST['statut'] != 1){
			$msg .= '<div class="erreur">Statut : 0 = membre ou 1 = admin.</div>';
		}
	} else {
		$_POST['statut'] = 0;
	}
		

	// ********
	// INSÉRER L'UTILISATEUR DANS LA BASE DE DONNÉES
	// ********

	if (empty($msg)) {
		if (isset($_GET['action']) && $_GET['action'] == "modification" && !empty($_GET['id'])){
			debug($_GET); 
			// Modification
			$resultat = $pdo -> prepare("REPLACE INTO membre VALUES (:id, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut)");
			$resultat -> bindParam(':id', $_POST['id_membre'], PDO::PARAM_INT);

		} else { // Ajout
			// Est-ce que le pseudo(et l'email) est bien disponible
			// Pour vérifier si le pseudo est dispo, je dois faire une requête auprès de la BDD et vérifier s'il y a au moins un résultat avec ce pseudo.
			$resultat = $pdo -> prepare("SELECT * FROM membre WHERE  pseudo = :pseudo");
			$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
			$resultat -> execute();

			if($resultat -> rowCount() > 0){
				$msg .= '<div class="erreur">Pseudo indisponible, veuillez renseigner un autre pseudo.</div>';
			} else {
				// le pseudo est bien disponible.
				$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut)");
			}
		}
		$mdp_crypte = md5($_POST['mdp']); // md5() cryptage simple
		$resultat -> bindParam(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> bindParam(':mdp',$mdp_crypte, PDO::PARAM_STR);
		$resultat -> bindParam(':nom',$_POST['nom'], PDO::PARAM_STR);
		$resultat -> bindParam(':prenom',$_POST['prenom'], PDO::PARAM_STR);
		$resultat -> bindParam(':email',$_POST['email'], PDO::PARAM_STR);
		$resultat -> bindParam(':civilite',$_POST['civilite'], PDO::PARAM_STR);
		$resultat -> bindParam(':ville',$_POST['ville'], PDO::PARAM_STR);
		$resultat -> bindParam(':code_postal',$_POST['code_postal'], PDO::PARAM_INT);
		$resultat -> bindParam(':adresse',$_POST['adresse'], PDO::PARAM_STR);
		$resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_INT);

		$resultat -> execute();
		
		$_GET['action'] = 'affichage'; // retour à l'affichage des membres.
		$id_last_insert = $pdo -> lastInsertId();
		$msg .= '<div class="validation"> Le membre '.$id_last_insert.' a bien été ajouté / modifié </div>';
	}
}





// ******************************************
// ******SUPRESSION D'UN MEMBRE**************
// ******************************************

if (isset($_GET['action']) && $_GET['action'] == 'suppression') {
	// Si une action est demandée et qu'il s'agit d'une suppression
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		debug($_GET);
		// Vérification que l'ID existe bel et bien et qu'il est un entier.
		// On vérifie que le produit existe en BDD en faisant une requête.
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		if ($resultat -> rowCount() !=0) {
			// Si le produit existe bien dans la BDD ...
			$membre = $resultat -> fetch(PDO::FETCH_ASSOC);
			// Je supprime l'enregistrement
			$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $membre[id_membre]");
			$_GET['action'] = 'affichage';
			header('location:gestion_membre.php?action=affichage');
			$msg .= '<div class="validation">Le membre N°'.$membre['id_membre'].' a bien été supprimé !</div>';
		}
	}
}





// ******************************************
// *************LIENS DU MENU****************
// ******************************************

$contenu .= '<h1>Gestion des membres</h1>';
$contenu .= '<div class="sousmenu">';
$contenu .= '<h3 class="sousmenu">Sous-menu</h3>';
$contenu .= '<a href="?action=affichage" class="sousmenu">Affichage des membres</a><br>';
$contenu .= '<a href="?action=ajout" class="sousmenu">Ajout d\'un membre</a><br>';
$contenu .= '</div>';






// ******************************************
// ********AFFICHAGE DES MEMBRES*************
// ******************************************

if (isset($_GET['action']) && $_GET['action'] == 'affichage') {
	// Récupérer les infos de tous les produits dans la BDD
	$resultat = $pdo -> query("SELECT * FROM membre") ;

// Faire des boucles pour afficher le tableau
	$contenu .= '<table border="1"><tr>';
	for($i = 0 ; $i < $resultat -> columnCount() ; $i++){
		$meta = $resultat -> getColumnMeta($i) ;
		if($meta['name'] != 'mdp'){	
			$contenu .= '<th>' . $meta['name'] . '</th>' ;
		}
	}
	$contenu .= '<th colspan="2">Actions</th>';
	$contenu .= '</tr>';
	$lignes = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	foreach ($lignes as $value){
		$contenu .= '<tr>' ;
		foreach ($value as $key2 => $valeur) {
			if($key2 != 'mdp'){
				$contenu .= '<td>' . $valeur . '</td>' ;
			}
		}
		$contenu .= '<td><a href="?action=modification&id='.$value['id_membre'].'"><img src="'.RACINE_SITE.'img/edit.png"></a></td>';
		$contenu .= '<td><a href="?action=suppression&id='.$value['id_membre'].'"><img src="'.RACINE_SITE.'img/delete.png"></a></td>';
		$contenu .= '</tr>' ;
	}
	$contenu .= '</table>';
}

echo $msg;
echo $contenu;



// ******************************************
// *******AFFICHAGE DU FORMULAIRE************
// ******************************************

// (action = ajout, modification)
if (isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) {
	// On me demande soit d'ajouter ,soit de modifier un produit. Je peux donc afficher le formaulaire

	// ******
	// MODIFICATION D'UN MEMBRE
	// ******
	if(isset($_GET['id'])) {
		// signifie qu'on est dans une action de modification
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	}

	$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
	$nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
	$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
	$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
	$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
	$ville = (isset($membre_actuel)) ? $membre_actuel['ville'] : '';
	$code_postal = (isset($membre_actuel)) ? $membre_actuel['code_postal'] : '';
	$adresse = (isset($membre_actuel)) ? $membre_actuel['adresse'] : '';
	$statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';
	//---
	$id_membre = (isset($membre_actuel)) ? $membre_actuel['id_membre'] : '';
	$submit = (isset($membre_actuel)) ? 'Modifier' : 'Enregister';


// je ne ferme pas mon if donc tous le HTML ci-dessous sera afficher si j'entre dans le IF.
?>
<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<form action="" method="post">
	<fieldset>
		<input type="hidden" name="id_membre" value="<?= $id_membre ; ?>">

		<label for="pseudo">Pseudo :</label>
		<input type="text" name="pseudo" id="pseudo"
		value="<?= $pseudo; ?>"><br>

		<label for="mdp">Mot de passe :</label>
		<input type="password" name="mdp" id="mdp"><br>

		<label for="nom">Nom :</label>
		<input type="text" name="nom" id="nom"
		value="<?= $nom ; ?>"><br>

		<label for="prenom">Prenom :</label>
		<input type="text" name="prenom" id="prenom"
		value="<?= $prenom ; ?>"><br>

		<label for="email">Email :</label>
		<input type="text" name="email" id="email"
		value="<?= $email ; ?>"><br>

		<label for="civilite">Civilité :</label>
		<select name="civilite" id="civilite">
			<option value="m" <?= (isset($membre_actuel) && $civilite == 'm') ? 'selected' : ''; ?>>Homme</option>
			<option value="f" <?= (isset($membre_actuel) && $civilite == 'f') ? 'selected' : ''; ?>>Femme</option>
		</select><br>

		<label for="ville">Ville :</label>
		<input type="text" name="ville" id="ville" value="<?= $ville ; ?>"><br>

		<label for="code_postal">Code postal :</label>
		<input type="text" name="code_postal" id="code_postal" value="<?= $code_postal ; ?>"><br>

		<label for="adresse">Adresse :</label>
		<input type="text" name="adresse" id="adresse" value="<?= $adresse ; ?>"><br>

		<label for="statut">Statut :</label>
		<select name="civilite" id="civilite">
			<option value="0" <?= (isset($membre_actuel) && $statut == '0') ? 'selected' : ''; ?>>Membre</option>
			<option value="1" <?= (isset($membre_actuel) && $statut == '1') ? 'selected' : ''; ?>>Admin</option>
		</select><br>


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