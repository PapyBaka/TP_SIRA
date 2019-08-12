<?php 
require "../req/modele.php";
require "../req/header.php";

if ($_SESSION["statut"] !== "Admin"){
    header('Location:'. RACINE .'index.php');
}
 
$error = null;
$success = isset($_GET["success"]) ? "Agence supprimée avec succès" : null;

echo "<pre>";
var_dump($_POST);
var_dump($_FILES);
echo "</pre>";

try {
    if (isset($_POST["enregistrer"])) {
        unset($_POST['enregistrer']);
        unset($_GET);
        if (empty($_POST["titre"]) || empty($_POST["adresse"]) || empty($_POST["ville"]) || empty($_POST["cp"]) || empty($_POST["description"]) || empty($_FILES["fichier"])) {
            throw new Exception("Tous les champs doivent être remplis");
        }
        /* INSERTION */
        if (empty($_POST["id"])) {
            
            $verif_inscription = verif_inscription($_POST,$_FILES);
            if (empty($verif_inscription["error"])) {
                
                $requete = execRequete("INSERT INTO agences (titre,adresse,ville,cp,description,photos) VALUES (?,?,?,?,?,?)",$verif_inscription["parametres"]);
                $success = "Agence ajoutée avec succès";
            }
        /* MODIFICATION */  
        } else {
            $verif_inscription = verif_inscription($_POST,$_FILES);
            if (empty($verif_inscription["error"])) {
                if (!empty($_POST["file"])) {
                    $requete = execRequete("UPDATE agences SET titre = ?,adresse = ?,ville = ?,cp = ?,description = ?,photos = ? WHERE id = ?",$verif_inscription["parametres"]);
                } else {
                    $requete = execRequete("UPDATE agences SET titre = ?,adresse = ?,ville = ?,cp = ?,description= ? WHERE id = ?",$verif_inscription["parametres"]);
                }
                $success = "Agence modifiée avec succès";
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
            $donnees = execRequete("SELECT titre,adresse,ville,cp,description FROM agences WHERE id = ?",[$_GET["id"]]);
            $info_membre = $donnees->fetch();
        } else if ($_GET["action"] == "delete") {
            $requete = execRequete("DELETE FROM agences WHERE id = ? LIMIT 1",[$_GET["id"]]);
            $success = "Agence supprimée avec succès";
            header("Location:gestion_agences.php?success");
        }
        
    }
    $donnees = execRequete("SELECT id,titre,adresse,ville,cp,description,photos FROM agences");
    $agences = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
echo "<pre>";
var_dump($agences);
echo "</pre>";
?>
  
<div class="container">
        
    <table class="table text-center table-bordered ">
        <thead class="thead-dark">
            <tr>
            <?php foreach ($agences[0] as $k => $info): ?>
                <th><?= $k ?></th>
                <?php endforeach ?>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="">
            <?php foreach ($agences as $agence): ?>
            <tr>
                <?php foreach ($agence as $k => $info): ?>
                <?php if ($k == "photos"): ?>
                <td class="align-middle"><img class="img-fluid p-0" width="150" height="150" src="<?= $info ?>"></td>
                <?php else: ?>
                <td class="align-middle"><?= $info ?></td>
                <?php endif ?>
                <?php endforeach ?>
                <td class="align-middle"><?= afficher_actions($agence) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <?php if (isset($error) || isset($verif_inscription["error"]["global"])): ?>
        <div class="alert alert-danger">
            <?= isset ($error) ? $error : ""?>
            <?= isset ($verif_inscription["error"]["global"]) ? $verif_inscription["error"]["global"] : ""?>
        </div>
    <?php endif ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif ?>

    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" value="<?= isset($info_membre) ? $_GET["id"] : '' ?>" <?= isset($info_membre) ? 'name="id"' : '' ?>>
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["titre"]) ? "is-invalid" : "" ?>" id="titre" name="titre" value="<?= isset($info_membre) ? $info_membre->titre : "" ?><?= isset($verif_inscription["error"]) ? $_POST["titre"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["titre"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["titre"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["adresse"]) ? "is-invalid" : "" ?>" id="adresse" name="adresse" placeholder="<?= isset($info_membre) ? "Facultatif" : "" ?>">
            <?php if (isset($verif_inscription["error"]["adresse"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["adresse"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="ville">Ville :</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["ville"]) ? "is-invalid" : "" ?>" id="ville" name="ville" value="<?= isset($info_membre) ? $info_membre->ville : "" ?><?= isset($verif_inscription["error"]) ? $_POST["ville"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["ville"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["ville"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="cp">CP :</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["cp"]) ? "is-invalid" : "" ?>" id="cp" name="cp" value="<?= isset($info_membre) ? $info_membre->cp : "" ?><?= isset($verif_inscription["error"]) ? $_POST["cp"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["cp"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["cp"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea type="text" class="form-control <?= isset($verif_inscription["error"]["description"]) ? "is-invalid" : "" ?>" id="description" name="description" value="<?= isset($info_membre) ? $info_membre->edescription : "" ?><?= isset($verif_inscription["error"]) ? $_POST["description"] : "" ?>"></textarea>
            <?php if (isset($verif_inscription["error"]["description"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["description"]; ?>
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