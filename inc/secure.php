<?php

function securiser($donnee)
{
    $donnee = trim($donnee);
    $donnee = stripslashes($donnee);
    $donnee = htmlspecialchars($donnee);
    return $donnee;
}

function melanger($nombre)
{
    $chaine = "0123456789ABCDEFGHIJKLMNOPQRSTUVWabcdefghijklmnopqrstuvw";
    return substr(str_shuffle(str_repeat($chaine, $nombre)), 0, $nombre);
}

function droit(){
    if(session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder à cette page";
        header('Location: index.php');
        exit();
    }
}
?>