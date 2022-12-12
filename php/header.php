<?php
header('Content-Type: text/html; charset=UTF-8'); // Utile per il browser// text/plain orientato a stampare gli attributi.

include('process.php');

//Inizializzo la sessione in caso.
if (!isset($_SESSION)) {
    session_start();
}

//Se l'utente è loggato, la grafica del gestore blog si differenzia.
if (isset($_SESSION['NomeUtente'])) {
    include('grafica_utente_loggato.php');
} else {
    include('grafica_utente_visitatore.php');
}
