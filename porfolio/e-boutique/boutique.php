<?php 
require_once('inc/init.inc.php');

// ******************************************
// ********* HAUT DE LA PAGE ****************
// ******************************************
$page = 'Boutique';
require_once('inc/haut.inc.php');




// ******************************************
// ******* RECUPERATION DES CATEGORIES ******
// ******************************************
// On récupère toutes les infos de toutes les catégories de produit (qui ont du stock !):
$resultat = $pdo -> query("SELECT DISTINCT categorie FROM produit WHERE stock != 0");
$categorie = $resultat -> fetchAll(PDO::FETCH_ASSOC);
//debug($categorie);

// On récupère toutes les infos de tous les produits correspondants à la catégorie passée en GET
if(isset($_GET['categorie']) && $_GET['categorie'] != ''){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE  categorie = :categorie AND stock != 0");
	$resultat -> bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
	$resultat -> execute();

	$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	// $produit est un tableau multi-dimentionnel
	//debug($produits);

	// Si aucun résultat (erreur dans l'url) alors on redirige vers une 404 ou vers boutique.php
	if($resultat -> RowCount() == 0){
		header('location:boutique.php');
	}
}




// ******************************************
// ********RECUPERATION DES PUBLICS**********
// ******************************************
// Liste des public
$resultat = $pdo -> query("SELECT DISTINCT public FROM produit WHERE stock != 0");
$public = $resultat -> fetchAll(PDO::FETCH_ASSOC);
//debug($public);

// On récupère toutes les infos de tous les produits correspondants à la catégorie passée en GET
if(isset($_GET['public']) && $_GET['public'] != ''){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE  public = :public AND stock != 0");
	$resultat -> bindParam(':public', $_GET['public'], PDO::PARAM_STR);
	$resultat -> execute();

	$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	// $produit est un tableau multi-dimentionnel
	//debug($produits);

	// Si aucun résultat (erreur dans l'url) alors on redirige vers une 404 ou vers boutique.php
	if($resultat -> RowCount() == 0){
		header('location:boutique.php');
	}
}




// ******************************************
// *************** Recherche ****************
// ******************************************
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
					)";
		$search = '%'.$_GET['search'].'%';
		$resultat = $pdo -> prepare($query);
		$resultat -> bindParam(':search', $search, PDO::PARAM_STR);
		$resultat -> execute();
		$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
	}
}








// ******************************************
// ***********AFFICHAGE PAR DEFAUT***********
// ******************************************
if(empty($_GET['categorie']) && empty($_GET['public']) && empty($_GET['search'])){

	// On va faire une pagination
	$resultat = $pdo->query("SELECT count(*) AS total FROM produit"); 
	$data_produit = $resultat -> fetch(PDO::FETCH_ASSOC); // On range sous forme d'un tableau
	$nombre_produits = $data_produit['total']; // On récupère le total

	$nombre_produits_par_page = 9;
	$nombre_de_page = ceil($nombre_produits / $nombre_produits_par_page); // Nombre de pages

	if(isset($_GET['page'])) // Véririfie que la variable existe
	{
		$page_actuelle = intval($_GET['page']); // string to integer

		if($page_actuelle > $nombre_de_page){ // renvoie à la dernière page
			$page_actuelle = $nombre_de_page;
		}
	}else{
		$page_actuelle = 1; // Si $_GET['page'] n'existe pas, on est sur la première page
	}

	// LIMIT $debut, $offset
	$debut = ($page_actuelle-1)*$nombre_produits_par_page; // Le premier produit est en 0 !!!!

	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE stock != 0 LIMIT ".$debut.", ".$nombre_produits_par_page);
	$resultat -> execute();

	$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);
}

?>







<!-- ######################################### -->
<!-- ########### CONTENU HTML ################ -->
<!-- ######################################### -->

<h1>Boutique</h1>
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
			<a href="?categorie=<?= $cat['categorie'] ?>"><?= $cat['categorie'] ?></a>
			<!-- Change l'URL et y ajoute ?catérorie=t-shirt par exemple -->
		</li>
		<?php endforeach; ?>
	</ul>
	<br>
	<ul>
		<li><b>PUBLIC :</b></li>
		<?php foreach ($public as $sexe) : ?>
		<li>
			<a href="?public=<?= $sexe['public'] ?>"><?= $sexe['public'] ?></a>
			<!-- Change l'URL et y ajoute ?catérorie=t-shirt par exemple -->
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="boutique-droite">
	<?php foreach ($produits as $produit) : ?>
		<!-- VIGNETTE PRODUIT -->
		<div class="boutique-produit">
			<h3><?= $produit['titre'] ?></h3>
			<a href="fiche_produit.php?id=<?= $produit['id_produit'] ?>">
				<img src="photo/<?= $produit['photo'] ?>">
			</a>
			<p class="prix">
				<?= $produit['prix'] ?> €
			</p>
			<p class="description">
				<?= substr($produit['description'], 0, 40).'...' ?>
			</p>
			<br><a href="fiche_produit.php?id=<?= $produit['id_produit'] ?>">Voir la fiche</a>
		</div>
	<?php endforeach ?>
	<div class="pagination">
		<p> Page :
		<?php for($i=1; $i<=$nombre_de_page; $i++){
			if($i == $page_actuelle){
				echo '<b>'.$i.'</b>';
			} else {
				echo '<a href="?page='.$i.'">'.$i.'</a>';
			}
			if($i != $nombre_de_page){
				echo ' - ';
			}
		} ?>
		</p>
	</div>
</div>

<?php 
// ******************************************
// **************BAS DE PAGE*****************
// ******************************************
require_once('inc/bas.inc.php');

?>