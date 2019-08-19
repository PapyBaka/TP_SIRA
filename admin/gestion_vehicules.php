<?php 
require "../req/modele.php";
require "../req/header.php";

if ($_SESSION["statut"] !== "Admin"){
    header('Location:'. RACINE .'index.php');
}
 
$error = null;
$success = null;
$requete = execRequete("SELECT id,titre FROM agences",[]);
$agences = $requete->fetchAll();

try {
    if (isset($_POST["enregistrer"])) {
        unset($_POST['enregistrer']);
        unset($_GET["success"]);
        
        /* INSERTION */
        if (empty($_POST["id"])) {
            $verif_vehicule = verif_vehicule($_POST,$_FILES);
            if (empty($verif_vehicule["error"])) {
                $requete = execRequete("INSERT INTO vehicules (titre,marque,modele,description,prix,agence_id,photos) VALUES (?,?,?,?,?,?,?)",$verif_vehicule["parametres"]);
                $success = "Véhicule ajouté avec succès";
            }
        /* MODIFICATION */  
            } else {
                echo "<pre>";
                var_dump($_FILES);
                echo "</pre>";
                
                if (!empty($_FILES["fichier"]["name"])) {
                    // FICHIER CHOISI
                    $verif_vehicule = verif_vehicule($_POST,$_FILES);
                    if (empty($verif_vehicule["error"])) {
                        $requete = execRequete("UPDATE vehicules SET titre = ?,marque = ?,modele = ?,description = ?,prix = ?,agence_id = ? photos = ? WHERE id = ?",$verif_vehicule["parametres"]);
                        $success = "Véhicule modifié avec succès";
                    }
                } else {
                    // FICHIER PAS CHOISI
                    $verif_vehicule = verif_vehicule($_POST);
                    if (empty($verif_vehicule["error"])) {
                    $requete = execRequete("UPDATE vehicules SET titre = ?,marque = ?,modele = ?,description = ?,prix= ?,agence_id = ? WHERE id = ?",$verif_vehicule["parametres"]);
                    $success = "Véhicule modifié avec succès";
                    }
                }
                
            }
        }       
} catch (Exception $e) {
    $error = $e->getMessage();
}

/* ACTIONS MODIFY DELETE */
try {
    if (isset($_GET["action"]) && isset($_GET["id"])) {
        if ($_GET["action"] == "modify") {
            $donnees = execRequete("SELECT titre,marque,modele,description,prix,agence_id,photos FROM vehicules WHERE id = ?",[$_GET["id"]]);
            $info_vehicule = $donnees->fetch();
        } else if ($_GET["action"] == "delete") {
            $requete = execRequete("DELETE FROM vehicules WHERE id = ? LIMIT 1",[$_GET["id"]]);
            $success = "Véhicule supprimé avec succès";
            header("Location:gestion_vehicules.php?success");
        }
        
    }
    /* RECUPERATION DES INFOS DE LA TABLE vehicules */
    $donnees = execRequete("SELECT id,titre,marque,modele,description,prix,agence_id,photos FROM vehicules");
    $vehicules = $donnees->fetchAll();
    $donnees = execRequete("SELECT DISTINCT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'vehicules'");
    $colonnes = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}


?>
  
