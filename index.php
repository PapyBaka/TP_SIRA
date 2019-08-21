<?php
require_once "req/modele.php";
require 'req/header.php';
$error = null;
if (isset($_GET["datedebut"]) && isset($_GET["datefin"])) {
    if (empty($_GET["datedebut"]) || empty($_GET["datefin"])) {
        $error = "Vous devez sélectionner une date de début et de fin afin de pouvoir réserver un véhicule";
    } else {
        $datedebut = new DateTime($_GET["datedebut"]);
        $datefin = new DateTime($_GET["datefin"]);
        $verif_date = verif_date($datedebut,$datefin);
        $error = $verif_date["error"];
    }
}

    if (isset($_GET["tri"])) {

        $choix_tri = $_GET["tri"] == "croissant" ? "ORDER BY PRIX" : "ORDER BY PRIX DESC";
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.marque, vehicules.modele, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence
        FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id
        WHERE NOT EXISTS (SELECT id_vehicule FROM reservations WHERE id_vehicule = vehicules.id) $choix_tri",[]);
    } else {
        $donnees = execRequete("SELECT vehicules.id, vehicules.titre, vehicules.marque, vehicules.modele, vehicules.prix, vehicules.description, vehicules.photos, agences.titre AS titre_agence 
        FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id 
        WHERE NOT EXISTS (SELECT id_vehicule FROM reservations WHERE id_vehicule = vehicules.id)",[]);
        
    }
    $vehicules = $donnees->fetchAll();
    

?>
</div>
<div class="container">

    <div class="navbar navbar-expand-lg navbar-dark light-color col-md-8 m-auto">

        <!-- Filtre choix dates -->
        <div class="container d-flex justify-content-center">
        
                <div class="d-flex flex-column">
                <p class="m-0 text-center font-weight-bold blue-grey-text">Sélectionner les dates de réservations afin de réserver un véhicule</p>
                
                <form action="" method="get" class="d-flex justify-content-center">
                    <div class="md-form">
                    <input class="mt-2"type="date" id="datedebut" class="form-control" name="datedebut" value="<?= isset($_GET['datedebut']) ? $_GET["datedebut"] : ''?>">
                    <label class="mt-3" for="datedebut">Date de début</label>
                    </div>
                    <div class="md-form">
                    <input class="mt-2" type="date" id="datefin" class="form-control" name="datefin" value="<?= isset($_GET['datefin']) ? $_GET["datefin"] : ''?>">
                    <label class="mt-3" for="datefin"><strong>Date de fin</strong></label>
                    </div>
                    <button class="btn btn-outline-primary h-50 mt-3" type="submit">Valider</button>
                </form>

                <?php if (isset($error)): ?>
                <p class="text-danger text-center font-weight-bold">
                <?= $error ?>
                </p>
                <?php endif ?>  
                </div>
        </div>
        
    </div>   

    
    <div class="col mt-4">
            <a class="text-center dropdown-toggle mr-4" data-toggle="dropdown">Trier par : <?= isset($_GET['tri']) ? "prix ".$_GET['tri'] : "" ?></a>
            
            <div class="dropdown-menu">
                <?php if (isset($_GET["datedebut"]) && isset($_GET["datefin"])): ?>
                    <a class="dropdown-item" href="?tri=croissant&datedebut=<?=$_GET["datedebut"]?>&datefin=<?=$_GET["datefin"]?>">Prix croissant</a>
                    <a class="dropdown-item" href="?tri=decroissant&datedebut=<?=$_GET["datedebut"]?>&datefin=<?=$_GET["datefin"]?>">Prix décroissant</a>
                <?php else: ?>
                    <a class="dropdown-item" href="?tri=croissant">Prix croissant</a>
                    <a class="dropdown-item" href="?tri=décroissant">Prix décroissant</a>
                <?php endif ?>
            </div>
        </div>
   
    
    
    <!-- AFFICHAGE DES VEHICULES RESERVABLES -->
   
    <?php foreach($vehicules as $vehicule):?>
    
    <div class="card m-4">
        <div class="view overlay zoom rounded hoverable">
            <?php if(!empty($_GET['datedebut']) && !empty($_GET['datefin']) && empty($error)):?>
            <a href="reservation.php?id=<?= $vehicule->id ?>&nbjours=<?= $verif_date["nb_jours"] ?>&datedebut=<?= $_GET["datedebut"] ?>&datefin=<?= $_GET["datefin"] ?>">
            <?php endif?>
                <div class="card-body blue-grey-text">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <!--Title-->
                            <h4 class="card-title h1"><?= $vehicule->titre?></h4>
                            <h5><?= $vehicule->marque?> - <?=$vehicule->modele ?></h5>
                            <hr class="mt-4">
                            <!--Text-->
                            <p class="h6 brown-text"><?= $vehicule->description?></p>
                            <p class="h6 brown-text"><?= $vehicule->titre_agence?></p>
                            <hr class="my-3">
                            <h3 class="h2"><strong><?= $vehicule->prix?>€</strong><small class="text-muted text-small"> /jour seulement* !</small></h3>
                            <p class="h5"><small class="text-muted text-small"> *voir condition général de vente</small></p>

                        </div>
                        <div class="col-6">
                        <img class="img-fluid rounded" src="<?= $vehicule->photos ?>">
                        </div>
                    </div>   
                </div>
            <?php if(!empty($_GET['datedebut']) && !empty($_GET['datefin']) && empty($error)):?>
                <div class="mask waves-effect waves-light rgba-black-strong flex-center">
                    <p class="white-text display-4">Réserver</p>
                </div>
            </a>
            <?php endif?>
            
        </div>  
    </div>
    <?php endforeach ?>

    
<?php 
$_SESSION['datedebut'] = $_GET['datedebut'];
$_SESSION['datefin'] = $_GET['datefin'];
$_SESSION['nbjours'] = $verif_date["nb_jours"];

?>
<?php require "req/footer.php";