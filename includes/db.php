<?php
    // Informations de connexion à la base de données
    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'test_idees';

    // Connexion à la base de données
    $conn = new mysqli($host, $username, $password, $database);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Définition du jeu de caractères
    $conn->set_charset("utf8");
?>
