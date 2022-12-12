<?php
include('connect.php'); //includo connessione db.
include('header.php');
include('cookiefont.php');
?>

<?php
$errori = [];

if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}
if (isset($_GET['IdBlog'])) {
    //echo $_GET['IdBlog'];
    $idblog = $mysqli->real_escape_string($_GET['IdBlog']);
}


$selectsottoargomenti = $mysqli->query("SELECT argomento.nome FROM argomento WHERE (argomento.id_blog_riferimento = '$idblog' AND argomento.id_post_riferimento IS NOT NULL)");
$sottoargomenti = $selectsottoargomenti->fetch_all(MYSQLI_ASSOC);
//echo json_encode($sottoargomenti);


$queryidutente = "SELECT id FROM utenti where username='$NomeUtente' ";
$result = $mysqli->query($queryidutente);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$test =  json_encode($rows);
foreach ($rows as $row) {
    $idautorepost = $row["id"];
}



if (isset($_POST['creapost_bottone'])) {

    $titolo = $mysqli->real_escape_string($_POST['titolo']);
    $descrizione = $mysqli->real_escape_string($_POST['descrizione']);
    $argomentopost = $mysqli->real_escape_string($_POST['argomentopost']);


    if (empty($titolo)) {
        array_push($errori, "Devi inserire un titolo per il tuo post");
    }
    if (strlen($titolo) > 100) {
        array_push($errori, "Titolo troppo lungo");
    }
    if (!empty($titolo)) {
        $controllotitolo = $mysqli->query("SELECT * from post WHERE (post.titolo = '$titolo')");
        $risultato = $controllotitolo->fetch_all();
        if ($controllotitolo == false) {
            $titolo = $titolo;
        } else if (!empty($risultato)) {
            array_push($errori, " Il titolo e' stato gia' utilizzato. ");
        }
    }



    // Controllo la descrizione
    if (empty($descrizione)) {
        array_push($errori, " Non è stata inserita una descrizione ");
    }
    if (strlen($descrizione) > 1000) {
        array_push($errori, " Descrizione eccessivamente lunga. ");
    }


    if (empty($argomentopost)) {
        array_push($errori, "Non è stato inserito un argomento");
    }
    if (strlen($argomentopost) > 20) {
        array_push($errori, " Argomento eccessivamente lungo. ");
    }
    if (!empty($argomentopost)) {
        $argomentopost = $mysqli->real_escape_string($_POST['argomentopost']);
        $controlloargomento = $mysqli->query("SELECT * from argomento WHERE (argomento.nome = '$argomentopost' AND argomento.id_blog_riferimento = '$idblog')");
        $risultatoargomento = $controlloargomento->fetch_all();
        if ($controlloargomento == false) {
            $argomentopost = $argomentopost;
        } else if (!empty($risultatoargomento)) {
            array_push($errori, "L'argomento esiste gia' in questo blog.");
        }
    }



    //In fase di creazione del post, l'utente deve scegliere un immagine di background, altrimenti viene inserita una di default
    if (empty($_FILES['imgcopertinapost']['name'])) {
        $imgcopertinapost = "defaultimgpost.jpg";
        $imgcopertinapost_tmp_name = "defaultimgpost.jpg";
        $upload_dir = "../img/postupload/";
        $upload_file = ($upload_dir . basename($imgcopertinapost));
    } else if (!empty($_FILES['imgcopertinapost']['size'] < 20971520)) {
        $imgcopertinapost = $_FILES['imgcopertinapost']['name'];
        $imgcopertinapost_tmp_name = $_FILES['imgcopertinapost']['tmp_name'];
        $upload_dir = "../img/postupload/";
        $upload_file = ($upload_dir . basename($imgcopertinapost));
    }

    //if (!($_FILES['imgcopertinapost']["type"] == "image/jpeg")) {
    //    array_push($errori, " Immagine non supportata ");
    //}



    echo implode($errori);

    if (empty($errori)) {

        $titolo = $mysqli->real_escape_string($_POST['titolo']);
        $descrizione = $mysqli->real_escape_string($_POST['descrizione']);
        $argomentopost = $mysqli->real_escape_string($_POST['argomentopost']);
        $immagine = $upload_file;
        $idpostappenacreato = 1234;
        $inserimentopost = $mysqli->query("INSERT INTO post(titolo,testo,autorepost,codiceblog) VALUES ('$titolo','$descrizione','$idautorepost','$idblog')");
        $idpostappenacreato =   $mysqli->insert_id;
        $inserimentoargomento = $mysqli->query("INSERT INTO argomento(id_blog_riferimento, id_post_riferimento,nome) VALUES ('$idblog', '$idpostappenacreato', '$argomentopost')");
        $idargomentoappenacreato = $mysqli->insert_id;
        $inserimentobanner = $mysqli->query("INSERT INTO grafica(pathimmagine,id_img_riferimento_post) VALUES ('$immagine', '$idpostappenacreato')");
        $aggiornopost = $mysqli->query("UPDATE post SET post.argomento_post = $idargomentoappenacreato WHERE post.idpost = $idpostappenacreato");
        header('Location: apriblog.php?IdBlog=' . $idblog);
    }
}



