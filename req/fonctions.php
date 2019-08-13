<?php
function is_connected() {
    if (isset($_SESSION["id"])) {
        return true;
    } else {
        return false;
    }
}

function deconnexion() {
    if (!empty($_SESSION)) {
        session_destroy();
        header("Location:index.php");
    }
}

function afficher_actions($infos) {
    $lien = $_SERVER['SCRIPT_NAME'];
return <<<HTML
    <a href="$lien?id=$infos->id"><i class="material-icons">search</i></a>
    <a href='$lien?id=$infos->id&action=modify'><i class="material-icons">edit</i></a>
    <a href='$lien?id=$infos->id&action=delete'><i class="material-icons">delete</i></a>
HTML;
}

function verif_inscription($infos) {
    /* VERIF FORMAT ET INSERTION DANS TABLEAU PARAMETRES */
    $error = null;

    /* VERIF MEMBRE */
    if (isset($infos["pseudo"])) {
        $pseudo = trim(htmlspecialchars($infos["pseudo"]));
        if (strlen($pseudo) < 5 || strlen($pseudo) > 15) {
            $error["pseudo"] = "Votre pseudo doit comprendre entre 5 et 15 caractères";
        } else {
            $parametres[] = $pseudo;
        }
    }
    
    if (isset($infos["nom"])) {
        $nom = trim(htmlspecialchars($infos["nom"]));
        if (preg_match("/([^A-Za-z])/",$nom)) {
            $error["nom"] = "Votre nom ne peut contenir que des lettres de l'alphabet";
        } else {
            $parametres[] = $nom;
        }
    }

    if (isset($infos["prenom"])) {
        $prenom = trim(htmlspecialchars($infos["prenom"]));
        if (preg_match("/([^A-Za-z])/",$prenom)) {
            $error["prenom"] = "Votre prénom ne peut contenir que des lettres de l'alphabet";
        } else {
            $parametres[] = $prenom;
        }
    }

    if (isset($infos["mail"])) {
        $mail = trim(htmlspecialchars($infos["mail"]));
        if (!filter_var($mail,FILTER_VALIDATE_EMAIL)) {
            $error["mail"] = "Votre mail doit être dans un format valide";
        } else {
            $parametres[] = $mail;
        }
    }

    if (!empty($infos["mdp"])) {
        $mdp = trim(htmlspecialchars($infos["mdp"]));
        if (strlen($mdp) < 8 || strlen($mdp) > 15) {
            $error["mdp"] = "Votre mot de passe doit comprendre entre 8 et 15 caractères";
        } else {
            $parametres[] = hash("sha512",$mdp);
        }
    } 

    if (isset($infos["civilite"])) {
        $parametres[] = $infos["civilite"];
    }

    if (isset($infos["statut"])) {
    $parametres[] = $infos["statut"];
    }

    if(!empty($infos['id'])){
        $parametres[] = $infos['id'];
    }

    /* VERIF TABLEAU PARAMETRES ET DISPONIBILITE PSEUDO/MAIL */
    try {
        if (!empty($error)) {
            throw new Exception("Des champs ne sont pas valides");
        }
         if (($execute_pseudo = execRequete("SELECT pseudo FROM membres WHERE pseudo = ? AND id NOT IN (?)",[$pseudo,$infos['id']])) == false) {
             throw new Exception("Erreur lors de la verification du pseudo");
         }
        
        if ($execute_pseudo->rowCount() != 0) {
            $error["pseudo"] = "Pseudo déjà existant";
        }
        if (($execute_mail = execRequete("SELECT email FROM membres WHERE email = ? AND id NOT IN (?)",[$mail,$infos['id']])) == false) {
            throw new Exception ("Erreur lors de de la verification du mail");
        }
        if ($execute_mail->rowCount() != 0) {
            $error["mail"] = "Mail déjà existant";
        }
    } catch (Exception $e) {
        $error["global"] = $e->getMessage();
    }

    return ["error" => $error,"parametres" => $parametres];
}

function verif_agence($infos,$file) {
    if (isset($file['fichier'])) {
        if ($file['fichier']['error'] != 0) {
            $error["fichier"] = "Erreur lors de l'accès au fichier";
        }
        if ($file['fichier']['size'] >= 1000000) {
            $error["fichier"] = "Taille du fichier trop importante";
        }
        //extensiosn autorisées
        $extension_autorisees = ["jpg", "jpeg", "png", "gif"];
        //nom et extension
        $info = pathinfo($file['fichier']['name']);
        //extension de notre fichier
        $extension_uploadee = $info['extension'];
        var_dump($info);
        //on va vérifier l'exentsion
        if (!in_array($extension_uploadee, $extension_autorisees)) {
            $error["fichier"] = "Extension non autorisée";
        }
        $nom = $file['fichier']['name'];
        move_uploaded_file($file['fichier']['tmp_name'], RACINE . 'img/' . $nom);
        $parametres[] = RACINE . 'utilities/img/' . $nom;
    }

    if (isset($infos["titre"])) {
        $titre = trim(htmlspecialchars($infos["titre"]));
        if (strlen($titre) < 5 || strlen($titre) > 30) {
            $error["titre"] = "Votre titre doit comprendre entre 5 et 30 caractères";
        } else {
            $parametres[] = $titre;
        }
    }

    if (isset($infos["adresse"])) {
        $adresse = trim(htmlspecialchars($infos["adresse"]));
        if (strlen($adresse) < 5) {
            $error["adresse"] = "Votre adresse doit comprendre au moins 5 caractères";
        } else {
            $parametres[] = $adresse;
        }
    }

    if (isset($infos["cp"])) {
        $cp = trim(htmlspecialchars($infos["cp"]));
        if (strlen($cp) != 5) {
            $error["cp"] = "Votre cp doit comprendre 5 caractères";
        } else {
            $parametres[] = $cp;
        }
    }

    if (isset($infos["ville"])) {
        $ville = $infos["ville"];
        $parametres[] = $ville;
    }

    if (isset($infos["description"])) {
        $description = trim(htmlspecialchars($infos["description"]));
        if (strlen($description) < 10) {
            $error["description"] = "Votre description doit comprendre au moins 10 caractères";
        } else {
            $parametres[] = $description;
        }
    }
}