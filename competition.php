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
        <link rel="stylesheet" href="lib/css/competition.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/matchs.css" type="text/css" />
        <link rel="stylesheet" href="lib/css/pronostic.css" type="text/css" />
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
                    <p style="text-transform: uppercase"><a href="pronostics.php">ACCUEIL PRONOSTIC</a> > <?php echo $groupe->nom ?></p>
                </div>
            </div>

            <main class="mygroup">
                <div class="maxwidth">
                    <h2 style="text-transform: uppercase"><?php echo $groupe->nom ?></h2>
                    <div class="row">
                        <div class="leftside">
                            <div class="acces">
                                <div>
                                    <p>Invitez vos amis à jouer avec vous.<br/>Envoyez leur ce code.</p>
                                    <div>
                                        <p><?php echo $groupe->code ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="addprono">
                                <div>
                                    <p>Pronostique les matchs de la coupe du monde pour gagner des points.<br/><span style="font-size: .8rem">Les pronostics se ferment 30 minutes avant le coup d'envoi.</span></p>

                                    <a href="send_prono.php?groupe=<?php echo $_GET['groupe'] ?>">Envoyer mon pronostic</a>
                                </div>
                            </div>
                        </div>
                        <div class="points">
                            <h3>Classement</h3>
                            <div class="section">
                                <?php
                                $sqlUser = 'SELECT U.id AS idUser, username, points FROM users U INNER JOIN liaison L ON U.id=L.id_user WHERE L.id_groupe = ' . $_GET['groupe'] . ' ORDER BY points DESC';
                                $resultatUser = $connexion->query($sqlUser);
                                $user = $resultatUser->fetchAll(PDO::FETCH_OBJ);

                                foreach ($user as $u) {
                                    ?>
                                    <div>
                                        <p><?php echo $u->username ?></p>
                                        <p><?php echo $u->points ?> points</p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <main class="resultat">
                <div class="maxwidth">
                    <?php
            if ($groupe->type_compet === "0") {
                echo "<h2>PRONOSTICS DU JOUR</h2>";
            }
            else if ($groupe->type_compet === "1") {
                echo "<h2>PRONOSTICS</h2>";
            }
                    ?>

                    <div class="matchs prono">
                        <?php

                        if ($groupe->type_compet === "0") {
                            $sqlSuccess = 'SELECT id, team1, team2, score1, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE vainqueur IS NULL';
                            $resultatSuccess = $connexion->query($sqlSuccess);
                            $success = $resultatSuccess->fetchAll(PDO::FETCH_OBJ);
                        }

                        else if ($groupe->type_compet === "1") {
                            $sqlSuccess = 'SELECT id, team1, team2, score1, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE vainqueur IS NULL HAVING team1 = "France" OR team2 = "France"';
                            $resultatSuccess = $connexion->query($sqlSuccess);
                            $success = $resultatSuccess->fetchAll(PDO::FETCH_OBJ);
                        }

                        foreach ($success as $s) {

                            date_default_timezone_set("Europe/Paris");
                            $today = date("Y-m-d");

                            $jour = substr($s->limite, 0, 10);

                            if ($groupe->type_compet === "0") {

                                if ($today === $jour) {
                                    ?>
                                    <section>
                                        <div class="head" style="font-weight: 500;">
                                            <p><?php echo $s->heure ?></p>
                                            <p><?php echo $s->team1 ?></p>
                                            <p><?php echo $s->team2 ?></p>
                                        </div>
                                        <?php
                                        $sqlProno2 = 'SELECT P.id AS idProno, resultat1, resultat2, P.vainqueur, id_match, id_groupe, id_user, team1, team2, heure, limite, username FROM pronostics P INNER JOIN matchs M ON M.id=P.id_match INNER JOIN users U ON U.id = P.id_user WHERE id_groupe = ' . $_GET['groupe'] . ' AND id_match = ' . $s->id;
                                        $resultatProno2 = $connexion->query($sqlProno2);
                                        $prono2 = $resultatProno2->fetchAll(PDO::FETCH_OBJ);

                                        if (!empty($prono2)) {

                                            foreach ($prono2 as $p2) {

                                                ?>
                                                <div>
                                                    <p style="font-weight: 500;"><?php echo $p2->username ?></p>
                                                    <p><?php echo $p2->resultat1 ?></p>
                                                    <p><?php echo $p2->resultat2 ?></p>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "<span style='display: flex; justify-content: center; margin-top: 1rem;'>Aucun pronostic en cours.</span>";
                                        }
                                        ?>
                                    </section>
                                    <?php
                                } else {
                                }
                            }
                            else if ($groupe->type_compet === "1") {
                                ?>
                                <section>
                                    <div class="head" style="font-weight: 500;">
                                        <p><?php echo $s->heure ?></p>
                                        <p><?php echo $s->team1 ?></p>
                                        <p><?php echo $s->team2 ?></p>
                                    </div>
                                    <?php
                                    $sqlProno2 = 'SELECT P.id AS idProno, resultat1, resultat2, P.vainqueur, id_match, id_groupe, id_user, team1, team2, heure, limite, username FROM pronostics P INNER JOIN matchs M ON M.id=P.id_match INNER JOIN users U ON U.id = P.id_user WHERE id_groupe = ' . $_GET['groupe'] . ' AND id_match = ' . $s->id;
                                    $resultatProno2 = $connexion->query($sqlProno2);
                                    $prono2 = $resultatProno2->fetchAll(PDO::FETCH_OBJ);

                                    if (!empty($prono2)) {

                                        foreach ($prono2 as $p2) {

                                            ?>
                                            <div>
                                                <p style="font-weight: 500;"><?php echo $p2->username ?></p>
                                                <p><?php echo $p2->resultat1 ?></p>
                                                <p><?php echo $p2->resultat2 ?></p>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo "<span style='display: flex; justify-content: center; margin-top: 1rem;'>Aucun pronostic en cours.</span>";
                                    }
                                    ?>
                                </section>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </main>

            <main class="resultat">
                <div class="maxwidth">
                    <?php
                    if ($groupe->type_compet === "0") {
                        echo "<h2>4 DERNIERS RESULTATS</h2>";
                    }
                    else if ($groupe->type_compet === "1") {
                        echo "<h2>DERNIERS RESULTATS</h2>";
                    }
                    ?>
                    <div class="matchs">
                        <?php
                        if ($groupe->type_compet === "0") {
                            $sqlScore = 'SELECT id, team1, team2, score1, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE vainqueur IS NOT NULL ORDER BY limite DESC LIMIT 4';
                            $resultatScore = $connexion->query($sqlScore);
                            $score = $resultatScore->fetchAll(PDO::FETCH_OBJ);
                        }
                        else if ($groupe->type_compet === "1"){
                            $sqlScore = 'SELECT id, team1, team2, score1, score2, id_jour, heure, vainqueur, limite FROM matchs WHERE vainqueur IS NOT NULL HAVING team1 = "France" OR team2 = "France" ORDER BY limite DESC LIMIT 4';
                            $resultatScore = $connexion->query($sqlScore);
                            $score = $resultatScore->fetchAll(PDO::FETCH_OBJ);
                        }

                        foreach ($score as $s) {
                            ?>
                            <section>
                                <div class="head">
                                    <p>Résultat final :</p>
                                    <p><?php echo $s->team1 ?></p>
                                    <p><?php echo $s->score1 ?></p>
                                    <p><?php echo $s->team2 ?></p>
                                    <p><?php echo $s->score2 ?></p>
                                </div>
                                <?php
                                $sqlProno = 'SELECT P.id AS idProno, resultat1, resultat2, vainqueur, id_match, id_groupe, username, id_user FROM pronostics P INNER JOIN users U ON P.id_user=U.id WHERE id_match = ' . $s->id . ' AND id_groupe = ' . $_GET['groupe'];
                                $resultatProno = $connexion->query($sqlProno);
                                $prono = $resultatProno->fetchAll(PDO::FETCH_OBJ);

                                foreach ($prono as $p) {
                                    ?>
                                    <div>
                                        <p><?php echo $p->username ?></p>
                                        <p><?php echo $s->team1 ?></p>
                                        <p><?php echo $p->resultat1 ?></p>
                                        <p><?php echo $s->team2 ?></p>
                                        <p><?php echo $p->resultat2 ?></p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </section>
                            <?php
                        }
                        ?>
                    </div>
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