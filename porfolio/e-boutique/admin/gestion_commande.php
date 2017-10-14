<?php
require_once('../inc/init.inc.php');
// Redirection  si pas un admin
if(!userAdmin()){ 
	header('location:'.RACINE_SITE.'connexion.php?action=deconnexion');
}
//debug($_GET);

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Gestion Commande';
require_once('../inc/haut.inc.php');







// ******************************************
// *************LIENS DU MENU****************
// ******************************************

$sousmenu .= '<h1>Gestion des commandes</h1>';
$sousmenu .= '<div class="sousmenu">';
$sousmenu .= '<h3 class="sousmenu">Sous-menu</h3>';
$sousmenu .= '<a href="?action=affichage" class="sousmenu">Affichage de toutes commandes</a><br>';
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
	$sousmenu .= '<p> Tri par : <p/>';
	$sousmenu .= '<ul>';
	$sousmenu .= '<li>Commande |
					<a href="?action=affichage&column=id_commande&order=ASC" class="sousmenuchoix">ASC</a> |
					<a href="?action=affichage&column=id_commande&order=DESC" class="sousmenuchoix">DESC</a>
				</li>';
	$sousmenu .= '<li>Membre |
					<a href="?action=affichage&column=id_membre&order=ASC" class="sousmenuchoix">ASC</a> | 
					<a href="?action=affichage&column=id_membre&order=DESC" class="sousmenuchoix">DESC</a>
				</li>';
	$sousmenu .= '<li>Montant |
					<a href="?action=affichage&column=montant&order=ASC" class="sousmenuchoix">ASC</a> | 
					<a href="?action=affichage&column=montant&order=DESC" class="sousmenuchoix">DESC</a>
				</li>';
	$sousmenu .= '<li>Date |
					<a href="?action=affichage&column=date&order=ASC" class="sousmenuchoix">ASC</a> | 
					<a href="?action=affichage&column=date&order=DESC" class="sousmenuchoix">DESC</a>
				</li>';
	$sousmenu .= '<li>Etat |
					<a href="?action=affichage&column=etat&order=ASC" class="sousmenuchoix">ASC</a> | 
					<a href="?action=affichage&column=etat&order=DESC" class="sousmenuchoix">DESC</a>
				</li>';
	$sousmenu .= '</ul>';
}
$sousmenu .= '</div>';
echo $sousmenu;











// ******************************************
// ******* MODIFICATION DE L'ETAT ***********
// ******************************************

if (isset($_GET['action']) && $_GET['action'] == 'modification') {
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$resultat = $pdo -> prepare("SELECT * FROM commande WHERE id_commande = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		
		$commande = $resultat -> fetch(PDO::FETCH_ASSOC);
		//debug($commande);

		$contenu .= '<h2>Commande n°'.$commande['id_commande'].'</h2>';
		$contenu .= '<form action="" method="post">';
			//$contenu .= '<input type="hidden" name="id_commande" value="'.$commande['id_commande'].'" />';
			$contenu .= '<label for="etat">Etat de la commande : </label>';
			$contenu .= '<select name="etat" id="etat">';
				$contenu .= '<option value="en cours de traitement">En cours de traitement</option>';
				$contenu .= '<option value="envoye">Envoy&eacute;</option>';
				$contenu .= '<option value="livre">Livr&eacute;</option>';
				$contenu .= '<option value="annule">Annul&eacute;</option>';
			$contenu .= '<\select>';
			$contenu .= '<br><input type="submit" value="Modifier">';
		$contenu .= '</form>';

		if($_POST){
		debug($_POST);	

		// Remplacement dans la BDD
		$resultat = $pdo -> prepare("UPDATE commande SET etat = :etat WHERE id_commande = :id");
		$resultat -> bindParam(':id', $commande['id_commande'], PDO::PARAM_INT);
		$resultat -> bindParam(':etat', $_POST['etat'], PDO::PARAM_STR);
		$resultat -> execute();
		header('location:gestion_commande.php?action=affichage');
		}	
	}
}





