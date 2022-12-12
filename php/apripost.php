<?php
include('connect.php'); //includo connessione db.
include('header.php');
include('cookiefont.php');

if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}


if (isset($_GET['idpost'])) {
    $idpost = $mysqli->real_escape_string($_GET['idpost']);
    $query = $mysqli->query("SELECT post.codiceblog FROM post WHERE (post.idpost = $idpost)");
    $rows = $query->fetch_all(MYSQLI_ASSOC);
    $idblogs =  json_encode($rows);
    foreach ($rows as $row) {
        $idblog = $row['codiceblog'];
    }




    //Richiedo le informazioni di un singolo post
    $Risultato = $mysqli->query("SELECT grafica.pathimmagine,post.titolo,post.idpost,utenti.username, post.testo, post.data FROM post,blog,utenti,grafica WHERE (blog.idblog = '$idblog' AND post.codiceblog = '$idblog' AND utenti.id=post.autorepost AND post.idpost='$idpost' AND grafica.id_img_riferimento_post= post.idpost)");
    $Posts = $Risultato->fetch_all(MYSQLI_ASSOC);
    foreach ($Posts as $Post) {
        $id = $Post['idpost'];
        $username = $Post['username'];
        $testo = $Post['testo'];
        $titolo = $Post['titolo'];
        $data = $Post['data'];
        $immagine = $Post['pathimmagine'];
    }



    //Richiedo tutti i commenti di un determinato post
    $Risultato1 = $mysqli->query("SELECT * FROM post,commento,utenti WHERE (commento.codice_post=post.idpost AND utenti.id = commento.autorecommento AND post.idpost = '$idpost')");
    $Tuttiicommenti = $Risultato1->fetch_all(MYSQLI_ASSOC);
}








?>


<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
?>

