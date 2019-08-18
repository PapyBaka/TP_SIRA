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
        unset($_GET['success']);
        if (empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["mail"]) || empty($_POST["pseudo"]) || empty($_POST["statut"]) || empty($_POST["civilite"])) {
            throw new Exception("Tous les champs doivent être remplis");
        }
        /* INSERTION */
        if (empty($_POST["id"])) {
            
            $verif_inscription = verif_inscription($_POST);
            if (empty($verif_inscription["error"])) {
                
                $requete = execRequete("INSERT INTO membres (pseudo,nom,prenom,email,mot_de_passe,civilite,statut) VALUES (?,?,?,?,?,?,?)",$verif_inscription["parametres"]);
                $success = "Membre ajouté avec succès";
            }
        /* MODIFICATION */  
        } else {
            $verif_inscription = verif_inscription($_POST);
            if (empty($verif_inscription["error"])) {
                if (!empty($_POST["mdp"])) {
                    $requete = execRequete("UPDATE membres SET pseudo = ?,nom = ?,prenom = ?,email = ?,mot_de_passe = ?,civilite= ?,statut= ? WHERE id = ?",$verif_inscription["parametres"]);
                } else {
                    $requete = execRequete("UPDATE membres SET pseudo = ?,nom = ?,prenom = ?,email = ?,civilite= ?,statut= ? WHERE id = ?",$verif_inscription["parametres"]);
                }
                $success = "Membre modifié avec succès";
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
            $donnees = execRequete("SELECT pseudo,nom,prenom,email,civilite,statut FROM membres WHERE id = ?",[$_GET["id"]]);
            $info_membre = $donnees->fetch();
        } else if ($_GET["action"] == "delete") {
            $requete = execRequete("DELETE FROM membres WHERE id = ? LIMIT 1",[$_GET["id"]]);
            $success = "Membre supprimé avec succès";
            header("Location:gestion_membres.php?success");
        }
        
    }
/* RECUPERATION DES INFOS DE LA TABLE MEMBRES */
    $donnees = execRequete("SELECT id,pseudo,nom,prenom,email,civilite,statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y - %Hh%i') AS date_enregistrement FROM membres WHERE id <> ?", [$_SESSION['id']]);
    $membres = $donnees->fetchAll();
    $donnees = execRequete("SELECT DISTINCT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'membres' AND COLUMN_NAME NOT IN ('mot_de_passe','type')");
    $colonnes = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<div class="container">
    <div class='table-responsive'>  
         <table class="table text-center">
            <thead class="bg-dark white-text">
                <tr>
                <?php foreach ($colonnes as $colonne): ?>
                    <th><?= $colonne->COLUMN_NAME ?></th>
                    <?php endforeach ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($membres as $membre): ?>
                <tr>
                    <?php foreach ($membre as $info): ?>
                    <td class="font-weight"><?= $info ?></td>
                    <?php endforeach ?>
                    <td><?= afficher_actions($membre) ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($error) || isset($verif_inscription["error"]["global"])): ?>
        <p class="text-danger text-center font-weight-bold mt-4 h6">
            <?= isset ($error) ? $error : ""?>
            <?= isset ($verif_inscription["error"]["global"]) ? $verif_inscription["error"]["global"] : ""?>
        </p>
    <?php endif ?>
    <?php if (!empty($success)): ?>
        <p class="text-success text-center font-weight-bold mt-4 h6">
            <?= $success ?>
        </p>
    <?php endif ?>
    <?php if (isset($_GET["success"])): ?>
        <p class="text-success text-center font-weight-bold mt-4 h6">
            <?php $success = "Membre supprimé avec succès"; ?>
            <?= $success ?>
        </p>
    <?php endif ?>

    <form method="post" action="">
        <input type="hidden" value="<?= isset($info_membre) ? $_GET["id"] : '' ?>" name="id">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["pseudo"]) ? "is-invalid" : "" ?>" id="pseudo" name="pseudo" value="<?= isset($info_membre) ? $info_membre->pseudo : "" ?>">
            <?php if (isset($verif_inscription["error"]["pseudo"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["pseudo"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control <?= isset($verif_inscription["error"]["mdp"]) ? "is-invalid" : "" ?>" id="mdp" name="mdp" placeholder="<?= isset($info_membre) ? "Facultatif" : "" ?>">
            <?php if (isset($verif_inscription["error"]["mdp"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["mdp"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["nom"]) ? "is-invalid" : "" ?>" id="nom" name="nom" value="<?= isset($info_membre) ? $info_membre->nom : "" ?><?= isset($verif_inscription["error"]) ? $_POST["nom"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["nom"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["nom"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control <?= isset($verif_inscription["error"]["prenom"]) ? "is-invalid" : "" ?>" id="prenom" name="prenom" value="<?= isset($info_membre) ? $info_membre->prenom : "" ?><?= isset($verif_inscription["error"]) ? $_POST["prenom"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["prenom"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["prenom"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <label for="mail">Mail</label>
            <input type="email" class="form-control <?= isset($verif_inscription["error"]["mail"]) ? "is-invalid" : "" ?>" id="mail" name="mail" value="<?= isset($info_membre) ? $info_membre->email : "" ?><?= isset($verif_inscription["error"]) ? $_POST["mail"] : "" ?>">
            <?php if (isset($verif_inscription["error"]["mail"])): ?>
                <div class="invalid-feedback">
                    <?= $verif_inscription["error"]["mail"]; ?>
                </div>
            <?php endif ?>
        </div>
        <div class="form-group">
            <select name="civilite" class="form-control">
                <label for="pseudo">Civilité</label>
                <option selected disabled>--- Civilité ---</option>
                <option <?= isset($info_membre) && $info_membre->civilite == "Homme" ? "selected" : "" ?><?= isset($verif_inscription["error"]) && $_POST["civilite"] == "Homme" ? "selected" : "" ?>>Homme </option>
                <option <?= isset($info_membre) && $info_membre->civilite == "Femme" ? "selected" : "" ?><?= isset($verif_inscription["error"]) && $_POST["civilite"] == "Femme" ? "selected" : "" ?>>Femme </option>        
            </select>
        </div>
        <div class="form-group">
            <select name="statut" class="form-control">
                <option selected disabled>--- Statut ---</option>
                <option <?= isset($info_membre) && $info_membre->statut == "Admin" ? "selected" : "" ?><?= isset($verif_inscription["error"]) && $_POST["statut"] == "Admin" ? "selected" : "" ?>>Admin </option>
                <option <?= isset($info_membre) && $info_membre->statut == "Membre" ? "selected" : "" ?><?= isset($verif_inscription["error"]) && $_POST["statut"] == "Membre" ? "selected" : "" ?>>Membre </option>        
            </select>
        </div>
        <div class="row justify-content-center">
                        <a class="btn btn-danger btn-lg" href="gestion_membres.php">Réinitialiser</a>
        <button type="submit" class="btn btn-info btn-lg" name="enregistrer">
                    Enregistrer
            </button> 
        </div>    
    </form>
    </div>

<?php
require "../req/footer.php";