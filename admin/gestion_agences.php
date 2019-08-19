<?php 
require "../req/modele.php";
require "../req/header.php";

if ($_SESSION["statut"] !== "Admin"){
    header('Location:'. RACINE .'index.php');
}
 
$error = null;
$success = null;

try {
    if (isset($_POST["enregistrer"])) {
        unset($_POST['enregistrer']);
        unset($_GET["success"]);
        /* INSERTION */
        if (empty($_POST["id"])) {
            echo "PAS D'ID";
            $verif_agence = verif_agence($_POST,$_FILES);
            if (empty($verif_agence["error"])) {
                $requete = execRequete("INSERT INTO agences (titre,adresse,ville,cp,description,photos) VALUES (?,?,?,?,?,?)",$verif_agence["parametres"]);
                $success = "Agence ajoutée avec succès";
            }
        /* MODIFICATION */  
            } else {
                echo "ID SET";
                echo "<pre>";
                var_dump($_FILES);
                echo "</pre>";
                
                if (!empty($_FILES["fichier"]["name"])) {
                    // FICHIER CHOISI
                    $verif_agence = verif_agence($_POST,$_FILES);
                    if (empty($verif_agence["error"])) {
                        $requete = execRequete("UPDATE agences SET titre = ?,adresse = ?,ville = ?,cp = ?,description = ?,photos = ? WHERE id = ?",$verif_agence["parametres"]);
                        $success = "Agence modifiée avec succès";
                    }
                } else {
                    // FICHIER PAS CHOISI
                    $verif_agence = verif_agence($_POST);
                    if (empty($verif_agence["error"])) {
                    $requete = execRequete("UPDATE agences SET titre = ?,adresse = ?,ville = ?,cp = ?,description= ? WHERE id = ?",$verif_agence["parametres"]);
                    $success = "Agence modifiée avec succès";
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
            $donnees = execRequete("SELECT titre,adresse,ville,cp,description,photos FROM agences WHERE id = ?",[$_GET["id"]]);
            $info_agence = $donnees->fetch();
        } else if ($_GET["action"] == "delete") {
            $requete = execRequete("DELETE FROM agences WHERE id = ?",[$_GET["id"]]);
/*             $requete = execRequete("DELETE agences,vehicules FROM agences INNER JOIN vehicules ON agences.id = vehicules.agence_id WHERE agences.id = ?",[$_GET["id"]]);
 */            $success = "Agence supprimée avec succès";
            header("Location:gestion_agences.php?success");
        }
        
    }
    /* RECUPERATION DES INFOS DE LA TABLE AGENCES */
    $donnees = execRequete("SELECT id,titre,adresse,ville,cp,description,photos FROM agences");
    $agences = $donnees->fetchAll();
    $donnees = execRequete("SELECT DISTINCT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'agences'");
    $colonnes = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}


?>
  
<div class="container-fluid">
<table class="table text-center table-bordered col-10 mx-auto">
        <thead class="thead-dark">
            <tr>
            <?php foreach ($colonnes as $colonne): ?>
                
                <th style="<?= $colonne->COLUMN_NAME == 'description' ? 'width:30%;' : '' ?>"><?= $colonne->COLUMN_NAME ?></th>
                <?php endforeach ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="">
            <?php foreach ($agences as $agence): ?>
            <tr>
                <?php foreach ($agence as $k => $info): ?>
                <?php if ($k == "photos"): ?>
                <td class="align-middle"><img class="img-fluid p-0" height="150" width="150" style="object-fit:cover;"  src="<?= $info ?>"></td>
                <?php else: ?>
                <td class="align-middle"><?= $info ?></td>
                <?php endif ?>
                <?php endforeach ?>
                <td class="align-middle"><?= afficher_actions($agence) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>


<div class="container">

    <?php if (isset($error) || isset($verif_agence["error"]["fichier"]) || isset($verif_agence["error"]["global"])): ?>
        <div class="alert alert-danger">
            <?= isset ($error) ? $error . "<br>" : ""?>
            <?= isset ($verif_agence["error"]["fichier"]) ? $verif_agence["error"]["fichier"] . "<br>" : ""?>
            <?= isset ($verif_agence["error"]["global"]) ? $verif_agence["error"]["global"] . "<br>" : ""?>
        </div>
    <?php endif ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif ?>
    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success">
            <?php $success = "Agence supprimée avec succès"; ?>
            <?= $success ?>
        </div>
    <?php endif ?>

    <form method="post" action="" enctype="multipart/form-data">

    <?php if (!empty($_POST["id"]) || !empty($_GET["id"])): ?>
        <?php if (isset($info_agence)): ?>
        <input type="text" value="<?= $_GET["id"] ?>" name="id">
        <?php elseif (isset($verif_agence["error"])): ?>
        <input type="text" value="<?= $_POST["id"] ?>" name="id">
        <?php endif ?>
    <?php endif ?>

        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" class="form-control <?= isset($verif_agence["error"]["titre"]) ? "is-invalid" : "" ?>" id="titre" name="titre" value="<?= isset($info_agence) ? $info_agence->titre : "" ?><?= isset($verif_agence["error"]) ? $_POST["titre"] : "" ?>">
            <?php if (isset($verif_agence["error"]["titre"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_agence["error"]["titre"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <input type="text" class="form-control <?= isset($verif_agence["error"]["adresse"]) ? "is-invalid" : "" ?>" id="adresse" name="adresse" value="<?= isset($info_agence) ? $info_agence->adresse : "" ?><?= isset($verif_agence["error"]) ? $_POST["adresse"] : "" ?>">
            <?php if (isset($verif_agence["error"]["adresse"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_agence["error"]["adresse"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="ville">Ville :</label>
            <input type="text" class="form-control <?= isset($verif_agence["error"]["ville"]) ? "is-invalid" : "" ?>" id="ville" name="ville" value="<?= isset($info_agence) ? $info_agence->ville : "" ?><?= isset($verif_agence["error"]) ? $_POST["ville"] : "" ?>">
            <?php if (isset($verif_agence["error"]["ville"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_agence["error"]["ville"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="cp">CP :</label>
            <input type="number" class="form-control <?= isset($verif_agence["error"]["cp"]) ? "is-invalid" : "" ?>" id="cp" name="cp" value="<?= isset($info_agence) ? $info_agence->cp : "" ?><?= isset($verif_agence["error"]) ? $_POST["cp"] : "" ?>">
            <?php if (isset($verif_agence["error"]["cp"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_agence["error"]["cp"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea type="text" class="form-control <?= isset($verif_agence["error"]["description"]) ? "is-invalid" : "" ?>" id="description" name="description"><?= isset($info_agence) ? $info_agence->description : "" ?><?= isset($verif_agence["error"]) ? $_POST["description"] : "" ?></textarea>
            <?php if (isset($verif_agence["error"]["description"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_agence["error"]["description"]; ?>
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
                        <a class="btn btn-danger btn-lg" href="gestion_agences.php">Réinitialiser</a>
        <button type="submit" class="btn btn-info btn-lg" name="enregistrer">
                    Enregistrer
            </button> 
        </div>    
    </form>
    </div>

<?php
require "../req/footer.php";