?>


<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">

    <div class="card text-center">
        <div class="card-header">
            <div class="card-body">
                <h5 class="card-title">Crea un nuovo post</h5>
                <form action="" method="POST" id="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titolo">Titolo</label>
                        <input type="text" class="form-control" name="titolo" id="titolo" placeholder="Ricetta per la torta alle mele">

                        <label for="descrizione">Di cosa parla il tuo post? </label>
                        <input type="text" class="form-control" name="descrizione" id="descrizione" placeholder="La torta alle mele è da sempre amata da milioni di famiglie italiane...">

                        <div>
                            <h6> Argomenti di questo blog già esistenti:
                                <?php foreach ($sottoargomenti as $sottoargomento) { ?>
                                <?php echo $sottoargomento["nome"];
                                } ?>
                            </h6>
                        </div>

                        <label for="argomentopost">Argomento del tuo post? </label>
                        <input type="text" class="form-control" name="argomentopost" id="argomentopost" placeholder="Cucina" pattern="^[a-zA-Z0-9_]+$">

                    </div>



                    <label for="imgcopertinapost">Carica una immagine di copertina </label>
                    <p class="text-center">
                        <input type="file" name="imgcopertinapost" accept=".jpg" class="custom-file">
                    </p>


                    <div class="form-group p-3">
                        <button type="submit" value="Crea" class="btn btn-dark float-left" name="creapost_bottone" id="creapost_bottone" disabled>
                            Crea post
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
        $(document).ready(function() {
            $("form").submit(function(event) {
                var errori = [];
                if ($("#titolo").val() === "") {
                    errori.push("Non è stato inserito un titolo");
                    $("#titolo").css('border-color', '#b32d39');; // rosso
                } else {
                    $("#titolo").css('border-color', '#28a745'); // verde
                }
                if ($("#descrizione").val() === "") {
                    errori.push("Non hai scritto nulla al tuo post");
                    $("#descrizione").css('border-color', '#b32d39');
                } else {
                    $("#descrizione").css('border-color', '#28a745');
                }
                if ($("#argomentopost").val() === "") {
                    errori.push("Non hai scritto nulla al tuo post");
                    $("#argomentopost").css('border-color', '#b32d39');
                } else {
                    $("#argomentopost").css('border-color', '#28a745');
                }
                if (errori.length > 0) {
                    event.preventDefault();
                }
            })
        })




        $(function() {
            $("#titolo").keyup(function() {
                if ($(this).val().length <= 100) {
                    $('#creapost_bottone').removeAttr('disabled'); //Il bottone diviene cliccabile
                } else {
                    $('#creapost_bottone').attr('disabled', 'disabled'); //Il bottone viene disattivato
                }
                $('#descrizione').keyup(function() {
                    if ($(this).val().length <= 1000) {
                        $('#creapost_bottone').removeAttr('disabled');
                    } else {
                        $('#creapost_bottone').attr('disabled', 'disabled');
                    }
                })
                $('#argomentopost').keyup(function() {
                    if ($(this).val().length <= 20) {
                        $('#creapost_bottone').removeAttr('disabled');
                    } else {
                        $('#creapost_bottone').attr('disabled', 'disabled');
                    }
                })
            })
        })
    </script>






</body>


</html>