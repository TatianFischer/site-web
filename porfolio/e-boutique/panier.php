<?php 
require_once('inc/init.inc.php');

// Affichage du panier
	// Affichege du total (fonction)
	// Payer
	// Vider
	// Incrémenter / décrémenter une quantité

// Suppression d'un produit de panier (retirerPanier)
// Traitement pour vider un panier

// Paiement
	//  vérification du stock
		// stock < à demander
		// stock vide, retrait du produit

	// Enregistrement BDD de la commande et des détails de la commande et modification du stock (commande, detail_commande, produit)
	// Envoi d'un email de confirmation (avec facture si elle est éditée).

// ******************************************
// ********** INCREMENTATION ****************
// ******************************************
	// Récupérer les infos du produit et vérifier le stock en BDD
	// L'emplacement du produit dans ma SESSION
	// Si le stock est supérieur à la quantité déjà dans le panier + 1, alors j'ajoute 1 dans le panier.
	// Sinon message d'avertissement.
if(isset($_GET['action']) && $_GET['action'] =='incrementation'){
	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$resultat = $pdo -> prepare("SELECT  stock FROM  produit  WHERE id_produit = :id");
		$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$resultat -> execute();
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);

		$position = array_search($_GET['id'], $_SESSION['panier']['id_produit']);

		if($position !== FALSE){
			if($produit['stock'] >= $_SESSION['panier']['quantite'][$position] + 1){
				$_SESSION['panier']['quantite'][$position]++;
			}else {
				$msg .= '<div class="erreur">Le stock du produit <b>'.$_SESSION['panier']['titre'][$position].'</b> est limité !</div>';
			}
		}
	}
}

// ******************************************
// ********** DECREMENTATION ****************
// ******************************************
// Récupérer l'emplacement du produit dans ma SESSION
// Retirer une unité dans le panier
// Si la quantité était supérieur à 1.
// Sinon on retire le produit.

if(isset($_GET['action']) && $_GET['action'] =='decrementation'){
	if(isset($_GET['id']) && is_numeric($_GET['id'])){
		$position = array_search($_GET['id'], $_SESSION['panier']['id_produit']);
		if($position !== FALSE){
			if($_SESSION['panier']['quantite'][$position] > 1){
				$_SESSION['panier']['quantite'][$position]--;
			} else {
				retirerProduit($_SESSION['panier']['id_produit'][$position]);
			}
		}
	}
}


// ******************************************
// ********* VIDER LE PANIER ****************
// ******************************************
if(isset($_GET['action']) && $_GET['action'] == 'vider'){
	unset($_SESSION['panier']);
	// Si l'action de vider le panier est demandée, alors on vide la partie panier de notre session tout simplement.
}	

// ******************************************
// ******* SUPPRIMER UN PRODUIT *************
// ******************************************
if (isset($_GET['action']) && $_GET['action'] == 'suppression') {
	if(is_numeric($_GET['id'])){
		retirerProduit($_GET['id']);
		// Si une demande de suppression d'un produit est passée dans l'URL alors j'execute la fonction retirerProduit (voir fonction.inc.php) en passant l'id du produit à supprimer dans l'URLS.
	}
}

// ******************************************
// ********** PAYER LE PANIER ***************
// ******************************************
if(isset($_POST['payer']) && !empty($_SESSION['panier']['id_produit'])){
	// Si l'utilisateur à cliquer sur le bouton "payer"
	// Deux problématiques
		// 1 : Les produits sont-ils toujours dispos ?
		// 2 : Enregistremnt en BDD des infos.
	for ($i=0; $i < count($_SESSION['panier']['id_produit']) ; $i++) { 
		$resultat = $pdo -> query("SELECT stock FROM produit WHERE id_produit = ".$_SESSION['panier']['id_produit'][$i]);
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);

		if ($produit['stock'] < $_SESSION['panier']['quantite'][$i]){
			// Le stock est inférieurà la quantité demandée, nous avons un problème. Probablement qu'un autre utlisateur est venu entre temps commander et acheté ce produit ...
			$msg .='<div class="erreur">Stock restant : '.$produit['stock'].'<br> Quantité demandée : '.$_SESSION['panier']['quantite'][$i].'.</div>';
			// 2 cas de figure :
			if($produit['stock'] > 0){
			// Il reste du stock
				$msg .='<div class="erreur">Le stock du produit '.$_SESSION['panier']['titre'][$i].' n\'est pas suffisant, votre commande a été modifiée.</div>';
				$_SESSION['panier']['quantite'][$i] = $produit['stock'];
			} else {
			// Il ne reste rien :,(
				$msg .='<div class="erreur">Le stock du produit '.$_SESSION['panier']['titre'][$i].' est en rupture de stock. Nous avons supprimé ce produit de votre commande.</div>';

				retirerProduit($_SESSION['panier']['id_produit'][$i]);
				$i--; // Car le array_splice() va décaler les id de 1 position en supprimant la ligne contenant le produit en rupture.
			}
		}
	}

	if (empty($msg)) { // Tout est OK !!
		// Infos dans la BDD ...
		// Envoyer un email
		// Supprimer le panier

		$id_membre = $_SESSION['membre']['id_membre'];
		$montant = montantTotal();

		$resultat = $pdo -> exec("INSERT INTO commande (id_membre, montant, date_enregistrement, etat) VALUES ('$id_membre', '$montant', NOW(), 'en cours de traitement')");

		$id_commande = $pdo -> lastInsertId(); // Me retourne l'id de l'INSERT qui vient juste d'être effectué ... 

		for ($i=0; $i < count($_SESSION['panier']['id_produit']) ; $i++) { 
			$id_produit = $_SESSION['panier']['id_produit'][$i];
			$quantite = $_SESSION['panier']['quantite'][$i];
			$prix = $_SESSION['panier']['prix'][$i];

			$resultat = $pdo -> exec("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES ('$id_commande', '$id_produit', '$quantite', '$prix')");

			// Pour chaque produit acheté je vais modifier le stock
			$resultat = $pdo -> exec("UPDATE produit SET stock = (stock - $quantite) WHERE  id_produit = '$id_produit'");
		}

		unset($_SESSION['panier']);
		$msg .= '<div class="validation">Félicitation, voici votre numéro de commande : <b>'.$id_commande.'</b></div>';

	}
}

