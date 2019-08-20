<?php
require_once "req/modele.php";
require 'req/header.php';

    if (isset($_GET["tri"])) {
        $choix_tri = $_GET["tri"] == "croissant" ? "ORDER BY PRIX ASC" : "ORDER BY PRIX DESC";
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
        FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id
        WHERE vehicules.id != (SELECT id_vehicule FROM reservations) $choix_tri",[]);
    } else {
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
        FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id
        WHERE EXISTS (SELECT id_vehicule FROM reservations WHERE reservations.id_vehicule != vehicules.id)",[]);
        
    }
    $vehicules = $donnees->fetchAll();

?>
<div class="container">

    <div class="navbar navbar-expand-lg navbar-dark light-color">
        <!-- Tri croissant/decroissant -->
        <div class="col">
            <a class="text-center dropdown-toggle mr-4" data-toggle="dropdown">Trier par : <?= isset($_GET['tri']) ? "prix ".$_GET['tri'] : "" ?></a>
            
            <div class="dropdown-menu">
            <a class="dropdown-item" href="?tri=croissant">Prix croissant</a>
            <a class="dropdown-item" href="?tri=décroissant">Prix décroissant</a>
            </div>
        </div>
        
        <!-- Tri croissant/decroissant -->

        <!-- Filtre choix dates -->
        
        <form action="" method="post" class="d-flex justify-content-center">
            <div class="md-form">
            <input class="mt-4"type="date" id="datedebut" class="form-control">
            <label class="mt-4" for="datedebut">Date de début</label>
            </div>
            <div class="md-form">
            <input class="mt-4" type="date" id="datefin" class="form-control">
            <label class="mt-4" for="datefin">Date de fin</label>
            </div>
        </form>
        <button class="btn btn-outline-primary" type="submit">Filtrer</button>

        <!-- Filtre choix dates -->
    </div>
    

   

    <!-- AFFICHAGE DES VEHICULES RESERVABLES -->
    <?php foreach($vehicules as $vehicule):?>
    <div class="card m-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-6">
                    <!--Title-->
                    <h4 class="card-title"><?= $vehicule->titre?></h4>
                    <hr class="mt-4">
                    <!--Text-->
                    <p class="card-text"><strong>Description: </strong><?= $vehicule->description?></p>
                    <p class="card-text"><strong>Agence: </strong><?= $vehicule->titre_agence?></p>
                    <h3 class="display-4"><strong><?= $vehicule->prix?>€</strong><small class="text-muted text-small"> /jour</small></h3>
                </div>
                <div class="col-6 view overlay zoom rounded">
                <a href="reservation.php?id=<?= $vehicule->id ?>">
                    <img class="img-fluid rounded" src="<?= $vehicule->photos ?>">
                    <div class="mask waves-effect waves-light rgba-black-strong flex-center">
                        <p class="white-text">RESERVER</p>
                    </div>
                    </a>
                </div>
            </div>   
        </div>
    </div>
    <?php endforeach ?>


</div>

<?php require "req/footer.php";