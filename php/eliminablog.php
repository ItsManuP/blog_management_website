<?php
include('connect.php');
?>
<?php
$idblogdaeliminare = $mysqli->real_escape_string($_POST['idblogdaeliminare']);
if (isset($idblogdaeliminare)) {
    $queryblogdaeliminare = $mysqli->query("DELETE FROM blog WHERE (blog.idblog = '$idblogdaeliminare')");
    if ($queryblogdaeliminare) {
        echo $idblogdaeliminare;
    } else {
        echo "Blog non eliminato";
    }
}
?>