// ******************************************
// ********AFFICHAGE DES COMMANDES***********
// ******************************************
if(isset($_GET['action']) && $_GET['action'] == 'affichage'){
	
	if(isset($_GET['column']) && isset($_GET['order'])) {
		$order = "";
		switch ($_GET['column']) {
			case 'id_commande':
				$order = ' id_commande';
				break;
			case 'id_membre':
				$order = ' id_membre';
				break;
			case 'montant':
				$order = ' montant';
				break;
			case 'date':
				$order = ' date_enregistrement';
				break;
			case 'etat':
				$order = ' etat';
				break;
			default:
				$msg = "Erreur dans la demande.";
				break;
		}
		if($_GET['order'] == 'ASC'){
			$order .= ' ASC';
		} elseif ($_GET['order'] == 'DESC') {
			$order .= ' DESC';
		}



		// paramètre issus du navigateur
		$resultat = $pdo -> prepare("SELECT * FROM commande ORDER BY" .$order);
		//$resultat -> bindParam(':order', $_GET['order'], PDO::PARAM_STR);
		$resultat -> execute();
	} else {
		// Pas de paramètres issus du navigateur dans la requête
		$resultat = $pdo -> query("SELECT * FROM commande");
	}

	// Message d'erreur
	if(isset($msg) && !empty($msg)){
		echo '<div class="erreur">'.$msg.'</div>';
	}

	// Faire des boucles pour afficher le tableau
	$contenu .= '<table border="1"><tr>';
	for($i = 0 ; $i < $resultat -> columnCount() ; $i++){
		$meta = $resultat -> getColumnMeta($i) ;
		$contenu .= '<th>' . $meta['name'] . '</th>' ;
	}

	$contenu .= '<th colspan="2">Actions</th>';
	$contenu .= '</tr>';

	$lignes = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	
	$total = 0;
	foreach ($lignes as $value){
		$contenu .= '<tr>' ;
		foreach ($value as $key2 => $valeur) {
			if($key2 == 'montant'){
				$total += $valeur;
				$contenu .= '<td>' . $valeur . ' €</td>' ;
			} else {
				$contenu .= '<td>' . $valeur . '</td>' ;
			}
		}

		$contenu .= '<td><a href="?action=modification&id='.$value['id_commande'].'"><img src="'.RACINE_SITE.'img/edit.png" title="Modification"></a></td>';
		$contenu .= '<td><a href="?action=voir&id='.$value['id_commande'].'"><img src="'.RACINE_SITE.'img/oeil.jpg" title="Détails de la commande"></a></td>';
		$contenu .= '</tr>' ;
	}
	$contenu .= '</table>';	
 // Chiffre d'affaire

$chiffreAffaire = '<table border="1" class="tableauRight">';
$chiffreAffaire .= '<tr>';
$chiffreAffaire .= '<td>Chiffre d\'affaire</td>';
$chiffreAffaire .= '</tr></tr>';
$chiffreAffaire .= '<td colspan="3">'.$total.' €</td>';
$chiffreAffaire .= '</tr>';
$chiffreAffaire .= '</table><br><hr/><br>';

}





// ******************************************
// *******AFFICHAGE DETAILS COMMANDES********
// ******************************************
if(isset($_GET['action']) && $_GET['action'] == "voir"){
	if(isset($_GET['id'])){
		// Affichage des informations sur le memmbre
		$req = "SELECT m.pseudo, m.nom, m.prenom, m.email, m.civilite, m.adresse, m.ville, m.code_postal, m.statut
		FROM commande c, membre m
		WHERE c.id_commande = :id
		AND c.id_membre = m.id_membre";
		$resultat = $pdo -> prepare($req);
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		$membre = $resultat -> fetch(PDO::FETCH_ASSOC);

		//debug($membre);
		extract($membre);

		?>







<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
		<h1>Profil de l'acheteur :</h1>
		<ul class="profil">
			<li>Prenom : <?php echo $prenom;?> </li>
			<li>Nom : <?php echo $nom;?></li>
			<li>Pseudo : <?php echo $pseudo;?></li>
			<li>Email : <?php echo $email;?></li>
			<li>Ville : <?php echo $ville;?></li>
			<li>Code Postal : <?php echo $code_postal;?></li>
			<li>Adresse : <?php echo $adresse;?></li>
			<li>Sexe : <?php if($civilite == "m"){echo 'Homme';}else{echo 'Femme';}?></li>
			<li>Statut : <?php if($statut == "0"){echo 'Visiteur';}else{echo 'Admin du site';}?></li>
		</ul>
		<br><hr><br>

		<?php
		// Affichage des details des produits

		$req2 = "SELECT c.id_commande, p.titre, p.photo, p.couleur, p.taille, d.quantite, d.prix
		FROM commande c, details_commande d, produit p
		WHERE c.id_commande = :id
		AND c.id_commande = d.id_commande
		AND d.id_produit = p.id_produit";
		// id non sûr
		$resultat = $pdo -> prepare($req2);
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();

		if($resultat -> rowCount() != 0){
			$contenu .= '<table border="1"><tr>';
			for($i = 0 ; $i < $resultat -> columnCount() ; $i++){
				$meta = $resultat -> getColumnMeta($i) ;
				if($meta['name'] == 'prix'){
					$contenu .= '<th>prix unitaire</th>' ;
				} else {
					$contenu .= '<th>' . $meta['name'] . '</th>' ;	
				}
			}
			$contenu .= '<th>prix/produit</th>' ;
			$contenu .= '</tr>';

			$lignes = $resultat -> fetchAll(PDO::FETCH_ASSOC);

			foreach ($lignes as $value){
				$contenu .= '<tr>' ;
				foreach ($value as $key2 => $valeur) {
					if($key2 == 'photo'){ // affichage de la photo
						$contenu .= '<td><img src="'.RACINE_SITE.'photo/'.$valeur.'" height="80"/></td>';
					} else if($key2 == 'quantite'){
						$contenu .= '<td>' . $valeur . '</td>' ;
						$quantite = $valeur;
					} else if($key2 == 'prix'){
						$contenu .= '<td>' . $valeur . '€</td>' ;
						$prix = $valeur;
					}else{
						$contenu .= '<td>' . $valeur . '</td>' ;
					}
				}
				$contenu .= '<td>' . ($prix*$quantite) . '€</td>' ;
				$contenu .= '</tr>' ;
			}
			$contenu .= '</table>';
		}else{
			// si la commande est vide
			$contenu .= '<div class="erreur">Commande Vide !!!</div>';
		}
		
	}
}
if(isset($_GET['action']) && $_GET['action']=='affichage'){echo $chiffreAffaire;}
echo $contenu;
?>

<?php 
require_once('../inc/bas.inc.php');
?>