<?php
include('connect.php'); //includo connessione db.
include('header.php');
include('cookiefont.php');




if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}

if (isset($_GET['IdBlog'])) {
    //echo $_GET['IdBlog'];
    $idblog = $mysqli->real_escape_string($_GET['IdBlog']);


    //Richiedo tutti i post di un determinato blog
    $Risultato = $mysqli->query("SELECT grafica.pathimmagine,post.idpost,post.titolo,post.idpost,utenti.username, post.testo, post.data FROM grafica,post,blog,utenti WHERE (blog.idblog = '$idblog' AND post.codiceblog = '$idblog' AND utenti.id=post.autorepost AND grafica.id_img_riferimento_post= post.idpost)");
    $Posts = $Risultato->fetch_all(MYSQLI_ASSOC);


    //Richiedo gli argomenti dei vari post
    $Risultato = $mysqli->query("SELECT argomento.nome,argomento.id_blog_riferimento FROM argomento WHERE (argomento.id_blog_riferimento = '$idblog')");
    $Argomenti = $Risultato->fetch_all(MYSQLI_ASSOC);

    //Richiedo gli argomenti argomenti dei vari post
    $Risultato = $mysqli->query("SELECT argomento.nome,argomento.id_blog_riferimento FROM argomento WHERE (argomento.id_blog_riferimento = '$idblog')");
    $Argomenti = $Risultato->fetch_all(MYSQLI_ASSOC);
    //echo json_encode($Argomenti);




}

//Richiedo i sottoargomenti del blog
$Richiestasottoargomenti = $mysqli->query("SELECT DISTINCT argomento.nome,argomento.id_post_riferimento FROM argomento,post WHERE (argomento.id_blog_riferimento = $idblog AND argomento.id_post_riferimento IS NOT NULL )");
$Sottoargomenti = $Richiestasottoargomenti->fetch_all(MYSQLI_ASSOC);
//echo json_encode($Sottoargomenti);


?>


<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">


    <form class="my-2 my-lg-0">
        <input type="text" class="form-control mr-sm-2 mt-1 bg-dark text-white" id="ricerca" name='ricerca' placeholder="Cerca tra i post" />
    </form>




    <?php
    foreach ($Posts as $Post) { ?>


        <div class=" card mt-2">
            <div class="card-header">
                <h5>
                    <?php echo  $Post['username'] . " <h6>scrive in data:</h6>" . $Post['data'] ?>
                </h5>


                <?php
                if ($Post['username'] == $NomeUtente) { ?>
                    <?php if ($codicepost = $Post['idpost']) {; ?>
                        <button type=" button" class="elimina-post btn btn-dark btn-sm" onclick="elimina(<?php echo $codicepost ?>,<?php echo $idblog ?>)">
                            Elimina Post
                    <?php
                    }
                } ?>

            </div>

            <div class="card-body">

                <img class=" img-thumbnail rounded mx-auto d-block" style="height: 20vw" <?php echo '<img src="' . $Post['pathimmagine']  . '" ' ?>>

                <h2 class="card-title text-center">
                    <?php echo $Post['titolo'] ?>
                </h2>
                <h4 class="card-text text-center">
                    <?php echo $Post['testo'] ?>
                </h4>
                <div>
                    <?php foreach ($Sottoargomenti as $Sottoarg) { ?>
                        <?php if ($Post['idpost'] == $Sottoarg['id_post_riferimento']) { ?>
                            <h6 class="card-text text-center">
                                Argomento: <?php echo ucfirst(htmlspecialchars($Sottoarg['nome'])) ?>
                            </h6>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>


            <div class="text-center" id"apripost">

                <button class="btn btn-dark" data-id="<?php echo $Post['idpost'] ?>">
                    <a class="btn btn-dark" href="apripost.php?idpost=<?php echo $Post['idpost']; ?>">
                        Apri Post
                    </a>
                </button>
            </div>
        </div>

    <?php }

    ?>
    <?php if (!empty($NomeUtente)) { ?>
        <div class="text-center">
            <button class="btn btn-dark mt-1">
                <a class="btn btn-dark" href=" creapost.php?IdBlog=<?php echo $idblog; ?>">CREA POST</a>
            </button>
        </div>
    <?php } ?>


    <script type="text/javascript">
        function elimina(a, b) {
            $('.elimina-post').on('click', function() {
                var idpost = a;
                var idblog = b;
                $.post({
                    type: "POST",
                    url: "eliminapost.php",
                    data: {
                        "idpost": idpost,
                        "idblog": idblog,
                    },
                    dataType: "text",
                    success: function(response) {
                        location.reload();
                    }
                })
            });
        };



        $(function() {

            $('#ricerca').keypress(function(event) { //Disabilito 'Invio' in quanto mi generava un errore durante la ricerca
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
                $('#ricerca').on('keyup', function() {
                    var testo = $(this).val().toLowerCase();
                    $(".card").each(function() {
                        //Se l'elemento non rispetta il match allora applico fadeout che applica opacità 0
                        if ($(this).text().search(new RegExp(testo, "i")) < 0) { //Utilizzo RegExp per match del testo con il pattern, 'i' global flag case-insensitive. 
                            $(this).fadeOut();
                        } else { // Mostro il contenuto se trovato
                            $(this).show();
                        }
                    });
                });
            });
        });




        $(function() {

            $("#mostraarea").on('click', function() {
                const idblog = $("div[name*='idblogcommento']").data('value');
                const idpost = $("div[name*='mostraaggiungicommento']").data('value');
                console.log(idblog);
                console.log(idpost);

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