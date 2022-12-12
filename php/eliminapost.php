<?php
include('connect.php');
include('head.php');
include('header.php');
?>

<?php

//funzione che permette di eliminare un post

$idblog = $mysqli->real_escape_string($_POST['idblog']);
$idpost = $mysqli->real_escape_string($_POST['idpost']);

if (isset($idpost)) {

    $querypost = $mysqli->query("DELETE FROM post WHERE (idpost = '$idpost')");
    if ($querypost) {
        header('Location: apriblog.php?IdBlog=' . $idblog);
    }
}

?>