<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

require_once('./inc/connexion.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "index";
require_once ('header.php');
?>

<div class="imageback"></div>

<div class="champion">
    <h2>DEVENEZ CHAMPION</h2>
    <p>Défiez vos amis et prouvez qui est le meilleur d'entre vous à travers une compétition privée.</p>
</div>

<div class="start">
    <div class="maxwidth">
        <h3>INSCRIVEZ-VOUS, INVITEZ VOS AMIS, PARIEZ !</h3>
	<?php
	if(isset($_SESSION['auth'])) {
	?>
        <a href="./pronostics.php"><div class="whitebutton">COMMENCER</div></a>
	<?php
	}
	else {
	?>
        <a href="./inscription.php"><div class="whitebutton">COMMENCER</div></a>
	<?php
	}
	?>
    </div>
</div>

<main class="index">
    <div class="maxwidth">
        <h2>GROUPES</h2>
        <div class="row">
            <section>
                <h4>GROUPE A</h4>
                <p>Russie</p>
                <p>Arabie Saoudite</p>
                <p>Egypte</p>
                <p>Uruguay</p>
            </section>
            <section>
                <h4>GROUPE B</h4>
                <p>Portgual</p>
                <p>Espagne</p>
                <p>Maroc</p>
                <p>Iran</p>
            </section>
            <section>
                <h4>GROUPE C</h4>
                <p>France</p>
                <p>Australie</p>
                <p>Perou</p>
                <p>Danemark</p>
            </section>
            <section>
                <h4>GROUPE D</h4>
                <p>Argentine</p>
                <p>Islande</p>
                <p>Croatie</p>
                <p>Nigeria</p>
            </section>
        </div>

        <div class="row">
            <section>
                <h4>GROUPE E</h4>
                <p>Bresil</p>
                <p>Suisse</p>
                <p>Costa Rica</p>
                <p>Serbie</p>
            </section>
            <section>
                <h4>GROUPE F</h4>
                <p>Allemagne</p>
                <p>Mexique</p>
                <p>Suede</p>
                <p>Coree du Sud</p>
            </section>
            <section>
                <h4>GROUPE G</h4>
                <p>Belgique</p>
                <p>Panama</p>
                <p>Tunisie</p>
                <p>Angleterre</p>
            </section>
            <section>
                <h4>GROUPE H</h4>
                <p>Pologne</p>
                <p>Senegal</p>
                <p>Colombie</p>
                <p>Japon</p>
            </section>
        </div>
    </div>
</main>

<?php
require_once ('footer.php');
?>