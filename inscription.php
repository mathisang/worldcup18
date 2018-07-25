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
    <title>Inscription : CDM 2018</title>
    <link rel="stylesheet" href="lib/css/style.css" type="text/css" />
    <link rel="stylesheet" href="lib/css/formulaire.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<?php
$courante = "inscription";
require_once ('header.php');
require_once ('./inc/secure.php');
?>

<div class="champion">
    <h2>INSCRIPTION</h2>

    <?php

    if(isset($_SESSION['auth'])) {
        ?>
        <p>Vous êtes déjà connecté.</p>
        <script type='text/javascript'>document.location.replace('resultat.php');</script>
    <?php
    }
    else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        if (empty($_POST['pseudo'])) {
            $erreurs['pseudo'] = "Vous devez renseigner le champ pseudo.";
        } else {
            $sqlUser = 'SELECT COUNT(username) FROM users WHERE username = "' . $_POST['pseudo'] . '"';
            $resultatUser = $connexion->query($sqlUser);
            $testUser = $resultatUser->fetchColumn();

            if ($testUser === "1") {
                $erreurs['pseudo'] = "Ce pseudo existe dejà.";
            }
        }

        if (strlen($_POST['pseudo']) < 3 || strlen($_POST['pseudo']) > 16) {
            $erreurs['pseudo'] = "Votre pseudo doit contenir entre 3 et 16 caractères";
        }

        if (empty($_POST['mdp']) || empty($_POST['confirmation'])) {
            $erreurs['mdp'] = "Vous devez remplir les champs du mot de passe.";
        } elseif (isset($_POST['mdp']) && isset($_POST['confirmation'])) {

            if ($_POST['mdp'] === $_POST['confirmation']) {

                if (strlen($_POST['mdp']) < 6 || strlen($_POST['mdp']) > 14) {
                    $erreurs['mdp'] = "Votre mot de passe doit contenir entre 6 et 14 caractères";
                }
            } else {
                $erreurs['mdp'] = "Les mots de passe ne correspondent pas";
            }

        }

        if (empty($_POST['email'])) {
            $erreurs['email'] = "Vous devez renseigner le champ email.";
        } else {
            $sql = 'SELECT email FROM users WHERE email = "' . $_POST['email'] . '"';
            $resultat = $connexion->query($sql);
            $testUser = $resultat->fetchColumn(PDO::FETCH_OBJ);

            if ($testUser === 1) {
                $erreurs['email'] = "Cet email existe dejà.";
            }
        }

        if (empty($erreurs)) {

            $pseudo = securiser($_POST['pseudo']);
            $email = securiser($_POST['email']);
            $password = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
            $cle = melanger(60);

            $mail = $email; // Déclaration de l'adresse de destination.

                                if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
                                {
                                    $passage_ligne = "\r\n";
                                } else {
                                    $passage_ligne = "\n";
                                }

                                $messagehtml = "<h2>Pronostic Coupe du Monde 2018</h2>"  .
				"<h2>Validation du compte</h2>"  .
                                    "Bonjour, " . $_POST['pseudo'] . "<br/>" .
				"Merci de cliquer sur ce lien, afin de valider votre compte : <a href='https://wc2018.mathisangibaud.fr/validation.php?keyValid=" . $cle . "'>valider mon compte</a>";


                                $boundary = "-----=" . md5(rand());

                                $sujet = "Validation du compte Pronostique : Coupe du Monde 2018";

                                $header = "From: \"" . $_POST['pseudo'] . "\"<" . $_POST['email'] . ">" . $passage_ligne;
                                $header .= "Reply-to: \"" . $_POST['pseudo'] . "\" <" . $_POST['email'] . ">" . $passage_ligne;
                                $header .= "MIME-Version: 1.0" . $passage_ligne;
                                $header .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;

                                $message = $passage_ligne . "--" . $boundary . $passage_ligne;

                                $message .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $passage_ligne;
                                $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
                                $message .= $passage_ligne . $messagehtml . $passage_ligne;

                                $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;

                                mail($mail, $sujet, $message, $header);

            $newRegister = 'INSERT INTO users(username, password, email, cle) VALUES ("' . $pseudo . '", "' . $password . '", "' . $email . '", "' . $cle . '")';
            $addRegister = $connexion->exec($newRegister);

            echo "<p style='color: #0f4582'>Ton compte : " . $_POST['pseudo'] . ", a été créé avec succès !</p>" .
                "<p style='color: #0f4582'>Pour pouvoir te connecter, tu dois valider ton compte. Vérifie tes emails.</p>" .
		"<p style='color: #d7171f'>Penses à regarder dans tes spams si tu ne reçois pas le mail.</p>";
        }
    }

    if (!empty($erreurs)) {
        foreach ($erreurs as $er) {
            echo "<span style='color: #d7171f'>" . $er . "</span><br/><br/>";
        }
    }
    ?>
        <p>La création de compte vous permet d'accéder à l'ensemble des fonctionnalités du site web.<br/>
            Cette étape obligatoire, vous permettra de rejoindre un groupe ainsi que de publier vos pronostiques.</p>

        <form method="post" action="">
            <p>Pseudo <span style="color: #d7171f">*</span></p>
            <input type="text" name="pseudo"/>
            <p>Mot de passe <span style="color: #d7171f">*</span></p>
            <input type="password" name="mdp"/>
            <p>Confirmation du mot de passe <span style="color: #d7171f">*</span></p>
            <input type="password" name="confirmation"/>
            <p>Email <span style="color: #d7171f">*</span><br/><span class="details">Un email valide est requis pour la validation du compte.</span></p>
            <input type="email" name="email"/>
            <p>
                <input type="submit" class="submit" name="register" value="S'inscrire"/>
            </p>
        </form>

        <?php
    }
    ?>

</div>

<?php
require_once ('footer.php');
?>
