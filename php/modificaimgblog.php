<?php
include('connect.php');
include('header.php');
?>


<?php

if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
} else {
    echo ('Registrati per un esperienza migliore!');
}


$idblog = $mysqli->real_escape_string($_GET['idblog']);
//echo $idblog;

//Query per recupero i blog dell'utente loggato
$Richiesta = $mysqli->query("SELECT * FROM grafica where(grafica.id_img_riferimento_blog='$idblog')");
$Blogs = $Richiesta->fetch_all(MYSQLI_ASSOC);
//echo json_encode($Blogs);



if (isset($_POST['confermaimgcopertina'])) {

    if (empty($_FILES['copertinablognuova']['name'])) {
        echo "non hai inserito un immagine";
    } else if ($_FILES['copertinablognuova']['size'] < 20971520) {
        echo "presa";
        $copertinablognuova = $_FILES['copertinablognuova']['name'];
        $copertinablognuova_tmp_name = $_FILES['copertinablognuova']['tmp_name'];
        $upload_dir = "../img/upload/";
        $upload_file = ($upload_dir . basename($copertinablognuova));
        echo ("qua ci sono arrivato");
        //Query per aggiornamento dati
        $Aggiornaimmagine = $mysqli->query("UPDATE grafica SET grafica.pathimmagine = '$upload_file' WHERE (grafica.id_img_riferimento_blog = '$idblog')");
        if ($Aggiornaimmagine) {
            echo "Fatta query";
            header("Location: index.php");
        }
    }
}


?>
<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
?>

<body>
    <div class="text-center">
        <?php foreach ($Blogs as $Blog) { ?>
            <h3> Attuale immagine di copertina </h1>

                <img style="object-fit:cover; height: 20vw" <?php echo '<img src="' . $Blog['pathimmagine']  . '" ' ?>>
            <?php } ?>

            <form action="" method="POST" id="form" enctype="multipart/form-data">

                <label for="copertinablognuova">Seleziona una nuova immagine di copertina </label>
                <p class="text-center">
                    <input type="file" id="copertinablognuova" name="copertinablognuova" accept=".jpg,.gif,.png" class="custom-file">
                </p>
                <div class="form-group p-1">
                    <button type="submit" id="test" value="Crea" class="btn btn-dark float-left" name="confermaimgcopertina">
                        Modifica immagine
                    </button>
                </div>

            </form>
    </div>




    <script>

    </script>




</body>

</html>