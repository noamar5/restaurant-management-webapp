<?php
    // Démarre la session PHP et inclut la connexion à la base de données
    session_start();
    include "connexion.php";

    // SÉCURITÉ : CONNEXION

    // On vérifie si l'utilisateur est bien connecté
    // Sinon, on le redirige vers la page de connexion
    if (!isset($_SESSION["employe_id"])) {
        header("Location: ../index.php");
        exit;
    }


    // SÉCURITÉ : RÔLES


    // On définit les rôles autorisés à accéder à cette page
    $roles_autorises = ["Admin", "User"];

    // On vérifie si le rôle de l'utilisateur fait partie des rôles autorisés
    if (!in_array($_SESSION["employe_metier"], $roles_autorises)) {
        // Sinon, on le renvoie vers le dashboard
        header("Location: ../dashboard.php");
        exit;
    }


    // AJOUT PLAT (ADMIN SEULEMENT)


    // Si l'utilisateur est Admin ET a soumis le formulaire d'ajout
    if (
        $_SESSION["employe_metier"] === "Admin"
        && isset($_POST["ajouter"])
    ) {
        // Récupération des données du formulaire
        $nom = $_POST["nom"];
        $description = $_POST["description"];
        $prix = $_POST["prix"];
        $allergenes = $_POST["allergenes"];

        // Requête SQL pour insérer un nouveau plat
        $sql = "INSERT INTO plats (nom, description, prix, allergenes)
                VALUES (:nom, :description, :prix, :allergenes)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":nom" => $nom,
            ":description" => $description,
            ":prix" => $prix,
            ":allergenes" => $allergenes
        ]);
    }


    // SUPPRESSION PLAT (ADMIN SEULEMENT)

    // Si l'utilisateur est Admin et a cliqué sur supprimer
    if (
        $_SESSION["employe_metier"] === "Admin"
        && isset($_POST["supprimer_plat"])
    ) {
        // On récupère l'id du plat à supprimer
        $id = (int) $_POST["supprimer_plat"];

        // Requête SQL de suppression
        $sql = "DELETE FROM plats WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([":id" => $id]);
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/style.css">
        <title>Gestion des plats</title>
    </head>
    <body>
        <div class="container">
            <div class="sidebar">
                <!-- Logo du site -->
                <img class="logo" src="../assets/images/Logo-Coquillages.png" alt="Logo">

                <!-- Menu de navigation -->
                <nav class="sidebar-nav">
                    <ul class="sidebar-ul">
                        <a class="sidebar-lien" href="reservations.php"><li>Réservations</li></a>
                        <a class="sidebar-lien" href="plats.php"><li>Plats</li></a>
                        <a class="sidebar-lien" href="fournisseurs.php"><li>Fournisseurs</li></a>
                        <a class="sidebar-lien deconnexion" href="deconnexion.php"><li>Déconnexion</li></a>
                    </ul>
                </nav>
            </div> <!-- Fin de .sidebar -->


            <div class="other">
                <header class="header">
                    <!-- Titre cliquable vers le dashboard -->
                    <h2><a href="../dashboard.php" class="nav-title">La maison du coquillage d'or</a></h2>
                </header> <!-- Fin de .header -->

                <section class="content">
                    <div class="home-box">

                        <h1>Plats</h1>

                        <!-- FORMULAIRE ADMIN : AJOUT PLAT -->

                        <?php // On vérifie si l'utilisateur connecté est Admin
                            if ($_SESSION["employe_metier"] === "Admin"):
                        ?>

                            <h2>Ajouter un plat</h2>

                            <!-- Formulaire d'ajout d'un plat -->
                            <form method="POST">
                                <input type="text" name="nom" placeholder="Nom du plat" required><br><br>
                                <textarea name="description" placeholder="Description" required></textarea><br><br>
                                <input type="number" step="0.01" name="prix" placeholder="Prix (€)" required><br><br>
                                <textarea name="allergenes" placeholder="Allergènes (ex : gluten, crustacés)"></textarea><br><br>
                                <button type="submit" name="ajouter">Ajouter</button>
                            </form>
                            <hr>
                        
                        <?php endif; // Fin de la condition Admin ?>


                        <!-- LISTE DES PLATS -->
                        <h2>Carte des plats</h2>

                        <?php
                            // Requête SQL pour récupérer tous les plats
                            $sql = "SELECT * FROM plats";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Si aucun plat n'existe
                            if (empty($plats)) {
                                echo "<p>Aucun plat.</p>";
                            } else {
                                // Sinon, on affiche le tableau plus ou moins grand suivant le rôle
                                if ($_SESSION["employe_metier"] === "Admin") {
                                    echo "<table border='1' cellpadding='5'>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Prix (€)</th>
                                            <th>Allergènes</th>
                                            <th>Actions</th>
                                        </tr>";;
                                } else {
                                    echo "<table border='1' cellpadding='4'>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Prix (€)</th>
                                            <th>Allergènes</th>
                                        </tr>";
                                }

                                // Pour chaque plat
                                foreach ($plats as $p) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($p["nom"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($p["description"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($p["prix"]) . "</td>";

                                    // Bouton allergènes (Admin + User)
                                    echo "<td>
                                            <form method='POST'>
                                                <input type='hidden' name='voir_allergenes' value='{$p["id"]}'>
                                                <button type='submit'>Voir</button>
                                            </form>
                                        </td>";

                                    // Actions (suppression uniquement pour Admin)
                                    
                                    if ($_SESSION["employe_metier"] === "Admin") {
                                        echo "<td>";
                                        echo "<form method='POST' onsubmit=\"return confirm('Supprimer ce plat ?')\">
                                                <input type='hidden' name='supprimer_plat' value='{$p["id"]}'>
                                                <button type='submit' style='color:red'>Supprimer</button>
                                            </form>";
                                        echo "</td>";
                                    }
                                    

                                    echo "</tr>";
                                }

                                echo "</table>";
                            }
                        ?>


                        <!-- AFFICHAGE DES ALLERGÈNES -->

                        <?php
                            // Si on a cliqué sur "Voir"
                            if (isset($_POST["voir_allergenes"])) {

                                // On récupère l'id du plat
                                $id = $_POST["voir_allergenes"];

                                // Requête pour récupérer les allergènes
                                $sql = "SELECT allergenes FROM plats WHERE id = :id";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([":id" => $id]);
                                $a = $stmt->fetch(PDO::FETCH_ASSOC);

                                // Affichage sécurisé des allergènes
                                echo "<p><strong>Allergènes :</strong> "
                                    . htmlspecialchars($a["allergenes"] ?: "Aucun")
                                    . "</p>";
                            }
                        ?>

                        <!-- Lien de retour -->
                        <p><a href="../dashboard.php">Retour à l'accueil</a></p>

                    </div> <!-- Fin de .home-box -->
                </section> <!-- Fin de .content -->

                <footer class="footer">
                    <p>Créé par Yanel Moisson, Noa Marchionni, Noah Gallou-Salaun et Ilona Leroy.</p>
                </footer> <!-- Fin de .footer -->
            </div> <!-- Fin de .other -->
        </div> <!-- Fin de .container -->
    </body>
</html>