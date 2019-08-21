<?php
require_once "req/modele.php";
require 'req/header.php';
$success = null;
$tva = 0.2;
$datedebut = new DateTime($_SESSION["datedebut"]);
$datefin = new DateTime($_SESSION["datefin"]);

if (!isset($_SESSION["id_vehicule"])) {
  $_SESSION["id_vehicule"] = $_GET["id"];
}
if (!is_connected()) {
    header('Location:' . RACINE . 'connexion.php');
}

if (empty($_SESSION["id_vehicule"])) {
    header('Location:' . RACINE . 'index.php');
}

$donnees = execRequete("SELECT id, titre, prix, description, photos, marque, modele
            FROM vehicules
            WHERE vehicules.id = ?", [$_SESSION["id_vehicule"]]);

$vehicule = $donnees->fetch();

$horsTaxe = $vehicule->prix*$_SESSION['nbjours'];
$TTC= $horsTaxe*($tva);
$total = $TTC + $horsTaxe;

$donnees = execRequete("SELECT agences.id, agences.titre, agences.description, agences.adresse, agences.cp, agences.ville, agences.photos
            FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id
            WHERE vehicules.id = ?", [$_SESSION["id_vehicule"]]);
$agence = $donnees->fetch();

if (isset($_SESSION["id_vehicule"]) && isset($_SESSION["datedebut"]) && isset($_SESSION["datefin"]) && isset($_SESSION["nbjours"]) && isset($_GET["confirm"])) {
  $parametres = [$_SESSION["datedebut"],$_SESSION["datefin"],$_SESSION["id_vehicule"],$agence->id,$_SESSION["id"],$total];
  echo "<pre>";
  var_dump($parametres);
  echo "</pre>";
  $exec = execRequete("INSERT INTO reservations VALUES (date_debut,date_fin,id_vehicule,id_agence,id_membre,prix_total)",$parametres);
  $success = "Bravo ! Vous avez bien réservé ce véhicule pour la période du " . $_SESSION["datedebut"] . " au " . $_SESSION["datefin"];
  header("refresh:3;url=index.php");
}
?>

<div class="container">

<!-- DETAILS VEHICULE -->
  <div class="card m-4">
    <div class="view overlay rounded hoverable">

    <h2 class="card-header blue-grey darken-3 text-white text-center">Détails réservation</h2>
            <div class="card-body ">

              <!-- Details vehicule -->

              <div class="row">
                <div class="col">
                <p class="text-center h2">Votre véhicule</p>
                <hr class="my-2">
                </div>
                <div class="col">
                <p class="text-center h2">Votre agence</p>
                <hr class="my-2">
                </div>
              </div>
              

              <div class="row align-items-center m-4 blue-grey-text">
                  <div class="col-3">

                      <!--Title-->

                      <p class="card-title h2"><?=$vehicule->titre?></p>
                      <p class="h4"><?=$vehicule->marque?> - <?=$vehicule->modele?></p>
                      <hr class="mt-4">
                      <!--Text-->
                      <p class="h6 brown-text"><?=$vehicule->description?></p>
                      <hr class="my-3">

                  </div>

                  <div class="col-3">
                    <img class="img-fluid rounded w-100 m-auto" src="<?=$vehicule->photos?>">
                  </div>

                  <div class="col-3">
                    <!--Title-->
                    <p class="card-title h2"><?=$agence->titre?></p>
                    <hr class="mt-4">
                    <!--Text-->
                    <p class="brown-text"><?=$agence->description?><br>
                    <?=$agence->adresse?><br>
                    <?=$agence->cp?><br>
                    <?=$agence->ville?></p>
                    <hr class="my-3">
                  </div>

                  <div class="col-3">
                    <img class="img-fluid rounded w-100 m-auto" src="<?=$agence->photos?>">
                  </div>
                  

              </div>

                <div class="col-12">
                  <table class="table">

                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Date de début</th>
                          <th scope="col">Date de fin</th>
                          <th scope="col">Nb jours</th>
                          <th scope="col">Prix H.T</th>
                          <th scope="col">Total H.T</th>

                        </tr>
                      </thead>

                      <tbody>
                        <tr>
                          <td><?= $_SESSION['datedebut'] ?></th>
                          <td><?= $_SESSION['datefin'] ?></td>
                          <td class="text-center"><?= $_SESSION['nbjours'] ?></td>
                          <td class="text-right"><?=$vehicule->prix?>€</td>
                          <td class="text-right"><?= $horsTaxe = $vehicule->prix*$_SESSION['nbjours']?>€</td>
                        </tr>

                        <tr>
                            <td colspan="4" align="right"><strong>Montant T.V.A : (20%)</strong></td>
                            <td class="text-right"><strong><?=$TTC= $horsTaxe*($tva)?> €</strong></td>
                        </tr>

                        <tr>
                            <td colspan="4" align="right"><strong>TOTAL TTC :</strong></td>
                            <td class="text-right"><strong><?=$TTC + $horsTaxe?> €</strong></td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>  
    </div>
    <div class="card-footer text-center">
      <a class="btn btn-primary btn-lg px-4" href="reservation.php?confirm">Réserver</a>
    </div>
</div>

<?php require "req/footer.php";