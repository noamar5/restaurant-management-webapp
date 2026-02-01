<?php // Démarre une session PHP et inclut le fichier connexion à la BDD
    session_start();
    include "pages/connexion.php";

    // On vérifie si l'utilisateur est bien connecté, sinon on le renvoie vers la page pour se connecter
    if (!isset($_SESSION["employe_id"])) {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <title>Accueil</title>
    </head>
    <body>
        
        <div class="sidebar">
            <img class="logo" src="assets/images/Logo-Coquillages.png" alt="Logo">
            <nav class="sidebar-nav"> <!-- Menu dans la sidebar -->
                <ul class="sidebar-ul">
                    <a class="sidebar-lien" href="pages/reservations.php"><li>Réservations</li></a>
                    <a class="sidebar-lien" href="pages/plats.php"><li>Plats</li></a>
                    <a class="sidebar-lien" href="pages/fournisseurs.php"><li>Fournisseurs</li></a>
                    <a class="sidebar-lien deconnexion" href="pages/deconnexion.php"><li>Déconnexion</li></a>
                </ul>
            </nav>
        </div> <!-- Fin de .sidebar -->
        <header class="header">
            <h2><a href="dashboard.php" class="nav-title">La maison du coquillage d'or</a></h2>
        </header> <!-- Fin de .header -->
        <main class="content">
			<div class="home-box">
				<h1>Bienvenue à la Maison du Coquillage d'Or</h1>

				<p>
					Cette interface vous permet de gérer les réservations, les plats
					et les fournisseurs du restaurant.
				</p>

				<h2>Accès rapide :</h2>
				<ul class="home-list">
					<a href="pages/reservations.php"><li>Réservations — consulter ou ajouter une réservation</li></a>
					<a href="pages/plats.php"><li>Plats — gérer les plats proposés à la carte</li></a>
					<a href="pages/fournisseurs.php"><li>Fournisseurs — mettre à jour la liste des fournisseurs</li></a>
				</ul>

				<p>Connecté en tant que <strong><?= $_SESSION["employe_identifiant"] ?></strong></p> <!-- On affiche le role de l'utilisateur -->
			</div> <!-- Fin de .home-box -->
        </main> <!-- Fin de .content -->

        <footer class="footer">
            <p>Créé par Yanel Moisson, Noa Marchionni, Noah Gallou-Salaun et Ilona Leroy.</p>
        </footer> <!-- Fin de .footer -->
    </body>
</html>