<?php 
require "req/modele.php";
require "req/header.php";
$error = [];
$success = null;

if (isset($_POST["inscription"])) {
    if (isset($_POST["pseudo"]) && isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["mail"]) && isset($_POST["mdp"]) && !empty($_POST["civilite"])) {

        $pseudo = trim(htmlspecialchars($_POST["pseudo"]));
        if (strlen($pseudo) < 5 || strlen($pseudo) > 15) {
            $error["pseudo"] = "Votre pseudo doit comprendre entre 5 et 15 caractères";
        } else {
            $parametres[] = $pseudo;
        }
        
        $nom = trim(htmlspecialchars($_POST["nom"]));
        if (preg_match("/([^A-Za-z])/",$nom)) {
            $error["nom"] = "Votre nom ne peut contenir que des lettres de l'alphabet";
        } else {
            $parametres[] = $nom;
        }

        $prenom = trim(htmlspecialchars($_POST["prenom"]));
        if (preg_match("/([^A-Za-z])/",$prenom)) {
            $error["prenom"] = "Votre prénom ne peut contenir que des lettres de l'alphabet";
        } else {
            $parametres[] = $prenom;
        }

        $mail = trim(htmlspecialchars($_POST["mail"]));
        if (!filter_var($mail,FILTER_VALIDATE_EMAIL)) {
            $error["mail"] = "Votre mail doit être dans un format valide";
        } else {
            $parametres[] = $mail;
        }

        $mdp = trim(htmlspecialchars($_POST["mdp"]));
        if (strlen($mdp) < 8 || strlen($mdp) > 15) {
            $error["mdp"] = "Votre mot de passe doit comprendre entre 8 et 15 caractères";
        } else {
            $parametres[] = hash("sha512",$mdp);
        }
        $parametres[] = $civilite = $_POST["civilite"];
         try {
            if (count($parametres) != 6) {
                throw new Exception("Des champs ne sont pas valides");
            }
            $requete_pseudo = "SELECT pseudo FROM membres WHERE pseudo = ?";
            $execute_pseudo = execRequete($requete_pseudo,[$pseudo]);
            if ($execute_pseudo->rowCount() != 0) {
                $error["pseudo"] = "Pseudo déjà existant";
            }
            $requete_mail = "SELECT email FROM membres WHERE email = ?";
            $execute_mail = execRequete($requete_mail,[$mail]);
            if ($execute_mail->rowCount() != 0) {
                $error["mail"] = "Mail déjà existant";
            }

            if($execute_pseudo->rowCount() == 0 && $execute_mail->rowCount() == 0){
                $requete = "INSERT INTO membres (pseudo,nom,prenom,email,mot_de_passe,civilite) VALUES (?,?,?,?,?,?)";
                execRequete($requete,$parametres); 
                $success = "Inscription réussie. Vous serez redirigé vers la page de connexion dans 5 secondes. Si ce n'est pas le cas, <a href='connexion.php'>cliquez ici</a>";
                header("refresh:5;url=index.php"); 
            }
            
        } catch (Exception $e) {
            $error["global"] = $e->getMessage();
        }  
    } else {
        $error["global"] = "Tous les champs doivent être completés";
    }
}

?>

<div class="container">
    <div class="card mt-4">

        <div class="card-header bg-dark text-center text-light">
            <h1 class="display-4">Inscription</h1>
        </div>
        <?php if (isset($error["global"])): ?>
        <div class="card-header alert alert-danger text-center font-weight-bold">
            <?= $error["global"] ?>
        </div>
        <?php endif ?>
        <?php if (isset($success)): ?>
        <div class="card-header alert alert-success text-center font-weight-bold">
            <?= $success ?>
        </div>
        <?php endif ?>
        <div class="card-body">
        <form method="post" action="">
            <div class="form-group row">
                    <label for="nom" class="col-md-4 col-form-label text-md-right font-weight-bold">Nom :</label>
                    <div class="col-md-5">
                        <input type="text" value="<?= !empty($error) ? $_POST["nom"] : "" ?>" class="form-control <?= isset($error["nom"]) ? "is-invalid" : "" ?>" name="nom" id="nom" >
                    
                    <?php if (isset($error["nom"])): ?>
                        <div class="invalid-feedback">
                            <?= $error["nom"]; ?>
                        </div>
                    <?php endif ?>
                    </div>
            </div>

            <div class="form-group row">
                    <label for="prenom" class="col-md-4 col-form-label text-md-right font-weight-bold">Prénom :</label>
                    <div class="col-md-5">
                        <input type="text" value="<?= !empty($error) ? $_POST["prenom"] : "" ?>" class="form-control <?= isset($error["prenom"]) ? "is-invalid" : "" ?>" name="prenom" id="prenom">
                        <?php if (isset($error["prenom"])): ?>
                            <div class="invalid-feedback">
                                <?= $error["prenom"]; ?>
                            </div>
                        <?php endif ?>
                    </div>
            </div>
            
            <div class="form-group row">
                <label for="pseudo" class="col-md-4 col-form-label text-md-right font-weight-bold">Pseudo :</label>
                <div class="col-md-5">
                    <input type="text" value="<?= !empty($error) ? $_POST["pseudo"] : "" ?>" class="form-control <?= isset($error["pseudo"]) ? "is-invalid" : "" ?>" name="pseudo" id="pseudo" maxlength="15">
                    <?php if (isset($error["pseudo"])): ?>
                            <div class="invalid-feedback">
                                <?= $error["pseudo"]; ?>
                            </div>
                        <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="mail" class="col-md-4 col-form-label text-md-right font-weight-bold">Mail :</label>
                <div class="col-md-5">
                <input type="email" value="<?= !empty($error) ? $_POST["mail"] : "" ?>" class="form-control <?= isset($error["mail"]) ? "is-invalid" : "" ?>" name="mail" id="mail">
                    <?php if (isset($error["mail"])): ?>
                            <div class="invalid-feedback">
                                <?= $error["mail"]; ?>
                            </div>
                        <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="mdp" class="col-md-4 col-form-label text-md-right font-weight-bold">Mot de passe :</label>
                <div class="col-md-5">
                    <input type="password" class="form-control <?= isset($error["mdp"]) ? "is-invalid" : "" ?>" name="mdp" id="mdp" maxlength="15">
                    <?php if (isset($error["mdp"])): ?>
                            <div class="invalid-feedback">
                                <?= $error["mdp"]; ?>
                            </div>
                        <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="civilite" class="col-md-4 col-form-label text-md-right font-weight-bold"></label>
                <div class="col-md-5">
                    <select class="form-control" name="civilite" id="civilite">
                        <option selected disabled>--- Choix du sexe ---</option>
                        <option >Homme</option>
                        <option >Femme</option>
                    </select>
                </div>
            </div>

                <div class="row justify-content-center">
                        <button type="submit" class="btn btn-info btn-lg" name="inscription">
                            S'inscrire
                        </button> 
                </div>       
        </form>

        </div>
        <div class="card-footer bg-dark "></div>

    </div>

</div>

<?php require "req/footer.php" ?>