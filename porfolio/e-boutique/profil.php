<?php 
require_once('inc/init.inc.php');
extract($_SESSION['membre']); // Remplace $_SESSION['membre']['key'] par $key

// Redirection  si user n'est pas connecté
if(!userConnecte()){ 
	header('location:connexion.php');
}

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************

$page = 'Profil';
require_once('inc/haut.inc.php');


// ******************************************
// ********** SUPPRESSION DE MEMBRE *********
// ******************************************
if(isset($_GET['action']) && $_GET['action'] == 'suppression' ){
	// Si une action est demandée et qu'il s'agit d'une suppression
	// Je supprime l'enregistrement
	$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $id_membre"); 

	unset($_SESSION['membre']);
	header('location:inscription.php?msg=bye'); 
}
?>

<!-- ####################################### -->
<!-- ######### CONTENU HTML ################ -->
<!-- ####################################### -->
<h1>Votre profil :</h1>
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
</ul><br>

<ul>
	<li><a href="membre.php"><img src="<?=RACINE_SITE?>img/edit.png"/>Mettre à jour mes informations</a></li>
	<li><a href="?action=suppression"><img src="<?=RACINE_SITE?>img/delete.png" />Se désinscrire</a></li>
</ul>

<h2>Commandes</h2>
<?php
// ******************************************
// ********AFFICHAGE DES COMMANDES***********
// ******************************************
$resultat = $pdo -> query("SELECT id_commande, montant, date_enregistrement, etat FROM commande WHERE id_membre = $id_membre");


$contenu .= '<table border="1"><tr>';
for($i = 0 ; $i < $resultat -> columnCount() ; $i++){
	$meta = $resultat -> getColumnMeta($i) ;
	$contenu .= '<th>' . $meta['name'] . '</th>' ;
}
$contenu .= '<th>Détails</th>';
$contenu .= '</tr>';

if($resultat -> rowCount() > 0){
	$lignes = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	foreach ($lignes as $value){
		$contenu .= '<tr>' ;
		foreach ($value as $key2 => $valeur) {
			$contenu .= '<td>' . $valeur . '</td>' ;
		}
		$contenu .= '<td><a href="?action=voir&id='.$value['id_commande'].'"><img src="'.RACINE_SITE.'img/oeil.jpg"></a></td>';
	}
} else {
	$contenu .= '<tr>';
	$contenu .= '<td colspan="5"> Pas de commande. <a href="boutique.php">Achetez vite !!! </a></td>';
}

$contenu .= '</tr>';
$contenu .= '</table>';

if(isset($_GET['action']) && $_GET['action'] == "voir"){
	if(isset($_GET['id'])){

		$contenu .= '<h2>Detail de la commande n°'.$_GET['id'].' :</h2>';

		$req = "SELECT c.id_commande, p.titre, p.photo, p.couleur, p.taille, d.quantite, d.prix
			FROM commande c, details_commande d, produit p
			WHERE c.id_commande = :id
			AND c.id_commande = d.id_commande
			AND d.id_produit = p.id_produit";
		// id non sûr
		$resultat = $pdo -> prepare($req);
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
			// si la commande est cide
			$contenu .= '<div class="erreur">Commande Vide !!!</div>';
		}
	}
}


echo $contenu;

require_once('inc/bas.inc.php');
 ?>