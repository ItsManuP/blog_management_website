<?php
include('connect.php');
include('header.php');
include('cookiefont.php');
?>


<!DOCTYPE html>
<html lang="it">

<?php
include('head.php');
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">

      <div class=" text-center ml-1">
            <form class=" form-iscrizione" method="POST" type="submit" id="form-iscrizione" enctype="multipart/form-data">

                  <div class="form-group">
                        <label for="NomeUtente"> Nome Utente </label>
                        <input type="text" name="NomeUtente" class="form-control" id="NomeUtente">
                  </div>


                  <div class="form-group">
                        <label for="Email"> Indirizzo email </label>
                        <input type="email" class="form-control" id="Email" name="Email">
                  </div>



                  <div class="form-group">
                        <label for="Password">Password </label>
                        <input type="password" name="Password" class="form-control" id="Password">
                  </div>

                  <div class="form-group">
                        <label for="ControlloPassword">Ripeti la Password </label>
                        <input type="password" name="ControlloPassword" class="form-control" id="Controllopassword">
                  </div>


                  <div class="form-group">
                        <laber for="Documento">Numero di Telefono</label>
                              <input type="text" id="Numerotelefono" name="Numerotelefono" pattern="[03]\d{8,10}" placeholder="Fisso o Mobile" class="form-control form-control-lg">
                  </div>


                  <div class="form-group">
                        <label for="Documento">Inserisci N° Documento</label>
                        <input type="text" id="Documento" name="Documento" pattern="[a-zA-Z]{2}\d{7}" placeholder="Ex: AY3574934" class="form-control form-control-lg">
                  </div>
                  <button type="submit" id="registrazionebutton" name="registrazionebottone" class="btn btn-primary mt-2" disabled>Conferma</button>
            </form>
            <div>

                  <script type="text/javascript">
                        $(function() {
                              $('#NomeUtente').keyup(function() {

                                    if ($(this).val().length < 15) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              $('#Password').keyup(function() {

                                    if ($(this).val().length < 15) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              $('#Controllopassword').keyup(function() {

                                    if ($(this).val().length < 15) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              $('#Email').keyup(function() {

                                    if ($(this).val().length < 15) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              $('#Numerotelefono').keyup(function() {

                                    if ($(this).val().length < 11) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              $('#Documento').keyup(function() {

                                    if ($(this).val().length < 10) {
                                          $('#registrazionebutton').removeAttr('disabled');
                                    } else {
                                          $('#registrazionebutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
                                    }
                              });
                              var richiesta;
                              $('#registrazionebutton').submit(function(event) {
                                    var NomeUtente = $('#NomeUtente').val();
                                    var Password = $('#Password').val();
                                    var Controllopassword = $('#Controllopassword').val();
                                    var Email = $('#Email').val();
                                    var Numerotelefono = $('#Numerotelefono').val();
                                    var Documento = $('#Documento').val();

                                    richiesta = $.ajax({
                                          url: "process.php",
                                          datatype: "JSON",
                                          type: "POST",
                                          data: {
                                                NomeUtente: NomeUtente,
                                                Password: Password,
                                                ControlloPassword: ControlloPassword,
                                                Email: Email,
                                                Numerotelefono: Numerotelefono,
                                                Documento: Documento,
                                          },
                                          success: function(risposta) {
                                                console.log("Richiesta eseguita");
                                          },
                                          error: function(risposta) {
                                                console.log("Richiesta Errore");
                                          }
                                    })
                              })
                        })
                  </script>


</body>

</html>