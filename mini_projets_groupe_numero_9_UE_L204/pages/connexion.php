<?php
    // Adresse du serveur de base de données (souvent localhost en local)
    $host = "localhost";

    // Nom de la base de données à laquelle on souhaite se connecter
    $dbname = "restaurant";

    // Nom d'utilisateur de la base de données
    $user = "root";

    // Mot de passe de la base de données
    $pass = "";

    try {
        // Création de la connexion à la base de données avec PDO
        $conn = new PDO(
            // DSN : type de BDD, hôte, nom de la base et encodage
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $user,
            $pass
        );

        // Configuration de PDO pour afficher les erreurs sous forme d'exceptions
        // Permet de repérer facilement les erreurs SQL
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        // En cas d'erreur de connexion, on arrête le script
        // et on affiche un message d'erreur explicite
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
