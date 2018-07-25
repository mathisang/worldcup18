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
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Pronostic : CDM 2018</title>
        <link rel="stylesheet" href="lib/css/competition.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/matchs.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/pronostic.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
<body>

<?php
$courante = "pronostique";
require_once ('header.php');

if(isset($_SESSION['auth'])) {

    if(!empty($_GET['groupe'])) {

        $sql = 'SELECT L.id AS idLiaison, id_user, id_groupe, G.id AS idGroupe, nom, code, type_compet FROM liaison L INNER JOIN groupes G ON G.id=L.id_groupe WHERE id_user = ' . $_SESSION['auth']->id . ' AND G.id = ' . $_GET['groupe'];
        $resultat = $connexion->query($sql);
        $groupe = $resultat->fetch(PDO::FETCH_OBJ);

        if (!empty($groupe)) {
            ?>

            <div class="arianne">
                <div class="maxwidth">
                    <p style="text-transform: uppercase"><a href="pronostics.php">ACCUEIL PRONOSTIC</a> > <a href="competition.php?groupe=<?php echo $_GET['groupe'] ?>"><?php echo $groupe->nom ?></a> > ENVOYER MON PRONOSTIC</p>
                </div>
            </div>

            <main id="match">
                <div class="maxwidth">
                    <h2>PRONOSTICS</h2>
                    <p style="margin-left: .5rem">Si tu trouve le vainqueur du match, tu gagneras 1 point.<br/>
                        Trouver le score exact du match te rapportera 3 points supplémentaires soit 4 points au total.</p>
                    <p style="margin-left: .5rem">Pour envoyer ton pronostic, tu dois sélectionner la journée et le jour du match, puis le match en question.</p><br/>
                    <div class="list">
                        <?php

                        if ($groupe->type_compet === "0") {
                            $sql1 = 'SELECT journee.id, nom FROM journee INNER JOIN jour ON journee.id=jour.id_journee INNER JOIN matchs ON matchs.id_jour=jour.id WHERE journee.id > 4 GROUP BY nom ORDER BY journee.id';
                            $resultat1 = $connexion->query($sql1);
                            $journee = $resultat1->fetchAll(PDO::FETCH_OBJ);
                        }
                        else if ($groupe->type_compet === "1") {
                            $sql1 = 'SELECT journee.id, nom FROM journee INNER JOIN jour ON journee.id=jour.id_journee INNER JOIN matchs ON matchs.id_jour=jour.id WHERE matchs.team1 = "France" OR matchs.team2 = "France" GROUP BY nom ORDER BY journee.id';
                            $resultat1 = $connexion->query($sql1);
                            $journee = $resultat1->fetchAll(PDO::FETCH_OBJ);
                        }

                        foreach ($journee as $j) {
                            ?>
                            <a href="./send_prono.php?groupe=<?php echo $_GET['groupe'] ?>&journee=<?php echo $j->id ?>">
                                <div class="bluebutton" <?php if(isset($_GET['journee']) && $j->id === $_GET['journee']) { echo 'style="background: #d7171f"'; } ?>><?php echo $j->nom ?></div>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    if(isset($_GET['journee'])) {

                        if ($groupe->type_compet === "0") {
                            $sql2 = 'SELECT J.id, jour, id_journee FROM jour J INNER JOIN matchs M ON M.id_jour=J.id WHERE id_journee = ' . $_GET['journee'] . ' AND M.vainqueur IS NULL GROUP BY jour ORDER BY J.id';
                            $resultat2 = $connexion->query($sql2);
                            $jour = $resultat2->fetchAll(PDO::FETCH_OBJ);
                        }

                        else if ($groupe->type_compet === "1") {
                            $sql2 = 'SELECT J.id, jour, id_journee FROM jour J INNER JOIN matchs M ON M.id_jour=J.id WHERE id_journee = ' . $_GET['journee'] . ' AND M.team1 = "France" OR M.team2 = "France" LIMIT 1';
                            $resultat2 = $connexion->query($sql2);
                            $jour = $resultat2->fetchAll(PDO::FETCH_OBJ);
                        }

                        echo '<div class="list">';

                        foreach ($jour as $jr) {
                            ?>
                            <a href="./send_prono.php?groupe=<?php echo $_GET['groupe'] ?>&journee=<?php echo $_GET['journee'] ?>&date=<?php echo $jr->id ?>">
                                <div class="bluebutton" style="text-align: center; <?php if(isset($_GET['date']) && $jr->id === $_GET['date']) { echo 'background: #d7171f"'; } ?>"><?php echo $jr->jour ?></div>
                            </a>
                            <?php
                        }
                        echo '</div>';

                        if(isset($_GET['date'])) {

                            if ($groupe->type_compet === "0") {
                                $sql = 'SELECT id, team1, score1, team2, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE id_jour = ' . $_GET['date'];
                                $resultat = $connexion->query($sql);
                                $matchs = $resultat->fetchAll(PDO::FETCH_OBJ);
                            }

                            else if ($groupe->type_compet === "1") {
                                $sql = 'SELECT id, team1, score1, team2, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE id_jour = ' . $_GET['date'] . ' HAVING team1 = "France" OR team2 = "France"';
                                $resultat = $connexion->query($sql);
                                $matchs = $resultat->fetchAll(PDO::FETCH_OBJ);
                            }

                            echo '<div class="list">';

                            foreach ($matchs as $m) {

                                date_default_timezone_set("Europe/Paris");
                                $today = date("Y-m-d H:i:s");

                                $sqlProno = 'SELECT id, resultat1, resultat2, vainqueur, id_match, id_groupe, id_user FROM pronostics WHERE id_user = ' . $_SESSION['auth']->id . ' AND id_match = ' . $m->id . ' AND id_groupe = ' . $_GET['groupe'];
                                $resultatProno = $connexion->query($sqlProno);
                                $selectProno = $resultatProno->fetch(PDO::FETCH_OBJ);

                                if ($today < $m->limite) {

                                    if (!empty($selectProno->resultat1)) {
                                        ?>

                                        <a><div class="nobluebutton" style="text-align: center; cursor: default;">Votre pronostique en cours :<br/><?php echo $m->team1 ?> <?php echo $selectProno->resultat1 ?> - <?php echo $selectProno->resultat2 ?> <?php echo $m->team2 ?></div></a>

                                        <?php
                                    }
                                    else {
                                        ?>

                                        <a href="./send_prono.php?groupe=<?php echo $_GET['groupe'] ?>&journee=<?php echo $_GET['journee'] ?>&date=<?php echo $_GET['date'] ?>&match=<?php echo $m->id ?>">
                                            <div class="bluebutton" style="text-align: center; <?php if(isset($_GET['match']) && $m->id === $_GET['match']) { echo 'background: #d7171f"'; } ?>"><?php echo $m->team1 ?> vs <?php echo $m->team2 ?></div>
                                        </a>

                                        <?php
                                    }
                                }
                                else if ($today > $m->limite && $m->vainqueur === NULL) {

                                    if (!empty($selectProno->resultat1)) {
                                        ?>

                                        <a><div class="nobluebutton" style="text-align: center; cursor: default;">Votre pronostique en cours :<br/><?php echo $m->team1 ?> <?php echo $selectProno->resultat1 ?> - <?php echo $selectProno->resultat2 ?> <?php echo $m->team2 ?></div></a>

                                        <?php
                                    }
                                    else {

                                        ?>

                                        <a><div class="nobluebutton" style="text-align: center; cursor: default;"><strong>Pronostic fermé</strong><br/><?php echo $m->team1 ?> - <?php echo $m->team2 ?></div></a>

                                        <?php
                                    }
                                }
                                else if ($today > $m->limite && $m->vainqueur !== NULL) {

                                    ?>

                                    <a><div class="nobluebutton" style="text-align: center; cursor: default;">Résultat final :<br/><?php echo $m->team1 ?> <?php echo $m->score1 ?> - <?php echo $m->score2 ?> <?php echo $m->team2 ?></div></a>

                                    <?php
                                }
                            }
                            echo '</div>';


                            if (isset($_POST['prono'])) {
                                if (isset($_POST['score1']) && isset($_POST['score2'])) {

                                    $sqlTeam1 = 'SELECT id, nom FROM teams WHERE nom = "' . $_POST['team1'] . '"';
                                    $resultatTeam1 = $connexion->query($sqlTeam1);
                                    $team1 = $resultatTeam1->fetch(PDO::FETCH_OBJ);

                                    $sqlTeam2 = 'SELECT id, nom FROM teams WHERE nom = "' . $_POST['team2'] . '"';
                                    $resultatTeam2 = $connexion->query($sqlTeam2);
                                    $team2 = $resultatTeam2->fetch(PDO::FETCH_OBJ);

                                    if ($_POST['score1'] > $_POST['score2']) {
                                        $vainqueur = $team1->id;
                                    } else if ($_POST['score1'] < $_POST['score2']) {
                                        $vainqueur = $team2->id;
                                    } else if ($_POST['score1'] === $_POST['score2']) {
                                        $vainqueur = 0;
                                    }

                                    $sqlVerif = 'SELECT id, id_match, id_groupe, id_user FROM pronostics WHERE id_match = ' . $_POST['match'] . ' AND id_user = ' . $_SESSION['auth']->id . ' AND id_groupe = ' . $_POST['groupe'];
                                    $resultatVerif = $connexion->query($sqlVerif);
                                    $verif = $resultatVerif->fetch(PDO::FETCH_OBJ);

                                    if(!empty($verif)) {
                                        echo "<p style='color: #d7171f; text-align: center;'>Vous avez déjà un pari en cours dans ce groupe pour ce match.</p>";
                                    }
                                    else {

                                        $add = 'INSERT INTO pronostics (resultat1, resultat2, vainqueur, id_match, id_groupe, id_user) VALUES (' . $_POST['score1'] . ', ' . $_POST['score2'] . ', ' . $vainqueur . ', ' . $_POST['match'] . ', ' . $_POST['groupe'] . ', ' . $_SESSION['auth']->id . ')';
                                        $addProno = $connexion->exec($add);

                                        echo "<p style='color: #0f4582; text-align: center;'>Votre pronostic est validé. Bonne chance !</p>";
                                        echo "<script type='text/javascript'>document.location.replace('./send_prono.php?groupe=" . $_GET['groupe'] . "&journee=" . $_GET['journee'] . "&date=" . $_GET['date'] . "');</script>";
                                    }
                                } else {
                                    echo "<p style='color: #d7171f; text-align: center;'>Merci de remplir les scores pour les deux équipes.</p>";
                                }
                            }

                            if(isset($_GET['match']))
                            {

                                $sqlScore = 'SELECT id, team1, score1, team2, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE id = ' . $_GET['match'];
                                $resultatScore = $connexion->query($sqlScore);
                                $score = $resultatScore->fetch(PDO::FETCH_OBJ);
                                ?>

                                <div class="centerform">

                                    <form action="./send_prono.php?groupe=<?php echo $_GET['groupe'] ?>&journee=<?php echo $_GET['journee'] ?>&date=<?php echo $_GET['date'] ?>" method="post">
                                        <input hidden="hidden" type="text" name="groupe" value="<?php echo $_GET['groupe'] ?>" />
                                        <input hidden="hidden" type="text" name="match" value="<?php echo $_GET['match'] ?>" />
                                        <input hidden="hidden" type="text" name="team1" value="<?php echo $score->team1 ?>" />
                                        <input hidden="hidden" type="text" name="team2" value="<?php echo $score->team2 ?>" />
                                        <p><?php echo $score->team1 ?></p>
                                        <input name="score1" type="number" min="0" />
                                        <p><?php echo $score->team2 ?></p>
                                        <input name="score2" type="number" min="0" />
                                        <p>
                                            <input type="submit" class="submit" name="prono" value="Parier" />
                                        </p>
                                    </form>
                                </div>
                                <?php
                            }
                        }

                    }
                    ?>
                </div>
            </main>

            <?php

        } else {
            echo '<p style="text-align: center">Vous n\'avez pas accès à ce groupe.</p>';
        }
    }
    else {
        echo '<p style="text-align: center">Merci de choisir un groupe.</p>';
    }
}
else {
    echo '<p style="text-align: center">Vous devez être connecté afin d\'afficher ce contenu</p>';
}
?>
<?php
require_once ('footer.php');
?>