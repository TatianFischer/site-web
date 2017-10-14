<?php 
require_once('inc/init.inc.php');

// TRAITEMENT DE LA DECONNEXION
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
	unset($_SESSION['membre']); // je vide la partie MEMEBRE de la session pour déconnecter USER.
	header('location:connexion.php'); // Je redirige vers la même page pour éviter de conserver les paramètres dans l'URL.
}

// Redirection  si user est connecté
if(userConnecte()){ 
	header('location:profil.php');
}

// ********* HAUT DE LA PAGE ****************
$page = 'Connexion';
require_once('inc/haut.inc.php');

// Traitement de la connexion
if ($_POST) {
	//debug($_POST);

	// Sécurité pseudo
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

	// Sécurité mdp
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

	if(empty($msg)){
		// Verification que le pseudo existe
		$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo=:pseudo");
		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> execute();

		if($resultat -> rowCount() > 0) {
			// Le pseudo existe en BDD, est-ce que le mdp correspond ?
			$membre = $resultat -> fetch(PDO::FETCH_ASSOC);

			if($membre['mdp'] == md5($_POST['mdp'])){
				// tout est ok le mot de passe correspond bien
				// Connecter l'utilisateur
				// Mettre les infos dans $_SESSION

				foreach ($membre as $key => $value) {
					if($key != 'mdp'){ // /!\ Ne pas mettre le mot de passe
						$_SESSION['membre'][$key] = $value;
					}
				}

				//debug($_SESSION);
				if(isset($_GET['page']) && $_GET['page'] != ''){
					header('location:'.$_GET['page'].'php');
				} else {
					header('location:profil.php');
				}

			}else{
				$msg .= '<div class="erreur">Erreur de mot de passe !</div>';
			}

		} else {
			// Le pseudo n'existe pas
			$msg .= '<div class="erreur">Pseudo inconnu !</div>';
		}
	}	
}




?>
<!-- ######### CONTENU HTML ################  -->
<h1>Connexion</h1>
<?php echo $msg; ?>
<form action="" method="post">
	<fieldset>
		<label for="pseudo">Pseudo :</label>
		<input type="text" name="pseudo" id="pseudo"
		value="<?php if (isset($_POST['pseudo'])) {echo $_POST['pseudo']; /* Affiche le pseudo s'il existe déjà*/} ?>"><br>

		<label for="mdp">Mot de passe :</label>
		<input type="password" name="mdp" id="mdp"><br>

		<input type="submit" value="Connexion">
	</fieldset>
</form>



<?php 
require_once('inc/bas.inc.php');

?>