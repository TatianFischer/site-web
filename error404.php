<?php
// HEADER ------------------------------
$title = '404';
$page = 'page 404';

require_once('inc/header.inc.php');
?>

<main id="page404">
	<p class="ml8">
		<span class="letters-container">
		    <span class="letters letters-left">404</span>
		    <span class="letters bang">!</span>
		  </span>
		  <span class="circle circle-white"></span>
		  <span class="circle circle-dark"></span>
		  <span class="circle circle-container"><span class="circle circle-dark-dashed"></span></span>
	</p>
	<p>Vous semblez perdu</p>
	<?php if (!empty($_SERVER['HTTP_REFERER'])) {
  		echo '<p><a href="'.$_SERVER['HTTP_REFERER'].'">Retour page précédente</a></p>';
	}?>
</main>

<?php

$js = 'anim404.js';
// FOOTER -------------------------------
require_once('inc/footer.inc.php');
?>