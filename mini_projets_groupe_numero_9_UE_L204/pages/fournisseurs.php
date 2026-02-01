<?php
    // Démarre la session PHP et inclut le fichier de connexion à la base de données
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


    // AJOUT FOURNISSEUR (ADMIN SEULEMENT)

    // Si l'utilisateur est Admin et a soumis le formulaire d'ajout
    if (
        $_SESSION["employe_metier"] === "Admin"
        && isset($_POST["ajouter_fournisseur"])
    ) {
        // Récupération des données du formulaire
        $nom = $_POST["nom"];
        $produit = $_POST["produit"];
        $adresse = $_POST["adresse"];
        $telephone = $_POST["telephone"];
        $date_contrat = $_POST["date_contrat"];

        // Requête SQL pour insérer un nouveau fournisseur
        $sql = "INSERT INTO fournisseurs (nom, produit, adresse, telephone, date_contrat)
                VALUES (:nom, :produit, :adresse, :telephone, :date_contrat)";

        // Préparation et exécution de la requête
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":nom" => $nom,
            ":produit" => $produit,
            ":adresse" => $adresse,
            ":telephone" => $telephone,
            ":date_contrat" => $date_contrat
        ]);
    }


    // SUPPRESSION FOURNISSEUR (ADMIN SEULEMENT)

    // Si l'utilisateur est Admin et a cliqué sur supprimer
    if (
        $_SESSION["employe_metier"] === "Admin"
        && isset($_POST["supprimer_fournisseur"])
    ) {
        // On récupère l'identifiant du fournisseur à supprimer
        $id = (int) $_POST["supprimer_fournisseur"];

        // Requête SQL de suppression du fournisseur
        $sql = "DELETE FROM fournisseurs WHERE id = :id";
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
        <title>Gestion des fournisseurs</title>
    </head>
    <body>
        <div class="container">
            <div class="sidebar">
                <!-- Logo du site -->
                <img class="logo" src="../assets/images/Logo-Coquillages.png" alt="Logo">

                <!-- Menu de navigation latéral -->
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
                    <!-- Titre cliquable vers le tableau de bord -->
                    <h2><a href="../dashboard.php" class="nav-title">La maison du coquillage d'or</a></h2>
                </header> <!-- Fin de .header -->

                <section class="content">
                    <div class="home-box">
                        <!-- Titre principal de la page -->
                        <h1>Fournisseurs</h1>

                        <!-- FORMULAIRE ADMIN : AJOUT FOURNISSEUR -->
                        <?php // On vérifie si l'utilisateur connecté est Admin
                            if ($_SESSION["employe_metier"] === "Admin"):
                        ?>
                            <h2>Ajouter un fournisseur</h2>

                            <form method="POST"> <!-- Formulaire d'ajout d'un fournisseur -->
                                <input type="text" name="nom" placeholder="Nom du fournisseur" required><br><br>
                                <input type="text" name="produit" placeholder="Produit fourni" required><br><br>
                                <input type="text" name="adresse" placeholder="Adresse" required><br><br>
                                <input type="text" name="telephone" placeholder="Téléphone" required><br><br>
                                <input type="date" name="date_contrat" required><br><br>
                                <button type="submit" name="ajouter_fournisseur">Ajouter</button>
                            </form>
                            <hr>

                        <?php endif; // Fin de la condition Admin ?>


                        <!-- LISTE DES FOURNISSEURS -->
                        <h2>Liste des fournisseurs</h2>

                        <?php
                            // Requête SQL pour récupérer tous les fournisseurs
                            $sql = "SELECT * FROM fournisseurs";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $fournisseurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Si aucun fournisseur n'existe
                            if (empty($fournisseurs)) {
                                echo "<p>Aucun fournisseur trouvé.</p>";
                            } else {
                                echo "<ul>";

                                // Pour chaque fournisseur
                                foreach ($fournisseurs as $f) {
                                    echo "<li class='list-row'>";

                                    // Nom du fournisseur
                                    echo "<div class='col col-name'>
                                            <strong>" . htmlspecialchars($f["nom"]) . "</strong>
                                        </div>";

                                    // Produit fourni
                                    echo "<div class='col col-produit'>
                                            " . htmlspecialchars($f["produit"]) . "
                                        </div>";

                                    // Actions disponibles
                                    echo "<div class='col col-actions'>";

                                    // Bouton pour voir les coordonnées (Admin + User)
                                    echo "<form method='POST'>";
                                        echo "<input type='hidden' name='voir_coordonnees' value='{$f["id"]}'>";
                                        echo "<button type='submit' class='btn-secondary'>Voir coordonnées</button>";
                                    echo "</form>";

                                    // Bouton supprimer visible uniquement pour les Admins
                                    if ($_SESSION["employe_metier"] === "Admin") {
                                        echo "<form method='POST'
                                                onsubmit=\"return confirm('Supprimer ce fournisseur ?')\">";
                                            echo "<input type='hidden' name='supprimer_fournisseur' value='{$f["id"]}'>";
                                            echo "<button type='submit' class='btn-danger'>Supprimer</button>";
                                        echo "</form>";
                                    }
                                    echo "</div>";
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }
                        ?>


                        <!-- AFFICHAGE DES COORDONNÉES DU FOURNISSEUR -->

                        <?php
                            // Si on a cliqué sur "Voir coordonnées"
                            if (isset($_POST["voir_coordonnees"])) {

                                // On récupère l'id du fournisseur
                                $id = $_POST["voir_coordonnees"];

                                // Requête SQL pour récupérer les coordonnées
                                $sql = "SELECT adresse, telephone, date_contrat
                                        FROM fournisseurs
                                        WHERE id = :id";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute([":id" => $id]);
                                $c = $stmt->fetch(PDO::FETCH_ASSOC);

                                // Affichage sécurisé des coordonnées
                                echo "<p><strong>Adresse :</strong> " . htmlspecialchars($c["adresse"]) . "<br>";
                                echo "<strong>Téléphone :</strong> " . htmlspecialchars($c["telephone"]) . "<br>";
                                echo "<strong>Date de contrat :</strong> " . htmlspecialchars($c["date_contrat"]) . "</p>";
                            }
                        ?>

                        <!-- Lien de retour vers le tableau de bord -->
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
