<?php 
session_start();
require_once "req/fonctions.php";
require_once "req/modele.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SIRA - LOCATION DE VEHICULES DE PRESTIGES</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="utilities/css/style.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Navigation -->
  
  <?php if (is_connected()): ?>
    <nav class="navbar navbar-dark bg-dark test">
      <div class="container">
        <a class="navbar-brand" href="#">SIRA</a>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Contactez-nous</a>
          </li>
        </ul>
            <a class="nav-link btn btn-danger" href="deconnexion.php">Se déconnecter</a>
      </div>
    </nav>
<?php else: ?>
<nav class="navbar navbar-dark bg-dark test">
    <div class="container">
      <a class="navbar-brand" href="#">SIRA</a>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Contactez-nous</a>
        </li>
      </ul>
    </div>
  </nav>
<?php endif ?>
<!-- MODAL -->