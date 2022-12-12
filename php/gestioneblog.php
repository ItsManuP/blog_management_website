<?php
include_once('connect.php');
include('cookiefont.php');
?>

<!DOCTYPE html>
<html lang="it">
<?php
include('head.php');
include('header.php');
?>

<?php
if (isset($_SESSION['NomeUtente'])) {
    $NomeUtente = $_SESSION['NomeUtente'];
}
$query = $mysqli->query("SELECT utenti.email FROM utenti WHERE (utenti.username <> '$NomeUtente')");
$risultato = $query->fetch_all();
//echo json_encode($risultato);

$query = $mysqli->query("SELECT blog.idblog,blog.titolo,utenti.id,utenti.username from blog,utenti WHERE (blog.autoreblog = utenti.id AND utenti.username = '$NomeUtente')");
$risultatodue = $query->fetch_all();
//echo json_encode($risultatodue);


//lista blog in cui l'utente è coautore
$listablogcoautore = $mysqli->query("SELECT blog.titolo,blog.coautore,utenti.id,blog.idblog from blog,utenti WHERE (utenti.username='$NomeUtente' AND blog.coautore=utenti.id)");
$risultatotre = $listablogcoautore->fetch_all();
//echo json_encode($risultatotre);


?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">

    <div id="areapersonaleutente" class="mt-3">
        <h2> Questo è il tuo profilo personale <?php echo $_SESSION['NomeUtente'] ?></h2>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-sm">
                <div id="bottone_aggiungi" class="btn bg-dark text-white btn-secondary btn-lg active" role="button">Seleziona il Coautore per un tuo blog</div>
            </div>
            <div class="col-sm">
                <div id="bottone_elimina" class="btn bg-dark text-white btn-secondary btn-lg active" role="button">Elimina Coautore da un tuo blog</div>
            </div>
            <div class="col-sm">
                <a id="bottone_elimina_blog_coautore" class="btn bg-dark text-white btn-secondary btn-lg active" role="button">Elimina blog in cui sei coautore</a>
            </div>
            <div class="col-sm">
                <a href="eliminaaccount.php" class="btn bg-dark text-white btn-secondary btn-lg active" role="button"> Elimina il tuo account permanentemente</a>
            </div>
        </div>
    </div>




    <div id="aggiungi" class="mt-2 text-center">
        <div id="mostraelencoaggiungi">
            <!-- mostro tutte le email degli utenti registrati, uno di essi se scelto diverrà il coautore del blog successivamente selezionato -->
            <select id="utenteemail" class="form-select form-select-lg mb-3">
                <?php foreach ($risultato as $utenti) { ?>
                    <option name=" email" id="<?php echo $utenti[0] ?>">
                        <?php echo $utenti[0] ?>
                    </option>
                <?php } ?>
            </select>
            <!-- mostro solamente i blog di cui l'utente loggato è amministratore e in essi è possibile settare il co-autore -->
            <select id="blognome" class="form-select form-select-lg mb-3">
                <?php foreach ($risultatodue as $blog) { ?>
                    <option name="utente" id="<?php $blog[1] ?>">
                        <?php echo $blog[1] ?>
                    </option>
                <?php } ?>
            </select>
            <button id="sceltauno" type="submit" class="btn btn-dark"> Conferma la scelta </button>
            <button id="sceltaunoannulla" type="submit" class="btn btn-dark"> Annulla </button>
        </div>
    </div>


    <div id="elimina" class="mt-2 text-center">
        <div id="mostraelencoelimina">
            <!-- mostro solamente i blog di cui l'utente loggato è amministratore e in essi è possibile settare il co-autore -->
            <select id="blognomeelimina" class="form-select form-select-lg mb-3">
                <?php foreach ($risultatodue as $blog) { ?>
                    <option>
                        <?php echo $blog[1] ?>
                    </option>

                <?php } ?>
            </select>

            <button id="sceltadue" class="btn btn-dark"> Conferma la scelta </button>
            <button id="sceltadueannulla" class="btn btn-dark"> Annulla </button>
        </div>
    </div>

    <div id='eliminacoautore' class="mt-2 text-center">
        <div id="mostraelencoeliminacoautore">
            <!-- mostro solamente i blog di cui l'utente è coautore -->
            <select id="blogcoautoreelimina" class="form-select form-select-lg mb-3">
                <?php foreach ($risultatotre as $listablogcoautore) { ?>
                    <option>
                        <?php echo $listablogcoautore[0] ?>
                    </option>

                <?php } ?>
            </select>
            <button id="sceltatre" class="btn btn-dark"> Conferma la scelta </button>
            <button id="sceltatreannulla" class="btn btn-dark"> Annulla </button>
        </div>
    </div>
    </div>



    <script>
        //Permette di aggiungere un coautore               
        $(function() {
            $('#aggiungi').hide();
            $('#bottone_aggiungi').on('click', function() {
                $('#aggiungi').show();
                $('#sceltauno').on('click', function() {
                    const email = $('select#utenteemail').val();
                    const blogtitolo = $('select#blognome').val();
                    console.log(email);
                    console.log(blogtitolo);
                    $.ajax({
                        type: "POST",
                        datatype: "JSON",
                        data: {
                            blogtitolo: blogtitolo,
                            email: email,
                        },
                        url: "aggiungicoautore.php",
                        success: function(data) {
                            console.log(data);
                            $('#mostraelencoaggiungi').html("<h2>" + "Hai aggiunto con successo il coautore" + "</h2>");
                        }
                    })
                })
            });
            $('#sceltaunoannulla').on('click', function() {
                $('#aggiungi').hide();
            })
        });



        //Permette di eliminare un coautore 
        $(function() {
            $('#elimina').hide();
            $('#bottone_elimina').on('click', function() {
                $('#elimina').show();
                $('#sceltadue').on('click', function() {
                    const blogtitolo = $('select#blognomeelimina').val();
                    console.log(blogtitolo);
                    $.ajax({
                        type: "POST",
                        datatype: "JSON",
                        data: {
                            blogtitolo: blogtitolo,
                        },
                        url: "eliminacoautore.php",
                        success: function(data) {
                            console.log(data);
                            $('#mostraelencoelimina').html("<h2>" + "Se presente, hai eliminato con successo il coautore" + "</h2>");
                        }
                    })
                })
            })
        })


        //Permette di eliminare il blog in cui sono coautore
        $(function() {
            $('#eliminacoautore').hide();
            $('#bottone_elimina_blog_coautore').on('click', function() {
                $('#eliminacoautore').show();
                $('#sceltatre').on('click', function() {
                    const blogcoautore = $('select#blogcoautoreelimina').val();
                    console.log(blogcoautore);
                    $.ajax({
                        type: "POST",
                        datatype: "JSON",
                        data: {
                            blogcoautore: blogcoautore,
                        },
                        url: 'eliminablogcoautore.php',
                        success: function(data) {
                            console.log(data);
                            $('#mostraelencoeliminacoautore').html("<h2>" + "Se presente, hai eliminato con successo il blog in cui sei coautore" + "</h2>");
                        }
                    })
                })

            })
        })
    </script>
</body>

</html>