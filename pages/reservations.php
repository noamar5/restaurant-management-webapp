<?php // Démarre une session PHP et inclut le fichier connexion à la BDD
    session_start();
    include "connexion.php";

    // On vérifie si l'utilisateur est bien connecté, sinon on le renvoie vers la page pour se connecter
    if (!isset($_SESSION["employe_id"])) {
        header("Location: ../index.php");
        exit;
    }

    $roles_autorises = ["Admin", "User"]; // On définit les rôles ayant accès à cette page

    if (!in_array($_SESSION["employe_metier"], $roles_autorises)) { // On vérifie si l'utilisateur fait partie de la liste
        header("Location: ../dashboard.php"); // Sinon, on le renvoie au dashboard
        exit;
    }


    // MODIFICATION CAPACITÉ (ADMIN)
    
    $sql = "SELECT capacite FROM nbr_places WHERE id = 1"; // On récupère la capacité maximale depuis la base de données
    $instruction = $conn->prepare($sql);
    $instruction->execute();

    $CAPACITE_MAX = (int) $instruction->fetchColumn(); // On convertit la chaîne de caractères en entier

    $message = "";

    // Si l'utilisateur est Admin et a soumis le formulaire
    if ($_SESSION["employe_metier"] === "Admin" && isset($_POST["modifier_capacite"])) { 
        $nouvelle_capacite = (int) $_POST["capacite"]; // Alors, on récupère la nouvelle capacité

        // On met à jour la BDD
        $sql = "UPDATE nbr_places 
                SET capacite = :capacite 
                WHERE id = 1";
        $instruction = $conn->prepare($sql);
        $instruction->execute([":capacite" => $nouvelle_capacite]);

        $CAPACITE_MAX = $nouvelle_capacite; // On met à jour la variable qui contient la valeur finale
    }


    // AJOUT RÉSERVATION

    if (isset($_POST["ajouter"])) { // Si quelqu'un envoie une nouvelle réservation

        $nom  = $_POST["nom"]; // On récupération des données du formulaire
        $tel  = $_POST["telephone"];
        $date = $_POST["date_reservation"];
        $nb   = (int) $_POST["nb_personnes"];

        // On calcule le nombre de personnes déjà réservées pour la date
        $sql = "SELECT SUM(nb_personnes)
                FROM reservations
                WHERE date_reservation = :date";
        $instruction = $conn->prepare($sql);
        $instruction->execute([":date" => $date]);
        $deja_reserve = (int) $instruction->fetchColumn();

        $places_restantes = $CAPACITE_MAX - $deja_reserve; // On met à jour la variable qui contient la valeur finale

        if ($nb > $places_restantes) { // S'il n'y a plus assez de place, alors
            $message = "<p style='color:red'> 
                Réservation refusée : il reste
                <strong>$places_restantes</strong> place(s)
            </p>"; // Réservation refusée
        } else { // Sinon on ajoute la réservation à la BDD
            $sql = "INSERT INTO reservations (nom_client, telephone, date_reservation, nb_personnes)
                    VALUES (:nom, :tel, :date, :nb)";
            $instruction = $conn->prepare($sql);
            $instruction->execute([
                ":nom" => $nom,
                ":tel" => $tel,
                ":date" => $date,
                ":nb" => $nb
            ]);

            $message = "<p style='color:green'>Réservation ajoutée avec succès</p>"; // Réservation ajoutée
        }
    }


    // SUPPRESSION RÉSERVATION (ADMIN SEULEMENT)

    // Si on clique sur supprimer, alors on supprime la réservation de la BDD
    if (isset($_POST["supprimer_reservation"])) {
        $id = (int) $_POST["supprimer_reservation"];

        $sql = "DELETE FROM reservations WHERE id = :id";
        $instruction = $conn->prepare($sql);
        $instruction->execute([":id" => $id]);
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/style.css">
        <title>Gestion des réservations</title>
    </head>
    <body>
        <div class="container">
            <div class="sidebar">
                <img class="logo" src="../assets/images/Logo-Coquillages.png" alt="Logo">
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
                    <h2><a href="../dashboard.php" class="nav-title">La maison du coquillage d'or</a></h2>
                </header> <!-- Fin de .header -->

                <section class="content">
                    <div class="home-box">

                        <h1>Gestion des réservations</h1>

                        <!-- ADMIN : MODIFIER CAPACITÉ -->
                        <?php // On vérifie si l'utilisateur connecté est Admin
                            if ($_SESSION["employe_metier"] === "Admin"): 
                        ?>
                            <h2>Capacité maximale</h2>

                            <form method="POST"> <!-- Formulaire pour modifier la capacité maximale -->
                                <input type="number" name="capacite" min="1" value="<?= $CAPACITE_MAX ?>" required>
                                <button type="submit" name="modifier_capacite">Mettre à jour</button>
                            </form>
                            <hr>

                        <?php endif; // On met fin au if() ?>

                        <!-- On affiche le message (succès ou erreur) -->
                        <?= $message ?>

                        <!-- AJOUT RÉSERVATION -->
                        <h2>Ajouter une réservation</h2>

                        <form method="POST"> <!-- Formulaire d'ajout de réservation -->
                            <input type="text" name="nom" placeholder="Nom du client" required><br><br>
                            <input type="text" name="telephone" placeholder="Téléphone" required><br><br>
                            <input type="date" name="date_reservation" required><br><br>
                            <input type="number" name="nb_personnes" min="1" required><br><br>
                            <button type="submit" name="ajouter">Ajouter</button>
                        </form>
                        <hr>

                        <!-- PLACES RESTANTES -->
                        <h2>Places restantes par date</h2>

                        <?php
                            // Requête SQL : total des personnes réservées par date
                            $sql = "SELECT date_reservation, SUM(nb_personnes) AS total
                                    FROM reservations
                                    GROUP BY date_reservation
                                    ORDER BY date_reservation";
                            $instruction = $conn->prepare($sql);
                            $instruction->execute();

                            // Récupération des résultats
                            $dates = $instruction->fetchAll(PDO::FETCH_ASSOC); 

                            if (empty($dates)) { // On vérifie s'il existe des réservations
                                echo "<p>Aucune réservation.</p>";
                            } else {
                                echo "<ul>";
                                // Si oui, pour chaque date
                                foreach ($dates as $d) {
                                    $restantes = $CAPACITE_MAX - $d["total"]; // On calcul du nombre de places restantes
                                    echo "<li class='places-item'>
                                        <span class='places-date'><strong>{$d["date_reservation"]}</strong></span>
                                        <span class='places-restantes'>{$restantes} place(s) restante(s)</span>
                                        </li>"; // Et on affiche le résultat
                                }
                                echo "</ul>";
                            }
                        ?>
                        <hr>
                        
                        <!-- LISTE DES RÉSERVATIONS -->
                        <h2>Liste des réservations</h2>

                        <?php // On récupère toutes les réservations dans la BDD, triées par date décroissante
                            $sql = "SELECT * 
                                    FROM reservations 
                                    ORDER BY date_reservation DESC";
                            $instruction = $conn->prepare($sql);
                            $instruction->execute();
                            $reservations = $instruction->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($reservations)) { // On vérifie s'il y a des réservations
                                echo "<p>Aucune réservation trouvée.</p>";
                            } else {
                                echo "<ul>";
                                // Si oui, on affiche chaque réservation
                                foreach ($reservations as $r) {
                                echo "<li>";
                                echo "<strong>" . htmlspecialchars($r["nom_client"]) . "</strong> — ";
                                echo "Téléphone : " . htmlspecialchars($r["telephone"]) . " | ";
                                echo "Date : " . htmlspecialchars($r["date_reservation"]) . " | ";
                                echo "Personnes : " . htmlspecialchars($r["nb_personnes"]);

                                // Bouton supprimer (ADMIN seulement)
                                echo " <form method='POST' style='display:inline'
                                        onsubmit=\"return confirm('Supprimer cette réservation ?')\">";
                                echo "<input type='hidden' name='supprimer_reservation' value='" . (int)$r["id"] . "'>";
                                echo "<button type='submit' style='color:red'>Supprimer</button>";
                                echo "</form>";

                                echo "</li>";
                            }

                                echo "</ul>";
                            }
                        ?>

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