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
        <title>Activation : CDM 2018</title>
        <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
<body>

<?php
$courante = "inscription";
require_once ('header.php');
?>
    <div class="champion">
        <h2>VALIDATION</h2>
        <?php

        if(isset($_SESSION['auth'])) {
            ?>
            <p>Vous êtes déjà connecté.</p>
            <?php
        }
        else {

            if(isset($_GET['keyValid'])) {

                $sql = 'SELECT cle, active FROM users WHERE cle = "' . $_GET['keyValid'] . '"';
                $resultat = $connexion->query($sql);
                $cleBDD = $resultat->fetch(PDO::FETCH_OBJ);

                if(isset($cleBDD->cle)) {

                    if ($cleBDD->cle === $_GET['keyValid']) {

                        if ($cleBDD->active === "0") {
                            $update = 'UPDATE users SET active = 1';
                            $activer = $connexion->exec($update);

                            echo "<p>Votre compte est maintenant validé, vous pouvez vous connectez.</p>" .
                                "<p>Retourner à <a href='./index.php'>l'accueil</a></p>";
                        } else {
                            echo "<p>Votre compte est déjà validé.</p>";
                        }
                    }
                }
                else {
                    echo "<p>La clé est erronée</p>";
                }

            }

            else {
                echo "<p>La clé est erronée</p>";
            }
        }
        ?>
    </div>
<?php
require_once ('footer.php');
?>