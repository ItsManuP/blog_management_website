<?php
include('connect.php');
?>

<?php

$blogtitolo = $mysqli->real_escape_string($_POST['blogtitolo']);

//$email = $mysqli->real_escape_string($_POST['email']);


$aggiorna = $mysqli->query("UPDATE blog SET coautore = NULL WHERE (blog.titolo = '$blogtitolo')");
echo json_encode($aggiorna);

?>