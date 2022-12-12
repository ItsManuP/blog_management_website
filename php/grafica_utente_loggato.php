<?php
$NomeUtente = $_SESSION['NomeUtente'];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">GESTIONALE BLOG</span>
    </button>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="gestioneblog.php">Area Personale di <?php echo $NomeUtente ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>