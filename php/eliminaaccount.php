<?php
include('connect.php');
include('head.php');
include('header.php'); ?>

<?php







//Eliminiamo l'account, se ha postato blog/commenti/post, verranno rimossi anche essi e sarà rimosso come coautore se esistente.
$NomeUtente = $mysqli->real_escape_string($_SESSION['NomeUtente']);
if (isset($NomeUtente)) {

    $elimina = $mysqli->query("DELETE FROM utenti WHERE (utenti.username = '$NomeUtente')");
    if ($elimina) {
        unset($_SESSION['NomeUtente']);
        session_destroy();
        header("Location: index.php");
    } else {
        header("Location: errore.php"); // da modificare
    }
}





?>