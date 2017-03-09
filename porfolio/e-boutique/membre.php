<?php
require_once('inc/init.inc.php'); 

//REDIRECTION SI USER N'EST PAS CONNECTE
if(!userConnecte()){
	header('location:connexion.php'); 
}

extract($_SESSION['membre']);
//debug($_SESSION['membre']);

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Modification membre';
require_once('inc/haut.inc.php');

if ($_POST) {
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


	// ********
	// INSÉRER L'UTILISATEUR DANS LA BASE DE DONNÉES
	// ********

	if (empty($msg)) {
		$resultat = $pdo -> prepare("REPLACE INTO membre VALUES (:id, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :statut)");

		$resultat -> bindParam(':id', $_POST['id_membre'], PDO::PARAM_INT);

		$resultat -> bindParam(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);

		$mdp_crypte = md5($_POST['mdp']); // md5() cryptage simple
		$resultat -> bindParam(':mdp',$mdp_crypte, PDO::PARAM_STR);

		$resultat -> bindParam(':nom',$_POST['nom'], PDO::PARAM_STR);

		$resultat -> bindParam(':prenom',$_POST['prenom'], PDO::PARAM_STR);

		$resultat -> bindParam(':email',$_POST['email'], PDO::PARAM_STR);

		$resultat -> bindParam(':civilite',$_POST['civilite'], PDO::PARAM_STR);
		$resultat -> bindParam(':ville',$_POST['ville'], PDO::PARAM_STR);

		$resultat -> bindParam(':code_postal',$_POST['code_postal'], PDO::PARAM_INT);

		$resultat -> bindParam(':adresse',$_POST['adresse'], PDO::PARAM_STR);

		$resultat -> bindParam(':statut', $statut, PDO::PARAM_INT);
		
		$resultat -> execute();
		
		$id_last_insert = $pdo -> lastInsertId();
		$msg .= '<div class="validation"> Le membre '.$id_last_insert.' a bien été modifié </div>';
	}
}


?>
<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<form action="" method="post" enctype="multipart/form-data">
<!-- l'attribut enctype permet de gérer les fichier upload grâce à la superglobale $_FILES -->

	<input type="hidden" name="id_membre" value="<?= $id_membre ?>" />

	<label>Pseudo : </label><br/>
	<input type="text" name="pseudo" value="<?= $pseudo ?>" /><br/><br/>

	<label>Mot de passe : </label><br/>
	<input type="text" name="mdp" value=""/><br/><br/>

	<label>Nom : </label><br/>
	<input type="text" name="nom" value="<?= $nom ?>"/><br/><br/>
	
	<label>Prénom : </label><br/>
	<input type="text" name="prenom" value="<?= $prenom ?>"/><br/><br/>
	
	<label>Email : </label><br/>
	<input type="text" name="email" value="<?= $email ?>"/><br/><br/>
	
	<label>Civilite :</label><br/>
	<select name="civilite">
		<option value="m" <?= (isset($membre_actuel) && $civilite == 'm') ? 'selected' : '' ?>>Homme</option>
		<option value="f" <?= (isset($membre_actuel) && $civilite == 'f') ? 'selected' : '' ?>>Femme</option>
	</select><br/><br/>
	
	<label>Ville : </label><br/>
	<input type="text" name="ville" value="<?= $ville ?>"/><br/><br/>
	
	<label>Code Postal: </label><br/>
	<input type="text" name="code_postal" value="<?= $code_postal ?>"/><br/><br/>
	
	<label>Adresse : </label><br/>
	<input type="text" name="adresse" value="<?= $adresse ?>"/><br/><br/>
	
	<input type="submit" value="Modifier" /><br/><br/>

</form>

<?php
// ******************************************
// **************BAS DE PAGE*****************
// ****************************************** 
require_once('inc/bas.inc.php'); 
?>