<body id="body">


    <div class="text-center mt-1">
        <h6> Cambia Font
            <select class="btn btn-dark text-white" id="cambiafont">
                <option>Predefinito </option>
                <option value="La Belle Aurore">La Belle Aurore</option>
                <option>Montserrat</option>
                <option>Nerko One</option>
            </select>
        </h6>
    </div>

    <div class="card mt-1">
        <div class="card-header">
            <h5>
                <?php echo  $username . " <h6>scrive in data:</h6>" . $data ?>
            </h5>
        </div>


        <div class="card-body">
            <img class=" img-thumbnail mx-auto d-block" style="height: 20vw" <?php echo '<img src="' . $immagine  . '" ' ?> <h3 class="card-title text-center">
            <h1 class="text-center"> <?php echo $titolo ?>
            </h1>

            <p class="card-text">
            <h4 class="text-center"> <?php echo $testo ?> </h4>
            </p>
            <div class=" card-header text-center" id="aggiungicommento">
                <?php if (isset($_SESSION['NomeUtente'])) { ?>
                    <button id="mipiace" class="btn btn-block btn-primary fa fa-thumbs-up" data-value="<?php echo $id ?>">Mi Piace</button>
                    <button id="nonmipiace" class="btn btn-block btn-primary fa fa-thumbs-up" data-value="<?php echo $id ?>">Non mi piace</button>
                    <h6 id="numerolikes"></h6>
                <?php } ?>
                <h6>Risposte dalla community: </h6>
                <div id="nuovicommenti">
                    <?php if (!empty($Tuttiicommenti)) { ?>
                        <?php foreach ($Tuttiicommenti as $Commenti) { ?>
                            <div class="commentoutenteloggato">
                                <h5><?php echo $Commenti['username'] . " ha scritto il " .  $Commenti['data'] ?>
                                    <h6><?php echo  $Commenti['descrizione']  ?></h6>
                                </h5>

                                <?php if ($Commenti['username'] == $NomeUtente) {
                                ?>
                                    <a class="btn btn-sm btn-dark " href="eliminacommento.php?idcommento=<?php echo $Commenti['idcommento']; ?>&idblog=<?php echo $idblog; ?>&idpost=<?php echo $id ?>">
                                        Elimina Commento
                                    </a>



                                <?php
                                } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>




                    <?php if (!empty($_SESSION)) { ?>
                        <div name="idblogcommento" data-value="<?php echo $idblog ?>">

                            <button type=" button" id="mostraarea" class="btn btn-dark">Aggiungi un commento </button>

                            <div class=" mostraaggiungicommento" name="mostraaggiungicommento" style="display:none" data-value="<?php echo $_GET['idpost'] ?>">

                                <form id=" commento-form">
                                    <div class="form-group">
                                        <label for="messaggio">Messaggio:</label>
                                        <input id="testocommento" name="testocommento" maxlength="250" class="form-control mb-2 mr-sm-2 user-bg user-text" rows="1" placeholder="Scrivi qualcosa.." />

                                        <div class="form-group">
                                            <button type="button" type="submit" id="postcommento" class="btn">Posta il commento</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" id="annullacommento" class="btn">Annulla</button>
                                    </div>
                                </form>

                            </div>
                        </div>


                    <?php } ?>

                </div>
            </div>
        </div>
    </div>



    <script>
        $(function() {

            $("#mostraarea").on('click', function() {
                const idblog = $("div[name*='idblogcommento']").data('value');
                const idpost = $("div[name*='mostraaggiungicommento']").data('value');
                console.log(idblog);
                console.log(idpost);

                //Evita che l'utente se clicchi invio mentre scriva il messaggio, lo reinderizzi in una pagina non voluta
                $('.mostraaggiungicommento').keypress(function(event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                    }
                })

                $(".mostraaggiungicommento").show();

                $("#annullacommento").click(function() {
                    $(".mostraaggiungicommento").hide();
                })

                $('#postcommento').on('click', function() {



                    const testocommento = $('#testocommento').val();
                    console.log(testocommento);


                    //$(this).find('#testocommento').val();
                    if (testocommento.length > 0 && testocommento != '') {
                        var post = $.ajax({
                            type: "POST",

                            data: {
                                idpost: idpost,
                                testocommento: testocommento,
                                idblog: idblog,
                            },
                            url: "aggiungicommento.php",
                            success: function(risposta) {


                                var json = $.parseJSON(risposta);
                                var array = $.makeArray(json);
                                $username = array[0].username;
                                $data = array[0].data;
                                $descrizione = array[0].descrizione;

                                $('#nuovicommenti').prepend("<h5>" + $username + " ha scritto il " + $data + "<h6>" + $descrizione + "</h6> </h5 >");
                                $('#testocommento').val(''); //pulizia della textarea
                            },
                            error: function(risposta) {
                                alert("errore aggiunta commento");
                            }

                        })
                    };

                })
            })
        });

        $(function() {
            $('#mipiace').on('click', function() {
                const idpost = $(this).data('value');
                console.log(idpost);
                var mettilike = "True";
                $.ajax({
                    type: "POST",
                    datatype: "JSON",
                    url: "likes.php",
                    data: {
                        idpost: idpost,
                        mettilike: mettilike,
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        const contatore = data.numero_likes;
                        console.log(contatore);
                        $('#numerolikes').html("Piace a " + contatore);
                    }
                })
            })

            $('#nonmipiace').on('click', function() {
                const idpost = $(this).data('value');
                console.log(idpost);
                var mettilike = "False";
                $.ajax({
                    type: "POST",
                    datatype: "json",
                    data: {
                        idpost: idpost,
                        mettilike: mettilike,
                    },
                    url: "likes.php",
                    success: function(data) {
                        var data = JSON.parse(data);
                        const contatore = data.numero_likes;
                        console.log(contatore);
                        $('#numerolikes').html("Piace a " + contatore);

                    }
                })
            })

        })


        $(function() {
            $('#cambiafont').click(function() {
                var valore_selezionato = $(this).val();
                console.log(valore_selezionato);
                if (valore_selezionato == "Predefinito") {
                    $('#body').css("font-family", "");
                } else if (valore_selezionato == "La Belle Aurore") {
                    $('#body').css("font-family", $(this).val());
                } else if (valore_selezionato == "Montserrat") {
                    $('body').css("font-family", $(this).val());
                } else if (valore_selezionato == "Nerko One") {
                    $('#body').css("font-family", $(this).val());
                }
            })
        })
    </script>
</body>

</html>