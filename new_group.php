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
    <title>Créer un groupe : CDM 2018</title>
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
        <p><a href="pronostics.php">ACCUEIL PRONOSTIC</a> > CRÉER UN GROUPE</p>
    </div>
</div>

<div class="champion">
    <h2>CRÉER UN GROUPE</h2>

    <?php

    if(isset($_SESSION['auth'])) {

        if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['creergroupe']))
        {
            if(empty($_POST['groupe'])) {
                echo "<p style='color: #d7171f'>Vous devez indiquer un nom de groupe.</p>";
            }
            else if (strlen($_POST['groupe']) > 20) {
                echo "<p style='color: #d7171f'>Le nom de groupe ne peut pas dépasser 20 caractères.</p>";
            }
            else
            {
                $sqlCount = 'SELECT COUNT(id_user) FROM liaison WHERE id_user = "' . $_SESSION['auth']->id . '"';
                $resultatCount = $connexion->query($sqlCount);
                $count = $resultatCount->fetchColumn();

                if($count === "3") {

                    echo "<p style='color: #d7171f'>Vous avez déjà rejoins 3 groupes. Vous ne pouvez pas créer davantage de groupe.</p>";

                }
                else {

                    $sqlTest = 'SELECT COUNT(*) FROM groupes WHERE nom = "' . $_POST['groupe'] . '"';
                    $resultatTest = $connexion->query($sqlTest);
                    $test = $resultatTest->fetchColumn();

                    if ($test === "1") {
                        echo "<p style='color: #d7171f'>Désolé ce nom est déjà utilisé.</p>";
                    }
                    else {

                        $sql1 = 'INSERT INTO groupes (nom, code, type_compet) VALUES ("' . $_POST['groupe'] . '", "' . $_POST['code'] . '", "' . $_POST['compet'] . '")';
                        $add1 = $connexion->exec($sql1);

                        $sqlId = 'SELECT id FROM groupes WHERE nom = "' . $_POST['groupe'] . '"';
                        $resultatId = $connexion->query($sqlId);
                        $groupeId = $resultatId->fetch(PDO::FETCH_OBJ);

                        $sql2 = 'INSERT INTO liaison (id_user, id_groupe) VALUES ("' . $_SESSION['auth']->id . '", "' . $groupeId->id . '")';
                        $add2 = $connexion->exec($sql2);

                        echo "<p style='color: #0f4582'>Ton groupe a été crée avec succès.</p>";
                        echo "<script type='text/javascript'>document.location.replace('competition.php?groupe=" . $groupeId->id . "');</script>";
                    }
                }
            }
        }
        $code = melanger(10);
        ?>
        <form method="post" action="">
            <p>Nom du groupe <span style="color: #d7171f">*</span></p>
            <input type="text" name="groupe" />
            <p>Type de compétition</p>
            <select name="compet" id="select">
                <option value="0">Compétition entière</option>
                <option value="1">France uniquement</option>
            </select>
            <p>Code d'accès<br/><span class="details">Ce code est généré aléatoirement, il permettra à vos amis de vous rejoindre.</span></p>
            <input type="text" name="code" value="<?php echo $code; ?>" readonly />
            <p>
                <input type="submit" class="submit" value="Créer le groupe" name="creergroupe" />
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
