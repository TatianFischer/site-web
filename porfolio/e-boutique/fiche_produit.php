<?php 
require_once('inc/init.inc.php');

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Boutique';
require_once('inc/haut.inc.php');

//
// On récupère toutes les infos de toutes les catégories de produit (qui ont du stock !):
$resultat = $pdo -> query("SELECT DISTINCT categorie FROM produit WHERE stock != 0");
$categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT DISTINCT public FROM produit WHERE stock != 0");
$public = $resultat -> fetchAll(PDO::FETCH_ASSOC);
?>
<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<div class="boutique-gauche">
	<div class="bandeau">
		<form action="?search=$_GET['search']" method="get" id="searchForm">
			<input type="text" name="search" id="searchInput">
			<ul class="product_list"></ul>
			<input type="submit" value="Rechercher">
		</form>
	</div>
	<ul>
		<li><b>CAT&Eacute;GORIE :</b></li>
		<?php foreach ($categorie as $cat) : ?>
		<li>
			<a href="boutique.php?categorie=<?= $cat['categorie'] ?>"><?= $cat['categorie'] ?></a>
			<!-- Change l'URL et y ajoute ?catérorie=t-shirt par exemple -->
		</li>
		<?php endforeach; ?>
	</ul>
	<br>
	<ul>
		<li><b>PUBLIC :</b></li>
		<?php foreach ($public as $sexe) : ?>
		<li>
			<a href="boutique.php?public=<?= $sexe['public'] ?>"><?= $sexe['public'] ?></a>
			<!-- Change l'URL et y ajoute ?catérorie=t-shirt par exemple -->
		</li>
		<?php endforeach; ?>
	</ul>
</div>




<?php
// ******************************************
// ********* VERIFICATION ID ****************
// ******************************************
if(isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();
} else {
	// Si l'id est vide ou n'existe pas dans url
	header('location:boutique.php');
}

if($resultat -> RowCount() != 0){ // Si le produit existe
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
		// debug($produit);
		extract($produit);
?>
<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->
<div class="produit">
	<h1><?= $titre ?></h1>
	<img class="produit" src="photo/<?= $photo ?>" alt="">
	<br>
	<ul class="produit">
		<li><b>Référence : </b><?= $reference ?></li>
		<li><b>Description : </b><?= $description ?></li>
		<li><b>Catégorie : </b><?= $categorie ?></li>
		<li><b>Couleur : </b><?= $couleur ?></li>
		<li><b>Taille : </b><?= $taille ?></li>
		<li><b>Public : </b><?php if($public == 'm'){echo 'Homme';} elseif($public == 'f') {echo 'Femme';} else { echo 'Unisexe';} ?></li>
		<li><b>Prix : </b><?= $prix ?> €</li>
	</ul>
	<br>
	<?php if($stock > 0) : ?>
		<i>Nombre de produit<?= ($stock >1 ) ? 's' : '' ?> disponible<?= ($stock >1 ) ? 's' : '' ?> : <?= $stock ?></i>
	<br><br>
	<form action="" method="post">
		<input type="hidden" name="id_produit" value="<?= $id_produit ?>">
		<label for="quantite">Quantité : </label>
		<select name="quantite" id="quantite">
			<?php
				$positionPdt = array_search($id_produit, $_SESSION['panier']['id_produit']);
				$quantite = $_SESSION['panier']['quantite']['$positionPdt'];
				$stock -= $quantite;
				for($i = 1; $i <= $stock && $i <= 5; $i++) : ?>
					<option value="<?= $i ?>"><?= $i ?></option>
			<?php endfor; ?>
		</select>
		<br>
		<input type="submit" value="Ajouter au panier">
	</form>

	<?php else : ?>
		<span style="color:red"> Produit en rupture de stock !!!</span>
	<?php endif ?>
</div>
	<br><hr><br>





<!-- ######################################### -->
<!-- ######## SUGGESTIONS DE PRODUITS ######## -->
<!-- ######################################### -->
	<!-- Afficher jusqu'à 5 suggestions de produit (tout produit sauf catégorie du produit en cours) -->
	<?php 
	$resultat = $pdo -> query("SELECT * FROM produit WHERE categorie != '$categorie' ORDER BY RAND() LIMIT 0,5");
	
	//Pour un produit de la même categorie
	//$resultat = $pdo -> query("SELECT * FROM produit WHERE categorie = '$categorie' ORDER BY RAND() LIMIT 5");
	$suggestion = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	//debug($suggestion);
	?>
	<!-- design :  faire des vignettes l'une à coté de l'autre
	 -->
	<div class="bloc_suggestion">
		<h1>Suggestions de produits</h1>
		<?php foreach ($suggestion as $produit) : ?>
			<div class="suggestion">
				<h3><?= $produit['titre'] ?></h3>
				<a href="?id=<?= $produit['id_produit'] ?> ">	<img src="photo/<?= $produit['photo'] ?>">
				</a>
				<p class="prix"><?= $produit['prix'] ?> €</p>
				<p>
					<a href="?id=<?= $produit['id_produit'] ?> ">Voir la fiche ...</a>
				</p>
			</div>
		<?php endforeach; ?>
		<br>
	</div>
	<hr>
<?php 
} else {
	// Le produit n'existe pas
	header('location:boutique.php');
}
// ******************************************
// ********* AJOUT AU PANIER ****************
// ******************************************
if($_POST){
	//debug($_POST);
	ajoutPanier($_POST['id_produit'], $_POST['quantite'], $titre, $prix, $photo); 
	// fonction crée dans inc/fonction.inc.php
	// Args : id_produit, quantite, prix, photo
}

//debug($_SESSION);
// ******************************************
// **************BAS DE PAGE*****************
// ******************************************
require_once('inc/bas.inc.php');
?>