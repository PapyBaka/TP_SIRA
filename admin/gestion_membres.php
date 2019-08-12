<?php 
require "../req/modele.php";
require "../req/header.php";
$error = null;
$success = isset($_GET["success"]) ? "Membre supprimé avec succès" : null;

echo "<pre>";
var_dump($_GET);
echo "</pre>";

try {
    if (isset($_POST["enregistrer"])) {
        unset($_POST['enregistrer']);
        unset($_GET);
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
    $donnees = execRequete("SELECT id,pseudo,nom,prenom,email,civilite,statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y - %Hh%i') AS date_enregistrement FROM membres");
    $membres = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
echo "<pre>";
echo "</pre>";
?>

<div class="container">
    
<table class="table text-center table-bordered">
    <thead class="thead-dark">
        <tr>
        <?php foreach ($membres[0] as $k => $info): ?>
            <th><?= $k ?></th>
            <?php endforeach ?>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($membres as $membre): ?>
        <tr>
            <?php foreach ($membre as $info): ?>
            <td><?= $info ?></td>
            <?php endforeach ?>
            <td><?= afficher_actions($membre) ?></td>
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

<form method="post" action="">
    <input type="hidden" value="<?= isset($info_membre) ? $_GET["id"] : '' ?>" <?= isset($info_membre) ? 'name="id"' : '' ?>>
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" class="form-control <?= isset($verif_inscription["error"]["pseudo"]) ? "is-invalid" : "" ?>" id="pseudo" name="pseudo" value="<?= isset($info_membre) ? $info_membre->pseudo : "" ?><?= isset($verif_inscription["error"]) ? $_POST["pseudo"] : "" ?>">
        <?php if (isset($verif_inscription["error"]["pseudo"])): ?>
            <div class="invalid-feedback">
                <?= $verif_inscription["error"]["pseudo"]; ?>
            </div>
        <?php endif ?>
    </div>
    <div class="form-group">
        <label for="nom">Mot de passe</label>
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