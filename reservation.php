<?php 
require_once "req/modele.php";
require 'req/header.php';

if (!is_connected()){
    header('Location:'. RACINE .'connexion.php');
}
$donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
            FROM vehicules
            INNER JOIN agences ON agences.id = vehicules.agence_id WHERE vehicules.id = ?",[$_GET["id"]]);
$vehicule = $donnees->fetch();
?>

<div class="container">

<!-- DETAILS VEHICULE -->
<div class="card aqua-gradient text-white m-auto m-4" style="width: 50rem;">
  <img class="card-img-top p-0" src="<?= $vehicule->photos ?>" alt="">
  <div class="card-body">
    <h4 class="card-title"><?= $vehicule->titre ?></h4>
    <hr class="my-4 white">
    <p class="card-text white-text"><?= $vehicule->description ?></p>
  </div>
</div>
<!-- FORMULAIRE RESERVATION -->

    
    
</div>