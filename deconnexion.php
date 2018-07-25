<?php

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

require_once ('./inc/connexion.php');
?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Déconnexion : CDM 2018</title>
        <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
<body>

<?php
$courante = "inscription";
require_once ('header.php');

session_destroy();
?>
    <div class="champion">
        <h2>DÉCONNEXION</h2>
        <p>Vous êtes maintenant déconnecté.</p>
    </div>
    <script type='text/javascript'>document.location.replace('index.php');</script>
<?php
require_once ('footer.php');
?>