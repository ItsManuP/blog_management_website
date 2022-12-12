<?php

include('grafica_utente_visitatore.php');
include('head.php');
include('cookiefont.php');

session_start();
unset($_SESSION['NomeUtente']); // $_SESSION=array();
session_destroy();
?>

<body>

    <div class="container-fluid">
        <div class="row h-75 d-flex">
            <div class="col-12 justify-content-center align-self-center text-center">
                <h1> Hai effettuato con successo il logout</h1>
                <h2><small> Riprendi la visione dei contenuti: <small></h2>
                <a href="index.php">
                    <button class="btn btn-dark text-white">
                        HOME
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>