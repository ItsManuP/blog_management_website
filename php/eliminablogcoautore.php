<?php include('connect.php');
?>
<?php
//Recupero blog in cui sono coautore

$blogcoautore = $mysqli->real_escape_string($_POST['blogcoautore']);

$query = $mysqli->query("DELETE FROM blog WHERE (blog.titolo ='$blogcoautore') ");
echo json_encode($query);
?>