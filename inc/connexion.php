<?php

$serveur = '';
$bdd = '';
$user = '';
$mdp = '';


try {
    $connexion = new PDO('mysql:host='.$serveur.';dbname='.$bdd,$user,$mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch(Exception $e) {
    die('Echec de connexion.');
}

?>
