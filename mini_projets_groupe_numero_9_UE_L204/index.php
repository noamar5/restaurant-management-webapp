<?php // Démarre une session PHP et inclut le fichier connexion à la BDD
    session_start();
    include "pages/connexion.php";
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Connexion</title>
        <link rel="stylesheet" href="assets/css/login.css">
    </head>
    <body>
        <div class="login-container">
            <h2>Connexion</h2>

            <form method="POST"> <!-- Formulaire de connexion -->
                <input type="text" name="identifiant" id="identifiant" placeholder="Identifiant" required>
                <input type="password" name="motdepasse" id="motdepasse" placeholder="Mot de passe" required>

                <button type="submit">Se connecter</button>
            </form>
        </div> <!-- Fin de .login-container -->

        <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") { // On vérifie que le formulaire a été envoyé en méthode POST

                $id  = $_POST["identifiant"] ?? ""; // On récupère l'identifiant
                $mdp = $_POST["motdepasse"] ?? ""; // On récupère le mot de passe

                // On crée une requête SQL pour récupérer l'utilisateur grâce à l'identifiant
                $sql = "SELECT id, identifiant, motdepasse, metier
                        FROM employes
                        WHERE identifiant = :identifiant";

                $instruction = $conn->prepare($sql); // On prépare la requête pour éviter les injections SQL
                $instruction->execute([ // On exécute la requête avec le paramètre sécurisé
                    ":identifiant" => $id
                ]);

                $user = $instruction->fetch(PDO::FETCH_ASSOC); // On récupère les données de l'utilisateur sous forme de tableau associatif

                // On vérifie que l'utilisateur existe et que le mot de passe est correct
                if ($user && password_verify($mdp, $user["motdepasse"])) {

                    // Si c'est bon, on stocke les informations utilisateur en session
                    $_SESSION["employe_id"] = $user["id"];
                    $_SESSION["employe_identifiant"] = $user["identifiant"];
                    $_SESSION["employe_metier"] = $user["metier"];

                    header("Location: dashboard.php"); // On redirige vers le dashboard
                    exit; // On stoppe le script
                } else {
                    echo "<p class='error'>Identifiants incorrects</p>"; // Sinon, message d'erreur
                }
            }
        ?>
    </body>
</html>
