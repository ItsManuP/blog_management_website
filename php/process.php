<?php
include('connect.php');
?>

<?php
Session_start();
$NomeUtente = "";
$Password = "";
$ControlloPassword = "";
$Email = "";
$Documento = "";
$error = array(); //array per la registrazione


if (isset($_POST['registrazionebottone'])) {


    $NomeUtente = $mysqli->real_escape_string($_POST['NomeUtente']);
    $Password = $mysqli->real_escape_string($_POST['Password']);
    $ControlloPassword = $mysqli->real_escape_string($_POST['ControlloPassword']);
    $Email = $mysqli->real_escape_string($_POST['Email']);
    $Numerotelefono = $mysqli->real_escape_string($_POST['Numerotelefono']);
    $Documento = $mysqli->real_escape_string($_POST['Documento']);



    //Gestisco gli errori nel caso l'utente non abbia compilato a dovere il form, utilizzo la funzione empty() = !isset($var)
    if (empty($NomeUtente)) {
        array_push($error, "E' richiesto un nome utente ");
    }
    if (empty($Password)) {
        array_push($error, "Devi inserire una password ");
    } else if (strlen(trim($Password)) < 6) {
        array_push($error, "Lunghezza password non sufficiente ");
    }
    if ($Password != $ControlloPassword) {
        array_push($error, "Le password non sono uguali ");
    }
    if (empty($Email)) {
        array_push($error, "Devi inserire una email ");
    }
    if (empty($Numerotelefono)) {
        array_push($error, "Devi inserire un telefono valido ");
    }
    if (empty($Documento)) {
        array_push($error, "Devi inserire un documento valido ");
    }

    $utente = '';
    $result = $mysqli->query("SELECT * FROM utenti WHERE (username = '$NomeUtente' OR email = '$Email' OR documento='$Documento')");

    while ($utente = $result->fetch_assoc()) {
        if ($utente) {
            if ($utente['username'] === $NomeUtente) {
                array_push($error, "Nome utente presente nel database ");
            }
            if ($utente['email'] === $Email) {
                array_push($error, "Scegli una email diversa. ");
            }
            if ($utente['documento'] === $Documento) {
                array_push($error, "Documento presente nel database ");
            }
        }
    }




    $dati = "";
    if (count($error) == 0) { // 0 errori accumulati, in questo caso il procedimento non ha avuto problemi e posso registrare l'utente
        $Password = password_hash($Password, PASSWORD_DEFAULT); //Evito sia in chiaro ; MD5, SHA1 and SHA256 son da evitare causa bruteforce, crypt() invece non è binary safe, uso password_hash
        $result = $mysqli->query("INSERT INTO utenti(telefono,username,password,email,documento) VALUES ('$Numerotelefono','$NomeUtente','$Password', '$Email', '$Documento')");
        $_SESSION['NomeUtente'] = $NomeUtente;
        header("Location: index.php");
    } else if (count($error) > 0) {
        $errori =  implode($error); //trasformo l'array in stringa evitando così ["contenutoerrore"]
        //$dati = json_encode($errori);
        echo $errori;
    }
};
