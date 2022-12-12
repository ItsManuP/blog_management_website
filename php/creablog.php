<?php
include_once('connect.php');
include_once('header.php');
include('cookiefont.php');
?>

<?php

if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}


//Mi permette di selezionare l'id dell'autore loggato e inserirlo nella query quando creo un nuovo blog in quanto autoreblog si aspetta l'id utente e non l'username
$queryidutente = "SELECT id FROM utenti where username='$NomeUtente' ";
$result = $mysqli->query($queryidutente);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$test =  json_encode($rows);
foreach ($rows as $row) {
    $idautoreblog = $row["id"];
}





$select = "SELECT * FROM argomento";
$queryargomento = $mysqli->query($select);
$listaargomento = $queryargomento->fetch_all(MYSQLI_BOTH);
//echo json_encode($listaargomento);

$selectuno = "SELECT * FROM blog";
$queryblog = $mysqli->query($selectuno);
$listablog = $queryblog->fetch_all(MYSQLI_BOTH);
//echo json_encode($listablog);

$selectdue = "SELECT * FROM grafica";
$querygrafica = $mysqli->query($selectdue);
$listagrafica = $querygrafica->fetch_all(MYSQLI_BOTH);
//echo json_encode($listagrafica);


$querynomeargomenti = $mysqli->query("SELECT argomento.nome FROM argomento WHERE (argomento.id_post_riferimento IS NULL)");
$listanomeargomenti = $querynomeargomenti->fetch_all(MYSQLI_ASSOC);
//echo json_encode($listanomeargomenti);



$idargomento = '';
$idblog = '';
$errori = [];

//Controllo formattazione testo e gestisco gli errori
if (isset($_POST['creablog_bottone'])) {

    $titolo = $mysqli->real_escape_string($_POST['titolo']);
    $argomento = $mysqli->real_escape_string($_POST['argomento']);
    $descrizione = $mysqli->real_escape_string($_POST['descrizione']);


    if (empty($titolo)) {
        array_push($errori, "Devi inserire un titolo per il tuo blog");
    }
    if (strlen($titolo) > 100) {
        array_push($errori, "Riduci la lunghezza del titolo");
    }
    if (!preg_match('/^[ A-Za-z]+$/', $titolo)) { // Calcio è accettata Cal123cio non viene accettata,idem 123Calcio
        array_push($errori, " Il titolo non e' ben formattato ");
    };


    //controllo l'inserimento di un argomento 

    if (empty($argomento)) {
        array_push($errori, "Manca un argomento al tuo blog");
    } else if (!empty($argomento)) {
        $query = $mysqli->query("SELECT * FROM argomento WHERE (argomento.nome = '$argomento')");
        $controlloargomento = $query->fetch_all();
        if ($query == false) {
            $argomento = $argomento;
        } else if (!empty($controlloargomento)) {
            array_push($errori, " L'argomento e' gia' presente in un blog ");
        }
    }


    // Controllo la descrizione
    if (empty($descrizione)) {
        array_push($errori, " Non e' stata inserita una descrizione ");
    }




    //In fase di creazione del blog, l'utente deve scegliere un immagine di background, altrimenti viene inserita una di default
    if (empty($_FILES['imgbackground']['name'])) {
        $imgbackground = "defaultimg.jpg";
        $imgbackground_tmp_name = "defaultimg.jpg";
        $upload_dir = "../img/";
        $upload_file = ($upload_dir . basename($imgbackground));
    } else if (!empty($_FILES['imgbackground']['size'] < 20971520)) {
        $imgbackground = $_FILES['imgbackground']['name'];
        $imgbackground_tmp_name = $_FILES['imgbackground']['tmp_name'];
        $upload_dir = "../img/upload/";
        $upload_file = ($upload_dir . basename($imgbackground));
    }
    //if (!($_FILES['imgbackground']["type"] == "image/jpeg")) {
    //    array_push($errori, " Immagine non supportata ");
    //}



    echo implode($errori);

    if (empty($errori)) {

        $titolo = $mysqli->real_escape_string($_POST['titolo']);
        $argomento = $mysqli->real_escape_string($_POST['argomento']);
        $descrizione = $mysqli->real_escape_string($_POST['descrizione']);
        $immagine = $upload_file;
        $idblogappenacreato = 1234;

        if ($inserimentoblog = $mysqli->query("INSERT INTO blog (titolo,descrizione,autoreblog) VALUES ('$titolo','$descrizione','$idautoreblog')")) {
            $idblogappenacreato =   $mysqli->insert_id;
            $insertargomento = $mysqli->query("INSERT INTO argomento (nome,id_blog_riferimento) VALUES ('$argomento', '$idblogappenacreato')");
            $inserimentobanner = $mysqli->query("INSERT INTO grafica (pathimmagine,	id_img_riferimento_blog) VALUES ('$immagine', '$idblogappenacreato')");
            header('Location: index.php');
        }
    }
}
//echo json_encode($errori);


unset($listaargomento);
unset($listagrafica);
unset($listablog);
?>





<!DOCTYPE html>
<html lang="it">
<?php
//includo file header
include 'head.php';
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">

    <div class="card text-center">
        <div class="card-header">
            <div class="card-body">
                <h5 class="card-title">Crea un nuovo Blog</h5>
                <form action="" method="POST" id="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titolo">TITOLO</label>
                        <input type="text" class="form-control" id="titolo" name="titolo" placeholder="Che titolo vorresti per il nuovo blog?">


                        <label for="descrizione">DESCRIZIONE </label>
                        <input type="text" class="form-control" name="descrizione" id="descrizione" placeholder="Descrivi il tuo blog">




                        <div class="mt-1">
                            <h6>Argomenti già esistenti:
                                <?php foreach ($listanomeargomenti as $argomenti) { ?>
                                    <?php echo $argomenti["nome"] ?>
                                <?php } ?>

                            </h6>
                        </div>


                        <div id=" nuovoargomento">
                            <input type="text" class="form-control" name="argomento" id="argomento" placeholder="Vuoi aggiungere un argomento personalizzato?"" display=" none">
                        </div>


                    </div>


                    <label for="imgbackground">Carica uno sfondo </label>
                    <p class="text-center">
                        <input type="file" name="imgbackground" accept=".jpg" class="custom-file">
                    </p>


                    <div class="form-group p-3">
                        <button type="submit" value="Crea" class="btn btn-dark float-left" name="creablog_bottone" id="creablog_bottone" disabled>
                            Crea il blog
                        </button>
                    </div>
                </form>
                <?php $Giorno = new DateTime("now", new DateTimeZone('Europe/Berlin')); ?>
                <div class=" card-footer text-muted">
                    <?php echo $Giorno->format('d/m/Y');
                    ?>
                </div>
            </div>
        </div>
    </div>







    <script type="text/javascript">
        $(function() {
            $("#titolo").keyup(function() {
                if ($(this).val().length <= 100) {
                    $('#creablog_bottone').removeAttr('disabled'); //Il bottone diviene cliccabile
                } else {
                    $('#creablog_bottone').attr('disabled', 'disabled'); //Il bottone viene disattivato
                }
                $('#descrizione').keyup(function() {
                    if ($(this).val().length <= 90) {
                        $('#creablog_bottone').removeAttr('disabled');
                    } else {
                        $('#creablog_bottone').attr('disabled', 'disabled');
                    }
                    $('#argomento').keyup(function() {
                        if ($(this).val().length <= 20) {
                            $('#creablog_bottone').removeAttr('disabled');
                        } else {
                            $('#creablog_bottone').attr('disabled', 'disabled');
                        }
                    })
                })
            })

        })
    </script>


</body>


</html>