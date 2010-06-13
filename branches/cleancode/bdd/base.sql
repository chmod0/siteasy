-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2010 at 03:51 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `Portail`
--
CREATE DATABASE `Portail` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `Portail`;

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `ip` int(9) NOT NULL,
  `nb_action` int(4) NOT NULL,
  `date_debut` int(10) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`ip`, `nb_action`, `date_debut`) VALUES
(2130706433, 2, 1269843604);

-- --------------------------------------------------------

--
-- Table structure for table `billet`
--

DROP TABLE IF EXISTS `billet`;
CREATE TABLE IF NOT EXISTS `billet` (
  `id_billet` int(11) NOT NULL AUTO_INCREMENT,
  `id_categ` int(11) NOT NULL,
  `nom_site` varchar(255) NOT NULL,
  `titre_billet` varchar(255) DEFAULT NULL,
  `auteur_billet` varchar(255) DEFAULT NULL,
  `contenu_billet` text,
  `date_billet` timestamp NULL DEFAULT NULL,
  `image` int(11) NOT NULL,
  PRIMARY KEY (`id_billet`),
  KEY `i_fk_billet_categorie` (`id_categ`),
  KEY `i_fk_billet_site` (`nom_site`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `billet`
--

INSERT INTO `billet` (`id_billet`, `id_categ`, `nom_site`, `titre_billet`, `auteur_billet`, `contenu_billet`, `date_billet`, `image`) VALUES
(1, 0, 'SiteTest', 'semkdhohc', 'test@gmail.com', '<span class=\\"Apple-style-span\\" style=\\"color: rgb(0, 0, 0); font-family: georgia, serif; \\"><h2 style=\\"font: normal normal bold 13px/normal \\''lucida grande\\'', helvetica, arial, sans-serif; padding-top: 0px; padding-right: 0px; padding-bottom: 3px; padding-left: 0px; margin-top: 3px; margin-right: 20px; margin-bottom: -6px; margin-left: 20px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(153, 0, 0); \\"><font class=\\"Apple-style-span\\" color=\\"#FFFFFF\\"><span class=\\"Apple-style-span\\" style=\\"color: rgb(0, 0, 0); font-family: georgia, serif; font-weight: normal; font-size: 12px; \\"><h2 style=\\"font: normal normal bold 13px/normal \\''lucida grande\\'', helvetica, arial, sans-serif; padding-top: 0px; padding-right: 0px; padding-bottom: 3px; padding-left: 0px; margin-top: 3px; margin-right: 20px; margin-bottom: -6px; margin-left: 20px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(153, 0, 0); \\"><font class=\\"Apple-style-span\\" color=\\"#FFFFFF\\">Â» Short Bio</font></h2><p style=\\"margin-left: 35px; margin-right: 30px; margin-top: 10px; \\"><font class=\\"Apple-style-span\\" color=\\"#FFFFFF\\">I\\''m assistant professor at the', '2010-03-23 12:36:35', 0),
(6, 4, 'lolol', 'billetzczz', 'test@gmail.com', 'Contenu', '2010-03-29 07:26:32', 3);

-- --------------------------------------------------------

--
-- Table structure for table `bloc`
--

DROP TABLE IF EXISTS `bloc`;
CREATE TABLE IF NOT EXISTS `bloc` (
  `id_bloc` int(11) NOT NULL AUTO_INCREMENT,
  `titre_bloc` varchar(255) DEFAULT NULL,
  `contenu_bloc` text,
  PRIMARY KEY (`id_bloc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bloc`
--


-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categ` int(11) NOT NULL AUTO_INCREMENT,
  `titre_categ` varchar(255) DEFAULT NULL,
  `libelle_categ` varchar(255) DEFAULT NULL,
  `nom_site` varchar(255) NOT NULL,
  PRIMARY KEY (`id_categ`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id_categ`, `titre_categ`, `libelle_categ`, `nom_site`) VALUES
(2, 'lol', 'lol', 'SiteTest'),
(3, 'Rugby', 'Rugby', 'MonSite'),
(4, 'categ', 'cate', 'lolol');

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_com` int(11) NOT NULL AUTO_INCREMENT,
  `id_billet` int(11) NOT NULL,
  `titre_com` varchar(255) DEFAULT NULL,
  `contenu_com` text,
  `auteur_com` varchar(255) DEFAULT NULL,
  `mail_auteur_com` varchar(255) DEFAULT NULL,
  `date_com` varchar(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id_com`),
  KEY `i_fk_commentaire_billet` (`id_billet`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `commentaire`
--


-- --------------------------------------------------------

--
-- Table structure for table `design`
--

DROP TABLE IF EXISTS `design`;
CREATE TABLE IF NOT EXISTS `design` (
  `id_design` int(11) NOT NULL AUTO_INCREMENT,
  `id_modele` int(11) NOT NULL,
  `libelle_design` varchar(255) DEFAULT NULL,
  `path_design` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_design`),
  KEY `i_fk_design_modele` (`id_modele`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `design`
--

INSERT INTO `design` (`id_design`, `id_modele`, `libelle_design`, `path_design`) VALUES
(1, 1, 'Bleu', '/design/blog/darkblue/'),
(2, 1, 'Blanc', '/design/blog/white/'),
(3, 1, 'Noir', '/design/blog/black/'),
(4, 2, 'Orange', '/design/page/orange/'),
(5, 2, 'Rouge', '/design/page/rouge/'),
(6, 2, 'Style 1', '/design/page/style1/'),
(7, 2, 'Style 2', '/design/page/style2/'),
(8, 2, 'Vert', '/design/page/vert/');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `nom_image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `mail`, `nom_image`) VALUES
(3, 'test@gmail.com', 'MCD.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `image_page`
--

DROP TABLE IF EXISTS `image_page`;
CREATE TABLE IF NOT EXISTS `image_page` (
  `num_page` int(11) NOT NULL,
  `num_image` int(11) NOT NULL,
  PRIMARY KEY (`num_page`,`num_image`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image_page`
--

INSERT INTO `image_page` (`num_page`, `num_image`) VALUES
(171, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lien`
--

DROP TABLE IF EXISTS `lien`;
CREATE TABLE IF NOT EXISTS `lien` (
  `id_lien` int(11) NOT NULL AUTO_INCREMENT,
  `num_page` int(11) NOT NULL,
  `num_page_est_reference_par` int(11) NOT NULL,
  `lien_cible` varchar(255) DEFAULT NULL,
  `lien_source` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_lien`),
  KEY `i_fk_lien_page` (`num_page`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `lien`
--

INSERT INTO `lien` (`id_lien`, `num_page`, `num_page_est_reference_par`, `lien_cible`, `lien_source`) VALUES
(42, 169, 168, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `liste_noire`
--

DROP TABLE IF EXISTS `liste_noire`;
CREATE TABLE IF NOT EXISTS `liste_noire` (
  `ip` int(9) NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `liste_noire`
--


-- --------------------------------------------------------

--
-- Table structure for table `modele`
--

DROP TABLE IF EXISTS `modele`;
CREATE TABLE IF NOT EXISTS `modele` (
  `id_modele` int(11) NOT NULL AUTO_INCREMENT,
  `libelle_modele` varchar(255) DEFAULT NULL,
  `desc_modele` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_modele`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `modele`
--

INSERT INTO `modele` (`id_modele`, `libelle_modele`, `desc_modele`) VALUES
(1, 'Blog', 'Un blog permet l''ajout, la modification et la suppression de billets dat&eacute;s et rang&eacute;s par cat&eacute;gories elles m&ecirc;mes &eacute;ditables.'),
(2, 'Page', 'Une page perso permet l''ajout, la modification et la suppression de pages.');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `num_page` int(11) NOT NULL AUTO_INCREMENT,
  `nom_site` varchar(255) NOT NULL,
  `id_bloc` int(11) NOT NULL,
  `titre_page` varchar(255) DEFAULT NULL,
  `contenu_page` text,
  PRIMARY KEY (`num_page`),
  KEY `i_fk_page_site` (`nom_site`),
  KEY `i_fk_page_bloc` (`id_bloc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=175 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`num_page`, `nom_site`, `id_bloc`, `titre_page`, `contenu_page`) VALUES
(168, 'Test', 0, 'Page1', ' Contenu1'),
(169, 'Test', 0, 'Page2', 'Contenu2'),
(170, 'Test', 0, 'Page3', 'Contenu3'),
(171, 'Test', 0, 'rjhkjgtrdovjfghune nouvelle page', 'hgrjdvhjgtrertyh');

-- --------------------------------------------------------

--
-- Table structure for table `recuperation`
--

DROP TABLE IF EXISTS `recuperation`;
CREATE TABLE IF NOT EXISTS `recuperation` (
  `id` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recuperation`
--


-- --------------------------------------------------------

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
  `nom_site` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `id_modele` int(11) NOT NULL,
  `id_design` int(11) NOT NULL,
  `titre_site` varchar(255) DEFAULT NULL,
  `desc_site` text,
  `mots_cle` varchar(255) DEFAULT NULL,
  `categ_site` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`nom_site`),
  KEY `i_fk_site_utilisateur` (`mail`),
  KEY `i_fk_site_modele` (`id_modele`),
  KEY `i_fk_site_design` (`id_design`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`nom_site`, `mail`, `id_modele`, `id_design`, `titre_site`, `desc_site`, `mots_cle`, `categ_site`) VALUES
('Test', 'test@gmail.com', 2, 8, '', '', ' ', 'aucune'),
('lolol', 'test@gmail.com', 1, 3, '', '', ' ', 'aucune');

-- --------------------------------------------------------

--
-- Table structure for table `sitecateg`
--

DROP TABLE IF EXISTS `sitecateg`;
CREATE TABLE IF NOT EXISTS `sitecateg` (
  `titre_site_categ` varchar(255) NOT NULL,
  `desc_site_categ` varchar(255) NOT NULL,
  PRIMARY KEY (`titre_site_categ`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sitecateg`
--

INSERT INTO `sitecateg` (`titre_site_categ`, `desc_site_categ`) VALUES
('sport', 'tous les sites concernant le sport en général'),
('informatique', 'tous les sites de geek');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `admin` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`mail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`mail`, `password`, `admin`) VALUES
('test@gmail.com', 'dd4dcfbt28ee4872sdadd5e2a46a7c8626e6db4cad2', '1');

