<?php
require_once('inc/init.inc.php');
// Redirection  si user est connecté
if(userConnecte()){ 
	header('location:profil.php');
}

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Inscription';
require_once('inc/haut.inc.php');

if ($_POST){
	debug($_POST);

// ******************************************
// ******** VERIFICATION DES CHAMPS *********
// ******************************************
	// $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
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
		

	// ******************************************
	// ***** INSERTION DES INFOS DANS LA BDD ****
	// ******************************************
		// Est-ce que le pseudo(et l'email) est bien disponible

	if (empty($msg)) {
		// Pour vérifier si le pseudo est dispo, je dois faire une requête auprès de la BDD et vérifier s'il y a au moins un résultat avec ce pseudo.
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE  pseudo=:pseudo");
		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> execute();

		if($resultat -> rowCount() > 0){
			$msg .= '<div class="erreur">Pseudo indisponible, veuillez renseigner un autre pseudo.</div>';
		} else {
			// le pseudo est bien disponible.
			$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, '0')");
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

			$resultat -> execute();
			// Redirection vers la page de connexion
			header('location:connexion.php');
		}
	}
}

?>

<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<h1>Inscription</h1>
<?php echo $msg; ?>
 
<form action="" method="post">
	<fieldset>
		<label for="pseudo">Pseudo :</label>
		<input type="text" name="pseudo" id="pseudo"
		value="<?php if (isset($_POST['pseudo'])) {echo $_POST['pseudo']; /* Affiche le pseudo s'il existe déjà*/} ?>"><br>

		<label for="mdp">Mot de passe :</label>
		<input type="password" name="mdp" id="mdp"><br>

		<label for="nom">Nom :</label>
		<input type="text" name="nom" id="nom"
		value="<?php if (isset($_POST['nom'])) {echo $_POST['nom'];} ?>"><br>

		<label for="prenom">Prenom :</label>
		<input type="text" name="prenom" id="prenom"
		value="<?php if (isset($_POST['prenom'])) {echo $_POST['prenom'];} ?>"><br>

		<label for="email">Email :</label>
		<input type="text" name="email" id="email"
		value="<?php if (isset($_POST['email'])) {echo $_POST['email'];} ?>"><br>

		<label for="civilite">Civilité :</label>
		<select name="civilite" id="civilite">
			<option value="m" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'm'){echo 'selected';} ?>>Homme</option>
			<option value="f" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f'){echo 'selected';} ?>>Femme</option>
		</select><br>

		<label for="ville">Ville :</label>
		<input type="text" name="ville" id="ville" value="<?php if (isset($_POST['ville'])) {echo $_POST['ville'];} ?>"><br>

		<label for="code_postal">Code postal :</label>
		<input type="text" name="code_postal" id="code_postal" value="<?php if (isset($_POST['code_postal'])) {echo $_POST['code_postal'];} ?>"><br>

		<label for="adresse">Adresse :</label>
		<input type="text" name="adresse" id="adresse" value="<?php if (isset($_POST['adresse'])) {echo $_POST['adresse'];} ?>"><br>

		<input type="submit" value="S'inscrire">
	</fieldset>
</form>











<!-- ######################################### -->
<!-- ######### BAS DE LA PAGE ################ -->
<!-- ######################################### -->
<?php 
require_once('inc/bas.inc.php');
?>