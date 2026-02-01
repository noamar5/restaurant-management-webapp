-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 14 déc. 2025 à 20:51
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `restaurant`
--

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

DROP TABLE IF EXISTS `employes`;
CREATE TABLE IF NOT EXISTS `employes` (
  `id` int NOT NULL,
  `identifiant` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `motdepasse` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `metier` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifiant` (`identifiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id`, `identifiant`, `motdepasse`, `metier`) VALUES
(0, 'Utilisateur', '$2y$10$YFXym27VatJJ5uVal//QyOTYWjsWLlhAU8yPkKMwaTQS.pA9Z6vIy', 'User'),
(1, 'Administrateur', '$2y$10$WoUoz9stI8LffrQFv8MyxORNvrJdbf68gvOXrNp0aGZtMR/b3kBMe', 'Admin');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `produit` varchar(150) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `date_contrat` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `nom`, `produit`, `adresse`, `telephone`, `date_contrat`) VALUES
(1, 'Pêcheries de Bretagne', 'Moules fraîches', 'Port de Brest, 29200 Brest', '02 98 00 00 01', '2022-03-15'),
(2, 'Océan Frais Brest', 'Crabes bretons', 'Quai de la Douane, 29200 Brest', '02 98 00 00 02', '2021-06-01'),
(3, 'Armor Langoustines', 'Langoustines vivantes', 'Zone portuaire, 56100 Lorient', '02 97 00 00 03', '2020-09-10'),
(4, 'La Criée Finistérienne', 'Homards', 'Port de Concarneau, 29900 Concarneau', '02 98 00 00 04', '2019-01-20'),
(5, 'Bigorneaux du Conquet', 'Bigorneaux', 'Le Conquet, 29217', '02 98 00 00 05', '2023-04-05'),
(6, 'Marée d’Iroise', 'Palourdes et coques', 'Port de Roscoff, 29680 Roscoff', '02 98 00 00 06', '2022-11-18'),
(7, 'Poissonnerie Saint-Louis', 'Bars et dorades', 'Saint-Louis, 29200 Brest', '02 98 00 00 07', '2020-02-28'),
(8, 'Atlantique Fruits de Mer', 'Crevettes roses', 'Zone maritime, 44000 Nantes', '02 40 00 00 08', '2018-07-12'),
(9, 'Brest Marée Service', 'Huîtres bretonnes', 'Port de commerce, 29200 Brest', '02 98 00 00 09', '2021-10-30'),
(11, 'Marée d’Armor', 'Coquilles Saint-Jacques', 'Port de Saint-Quay, 22410 Saint-Quay-Portrieux', '02 96 70 00 10', '2021-05-18'),
(12, 'Poissonnerie de Cornouaille', 'Soles et turbots', 'Marché central, 29000 Quimper', '02 98 55 00 11', '2020-09-02'),
(13, 'Viviers de l’Iroise', 'Homards vivants', 'Port de Lanildut, 29840 Lanildut', '02 98 04 00 12', '2019-03-14'),
(14, 'Océan Nord Atlantique', 'Saumons frais', 'Zone portuaire, 56100 Lorient', '02 97 83 00 13', '2022-06-01'),
(15, 'Côte Sauvage Pêche', 'Bars sauvages', 'Port du Croisic, 44490 Le Croisic', '02 40 23 00 14', '2021-11-09'),
(16, 'Maison Le Guen', 'Bulots et bigorneaux', 'Port de Douarnenez, 29100 Douarnenez', '02 98 92 00 15', '2018-02-20'),
(17, 'Ker Breizh Produits de la Mer', 'Palourdes', 'Zone conchylicole, 56470 La Trinité-sur-Mer', '02 97 55 00 16', '2023-01-10'),
(18, 'Pêche Artisanale Bretonne', 'Maquereaux et sardines', 'Port de Saint-Guénolé, 29760 Penmarc’h', '02 98 58 00 17', '2020-07-27'),
(19, 'Les Huîtres de Penfoul', 'Huîtres creuses', 'Presqu’île de Rhuys, 56370 Sarzeau', '02 97 41 00 18', '2019-12-05'),
(20, 'Armement Marin du Ponant', 'Langoustes', 'Port de commerce, 29200 Brest', '02 98 44 00 19', '2017-04-22'),
(21, 'Cap Marine Distribution', 'Produits de la mer surgelés', 'Zone industrielle maritime, 76600 Le Havre', '02 35 19 00 20', '2022-08-30');

-- --------------------------------------------------------

--
-- Structure de la table `nbr_places`
--

DROP TABLE IF EXISTS `nbr_places`;
CREATE TABLE IF NOT EXISTS `nbr_places` (
  `id` int NOT NULL,
  `capacite` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `nbr_places`
--

INSERT INTO `nbr_places` (`id`, `capacite`) VALUES
(1, 120);

-- --------------------------------------------------------

--
-- Structure de la table `plats`
--

DROP TABLE IF EXISTS `plats`;
CREATE TABLE IF NOT EXISTS `plats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(6,2) NOT NULL,
  `allergenes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `plats`
--

INSERT INTO `plats` (`id`, `nom`, `description`, `prix`, `allergenes`) VALUES
(1, 'Moules Frites', 'Moules fraîches de Bretagne servies avec frites maison', 14.50, 'Mollusques, Crustacés'),
(2, 'Crabe Farci', 'Crabe breton farci aux herbes et citron', 18.00, 'Crustacés'),
(3, 'Langoustines Grillées', 'Langoustines fraîches grillées au beurre persillé', 24.00, 'Crustacés, Lait'),
(4, 'Homard Bleu Breton', 'Homard breton grillé accompagné de légumes', 38.00, 'Crustacés'),
(5, 'Bigorneaux', 'Bigorneaux servis avec aïoli maison', 9.50, 'Mollusques'),
(6, 'Plateau de Fruits de Mer', 'Huîtres, crevettes, crabes, moules et langoustines', 32.00, 'Mollusques, Crustacés'),
(7, 'Bar Grillé', 'Filet de bar grillé sauce citronnée', 22.00, 'Poissons'),
(8, 'Dorade Royale', 'Dorade entière rôtie au four aux herbes', 21.00, 'Poissons'),
(9, 'Huîtres de Bretagne (12)', 'Huîtres fraîches du Finistère', 15.00, 'Poissons, Gluten'),
(10, 'Soupe de Poisson Maison', 'Soupe de poisson bretonne, rouille, croûtons', 11.00, 'Poissons, Gluten'),
(13, 'Saint-Jacques poêlées', 'Noix de Saint-Jacques poêlées, fondue de poireaux et beurre blanc', 26.50, 'Mollusques, Lait'),
(14, 'Soupe de la mer', 'Soupe de poissons et crustacés, rouille maison', 13.00, 'Poissons, Crustacés, Gluten'),
(15, 'Filet de sole meunière', 'Filet de sole meunière au beurre citronné', 29.00, 'Poissons, Lait'),
(16, 'Risotto aux fruits de mer', 'Risotto crémeux aux moules, crevettes et calamars', 24.50, 'Crustacés, Mollusques, Lait'),
(17, 'Poulpe grillé', 'Poulpe grillé, pommes de terre rôties et sauce vierge', 27.00, 'Mollusques'),
(18, 'Bouillabaisse bretonne', 'Assortiment de poissons de roche et fruits de mer', 34.00, 'Poissons, Crustacés, Mollusques'),
(19, 'Tartare de saumon', 'Tartare de saumon frais, citron vert et ciboulette', 21.00, 'Poissons'),
(20, 'Encornets à l’armoricaine', 'Encornets mijotés dans une sauce tomate au vin blanc', 22.50, 'Mollusques, Sulfites'),
(21, 'Cabillaud rôti', 'Dos de cabillaud rôti, purée maison et jus réduit', 25.00, 'Poissons'),
(22, 'Plateau de fruits de mer royal', 'Huîtres, crevettes, langoustines, crabes et bulots', 45.00, 'Crustacés, Mollusques');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `nb_personnes` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `nom_client`, `telephone`, `date_reservation`, `nb_personnes`) VALUES
(32, 'Laura Perez', '0623456789', '2025-12-16', 7),
(9, 'Martin Dupont', '0601020304', '2025-01-10', 4),
(10, 'Claire Legrand', '0611223344', '2025-01-10', 2),
(11, 'Paul Bernard', '0622334455', '2025-01-10', 6),
(12, 'Sophie Moreau', '0633445566', '2025-01-10', 4),
(14, 'Lucie Fontaine', '0655667788', '2025-01-11', 2),
(15, 'Antoine Renaud', '0666778899', '2025-01-11', 4),
(16, 'Camille Girard', '0677889900', '2025-01-11', 6),
(17, 'Thomas Leroy', '0688990011', '2025-01-11', 4),
(18, 'Emma Roux', '0699001122', '2025-01-11', 8),
(19, 'Nicolas Chevalier', '0612345678', '2025-01-12', 5),
(20, 'Laura Perez', '0623456789', '2025-01-12', 4),
(21, 'Hugo Lambert', '0634567890', '2025-01-12', 2),
(22, 'Manon Colin', '0645678901', '2025-01-12', 6),
(23, 'Adrien Millet', '0656789012', '2025-01-12', 4),
(24, 'Sarah Nguyen', '0667890123', '2025-01-13', 3),
(25, 'Kevin Marchand', '0678901234', '2025-01-13', 4),
(26, 'Julie Besson', '0689012345', '2025-01-13', 2),
(27, 'Maxime Robert', '0690123456', '2025-01-13', 6),
(28, 'Chloé Simon', '0609876543', '2025-01-13', 4),
(31, 'Nathan plarie', '0628216748', '2025-12-30', 20);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
