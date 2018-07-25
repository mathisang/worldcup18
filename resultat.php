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
    <title>Resultat : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/resultat.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/matchs.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "resultat";
require_once ('header.php');
?>

<main>
    <div class="maxwidth">
        <h2>CLASSEMENT</h2>
        <?php

        echo '<div class="classement">';

        for ($i = "A"; $i <= "D"; $i++) {
        $sql2 = 'SELECT id, nom , points, groups FROM teams WHERE groups = "' . $i . '" ORDER BY points DESC';
        $resultat2 = $connexion->query($sql2);
        $groupe = $resultat2->fetchAll(PDO::FETCH_OBJ);
        ?>

        <section>
            <h4>GROUPE <?php echo $i ?></h4>
            <?php

            foreach ($groupe as $g) {

                ?>
                <div>
                    <p><?php echo $g->nom ?></p>
                    <p><?php echo $g->points ?></p>
                </div>
                <?php
            }
            echo '</section>';
            }
            echo '</div>';

            echo '<div class="classement">';

            for ($i = "E"; $i <= "H"; $i++) {
            $sql2 = 'SELECT id, nom , points, groups FROM teams WHERE groups = "' . $i . '" ORDER BY points DESC';
            $resultat2 = $connexion->query($sql2);
            $groupe = $resultat2->fetchAll(PDO::FETCH_OBJ);
            ?>

            <section>
                <h4>GROUPE <?php echo $i ?></h4>
                <?php

                foreach ($groupe as $g) {

                    ?>
                    <div>
                        <p><?php echo $g->nom ?></p>
                        <p><?php echo $g->points ?></p>
                    </div>
                    <?php
                }
                echo '</section>';
                }
                echo '</div>';



                ?>


    </div>
</main>

<main id="match">
    <div class="maxwidth">
        <h2>MATCHS</h2>
        <p>Clique sur la journée de ton choix pour afficher les matchs et les résultats correspondants.</p><br/>
        <div class="list">
            <a href="./resultat.php?journee=1#match"><div class="bluebutton">Journée 1</div></a>
            <a href="./resultat.php?journee=2#match"><div class="bluebutton">Journée 2</div></a>
            <a href="./resultat.php?journee=3#match"><div class="bluebutton">Journée 3</div></a>
            <a href="./resultat.php?journee=4#match"><div class="bluebutton">Huitième de finale</div></a>
            <a href="./resultat.php?journee=5#match"><div class="bluebutton">Quart de finale</div></a>
        </div>

        <?php

        if(isset($_GET['journee'])) {

            echo '<div class="matchs">';

            $sqlJournee = 'SELECT id, nom FROM journee WHERE id = ' . $_GET['journee'];
            $resultatJournee = $connexion->query($sqlJournee);
            $idJournee = $resultatJournee->fetch(PDO::FETCH_OBJ);

            $sql = 'SELECT J.id AS idJour, jour, id_journee, nom FROM jour J INNER JOIN journee JR ON J.id_journee=JR.id WHERE id_journee = ' . $_GET['journee'];
            $resultat = $connexion->query($sql);
            $journee = $resultat->fetchAll(PDO::FETCH_OBJ);

            echo '<h3>' . $idJournee->nom . '</h3>';

            foreach ($journee as $j) {

                ?>
                <section>
                <h4><?php echo $j->jour ?></h4>
                <?php
                $sql = 'SELECT id, team1, score1, team2, score2, id_jour, heure FROM matchs WHERE id_jour = ' . $j->idJour;
                $resultat = $connexion->query($sql);
                $jour = $resultat->fetchAll(PDO::FETCH_OBJ);

                foreach ($jour as $jr) {
                    ?>
                    <div>
                        <p style="font-weight: 500;"><?php echo $jr->team1 ?></p>
                        <p><?php echo $jr->score1 ?></p>
                        <p style="font-weight: 500;"><?php echo $jr->team2 ?></p>
                        <p><?php echo $jr->score2 ?></p>
                        <p><?php echo substr($jr->heure, 0, 5) ?></p>
                    </div>
                    </section>
                    <?php
                }
            }

            echo '</div>';
        }
        else {

        }

        ?>

    </div>
</main>

<?php
require_once ('footer.php');
?>
