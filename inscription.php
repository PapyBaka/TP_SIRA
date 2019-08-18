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
            $error["nom"] = "Votre nom ne peut contenir seulement des lettres de l'alphabet";
        } else {
            $parametres[] = $nom;
        }

        $prenom = trim(htmlspecialchars($_POST["prenom"]));
        if (preg_match("/([^A-Za-z])/",$prenom)) {
            $error["prenom"] = "Votre prénom ne peut contenir seulement des lettres de l'alphabet";
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
                throw new Exception("Certains champs ne sont pas valides");
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
                $success = "Inscription réussie. Vous serez redirigé(e) vers la page de connexion dans 5 secondes... Si ce n'est pas le cas, <a href='connexion.php'>cliquez ici</a>";
                header("refresh:5;url=connexion.php"); 
            }
            
        } catch (Exception $e) {
            $error["global"] = $e->getMessage();
        }  
    } else {
        $error["global"] = "Tous les champs doivent être complétés";
    }
}

?>

<div class="container">
    <div class="card mt-4">

        <h4 class="card-header blue-grey darken-1 white-text text-center py-4">
            <strong>Inscription</strong></h4>

        <?php if (isset($error["global"])): ?>
            <p class="text-danger text-center font-weight-bold mt-4 h6">
                <?= $error["global"] ?>
            </p>
        <?php endif ?>

        <?php if (isset($success)): ?>
            <p class="text-success text-center font-weight-bold mt-4 h6">
                <?= $success ?>
            </p>
        <?php endif ?>

        <div class="card-body px-lg-5 pt-0 mt-2">

            <form method="POST" class="text-center" style="color: #757575;" action="">
            

            <div class="form-row">
                <div class="col">
                    <!-- First name -->
                    <div class="md-form">
                        <input type="text" value="<?= !empty($error) ? $_POST["nom"] : "" ?>" class="form-control <?= isset($error["nom"]) ? "is-invalid" : "" ?>" name="nom" id="nom">
                            
                            <?php if (isset($error["nom"])): ?>
                                <div class="invalid-feedback">
                                    <?= $error["nom"]; ?>
                                </div>
                            <?php endif ?>

                        <label for="nom">Nom</label>
                    </div>
                </div>
                <div class="col">
                    <!-- Last name -->
                    <div class="md-form">
                        <input type="text" value="<?= !empty($error) ? $_POST["prenom"] : "" ?>" class="form-control <?= isset($error["prenom"]) ? "is-invalid" : "" ?>" name="prenom" id="prenom">
                            
                            <?php if (isset($error["prenom"])): ?>
                                <div class="invalid-feedback">
                                    <?= $error["prenom"]; ?>
                                </div>
                            <?php endif ?>
                            
                        <label for="prenom">Prénom</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <div class="md-form">
                        <input type="text" value="<?= !empty($error) ? $_POST["pseudo"] : "" ?>" class="form-control <?= isset($error["pseudo"]) ? "is-invalid" : "" ?>" name="pseudo" id="pseudo" maxlength="15">
                                
                                <?php if (isset($error["pseudo"])): ?>
                                    <div class="invalid-feedback">
                                        <?= $error["pseudo"]; ?>
                                    </div>
                                <?php endif ?>

                        <label for="pseudo">Pseudo</label>
                    </div>
               </div>
               <div class="col">
                    <div class="md-form">
                        <input type="email" value="<?= !empty($error) ? $_POST["mail"] : "" ?>" class="form-control <?= isset($error["mail"]) ? "is-invalid" : "" ?>" name="mail" id="mail">
                                
                                <?php if (isset($error["mail"])): ?>
                                    <div class="invalid-feedback">
                                        <?= $error["mail"]; ?>
                                    </div>
                                <?php endif ?>

                        <label for="mail">Email</label>
                    </div>
               </div>
                
            </div>
            <div class="form-row">

               <div class="col">
                    <div class="md-form">
                        <input type="password" class="form-control <?= isset($error["mdp"]) ? "is-invalid" : "" ?>" name="mdp" id="mdp" maxlength="15">
                        <label for="mdp" >Mot de passe</label>
                        <small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                            8 caractères minimum
                        </small>
                            <?php if (isset($error["mdp"])): ?>
                                <div class="invalid-feedback">
                                    <?= $error["mdp"]; ?>
                                </div>
                            <?php endif ?>
                    </div>
                </div>

                <div class="col">
                    <div class="md-form">
                            <select class="browser-default custom-select colorful-select dropdown-default" name="civilite" id="civilite">
                                <option value="" selected disabled>--- Choix du sexe</option>
                                <option >Homme</option>
                                <option >Femme</option>
                            </select>

                     </div>
                </div>

            </div>
                

                
                        
                    </div>
                </div>

                    <div class="row justify-content-center">
                            <button type="submit" class="btn btn-default " name="inscription">
                                S'inscrire
                            </button> 
                    </div>       
            </form>

        </div>

    </div>

</div>

<?php require "req/footer.php" ?>