//debug($_SESSION);

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Panier';
require_once('inc/haut.inc.php');
?>


<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->

<!-- ****************************************** -->
<!-- ******* AFFICHAGE DU PANIER ************** -->
<!-- ****************************************** -->
<h1>Panier</h1>
<?php echo $msg; ?>

<table class="panier">
	<tr>
		<td colspan="8"> PANIER <?= (quantitePanier()) ? '('.quantitePanier().')' : '' ?></td>
	</tr>
	<tr>
		<th>Photo</th>
		<th>Titre</th>
		<th>Quantité</th>
		<th>Prix unitaire (HT)</th>
		<th>Prix unitaire (TTC)</th>
		<th>Total (HT)</th>
		<th>Total (TTC)</th>
		<th>Supprimer tout</th>
	</tr>

	<?php if(empty($_SESSION['panier']['id_produit'])) : ?>
		<tr>
			<td colspan="8">Votre panier est vide !</td>
		</tr>
	<?php else : ?>
		<?php for($i = 0 ; $i < count($_SESSION['panier']['id_produit']); $i++) : ?>
			<tr>
				<td>
					<a href="fiche_produit.php?id=<?= $_SESSION['panier']['id_produit'][$i] ?>">
						<img src="photo/<?= $_SESSION['panier']['photo'][$i] ?>" height="50">
					</a>
				</td>

				<td>
					<a href="fiche_produit?id=<?= $_SESSION['panier']['id_produit'][$i] ?>">
						<?= $_SESSION['panier']['titre'][$i]?>
					</a>
				</td>

				<td>
					<a href="?action=decrementation&id=<?= $_SESSION['panier']['id_produit'][$i] ?>">
						<img src="img/moins.png" alt="moins" width="15">
					</a>
					<span>
						<?= $_SESSION['panier']['quantite'][$i] ?>
					</span>
					<a href="?action=incrementation&id=<?= $_SESSION['panier']['id_produit'][$i] ?>">
						<img src="img/plus.png" alt="plus" width="15">
					</a>
				</td>

				<td>
					<?= round($_SESSION['panier']['prix'][$i], 2) ?>€
				</td>
				<td>
					<?= round(($_SESSION['panier']['prix'][$i]*1.2), 2) ?>€
				</td>

				<td>
					<?= round($_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i], 2) ?>€
				</td>
				<td>
					<?=  round(($_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i]*1.2), 2) ?>€
				</td>

				<td>
					<a href="?action=suppression&id=<?= $_SESSION['panier']['id_produit'][$i]?>">
						<img src="img/delete.png" alt="">
					</a>
				</td>
			</tr>
		<?php endfor; ?>
	
		<tr>
			<td colspan="3" rowspan="2">MONTANT TOTAL :</td>
			<td colspan="2"><b><?= montantTotal() ?>€ (HT)</b></td>
			<td colspan="2"><b><?= montantTotal()*1.2 ?>€ (TTC)</b></td>
			<td colspan="1" rowspan="2">
				<button><a href="?action=vider">Supprimer le panier</a></button>
			</td>
		</tr>

		<tr>
			<?php if(userConnecte()) : ?>
				<td colspan="4">
					<form action="" method="post">
						<input type="hidden" name="total" value="<?= montantTotal() ?>">
						<input type="submit" value="Payer le panier" name="payer">
					</form>
				</td>
			<?php else : ?>
				<td colspan="4">Veuillez vous <a href="connexion.php?page=panier">connecter</a> ou vous <a href="inscription.php?page=panier">inscrire</a> !</td>
			<?php endif ; ?>
		</tr>
	<?php endif; ?>
</table>


<?php 
// ******************************************
// **************BAS DE PAGE*****************
// ******************************************
require_once('inc/bas.inc.php');

?>