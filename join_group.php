<?php

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

require_once ('./inc/connexion.php');

require_once ('./inc/secure.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rejoindre un groupe : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "pronostique";
require_once ('header.php');
?>
<div class="arianne">
    <div class="maxwidth">
        <p><a href="pronostics.php">ACCUEIL PRONOSTIC</a> > REJOINDRE UN GROUPE</p>
    </div>
</div>

<div class="champion">
    <h2>REJOINDRE UN GROUPE</h2>
    <?php

    if(isset($_SESSION['auth'])) {

    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['joingroupe']))
    {

        $sql = 'SELECT id, nom, code FROM groupes WHERE code = "' . $_POST['acces'] . '"';
        $resultat = $connexion->query($sql);
        $groupe = $resultat->fetch(PDO::FETCH_OBJ);

        if (empty($_POST['acces'])) {
            echo "<p style='color: #d7171f'>Vous devez remplir le code d'accès.</p>";
        }
        else if (isset($groupe->nom)) {

            $sqlCount = 'SELECT COUNT(id_user) FROM liaison WHERE id_user = "' . $_SESSION['auth']->id . '"';
            $resultatCount = $connexion->query($sqlCount);
            $count = $resultatCount->fetchColumn();

            if($count === "3") {

                echo "<p style='color: #d7171f'>Vous avez déjà rejoins 3 groupes. Vous ne pouvez pas rejoindre davantage de groupe.</p>";

            }
            else {

                $sqlTest = 'SELECT COUNT(*) FROM liaison WHERE id_user = "' . $_SESSION['auth']->id . '" AND id_groupe = "' . $groupe->id . '"';
                $resultatTest = $connexion->query($sqlTest);
                $test = $resultatTest->fetchColumn();

                if ($test === "1") {
                    echo "<p style='color: #d7171f'>Vous avez déjà rejoins ce groupe</p>";
                }
                else {
                    $sqlJoin = 'INSERT INTO liaison (id_user, id_groupe) VALUES ("' . $_SESSION['auth']->id . '", "' . $groupe->id . '")';
                    $addJoin = $connexion->exec($sqlJoin);

                    echo "<p style='color: #0f4582'>Vous avez rejoins le groupe " . $groupe->nom . " !</p>";
                    echo "<script type='text/javascript'>document.location.replace('competition.php?groupe=" . $groupe->id . "');</script>";
                }
            }
        }
        else {
            echo "<p style='color: #d7171f'>Le code d'accès est incorrect.</p>";
        }
    }
        ?>
        <form method="post" action="">
            <p>Code d'accès au groupe <span style="color: #d7171f">*</span></p>
            <input type="text" name="acces" />
            <p>
                <input type="submit" class="submit" value="Rejoindre" name="joingroupe" />
            </p>
        </form>
        <?php
    }
    else {
        ?>
        <p>Vous devez être connecté afin d'afficher ce contenu</p>
        <?php
    }
    ?>

</div>

<?php
require_once ('footer.php');
?>
