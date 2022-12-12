<?php

include_once('connect.php'); //includo connessione db.
include('header.php');
include('cookiefont.php');
?>

<?php


//inizialmente l'index di font non è definito in quanto per default non è stata fatta una scelta dell'utente sul font stesso,nascondo questo errore che verrà subito eliminato una volta che l'utente sceglie un font per il sito







if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
} else {
    echo ('Registrati per un esperienza migliore!');
}



$Risultato = $mysqli->query("SELECT DISTINCT utenti.id,utenti.username,blog.descrizione,blog.coautore,blog.autoreblog,blog.titolo,blog.idblog,grafica.id_img_riferimento_blog,grafica.pathimmagine FROM utenti,blog,grafica,argomento WHERE (utenti.id=blog.autoreblog AND grafica.id_img_riferimento_blog=blog.idblog )");
$blogs = $Risultato->fetch_all(MYSQLI_ASSOC);
//echo json_encode($blogs);

$selectargomento = $mysqli->query("SELECT argomento.nome,argomento.id_blog_riferimento from argomento,blog WHERE (argomento.id_blog_riferimento = blog.idblog AND argomento.id_post_riferimento IS NULL)");
$argomento = $selectargomento->fetch_all(MYSQLI_ASSOC);
//echo json_encode($argomento);

$selectsottoargomento = $mysqli->query("SELECT argomento.nome,argomento.id_blog_riferimento,argomento.id_post_riferimento FROM argomento,blog,post WHERE (argomento.id_post_riferimento = post.idpost AND argomento.id_blog_riferimento = blog.idblog AND argomento.id_post_riferimento IS NOT NULL)");
$sottoargomento = $selectsottoargomento->fetch_all(MYSQLI_ASSOC);
//echo json_encode($sottoargomento);


// inner join utile per mostrare i coautori
$selectcoautori = $mysqli->query("SELECT DISTINCT utenti.id,utenti.username FROM blog INNER JOIN utenti ON blog.coautore = utenti.id");
$coautori = $selectcoautori->fetch_all(MYSQLI_ASSOC);
//echo json_encode($coautori);



$selezionecoautore = $mysqli->query("SELECT blog.coautore,utenti.id,utenti.username FROM blog,utenti WHERE (utenti.username = '$NomeUtente' and blog.coautore = utenti.id)");
$coautoriblog = $selezionecoautore->fetch_all(MYSQLI_ASSOC);
$Nomecoautoreblog = json_encode($coautoriblog);



$mysqli->close();

?>


