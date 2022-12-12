<?php
include('connect.php'); //includo connessione db.
?>



<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}


//Ricavo l'id dell'utente

$result = $mysqli->query("SELECT id FROM utenti where (username='$NomeUtente')");
$rows = $result->fetch_all(MYSQLI_ASSOC);
foreach ($rows as $row) {
    $utente_id = $row["id"];
}





//recupero idpost e la scelta dell'utente se ha cliccato il button mi piace o non mi piace
$idpost = $mysqli->real_escape_string($_POST['idpost']);
$mettilike = $mysqli->real_escape_string($_POST['mettilike']);

//Questa richiesta mi permette di capire se ho già dei like dell'utente loggato allo specifico post o no.
$check = $mysqli->query("SELECT * from utenti_like where (utenti_like.id_utente = '$utente_id' AND utenti_like.id_post = '$idpost')");
$like = $check->fetch_all();






//Inizialmente è 0, nel caso venga messo mi piace, count ritorna 3 e non è possibile aggiungere nuovamente like, ma solamente rimuoverlo.
if (isset($idpost)) {
    if (count($like) == 0 && $mettilike == "True") {
        $aggiungilike = $mysqli->query("INSERT INTO utenti_like(id_utente,id_post) VALUES ('$utente_id','$idpost')");
        $queryuno = $mysqli->query("UPDATE post SET post.numero_likes = post.numero_likes + 1 WHERE (post.idpost = '$idpost')");
    } else if (count($like) != 0 && $mettilike == "False") {
        $toglilike = $mysqli->query("DELETE FROM utenti_like WHERE (utenti_like.id_post = '$idpost' AND utenti_like.id_utente = '$utente_id')");
        $querydue = $mysqli->query("UPDATE post SET post.numero_likes = post.numero_likes - 1 WHERE (post.idpost = '$idpost')");
    }
}


//quanti likes ho al post?
$query = $mysqli->query("SELECT post.numero_likes from post where (post.idpost = '$idpost')");
$numerolikes = $query->fetch_array();
$NumeroLikes = json_encode($numerolikes);
echo $NumeroLikes;
?>