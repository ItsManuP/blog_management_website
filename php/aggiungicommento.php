<?php
include('connect.php');

if (!isset($_SESSION)) {
    session_start();
}
?>


<?php



//funzione che aggiunge un commento ad un determinato post di un determinato blog

$NomeUtente = $_SESSION['NomeUtente'];

$query = $mysqli->query("SELECT id FROM utenti WHERE (utenti.username = '$NomeUtente')");
$ids = $query->fetch_all();
foreach ($ids as $id) {
    $idautorecommento = $id[0];
}



if (isset($_POST['testocommento'])) {
    $testocommento = $mysqli->real_escape_string($_POST['testocommento']);
}


if (isset($_POST['idpost'])) {
    $idpostcommento = $_POST['idpost'];
}

if (isset($_POST['idblog'])) {
    $idblogcommento = $_POST['idblog'];
}


if (isset($idautorecommento) && isset($idblogcommento) && isset($idpostcommento) && isset($testocommento)) {
    //Inserisco i dati dalla richiesta post ajax
    $query = $mysqli->query("INSERT INTO commento(autorecommento,codice_post,descrizione) VALUES ('$idautorecommento','$idpostcommento','$testocommento')");
    //se la query viene eseguita,recupero i dati dell'ultimo commento inserito, tra i quali ID,DATA,TESTO, Salvati come array li ritorno come response alla chiamata ajax, in modo da mostrare il risultato
    $id_ultimocommento_inserito = $mysqli->insert_id;
    $querydue = $mysqli->query("SELECT commento.data,commento.descrizione,utenti.username FROM commento JOIN utenti on utenti.id=commento.autorecommento WHERE commento.idcommento = '$id_ultimocommento_inserito' LIMIT 1");
    $ultimocommentoinserito = $querydue->fetch_all(MYSQLI_ASSOC);
    $datas = json_encode($ultimocommentoinserito);
}
if ($query && $querydue) {
    echo $datas;
} else {
    echo json_encode(['Errore']);
}


?>

