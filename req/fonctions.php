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
return <<<HTML
    <a href='gestion_membres.php?id=$infos->id'><i class="material-icons">search</i></a>
    <a href='gestion_membres.php?id=$infos->id&action=modify'><i class="material-icons">edit</i></a>
    <a href='gestion_membres.php?id=$infos->id&action=delete'><i class="material-icons">delete</i></a>
HTML;
}

function verif_inscription($infos) {
    /* VERIF FORMAT ET INSERTION DANS TABLEAU PARAMETRES */
    $error = null;
    $pseudo = trim(htmlspecialchars($infos["pseudo"]));
    
    if (strlen($pseudo) < 5 || strlen($pseudo) > 15) {
        $error["pseudo"] = "Votre pseudo doit comprendre entre 5 et 15 caractères";
    } else {
        $parametres[] = $pseudo;
    }
    
    $nom = trim(htmlspecialchars($infos["nom"]));
    if (preg_match("/([^A-Za-z])/",$nom)) {
        $error["nom"] = "Votre nom ne peut contenir que des lettres de l'alphabet";
    } else {
        $parametres[] = $nom;
    }

    $prenom = trim(htmlspecialchars($infos["prenom"]));
    if (preg_match("/([^A-Za-z])/",$prenom)) {
        $error["prenom"] = "Votre prénom ne peut contenir que des lettres de l'alphabet";
    } else {
        $parametres[] = $prenom;
    }

    $mail = trim(htmlspecialchars($infos["mail"]));
    if (!filter_var($mail,FILTER_VALIDATE_EMAIL)) {
        $error["mail"] = "Votre mail doit être dans un format valide";
    } else {
        $parametres[] = $mail;
    }

    if (isset($infos["mdp"])) {
        $mdp = trim(htmlspecialchars($infos["mdp"]));
        if (strlen($mdp) < 8 || strlen($mdp) > 15) {
            $error["mdp"] = "Votre mot de passe doit comprendre entre 8 et 15 caractères";
        } else {
            $parametres[] = hash("sha512",$mdp);
        }
    }  
    $parametres[] = $infos["civilite"];
    $parametres[] = $infos["statut"];

    if(!empty($infos['id'])){
        $parametres[] = $infos['id'];
    }

    /* VERIF TABLEAU PARAMETRES ET DISPONIBILITE PSEUDO/MAIL */
    try {
        if (count($parametres) != count($infos)) {
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
    } catch (Exception $e) {
        $error["global"] = $e->getMessage();
    }
    

    return ["error" => $error,"parametres" => $parametres];
}