<!Doctype html>
<html>
    <head>
        <title>Mon Site - <?= $page ?></title>
        <link rel="stylesheet" href="<?php echo RACINE_SITE ?>css/style.css"/>
    </head>
    <body>    
        <header>
			<div class="conteneur-full">                      
				<span>
					<a href="" title="Mon Site">MonSite.com</a>
				</span>
				<nav>
					<ul>
					<?php if(userConnecte()) : ?>
						<li <?= ($page=='Profil') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>profil.php">Profil</a>
						</li>
						<li <?= ($page=='Boutique') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>boutique.php">Boutique</a>
						</li>
						<li <?= ($page=='Panier') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>panier.php">
								Panier
								<?php if(quantitePanier()) : ?>
									<span class="bulle"><?= quantitePanier(); ?></span>
								<?php endif ?>
							</a>
						</li>

						<?php if(userAdmin()) : ?>
							<li <?= ($page=='Gestion Boutique') ? 'class="active"' : ''?> >
								<a href="<?php echo RACINE_SITE ?>admin/gestion_boutique.php">Gestion Boutique</a>
							</li>
							<li <?= ($page=='Gestion Membre') ? 'class="active"' : ''?> >
								<a href="<?php echo RACINE_SITE ?>admin/gestion_membre.php">Gestion Membre</a>
							</li>
							<li <?= ($page=='Gestion Commande') ? 'class="active"' : ''?> >
								<a href="<?php echo RACINE_SITE ?>admin/gestion_commande.php">Gestion Commande</a>
							</li>
						<?php endif; ?>

						<li>
							<a href="<?php echo RACINE_SITE ?>connexion.php?action=deconnexion">Déconnexion</a>
						</li>
					
					<?php else: ?>
						<li <?= ($page=='Connexion') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>connexion.php">Connexion</a>
						</li>
						<li <?= ($page=='Inscription') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>inscription.php">Inscription</a>
						</li>
						<li <?= ($page=='Boutique') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>boutique.php">Boutique</a>
						</li>
						<li <?= ($page=='Panier') ? 'class="active"' : ''?> >
							<a href="<?php echo RACINE_SITE ?>panier.php">
								Panier
								<?php if(quantitePanier()) : ?>
									<span class="bulle"><?= quantitePanier(); ?></span>
								<?php endif ?>
							</a>
						</li>
					<?php endif;?>
					
					<!-- Cela nous donne des liens de ce type :  -->
					<!-- <a class="active" href="profil.php" >Profil</a> -->
					</ul>
				</nav>
				<span></span>
			</div>
        </header>
        <section>
			<div class="conteneur">