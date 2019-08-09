<?php
require "req/modele.php";
require "req/header.php";
if (is_connected()) {
    header("Location:index.php");
}
$error = null;
$succes = null;

if (isset($_POST["connexion"])) {
    if (!empty($_POST["identifiant"]) && !empty($_POST["mdp"])) {
        $identifiant = trim(htmlspecialchars($_POST["identifiant"]));
        $mdp = hash("sha512",trim(htmlspecialchars($_POST["mdp"])));
        try {
            $requete = "SELECT * FROM membres WHERE pseudo = ? OR email = ? AND mot_de_passe = ?";
            $exec = execRequete($requete,[$identifiant,$identifiant,$mdp]);
            if ($exec->rowCount() == 0) {
                throw new Exception("Combinaison pseudo/mail et mot de passe incorrecte");
            }
            $donnees = $exec->fetch();
            $_SESSION["id"] = $donnees->id;
            $_SESSION["statut"] = $donnees->statut;
            $_SESSION["pseudo"] = $donnees->pseudo;
            $_SESSION["prenom"] = $donnees->prenom;
            $_SESSION["nom"] = $donnees->nom;
            $_SESSION["mail"] = $donnees->email;
            $_SESSION["civilite"] = $donnees->civilite;
            header("Location:index.php");
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Tous les champs doivent être remplis";
    }
}
?>

<div class="container">
    <div class="card mt-4">
        <div class="card-header bg-dark text-center text-white">
            <h1 class="display-4">Connexion</h1>
        </div>
        <?php if (isset($error)): ?>
        <div class="card-header alert text-center alert-danger font-weight-bold">
            <?= $error ?>
        </div>
        <?php endif ?>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group row">
                    <label for="identifiant" class="col-md-5 col-form-label text-md-right font-weight-bold">Pseudo ou Email : </label>
                    <div class="col-md-4">
                        <input class="form-control" type="text" id="identifiant" name="identifiant">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mdp" class="col-md-5 col-form-label text-md-right font-weight-bold">Mot de passe :</label>
                    <div class="col-md-4">
                        <input class="form-control" type="password" id="mdp" name="mdp">
                    </div>
                </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-outline-success btn-lg" name="connexion">
                                Se connecter
                        </button> 
                    </div>            
                </form>
        </div>
    </div>
    <div class="card-footer bg-dark "></div>

</div>
<?php
require "req/footer.php";
?>