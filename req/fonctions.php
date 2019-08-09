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
    echo "chercher";
    echo "modifier";
    echo "supprimer";
}