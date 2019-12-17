<?php
session_start();
require_once "fonctions.php";

function connect() {
    $pdo = new PDO
				('mysql:host=localhost;dbname=sira;charset=utf8',
				 'root', 'root',
				 [
				 	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				  	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
				  ]
                );
    return $pdo;
}

function execRequete($req, $param = null){
	$query = connect()->prepare($req);
	$query->execute($param);
	return $query;
}

define("RACINE","/TP_SIRA/");