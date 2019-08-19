<?php
require_once "req/modele.php";
require 'req/header.php';

            
        // ------- PRIX CROISSANT / DECROISSANT ------- // 

            if (isset($_GET["tri"])) {
                if ($_GET["tri"] == "croissant") {
                    $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
                    FROM vehicules
                    INNER JOIN agences ON agences.id = vehicules.agence_id ORDER BY vehicules.prix",[]);
                    $vehicules = $donnees->fetchAll();
                    
                } else if ($_GET["tri"] == "décroissant") {
                    $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
                    FROM vehicules
                    INNER JOIN agences ON agences.id = vehicules.agence_id ORDER BY vehicules.prix DESC",[]);
                    $vehicules = $donnees->fetchAll();
                }
                
            }else{
                    
                $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
                FROM vehicules
                INNER JOIN agences ON agences.id = vehicules.agence_id",[]);
                $vehicules = $donnees->fetchAll();
            }

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
                <div class="row">
                    <div class="col-6">
                        <!--Title-->
                        <h4 class="card-title text-center"><?= $vehicule->titre?></h4>
                        <hr class="mt-4">
                        <!--Text-->
                        <p class="card-text"><strong>Description : </strong><?= $vehicule->description?></p>
                        <hr class="my-1">
                        <p class="card-text"><strong>Prix : </strong><?= $vehicule->prix?> €/jour</p>
                        <hr class="my-1">
                        <p class="card-text"><strong>Agence : </strong><?= $vehicule->titre_agence?></p>
                    </div>
                    <div class="col-6">
                        <img class="img-fluid w-100 p-4" src="<?= $vehicule->photos ?>"> 
                    </div>
                </div>   
            </div>
            <div class="card-footer text-muted text-center">
            <a href="reservation.php?id=<?= $vehicule->id ?>" class="btn btn-primary">Réserver</a>
            </div>
        </div>
    <?php endforeach ?>


</div>

<?php require "req/footer.php";