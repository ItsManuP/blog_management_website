<?php

//connessione al database
$mysqli = new mysqli('localhost', 'root', '', 'basididati');
if ($mysqli->connect_error) {
    die('Errore di connessione al database (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
