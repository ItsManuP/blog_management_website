<?php
include('connect.php');
include('header.php');
include('cookiefont.php');
?>


<?php
$NomeUtente = '';
$Password = '';
$Passwordnoncryptata = '';
$error_login = [];



if (isset($_POST['loginbutton'])) {

  $NomeUtente = $mysqli->real_escape_string($_POST['NomeUtente']);
  $Passwordnoncryptata =  $mysqli->real_escape_string($_POST['Password']);


  if (empty($_POST['NomeUtente'])) {
    array_push($error_login, "Devi inserire un username valido");
  }

  if (empty($_POST['Password'])) {
    array_push($error_login, "Devi inserire una password valida");
  }

  if (!empty($error_login)) {
    $errori = implode($error_login);
    echo $errori;
  }
  //se non presenti errori, $error_login è vuoto quindi posso procedere
  if (empty($error_login)) {
    //Password tornata dal tabase in hash
    $hash = $mysqli->query("SELECT u.password FROM utenti as u  WHERE u.username = '$NomeUtente' ");
    $rows = $hash->fetch_all(MYSQLI_ASSOC);

    $test =  json_encode($rows);

    foreach ($rows as $row) {
      $Password = $row["password"];
      //Questo mi permette di assegnare il risultato della query $hash ad una variabile la quale ha il valore della password hashata
      //La password verrà successivamente confrontata con quella digitata dall'utente non cryptata direttamente con quella salvata nel database
    }




    if (password_verify($Passwordnoncryptata, $Password)) {
      $_SESSION['NomeUtente'] = $NomeUtente;
      header("Location: index.php");
    }

    if (!password_verify($Passwordnoncryptata, $Password)) {
      echo "<h4>" . "I dati inseriti non sono corretti" . "<h4>";
    }
  }
}

?>



<!DOCTYPE html>
<html lang="it">

<?php
include('head.php');
?>

<body id="body" style="font-family:<?php echo htmlspecialchars($Font) ?>;">



  <div class="card">
    <div class=" card-header text-center">
      <div class="card-body">
        <h5 class="card-title">Effettua il Login</h5>
        <form class="" method="POST" action="">

          <div class="form-group p-3">
            <input type=" text" name="NomeUtente" class="form-control" id="NomeUtente" placeholder="Nome Utente">

          </div>

          <div class="form-group p-3">
            <input type="password" name="Password" class="form-control" id="Password" placeholder="Password">

          </div>

          <button type="submit" id="loginbutton" name="loginbutton" class="btn btn-primary" disabled>Conferma</button>

        </form>
      </div>
    </div>
  </div>




  <script>
    $(function() {
      $('#NomeUtente').keyup(function() {

        if ($(this).val().length < 15) {
          $('#loginbutton').removeAttr('disabled');
        } else {
          $('#loginbutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
        }
      });

      $('#Password').keyup(function() {

        if ($(this).val().length < 15) {
          $('#loginbutton').removeAttr('disabled');
        } else {
          $('#loginbutton').attr('disabled', 'disabled'); //Il bottone viene disattivato
        }
      });
    })
  </script>

</body>

</html>