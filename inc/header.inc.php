<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<meta name="description" content="<?= $page ?>">
		
		<title>Tatiana Fischer | <?= $title ?></title>
		
		<!--Pour Internet Explorer : s'assurer qu'il utilise la dernière version du moteur de rendu-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
    
        <!--Affichage sans zoom sur les mobiles-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Bootstrap -->
		<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
		<!-- Font Awesome -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		
		<!-- font-family: 'Gloria Hallelujah', cursive; -->
		<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
		
		<!-- Mes feuilles de style -->
		<link rel="stylesheet" href="assets/css/style-menu.css" media="screen">
		<link rel="stylesheet" href="assets/css/style-index.css" media="screen">
		
		<!-- HTML 5 Shiv-min script tag with SRI - Polyfill (voir p8 du cahier)-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" integrity="sha256-3Jy/GbSLrg0o9y5Z5n1uw0qxZECH7C6OQpVBgNFYa0g=" crossorigin="anonymous"></script>
	</head>

	<body>
		<div class="container r-p">
<!-- Barre de navigation -->	
            <nav class="navbar" role="navigation" id='top'>
                <div class="container-fluid r-p">
                    <div class="navbar-header r-m">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span> 
                        </button>
                        <a class="navbar-brand r-m" href="index.php">FISCHER  Tatiana</a>
                    </div>
                    <div class="collapse navbar-collapse r-p r-m" id="menu">
                        <ul class="nav navbar-nav">
                            <li <?= ($title=='C.V') ? 'class="active"' : ''?> >
                                <a href="CV.php">C.V.</a>
                            </li>
                            <li <?= ($title=='Formation') ? 'class="active"' : ''?>>
                                <a href="formation.php">Formation</a>
                            </li>
                            <li <?= ($title=='Porfolio') ? 'class="active"' : ''?> >
                                <a href="porfolio.php">Portfolio</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
<!-- Fin de la barre de navigation-->