<?php
include('connect.php');
include('head.php');
include('header.php');
?>
<?php



//funzione che elimina un commento

// Recupero relativi id dei commenti dei post di un relativo blog, se l'autore del commento è loggato ed è in questa pagina
// avrà la possibilità di eliminare il commento dal post
$codicecommento = $mysqli->real_escape_string($_GET['idcommento']);
$idblog = $mysqli->real_escape_string($_GET['idblog']); //Posso riutilizzarla anche quando elimino il post
$idpost = $mysqli->real_escape_string($_GET['idpost']);
$querycommento = $mysqli->query("DELETE FROM commento WHERE(commento.idcommento='$codicecommento')");
if ($querycommento) {

    header("Location:apriblog.php?IdBlog=$idblog");
    unset($idblog);
    unset($idpost);
    unset($codicecommento);
} else {
    header("Location: Errore.php");
}