<div class="container">
        
    <table class="table text-center table-bordered ">
        <thead class="thead-dark">
            <tr>
            <?php foreach ($colonnes as $colonne): ?>
                <th style="<?= $colonne->COLUMN_NAME == 'description' ? 'width:30%;' : '' ?>"><?= $colonne->COLUMN_NAME ?></th>
                <?php endforeach ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="mr-4">
            <?php foreach ($vehicules as $vehicule): ?>
            <tr >
                <?php foreach ($vehicule as $k => $info): ?>
                <?php if ($k == "photos"): ?>
                <td class="align-middle"><img class="img-fluid p-0" width="150" height="150" src="<?= $info ?>"></td>
                <?php else: ?>
                <td class="align-middle "><?= $info ?></td>
                <?php endif ?>
                <?php endforeach ?>
                <td class="align-middle"><?= afficher_actions($vehicule) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- AFFICHAGE DES ERREURS ET DES SUCCESS -->
    <?php if (isset($error) || isset($verif_vehicule["error"]["fichier"]) || isset($verif_vehicule["error"]["global"])): ?>
        <div class="alert alert-danger">
            <?= isset ($error) ? $error . "<br>" : ""?>
            <?= isset ($verif_vehicule["error"]["fichier"]) ? $verif_vehicule["error"]["fichier"] . "<br>" : ""?>
            <?= isset ($verif_vehicule["error"]["global"]) ? $verif_vehicule["error"]["global"] . "<br>" : ""?>
        </div>
    <?php endif ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif ?>
    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success">
            <?php $success = "Véhicule supprimé avec succès"; ?>
            <?= $success ?>
        </div>
    <?php endif ?>

    <form method="post" action="" enctype="multipart/form-data">

    <?php if (!empty($_POST["id"]) || !empty($_GET["id"])): ?>
        <?php if (isset($info_vehicule)): ?>
        <input type="text" value="<?= $_GET["id"] ?>" name="id">
        <?php elseif (isset($verif_vehicule["error"])): ?>
        <input type="text" value="<?= $_POST["id"] ?>" name="id">
        <?php endif ?>
    <?php endif ?>
    <div class="form-group">
            <div class="form-group">
              <select class="form-control <?= isset($verif_vehicule["error"]["agence_id"]) ? "is-invalid" : "" ?>" name="agence_id" id="agence_id">
              <option disabled selected>------ Choix de l'agence ---------</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= $agence->id ?>"<?= isset($info_vehicule) ? "selected" : "" ?><?= isset($verif_vehicule["error"]) ? "selected" : "" ?>><?= $agence->titre?></option>
                <?php endforeach ?>
              </select>
            </div>
            <?php if (isset($verif_vehicule["error"]["agence_id"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["agence_id"]; ?>
                </div>
            <?php endif ?>
        </div>

        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" class="form-control <?= isset($verif_vehicule["error"]["titre"]) ? "is-invalid" : "" ?>" id="titre" name="titre" value="<?= isset($info_vehicule) ? $info_vehicule->titre : "" ?><?= isset($verif_vehicule["error"]) ? $_POST["titre"] : "" ?>">
            <?php if (isset($verif_vehicule["error"]["titre"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["titre"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="marque">Marque :</label>
            <input type="text" class="form-control <?= isset($verif_vehicule["error"]["marque"]) ? "is-invalid" : "" ?>" id="marque" name="marque" value="<?= isset($info_vehicule) ? $info_vehicule->marque : "" ?><?= isset($verif_vehicule["error"]) ? $_POST["marque"] : "" ?>">
            <?php if (isset($verif_vehicule["error"]["marque"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["marque"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="modele">Modèle :</label>
            <input type="text" class="form-control <?= isset($verif_vehicule["error"]["modele"]) ? "is-invalid" : "" ?>" id="modele" name="modele" value="<?= isset($info_vehicule) ? $info_vehicule->modele : "" ?><?= isset($verif_vehicule["error"]) ? $_POST["modele"] : "" ?>">
            <?php if (isset($verif_vehicule["error"]["modele"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["modele"]; ?>
                </div>
            <?php endif ?>
        </div>
        
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea type="text" class="form-control <?= isset($verif_vehicule["error"]["description"]) ? "is-invalid" : "" ?>" id="description" name="description"><?= isset($info_vehicule) ? $info_vehicule->description : "" ?><?= isset($verif_vehicule["error"]) ? $_POST["description"] : "" ?></textarea>
            <?php if (isset($verif_vehicule["error"]["description"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["description"]; ?>
                </div>
            <?php endif ?>
        </div>

        <div class="form-group">
            <label for="prix">Prix :</label>
            <input type="number" class="form-control <?= isset($verif_vehicule["error"]["prix"]) ? "is-invalid" : "" ?>" id="prix" name="prix" value="<?= isset($info_vehicule) ? $info_vehicule->prix : "" ?><?= isset($verif_vehicule["error"]) ? $_POST["prix"] : "" ?>">
            <?php if (isset($verif_vehicule["error"]["prix"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_vehicule["error"]["prix"]; ?>
                </div>
            <?php endif ?>
        </div>

        <div class="form-group">
            <div class="custom-file">
                <input class="custom-file-input" type="file" name="fichier">
                <label class="custom-file-label">Choisir une photo</label>
            </div>
        </div>
        
        <div class="row justify-content-center">
                        <a class="btn btn-danger btn-lg" href="gestion_vehicules.php">Réinitialiser</a>
        <button type="submit" class="btn btn-info btn-lg" name="enregistrer">
                    Enregistrer
            </button> 
        </div>    
    </form>
    </div>

<?php
require "../req/footer.php";