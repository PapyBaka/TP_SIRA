<?php
require_once "req/modele.php";
require 'req/header.php';

if (!is_connected()) {
    header('Location:' . RACINE . 'connexion.php');
}

if (empty($_GET["datedebut"]) || empty($_GET["datefin"]) || empty($_GET["id"]) || empty($_GET["nbjours"])) {
    header('Location:' . RACINE . 'index.php');
}

$donnees = execRequete("SELECT id, titre, prix, description, photos, marque, modele
            FROM vehicules
            WHERE vehicules.id = ?", [$_GET["id"]]);
$vehicule = $donnees->fetch();
$donnees = execRequete("SELECT agences.id, agences.titre, agences.description, agences.adresse, agences.cp, agences.ville, agences.photos
            FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id
            WHERE vehicules.id = ?", [$_GET["id"]]);
$agence = $donnees->fetch();
?>

<div class="container">

<!-- DETAILS VEHICULE -->
  <div class="card m-4">
    <div class="view overlay rounded hoverable">

    <h1 class="card-header">Détails véhicule</h1>
            <div class="card-body blue-grey-text">

              <!-- Details vehicule -->


              <div class="row align-items-center m-4">

                <div class="col-6">

                    <!--Title-->

                    <h4 class="card-title h1"><?=$vehicule->titre?></h4>
                    <h5><?=$vehicule->marque?> - <?=$vehicule->modele?></h5>
                    <hr class="mt-4">
                    <!--Text-->
                    <p class="h6 brown-text"><?=$vehicule->description?></p>
                    <hr class="my-3">

                </div>

                <div class="col-6">
                <img class="img-fluid rounded w-50 m-auto" src="<?=$vehicule->photos?>">
                </div>

              </div>
</div>

              <!-- Details agence -->
              <h1 class="card-header">Détails agence</h1>
              <div class="card-body">
              <div class="row align-items-center m-4">

                <div class="col-6">
                  <!--Title-->
                  <h4 class="card-title h1"><?=$agence->titre?></h4>
                  <hr class="mt-4">
                  <!--Text-->
                  <p class="h6 brown-text"><?=$agence->description?></p>
                  <p class="h6 brown-text"><?=$agence->adresse?></p>
                  <p class="h6 brown-text"><?=$agence->cp?></p>
                  <p class="h6 brown-text"><?=$agence->ville?></p>
                  <hr class="my-3">
                </div>

                <div class="col-6">
                <img class="img-fluid rounded w-50 m-auto" src="<?=$agence->photos?>">
                </div>

                </div>
              </div>


            </div>



    </div>

  </div>
  <h3 class="h2"><strong><?=$vehicule->prix?>€</strong><small class="text-muted text-small"> /jour</small></h3>

</div>