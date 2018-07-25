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
    <title>Pronostic : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/resultat.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/matchs.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/pronostic.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "pronostique";
require_once ('header.php');
?>

<main>
    <div class="maxwidth">
        <h2>PRONOSTIC</h2>
        <div class="classement">
            <?php
            if(isset($_SESSION['auth'])) {
            ?>
            <p>Pour commencer à pronostiquer, vous devez créer ou rejoindre un groupe d'amis.<br/>Tu peux avoir jusqu'à 3 groupes d'amis</p>
            <div class="list">
                <a href="./new_group.php">
                    <div class="bluebutton">Créer un groupe</div>
                </a>
                <a href="./join_group.php">
                    <div class="bluebutton">Rejoindre un groupe</div>
                </a>
            </div>
        </div>
        <div class="champion listgroup">
            <h2 style="font-size: 1.7rem">MES GROUPES</h2>
        <?php

        $sql = 'SELECT L.id AS idLiaison, id_user, id_groupe, G.id AS idGroupe, nom, code FROM liaison L INNER JOIN groupes G ON G.id=L.id_groupe WHERE id_user = ' . $_SESSION['auth']->id;
        $resultat = $connexion->query($sql);
        $groupe = $resultat->fetchAll(PDO::FETCH_OBJ);

        foreach ($groupe as $g) {

            if (isset($g->idLiaison)) {

                echo '<a href="competition.php?groupe=' . $g->idGroupe . '"><div class="groups">' . $g->nom . '</div></a><br/>';

            }
        }
        if (empty($groupe)) {
                echo "<p>Vous n'avez pas de groupe pour le moment, créez en un ou rejoingez celui de vos amis</p>";
            }
            ?>
        </div>
            <?php

        }
        else {
            echo "<p>Vous devez être connecté afin d'afficher ce contenu</p>";
        }
        ?>
    </div>
</main>

<?php
require_once ('footer.php');
?>