<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">

    <form class=" my-2 my-lg-0">
        <input type="text" class="form-control mr-sm-2 bg-dark text-white" id="ricerca" name='ricerca' maxlength="45" placeholder="Cerca Blog per Titolo,Autore,Argomenti" />
    </form>

    <div class=" mb-2 mt-1 text-center">
        <?php if (isset($_SESSION['NomeUtente'])) { ?>

            <h6>
                <a href="creablog.php" class="btn bg-dark text-white">
                    Crea un Blog
                </a>
            <?php } ?>

            Cambia Font
            <select class="btn btn-dark text-white text-center" id="cambiafont">
                <option>Semplice</option>
                <option style="font-family: La Belle Aurore">La Belle Aurore</option>
                <option style="font-family: Montserrat">Montserrat</option>
                <option style="font-family: Nerko One">Nerko One</option>
            </select>
            </h6>
    </div>



    <div class=" row" style="object-fit: cover;width: 100vw; height: 15vw; padding-left: 10vw; padding-right:10vw">
        <?php foreach ($blogs as $blog) { ?>
            <div id="<?php echo $blog["idblog"]; ?>" class="card col-sm-6 " name="blog" style="text-align:center">
                <img class="card-img-top" style="object-fit:cover; height: 20vw" <?php echo '<img src="' . $blog['pathimmagine']  . '" ' ?>>

                <h4 class="card-title mt-2" style="border-color: red;">
                    <?php echo ucfirst(htmlspecialchars($blog['titolo'])); ?>
                </h4>

                <div class=" card-body">
                    <h5 class="card-subtitle mb-2 text-black">
                        Autore: <?php echo ucfirst(htmlspecialchars($blog['username'])); ?>
                        <?php foreach ($coautori as $coautore) { ?>
                            <?php if ($blog['coautore'] == $coautore['id']) { ?>
                                Coautore: <?php echo ucfirst(htmlspecialchars($coautore['username'])); ?>
                            <?php } ?>
                        <?php  } ?>
                    </h5>
                </div>

                <!-- ARGOMENTO !-->
                <h6 class="card-subtitle mb-2 text-muted">
                    Argomento:
                    <?php foreach ($argomento as $argo) { ?>
                        <?php if ($blog['idblog'] == $argo['id_blog_riferimento']) {
                            echo ucfirst(htmlspecialchars($argo['nome'])); ?>
                        <?php  } ?>
                    <?php } ?>
                </h6>

                <!-- SOTTOARGOMENTO !-->
                <h6 class="card-subtitle mb-2 text-muted">
                    Sottoargomento:
                    <?php foreach ($sottoargomento as $sottoarg) {
                        if ($blog['idblog'] == $sottoarg['id_blog_riferimento']) {
                            echo ucfirst(htmlspecialchars($sottoarg['nome']));
                        } ?>
                    <?php } ?>
                </h6>




                <p class="card-text text-black">
                    <?php echo ucfirst(htmlspecialchars($blog['descrizione'])); ?>
                </p>
                <div class="row">
                    <a class="btn btn-info bg-dark text-white" href="apriblog.php?IdBlog=<?php echo $blog['idblog']; ?>">Visualizza il Blog</a>
                </div>

                <?php if ($blog['username'] == $NomeUtente) { ?>
                    <div class="row">
                        <a class=" btn btn-info mt-1 bg-dark text-white" onclick="elimina(<?php echo $blog['idblog'] ?>)" name="eliminablog" id="eliminablog"> Elimina il Blog</a>
                        <a class=" btn btn-info mt-1 bg-dark text-white" name="modificaimgblog" id="modificaimgblog" href="modificaimgblog.php?idblog=<?php echo $blog['idblog']; ?>"> Modifica Copertina</a>
                    </div>

                <?php } ?>



            </div>

        <?php }
        ?>
    </div>


    <script>
        $(function() {

            $('#ricerca').keypress(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }

                $('#ricerca').on('keyup', function() {

                    var testo = $(this).val().toLowerCase();

                    $(".card").each(function() {

                        //Se l'elemento non rispetta il match allora applico fadeout che applica opacità 0

                        if ($(this).text().search(new RegExp(testo, "i")) < 0) { //Utilizzo RegExp per match del testo con il pattern, i global flag case-insensitive. 
                            $(this).fadeOut();
                        } else { // Mostro il contenuto se trovato
                            $(this).show();
                        }
                    });
                });
            });
        });

        //Questa funzione mi permette di eliminare un blog ed nascondere il blog stesso alla vista senza dover effettuare un reload della pagina
        function elimina(idblog) {

            // $('#eliminablog').on('click', function() {

            var idblogdaeliminare = idblog;
            //$("a[name*='eliminablog']").data('value');
            console.log(idblogdaeliminare);

            $.ajax({
                type: "POST",
                dataType: "text",
                data: {
                    idblogdaeliminare: idblogdaeliminare,
                },
                url: "eliminablog.php",
                success: function(dati) {
                    var idblogdaeliminare = dati;
                    console.log(idblogdaeliminare);
                    $('.row > div').each(function() { //Per ogni classe row controllo i relativi sotto div, se uno di questi ha un id che matcha con l'id blog da eliminare, lo nascondo alla vista.
                        if ($(this).attr('id') == idblogdaeliminare) {
                            $(this).hide();
                        }
                    })

                }
            })
            //  })
        }

        $(function font() {

            $('#cambiafont').click(function() {
                var valore_selezionato = $(this).val();
                //console.log(valore_selezionato);
                var Font = "Font";
                if (valore_selezionato == "Predefinito") {
                    $('#body').css("font-family", "");
                    valore_selezionato = "";
                } else if (valore_selezionato == "La Belle Aurore") {
                    // $('#body').css("font-family", $(this).val());
                    valore_selezionato = "La Belle Aurore"
                } else if (valore_selezionato == "Montserrat") {
                    // $('body').css("font-family", $(this).val());
                    valore_selezionato = "Montserrat";
                } else if (valore_selezionato == "Nerko One") {
                    // $('#body').css("font-family", $(this).val());
                    valore_selezionato = "Nerko One";
                }
                const setCookie = (name, value) => {
                    document.cookie =
                        name +
                        "=" +
                        value +
                        ";" + ";path=/"
                };
                setCookie(Font, valore_selezionato);
                $('#body').css("font-family", valore_selezionato);
            })

        })
    </script>

</body>


</html>