<?php
require_once "req/modele.php";
require 'req/header.php';

            $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
            FROM vehicules
            INNER JOIN agences ON agences.id = vehicules.agence_id",[]);
            $vehicules = $donnees->fetchAll();

?>
<div class="container">

    <div class="row border d-flex justify-content-center">
        <!-- Tri croissant/decroissant -->
        <div class="col">
            <button class="btn btn-primary text-center dropdown-toggle mr-4" type="button" data-toggle="dropdown">Trier par</button>
            <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Prix croissant</a>
            <a class="dropdown-item" href="#">Prix décroissant</a>
            </div>
        </div>
        
        <!-- Tri croissant/decroissant -->

        <!-- Filtre choix dates -->
        <div class="col">
        <form action="" method="post" class="d-flex justify-content-center">
            <div class="md-form">
            <input class="mt-4"type="date" id="datedebut" class="form-control">
            <label class="mt-4" for="datedebut">Date de début</label>
            </div>
            <div class="md-form">
            <input class="mt-4" type="date" id="datefin" class="form-control">
            <label class="mt-4" for="datefin">Date de fin</label>
            </div>
            <button class="btn btn-primary" type="submit">Filtrer</button>
        </form>
        </div>
        
        <!-- Filtre choix dates -->
    </div>
    

   

    <!-- AFFICHAGE DES VEHICULES RESERVABLES -->
    <?php foreach($vehicules as $vehicule):?>
    <div class="card m-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <!--Title-->
                    <h4 class="card-title"><?= $vehicule->titre?></h4>
                    <hr class="mt-4">
                    <!--Text-->
                    <p class="card-text"><strong>Description: </strong><?= $vehicule->description?></p>
                    <hr class="my-1">
                    <p class="card-text"><strong>Prix: </strong><?= $vehicule->prix?></p>
                    <hr class="my-1">
                    <p class="card-text"><strong>Agence: </strong><?= $vehicule->titre_agence?></p>
                </div>
                <div class="col-6">
                    <img class="img-fluid w-50" src="<?= $vehicule->photos ?>"> 
                </div>
            </div>   
        </div>
        <div class="card-footer text-muted">
        <a href="reservation.php?id=<?= $vehicule->id ?>" class="btn btn-primary">Réserver</a>
        </div>
    </div>
    <?php endforeach ?>

</div>

<?php require "req/footer.php";