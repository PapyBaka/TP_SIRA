<?php
require_once "req/modele.php";
require 'req/header.php';
    if (isset($_GET["tri"])) {
        $choix_tri = $_GET["tri"] == "croissant" ? "ORDER BY PRIX ASC" : "ORDER BY PRIX DESC";
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
            FROM vehicules
            INNER JOIN agences ON agences.id = vehicules.agence_id $choix_tri",[]);
    } else {
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
            FROM vehicules
            INNER JOIN agences ON agences.id = vehicules.agence_id",[]);
    }
    $vehicules = $donnees->fetchAll();

?>
<div class="container">

    <div class="row border d-flex align-items-center justify-content-center">
        <!-- Tri croissant/decroissant -->
        <div class="col-4 text-center">
            <button class="btn btn-primary text-center dropdown-toggle mr-4" type="button" data-toggle="dropdown">Trier par</button>
            <div class="dropdown-menu">
            <a class="dropdown-item" href="index.php?tri=croissant">Prix croissant</a>
            <a class="dropdown-item" href="index.php?tri=decroissant">Prix décroissant</a>
            </div>
        </div>
        
        <!-- Tri croissant/decroissant -->

        <!-- Filtre choix dates -->
        <div class="col-4">
        <form action="" method="post" class="d-flex justify-content-around">
            <div class="md-form">
            <input class="mt-4"type="date" id="datedebut" class="form-control">
            <label class="mt-4" for="datedebut">Date de début</label>
            </div>
            <div class="md-form">
            <input class="mt-4" type="date" id="datefin" class="form-control">
            <label class="mt-4" for="datefin">Date de fin</label>
            </div>
            <div class="w-100"></div>
            <button class="btn btn-primary" type="submit">Filtrer</button>
        </form>
        </div>
        
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