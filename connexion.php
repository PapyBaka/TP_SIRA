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

            <h4 class="card-header blue-grey darken-1 white-text text-center py-4">
            <strong>Connexion</strong></h4>

            <?php if (isset($error)): ?>
                <p class="text-danger text-center font-weight-bold mt-4">
                    <?= $error ?>
                </p>
            <?php endif ?>

            <!--Card content-->
            <div class="card-body px-lg-5 pt-0 mt-2">

            <!-- Form -->
            <form method="POST" class="text-center" style="color: #757575;" action="">

                <!-- Pseudo / email -->
                <div class="md-form">
                    <input type="text" id="materialLoginFormEmail" class="form-control" name="identifiant">
                    <label for="materialLoginFormEmail">E-mail ou pseudo</label>
                </div>

                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="materialLoginFormPassword" class="form-control" name="mdp">
                    <label for="materialLoginFormPassword">Mot de passe</label>
                </div>

              

                <!-- Sign in button -->
                <button class="btn btn-default btn-block my-4 waves-effect z-depth-0" type="submit" name="connexion">Se connecter</button>

                <!-- Register -->
                <p>Pas encore membre ?
                <a href="<?= RACINE."inscription.php"?>">S'inscrire</a>
                </p>

            </form>
            <!-- Form -->

            </div>

            </div>
        </div>
    

</div>

<?php
require "req/footer.php";
?>