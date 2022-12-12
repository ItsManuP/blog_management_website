<?php
include('connect.php');
include('cookiefont.php');
?>

<?php
$blogtitolo = $mysqli->real_escape_string($_POST['blogtitolo']);

$email = $mysqli->real_escape_string($_POST['email']);


$select = $mysqli->query("SELECT utenti.id FROM utenti WHERE (utenti.email = '$email')");
$idutente = $select->fetch_all();
foreach ($idutente as $id) {
    $utente = $id[0];
}

$aggiorna = $mysqli->query("UPDATE blog SET coautore = $utente WHERE (blog.titolo = '$blogtitolo')");



?>
