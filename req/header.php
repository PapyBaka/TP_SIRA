
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SIRA - LOCATION DE VEHICULES DE PRESTIGES</title>

  <!-- Bootstrap core CSS -->
  <!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.7/css/mdb.min.css" rel="stylesheet">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="utilities/css/style.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Navigation -->
  
  
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="<?= RACINE ?>">SIRA</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
          aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
        <ul class="navbar-nav ml-auto">

        <?php if (is_connected()): ?>

          <?php if ($_SESSION["statut"] == "Admin"): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE . 'admin/gestion_membres.php'?>">Gestion membres</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE . 'admin/gestion_commandes.php'?>">Gestion commandes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE . 'admin/gestion_vehicules.php'?>">Gestion véhicules</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE . 'admin/gestion_agences.php'?>">Gestion agences</a>
          </li>
          <?php else: ?>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
                  <a class="dropdown-item" href="#">My account</a>
                  <a class="dropdown-item" href="#">Log out</a>
                </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Mon compte</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contactez-nous</a>
          </li>
          <?php endif ?>
         </ul>
        <ul class="navbar-nav ml-auto nav-flex-icons">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-default"
              aria-labelledby="navbarDropdownMenuLink-333">
              <a class="dropdown-item" href="#">Mon compte</a>              
              <a class="dropdown-item" href="<?= RACINE ."deconnexion.php"?>">Se déconnecter</a>
            </div>
          </li>
        </ul>
      </div>
      <?php else: ?>
      <ul class="navbar-nav">
      
        <li class="nav-item">
          <a class="nav-link" href="<?= RACINE."connexion.php"?>">Se connecter</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= RACINE."inscription.php"?>">S'inscrire</a>
        </li>

        <li class="nav-item">
        <a class="nav-link waves-effect waves-light"> 
          <i class="fas fa-envelope"></i>Contact
        </a>
      </li>
      </ul>
    </div>
  </nav>
      
    </nav>


<?php endif ?>
<!-- MODAL -->
