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
    <a data-toggle="modal" data-target="#suppression$infos->id"><i class="material-icons">delete</i></a>
    <!-- Modal -->
    <div class="modal fade" id="suppression$infos->id" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close mr-0" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            
        </div>
        <div class="modal-body">
        <h5 class="modal-title m-auto">Attention !</h5>
            Supprimer une agence effacera tous les véhicules associés
        </div>
        <div class="modal-footer justify-content-center">
            <a href='$lien?id=$infos->id&action=delete' class="btn btn-danger">Supprimer</a>
        </div>
        </div>
    </div>
    </div>
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
        if (empty($_POST["prenom"]) || empty($_POST["nom"]) || empty($_POST["mail"]) || empty($_POST["pseudo"]) || empty($_POST["statut"]) || empty($_POST["civilite"])) {
            throw new Exception("Tous les champs doivent être remplis");
        }
        if (!empty($error)) {
            throw new Exception("Certains champs ne sont pas valides");
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



function verif_agence($infos,$file = null) {
    $error = null;

    /* VERIF FORMAT CHAMPS */
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

    if (isset($infos["ville"])) {
        $ville = $infos["ville"];
        $parametres[] = $ville;
    }

    if (isset($infos["cp"])) {
        $cp = trim(htmlspecialchars($infos["cp"]));
        if (strlen($cp) != 5) {
            $error["cp"] = "Votre cp doit comprendre 5 caractères";
        } else {
            $parametres[] = $cp;
        }
    }

    if (isset($infos["description"])) {
        $description = trim(htmlspecialchars($infos["description"]));
        if (strlen($description) < 10) {
            $error["description"] = "Votre description doit comprendre au moins 10 caractères";
        } else {
            $parametres[] = $description;
        }
    }
    
    if (!empty($file['fichier'])) {
        try {
            // Verifie qu'il n'y a pas d'erreur
            if ($file['fichier']['error'] != 0) {
                throw new Exception("Erreur lors de l'accès au fichier");
            }

            // Verifie la taille du fichier
            if ($file['fichier']['size'] >= 1000000) {
                throw new Exception("Taille du fichier trop importante");
            }
            //extensions autorisées
            $extension_autorisees = ["jpg", "jpeg", "png", "gif"];
            //nom et extension
            $info = pathinfo($file['fichier']['name']);
            //extension de notre fichier
            $extension_uploadee = $info['extension'];
            //on va vérifier l'extension
            if (!in_array($extension_uploadee, $extension_autorisees)) {
                throw new Exception("Extension non autorisée");
            }
            $nom = $file['fichier']['name'];
            move_uploaded_file($file['fichier']['tmp_name'], '../utilities/img/agences/' . $nom);
            $parametres[] = RACINE . 'utilities/img/agences/' . $nom;

        } catch (Exception $e) {
            $error["fichier"] = $e->getMessage();
        }
    }

    if (isset($infos["id"])) {
        $id = $infos["id"];
        $parametres[] = $id;
    }

    /* VERIF ERREURS ET DOUBLONS */
    try {
        if (empty($_POST["titre"]) || empty($_POST["adresse"]) || empty($_POST["ville"]) || empty($_POST["cp"]) || empty($_POST["description"]) || empty($_FILES["fichier"])) {
            throw new Exception("Tous les champs doivent être remplis");
        }
        if (!empty($error)) {
            throw new Exception("Des champs ne sont pas valides");
        }
        if (!isset($id)) {
            if (($execute_titre = execRequete("SELECT titre FROM agences WHERE titre = ?",[$titre])) == false) {
                throw new Exception("Erreur lors de la verification du titre");
            }
        } else {
            if (($execute_titre = execRequete("SELECT titre FROM agences WHERE titre = ? AND id NOT IN (?)",[$titre,$infos['id']])) == false) {
                throw new Exception("Erreur lors de la verification du titre");
            }
        }
        
        if ($execute_titre->rowCount() != 0) {
            $error["titre"] = "Titre déjà existant";
        }

        if (!isset($id)) {
            if (($execute_adresse = execRequete("SELECT adresse FROM agences WHERE adresse = ?",[$adresse])) == false) {
                throw new Exception ("Erreur lors de de la verification de l'adresse");
            }
        } else {
            if (($execute_adresse = execRequete("SELECT adresse FROM agences WHERE adresse = ? AND id NOT IN (?)",[$adresse,$infos['id']])) == false) {
                throw new Exception ("Erreur lors de de la verification de l'adresse");
            }
        }
        
        if ($execute_adresse->rowCount() != 0) {
            $error["adresse"] = "Adresse déjà existante";
        }
    } catch (Exception $e) {
        $error["global"] = $e->getMessage();
    }

    return ["error" => $error, "parametres" => $parametres];
}

/* VERIF VEHICULE */
function verif_vehicule($infos,$file = null) {
    $error = null;

    /* VERIF FORMAT CHAMPS */
    if (isset($infos["titre"])) {
        $titre = trim(htmlspecialchars($infos["titre"]));
        if (strlen($titre) < 5 || strlen($titre) > 30) {
            $error["titre"] = "Votre titre doit comprendre entre 5 et 30 caractères";
        } else {
            $parametres[] = $titre;
        }
    }

    if (isset($infos["marque"])) {
        $marque = trim(htmlspecialchars($infos["marque"]));
            $parametres[] = $marque;
    }

    if (isset($infos["modele"])) {
        $modele = $infos["modele"];
        $parametres[] = $modele;
    }

    if (isset($infos["description"])) {
        $description = trim(htmlspecialchars($infos["description"]));
        if (strlen($description) < 10) {
            $error["description"] = "Votre description doit comprendre au moins 10 caractères";
        } else {
            $parametres[] = $description;
        }
    }

    if (isset($infos["prix"])) {
        $prix = $infos["prix"];
        $parametres[] = $prix;
    }

    if (isset($infos["agence_id"])) {
        $agence_id = $infos["agence_id"];
        $parametres[] = $agence_id;
    }

    
    if (!empty($file['fichier'])) {
        try {
            
            // Verifie qu'il n'y a pas d'erreur
            if ($file['fichier']['error'] != 0) {
                throw new Exception("Erreur lors de l'accès au fichier");
            }

            // Verifie la taille du fichier
            if ($file['fichier']['size'] >= 1000000) {
                throw new Exception("Taille du fichier trop importante");
            }
            //extensions autorisées
            $extension_autorisees = ["jpg", "jpeg", "png", "gif"];
            //nom et extension
            $info = pathinfo($file['fichier']['name']);
            //extension de notre fichier
            $extension_uploadee = $info['extension'];
            //on va vérifier l'extension
            if (!in_array($extension_uploadee, $extension_autorisees)) {
                throw new Exception("Extension non autorisée");
            }
            $nom = $file['fichier']['name'];
            move_uploaded_file($file['fichier']['tmp_name'], '../utilities/img/vehicules/' . $nom);
            $parametres[] = RACINE . 'utilities/img/vehicules/' . $nom;

        } catch (Exception $e) {
            $error["fichier"] = $e->getMessage();
        }
    }

    if (isset($infos["id"])) {
        $id = $infos["id"];
        $parametres[] = $id;
    }

    /* VERIF ERREURS ET DOUBLONS */
    try {
        // Verifie que tous les champs sont remplis
        if (empty($_POST["titre"]) || empty($_POST["marque"]) || empty($_POST["modele"]) || empty($_POST["description"]) || empty($_POST["prix"]) || empty($_POST["agence_id"]) || empty($_FILES["fichier"])) {
            throw new Exception("Tous les champs doivent être remplis");
        }
        // Verifie que tous les champs sont valides
        if (!empty($error)) {
            throw new Exception("Des champs ne sont pas valides");
        }
    } catch (Exception $e) {
        $error["global"] = $e->getMessage();
    }

    return ["error" => $error, "parametres" => $parametres];
}