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
    <title>Connexion : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "connexion";
require_once ('header.php');
?>

<div class="champion">
    <h2>CONNEXION</h2>

    <?php

    if(isset($_SESSION['auth'])) {
        ?>
        <p>Vous êtes déjà connecté.</p>
        <?php
    }
    else {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['connexion'])) {
            if (empty($_POST['pseudo'])) {
                $erreurs['pseudo'] = "Vous devez remplir le champ pseudo";
            }
            if (empty($_POST['mdp'])) {
                $erreurs['mdp'] = "Vous devez remplir le champ mot de passe";
            } else {

                $pseudo = $_POST['pseudo'];
                $password = $_POST['mdp'];

                $sql = 'SELECT id, username, password, email, cle, active, admin FROM users WHERE username = "' . $pseudo . '" AND active = "1"';
                $resultat = $connexion->query($sql);
                $etat = $resultat->fetch(PDO::FETCH_OBJ);

                if (isset($etat->username) && password_verify($password, $etat->password)) {
                    $_SESSION['auth'] = $etat;
                    echo "<p style='color: #0f4582'>Bonjour " . $_SESSION['auth']->username . " !</p>" .
                        "<p style='color: #0f4582'>Vous allez être redirigé vers la page d'accueil.</p>";

                    echo "<script type='text/javascript'>document.location.replace('index.php');</script>";

                    exit();
                } else {
                    echo "<p style='color: #d7171f'>Votre pseudo ou votre mot de passe ne correspond pas.</p>";
                }

            }
        }

        if (!empty($erreurs)) {

            foreach ($erreurs as $er) {
                echo "<span style='color: #d7171f'>" . $er . "</span><br/><br/>";
            }
        }

        ?>

        <form method="post" action="">
            <p>Pseudo</p>
            <input type="text" name="pseudo"/>
            <p>Mot de passe</p>
            <input type="password" name="mdp"/>
            <p>
                <input type="submit" class="submit" name="connexion" value="Se connecter"/>
            </p>
        </form>
        <?php
    }
    ?>

</div>

<?php
require_once ('footer.php');
?>
