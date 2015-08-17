SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE `evenement` ADD `evenement_zDateHeureSaisie` DATETIME NULL AFTER `evenement_zDateHeureDebut` ; 
UPDATE evenement SET evenement.evenement_zDateHeureSaisie = evenement.evenement_zDateHeureDebut; 

ALTER TABLE `clients` CHANGE `client_zNom` `client_zNom` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `client_zPrenom` `client_zPrenom` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `client_zFonction` `client_zFonction` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `client_zMail` `client_zMail` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `client_zLogin` `client_zLogin` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `client_zPass` `client_zPass` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `client_zTel` `client_zTel` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `client_zRue` `client_zRue` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE `client_zVille` `client_zVille` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;

ALTER TABLE `clients` CHANGE `client_iNumIndividu` `client_iNumIndividu` VARCHAR( 80 ) NULL DEFAULT NULL ;
/*************15/09/2011************/
ALTER TABLE clients CHANGE client_iUtilisateurCreateurId client_iUtilisateurCreateurId INT(11) NULL ;
/*************15/09/2011************/
/*************20/09/2011************/
ALTER TABLE `evenement` ADD `evenement_origine` INT(11) DEFAULT '0' NULL ;
UPDATE evenement SET evenement_origine = 2 WHERE evenement_origine = 0 ; 
/*************20/09/2011************/
/*************01/10/2012************/
ALTER TABLE `clients` ADD `client_iRefIndividu` VARCHAR( 80 ) NULL AFTER `client_iNumIndividu` ;
ALTER TABLE `clients` CHANGE `client_iSociete` `client_iSociete` INT( 11 ) NULL ;
/*************01/10/2012************/
/*************10/10/2012************/
ALTER TABLE `clients` ADD `client_dateCreation` DATETIME NULL DEFAULT NULL ,
ADD `client_dateMaj` DATETIME NULL DEFAULT NULL ;
ALTER TABLE `societe` CHANGE `CodeTiers` `CodeTiers` INT( 6 ) NULL ;
/*************10/10/2012************/

/*************16/01/2012************/
CREATE TABLE IF NOT EXISTS `etatevenement` (
  `etat_id` INT(11) NOT NULL AUTO_INCREMENT,
  `etat_iEvenementId` INT(11) NOT NULL,
  `etat_iTypeEtatId` INT(11) NOT NULL,
  `etat_zCommentaire` TEXT,
  `etat_zDateSaisie` DATETIME DEFAULT NULL,
  PRIMARY KEY (`etat_id`),
  KEY `etat_iEvenementId` (`etat_iEvenementId`),
  KEY `etat_iTypeEtatId` (`etat_iTypeEtatId`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `typeetat` (
  `typeetat_id` INT(11) NOT NULL AUTO_INCREMENT,
  `typeetat_zLibelle` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`typeetat_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `typeetat` (`typeetat_id`, `typeetat_zLibelle`) VALUES
(1, 'Cours produit'),
(2, 'Cours annulé'),
(3, 'Cours deplacé');

/*************16/01/2012************/
/*************29/01/2012************/
ALTER TABLE `utilisateurs` ADD `utilisateur_bSuperviseur` TINYINT NULL DEFAULT '0' AFTER `utilisateur_statut` ,
ADD `utilisateur_iSuperviseurId` INT NULL DEFAULT NULL AFTER `utilisateur_bSuperviseur` ;

ALTER TABLE `utilisateurs` DROP `utilisateur_iSuperviseurId` ;
/*************29/01/2012************/

/*************03/04/2012************/
ALTER TABLE `utilisateurs` ADD `utilisateur_bSendExcel` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `utilisateurs` ADD `utilisateur_frequenceSendExcel` INT NOT NULL DEFAULT '0';
/*************03/04/2012************/

/*************07/04/2012************/
CREATE TABLE `utilisateursindisponibilite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `lundi_debut_matin` varchar(8) DEFAULT '00:00:00',
  `lundi_fin_matin` varchar(8) DEFAULT '00:00:00',
  `lundi_debut_apres_midi` varchar(8) DEFAULT '00:00:00',
  `lundi_fin_soir` varchar(8) DEFAULT '00:00:00',
  `mardi_debut_matin` varchar(8) DEFAULT '00:00:00',
  `mardi_fin_matin` varchar(8) DEFAULT '00:00:00',
  `mardi_debut_apres_midi` varchar(8) DEFAULT '00:00:00',
  `mardi_fin_soir` varchar(8) DEFAULT '00:00:00',
  `mercredi_debut_matin` varchar(8) DEFAULT '00:00:00',
  `mercredi_fin_matin` varchar(8) DEFAULT '00:00:00',
  `mercredi_debut_apres_midi` varchar(8) DEFAULT '00:00:00',
  `mercredi_fin_soir` varchar(8) DEFAULT '00:00:00',
  `jeudi_debut_matin` varchar(8) DEFAULT '00:00:00',
  `jeudi_fin_matin` varchar(8) DEFAULT '00:00:00',
  `jeudi_debut_apres_midi` varchar(8) DEFAULT '00:00:00',
  `jeudi_fin_soir` varchar(8) DEFAULT '00:00:00',
  `vendredi_debut_matin` varchar(8) DEFAULT '00:00:00',
  `vendredi_fin_matin` varchar(8) DEFAULT '00:00:00',
  `vendredi_debut_apres_midi` varchar(8) DEFAULT '00:00:00',
  `vendredi_fin_soir` varchar(8) DEFAULT '00:00:00',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `utilisateursindisponibilite_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
/*************07/04/2012************/

/*************18/04/2012************/
CREATE TABLE IF NOT EXISTS `logevent_all` (
  `logevent_id` int(11) NOT NULL AUTO_INCREMENT,
  `logevent_evenementLibelle` varchar(255) DEFAULT NULL,
  `logevent_evenementDescription` text,
  `logevent_evenementContactTel` text,
  `logevent_evenementDateHeureDebut` datetime DEFAULT NULL,
  `logevent_evenementDateHeureSaisie` datetime DEFAULT NULL,
  `logevent_evenementOrigine` varchar(255) DEFAULT NULL,
  `logevent_evenementDure` varchar(255) DEFAULT NULL,
  `logevent_typeevenements` varchar(255) DEFAULT NULL,
  `logevent_stagiaireCivilite` varchar(255) DEFAULT NULL,
  `logevent_stagiaireNom` varchar(255) DEFAULT NULL,
  `logevent_stagiairePrenom` varchar(255) DEFAULT NULL,
  `logevent_stagiaireFonction` varchar(255) DEFAULT NULL,
  `logevent_stagiaireMail` varchar(255) DEFAULT NULL,
  `logevent_stagiaireTel` text,
  `logevent_stagiaireMobile` text,
  `logevent_stagiaireLogin` text,
  `logevent_stagiairePassword` text,
  `logevent_stagiaireAdresse` text,
  `logevent_stagiaireNumeroIndividu` varchar(255) DEFAULT NULL,
  `logevent_stagiaireSociete` varchar(255) DEFAULT NULL,
  `logevent_stagiaireTestDebut` tinyint(4) NOT NULL DEFAULT '0',
  `logevent_profCivilite` varchar(255) DEFAULT NULL,
  `logevent_profNom` varchar(255) DEFAULT NULL,
  `logevent_profPrenom` varchar(255) DEFAULT NULL,
  `logevent_profTel` text,
  `logevent_profLogin` text,
  `logevent_profPassword` text,
  `logevent_profAdresse` text,
  PRIMARY KEY (`logevent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
/*************18/04/2012************/ LIVRE EN PROD

/*************05/05/2012************/
CREATE TABLE `utilisateursdisponibilite` (
  `utilisateursdisponibilite_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateursdisponibilite_utilisateur` int(11) NOT NULL,
  `utilisateursdisponibilite_jour` int(11) NOT NULL,
  `utilisateursdisponibilite_type` int(11) NOT NULL,
  `utilisateursdisponibilite_debut` varchar(55) NOT NULL,
  `utilisateursdisponibilite_fin` varchar(55) NOT NULL,
  PRIMARY KEY (`utilisateursdisponibilite_id`),
  KEY `utilisateursdisponibilite_type` (`utilisateursdisponibilite_type`),
  KEY `utilisateursdisponibilite_utilisateur` (`utilisateursdisponibilite_utilisateur`),
  CONSTRAINT `utilisateursdisponibilite_ibfk_2` FOREIGN KEY (`utilisateursdisponibilite_utilisateur`) REFERENCES `utilisateurs` (`utilisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `utilisateursdisponibilite_ibfk_1` FOREIGN KEY (`utilisateursdisponibilite_type`) REFERENCES `typeevenements` (`typeevenements_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


--
-- Structure de la table `ztempplage1`
--

CREATE TABLE IF NOT EXISTS `ztempplage1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heure` datetime NOT NULL,
  `am` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `ztempplage1`
--

INSERT INTO `ztempplage1` (`id`, `heure`, `am`) VALUES
(1, '0000-00-00 07:00:00', 1),
(2, '0000-00-00 08:00:00', 1),
(3, '0000-00-00 09:00:00', 1),
(4, '0000-00-00 10:00:00', 1),
(5, '0000-00-00 11:00:00', 1),
(6, '0000-00-00 12:00:00', 1),
(7, '0000-00-00 13:00:00', 0),
(8, '0000-00-00 14:00:00', 0),
(9, '0000-00-00 15:00:00', 0),
(10, '0000-00-00 16:00:00', 0),
(11, '0000-00-00 17:00:00', 0),
(12, '0000-00-00 18:00:00', 0),
(13, '0000-00-00 19:00:00', 0),
(14, '0000-00-00 20:00:00', 0),
(15, '0000-00-00 21:00:00', 0),
(16, '0000-00-00 22:00:00', 0),
(17, '0000-00-00 23:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ztempplage2`
--

CREATE TABLE IF NOT EXISTS `ztempplage2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heure` datetime NOT NULL,
  `am` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `ztempplage2`
--

INSERT INTO `ztempplage2` (`id`, `heure`, `am`) VALUES
(1, '0000-00-00 07:00:00', 1),
(2, '0000-00-00 07:30:00', 1),
(3, '0000-00-00 08:00:00', 1),
(4, '0000-00-00 08:30:00', 1),
(5, '0000-00-00 09:00:00', 1),
(6, '0000-00-00 09:30:00', 1),
(7, '0000-00-00 10:00:00', 1),
(8, '0000-00-00 10:30:00', 1),
(9, '0000-00-00 11:00:00', 1),
(10, '0000-00-00 11:30:00', 1),
(11, '0000-00-00 12:00:00', 1),
(12, '0000-00-00 12:30:00', 1),
(13, '0000-00-00 13:00:00', 0),
(14, '0000-00-00 13:30:00', 0),
(15, '0000-00-00 14:00:00', 0),
(16, '0000-00-00 14:30:00', 0),
(17, '0000-00-00 15:00:00', 0),
(18, '0000-00-00 15:30:00', 0),
(19, '0000-00-00 16:00:00', 0),
(20, '0000-00-00 16:30:00', 0),
(21, '0000-00-00 17:00:00', 0),
(22, '0000-00-00 17:30:00', 0),
(23, '0000-00-00 18:00:00', 0),
(24, '0000-00-00 18:30:00', 0),
(25, '0000-00-00 19:00:00', 0),
(26, '0000-00-00 19:30:00', 0),
(27, '0000-00-00 20:00:00', 0),
(28, '0000-00-00 20:30:00', 0),
(29, '0000-00-00 21:00:00', 0),
(30, '0000-00-00 21:30:00', 0),
(31, '0000-00-00 22:00:00', 0),
(32, '0000-00-00 22:30:00', 0),
(33, '0000-00-00 23:00:00', 0),
(34, '0000-00-00 23:30:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ztempplage3`
--

CREATE TABLE IF NOT EXISTS `ztempplage3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heure` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `am` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Contenu de la table `ztempplage3`
--

INSERT INTO `ztempplage3` (`id`, `heure`, `am`) VALUES
(1, '0000-00-00 07:00:00', 1),
(2, '0000-00-00 07:20:00', 1),
(3, '0000-00-00 07:40:00', 1),
(4, '0000-00-00 08:00:00', 1),
(5, '0000-00-00 08:20:00', 1),
(6, '0000-00-00 08:40:00', 1),
(7, '0000-00-00 09:00:00', 1),
(8, '0000-00-00 09:20:00', 1),
(9, '0000-00-00 09:40:00', 1),
(10, '0000-00-00 10:00:00', 1),
(11, '0000-00-00 10:20:00', 1),
(12, '0000-00-00 10:40:00', 1),
(13, '0000-00-00 11:00:00', 1),
(14, '0000-00-00 11:20:00', 1),
(15, '0000-00-00 11:40:00', 1),
(16, '0000-00-00 12:00:00', 1),
(17, '0000-00-00 12:20:00', 1),
(18, '0000-00-00 12:40:00', 1),
(19, '0000-00-00 13:00:00', 0),
(20, '0000-00-00 13:20:00', 0),
(21, '0000-00-00 13:40:00', 0),
(22, '0000-00-00 14:00:00', 0),
(23, '0000-00-00 14:20:00', 0),
(24, '0000-00-00 14:40:00', 0),
(25, '0000-00-00 15:00:00', 0),
(26, '0000-00-00 15:20:00', 0),
(27, '0000-00-00 15:40:00', 0),
(28, '0000-00-00 16:00:00', 0),
(29, '0000-00-00 16:20:00', 0),
(30, '0000-00-00 16:40:00', 0),
(31, '0000-00-00 17:00:00', 0),
(32, '0000-00-00 17:20:00', 0),
(33, '0000-00-00 17:40:00', 0),
(34, '0000-00-00 18:00:00', 0),
(35, '0000-00-00 18:20:00', 0),
(36, '0000-00-00 18:40:00', 0),
(37, '0000-00-00 19:00:00', 0),
(38, '0000-00-00 19:20:00', 0),
(39, '0000-00-00 19:40:00', 0),
(40, '0000-00-00 20:00:00', 0),
(41, '0000-00-00 20:20:00', 0),
(42, '0000-00-00 20:40:00', 0),
(43, '0000-00-00 21:00:00', 0),
(44, '0000-00-00 21:20:00', 0),
(45, '0000-00-00 21:40:00', 0),
(46, '0000-00-00 22:00:00', 0),
(47, '0000-00-00 22:20:00', 0),
(48, '0000-00-00 22:40:00', 0),
(49, '0000-00-00 23:00:00', 0),
(50, '0000-00-00 23:20:00', 0),
(51, '0000-00-00 23:40:00', 0);

/*************05/05/2012************/LIVREEEE

/*************19/06/2012************/

CREATE TABLE IF NOT EXISTS `validation` (
  `validation_id` int(11) NOT NULL AUTO_INCREMENT,
  `validation_zLibelle` varchar(255) NOT NULL,
  PRIMARY KEY (`validation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `validation`
--

INSERT INTO `validation` (`validation_id`, `validation_zLibelle`) VALUES
(1, 'Présent'),
(2, 'Absent'),
(3, 'Reporté');


CREATE TABLE IF NOT EXISTS `evenementvalidation` (
  `evenementvalidation_id` int(11) NOT NULL AUTO_INCREMENT,
  `evenementvalidation_eventId` int(11) NOT NULL,
  `evenementvalidation_validationId` int(11) NOT NULL,
  `evenementvalidation_date` datetime NOT NULL,
  `evenementvalidation_commentaire` text,
  PRIMARY KEY (`evenementvalidation_id`),
  KEY `evenementvalidation_eventId` (`evenementvalidation_eventId`),
  KEY `evenementvalidation_validationId` (`evenementvalidation_validationId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `evenementvalidation`
--
ALTER TABLE `evenementvalidation`
  ADD CONSTRAINT `evenementvalidation_ibfk_2` FOREIGN KEY (`evenementvalidation_validationId`) REFERENCES `validation` (`validation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `evenementvalidation_ibfk_1` FOREIGN KEY (`evenementvalidation_eventId`) REFERENCES `evenement` (`evenement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
/*************19/06/2012 EN PROD************/

/*************01/08/2013************/
CREATE TABLE `groupe` (
  `groupe_id` int(11) NOT NULL AUTO_INCREMENT,
  `groupe_libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`groupe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `groupe` (`groupe_id`, `groupe_libelle`) VALUES
(1, 'FR téléphone'),
(2, 'FR face à face'),
(3, 'Maurice');

CREATE TABLE `utilisateursgroup` (
  `utilisateursgroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateursgroup_utilisateurId` int(11) NOT NULL,
  `utilisateursgroup_groupId` int(11) NOT NULL,
  PRIMARY KEY (`utilisateursgroup_id`),
  KEY `utilisateursgroup_utilisateurId` (`utilisateursgroup_utilisateurId`,`utilisateursgroup_groupId`),
  KEY `utilisateursgroup_groupId` (`utilisateursgroup_groupId`),
  CONSTRAINT `utilisateursgroup_ibfk_2` FOREIGN KEY (`utilisateursgroup_groupId`) REFERENCES `groupe` (`groupe_id`),
  CONSTRAINT `utilisateursgroup_ibfk_1` FOREIGN KEY (`utilisateursgroup_utilisateurId`) REFERENCES `utilisateurs` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*************01/08/2013************/


/*************A LIVRER EN PROD************/
/*************29/08/2013************/
ALTER TABLE `clients` ADD INDEX ( `client_iNumIndividu` ) ;

CREATE TABLE IF NOT EXISTS `niveau` (
  `niveau_id` int(11) NOT NULL AUTO_INCREMENT,
  `niveau_libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`niveau_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `niveau`
--

INSERT INTO `niveau` (`niveau_id`, `niveau_libelle`) VALUES
(1, 'Débutant'),
(2, 'Intermediaire'),
(3, 'Avancé');

CREATE TABLE `stage` (
  `stage_id` int(11) NOT NULL AUTO_INCREMENT,
  `stage_dateEnregistrement` datetime DEFAULT NULL,
  `stage_stage` int(11) DEFAULT NULL,
  `stage_clientNumIndividu` varchar(255) DEFAULT NULL,
  `stage_niveau` int(11) DEFAULT NULL,
  `stage_closOuvert` tinyint(4) NOT NULL DEFAULT '0',
  `stage_stageLangue` varchar(255) DEFAULT NULL,
  `stage_stageType` varchar(255) DEFAULT NULL,
  `stage_testDateDebutQcmProd` datetime DEFAULT NULL,
  `stage_testDateDebut` datetime DEFAULT NULL,
  `stage_testDateDebutConv` datetime DEFAULT NULL,
  `stage_testDebutNoteQcm` double DEFAULT NULL,
  `stage_testDebutNoteConv` double DEFAULT NULL,
  `stage_testDebutNoteMes` double DEFAULT NULL,
  `stage_testFintPrevDate` varchar(255) DEFAULT NULL,
  `stage_testDateFinQcmProd` datetime DEFAULT NULL,
  `stage_testFinNoteQcm` double DEFAULT NULL,
  `stage_testeurFin` varchar(255) DEFAULT NULL,
  `stage_testDateFin` datetime DEFAULT NULL,
  `stage_testFinNoteConv` double DEFAULT NULL,
  `stage_testDateFinMes` datetime DEFAULT NULL,
  `stage_testFinNoteMes` double DEFAULT NULL,
  `stage_testFinNoteLibelle` varchar(255) DEFAULT NULL,
  `stage_testFinFinDivers` varchar(255) DEFAULT NULL,
  `stage_testFinObsConv` text NOT NULL,
  `stage_modification` varchar(255) DEFAULT NULL,
  `stage_profTutorat` int(11) DEFAULT NULL,
  `stage_profTel` int(11) DEFAULT NULL,
  `stage_ProfFaf` int(11) DEFAULT NULL,
  PRIMARY KEY (`stage_id`),
  KEY `stage_clientNumIndividu` (`stage_clientNumIndividu`),
  KEY `stage_niveau` (`stage_niveau`),
  KEY `stage_profTutorat` (`stage_profTutorat`),
  KEY `stage_profTel` (`stage_profTel`),
  KEY `stage_ProfFaf` (`stage_ProfFaf`),
  CONSTRAINT `stage_ibfk_5` FOREIGN KEY (`stage_clientNumIndividu`) REFERENCES `clients` (`client_iNumIndividu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stage_ibfk_1` FOREIGN KEY (`stage_niveau`) REFERENCES `niveau` (`niveau_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stage_ibfk_2` FOREIGN KEY (`stage_profTutorat`) REFERENCES `utilisateurs` (`utilisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stage_ibfk_3` FOREIGN KEY (`stage_profTel`) REFERENCES `utilisateurs` (`utilisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `stage_ibfk_4` FOREIGN KEY (`stage_ProfFaf`) REFERENCES `utilisateurs` (`utilisateur_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


ALTER TABLE `evenement` ADD `evenement_firstcours` TINYINT NULL DEFAULT NULL ;
--
-- Structure de la table `suiviestagiaire`
--
DROP TABLE IF EXISTS `suiviestagiaire`;
CREATE TABLE IF NOT EXISTS `suiviestagiaire` (
  `suiviestagiaire_id` int(11) NOT NULL AUTO_INCREMENT,
  `suiviestagiaire_numindividu` varchar(80) DEFAULT NULL,
  `suiviestagiaire_nom` varchar(255) DEFAULT NULL,
  `suiviestagiaire_prenom` varchar(255) DEFAULT '',
  `suiviestagiaire_dateinvit` date DEFAULT NULL,
  `suiviestagiaire_dateauto` date DEFAULT NULL,
  `suiviestagiaire_dateprevcours` date DEFAULT NULL,
  `suiviestagiaire_heureprevcours` time DEFAULT NULL,
  `suiviestagiaire_datenextcours` date DEFAULT NULL,
  `suiviestagiaire_heurenextcours` time DEFAULT NULL,
  `suiviestagiaire_prof` varchar(255) DEFAULT NULL,
  `suiviestagiaire_proftel` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`suiviestagiaire_id`),
  KEY `suiviestagiaire_numindividu` (`suiviestagiaire_numindividu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `suiviestagiaire`
--
ALTER TABLE `suiviestagiaire`
  ADD CONSTRAINT `suiviestagiaire_ibfk_1` FOREIGN KEY (`suiviestagiaire_numindividu`) REFERENCES `clients` (`client_iNumIndividu`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Structure de la table `clientsauto`
--

DROP TABLE IF EXISTS `clientsauto`;
CREATE TABLE IF NOT EXISTS `clientsauto` (
  `clientsauto_id` int(11) NOT NULL AUTO_INCREMENT,
  `clientsauto_clientid` int(11) NOT NULL,
  `clientsauto_dateinvit` date DEFAULT NULL,
  `clientsauto_auto` date DEFAULT NULL,
  PRIMARY KEY (`clientsauto_id`),
  UNIQUE KEY `clientsauto_clientid` (`clientsauto_clientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `clientsauto`
--
ALTER TABLE `clientsauto`
  ADD CONSTRAINT `clientsauto_ibfk_1` FOREIGN KEY (`clientsauto_clientid`) REFERENCES `clients` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
/*************29/08/2013************/

/*************23/09/2013************/
ALTER TABLE `utilisateurs` ADD `utilisateur_bGenerateDispo` TINYINT NULL DEFAULT '0' ;
/*************23/09/2013************/

/*************02/10/2013************/
ALTER TABLE `evenementvalidation` ADD `evenementvalidation_skype` INT NULL DEFAULT NULL AFTER `evenementvalidation_validationId` ;

CREATE TABLE IF NOT EXISTS `modelmail` (
  `modelmail_id` int(11) NOT NULL AUTO_INCREMENT,
  `modelmail_type` int(11) NOT NULL,
  `modelmail_objet` varchar(255) NOT NULL,
  `modelmail_label` text NOT NULL,
  `modelmail_ident` varchar (255) NOT NULL,
  `modelmail_value` int(11) NOT NULL,
  `modelmail_content` text NOT NULL,
  PRIMARY KEY (`modelmail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1

insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('1','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et proposer auto-planification','sendMailAuto','1','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bienvenue sur le planning en ligne de Forma2+ / <i>Welcome to the Forma2+ online planning system.</i><br /><br />Vous &ecirc;tes inscrit(e) en tant que stagiaire. Vous pouvez maintenant planifier vos cours avec votre professeur <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> en vous connectant sur : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><i>You are registered as trainee. You can now plan your courts lessons with your teacher <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> to logging on to: <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /></i></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><i><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></i></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><i></i></font><i><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p></i></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('2','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et relancer auto-planification','sendMailRelanceAuto','2','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bienvenue sur le planning en ligne de Forma2+ / <i>Welcome to the Forma2+ online planning system.</i><br /><br />Votre professeur <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a>  n\'arrive pas &agrave; vous joindre pour l\'organisation de votre formation, nous vous remercions de planifier votre prochain cours t&eacute;l&eacute;phonique en vous connectant sur  son planning : Planning en ligne/Forma2+ : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><br /><br />You are registered as trainee. You can now plan your courts lessons with your teacher <b>%s</b> : %s ; <a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a> to logging on to:<a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><br /></font></p><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('3','1','Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson','Enregistrer et envoyer un mail de changement de professeur','sendMailChangeProf','3','<p style=\"padding:15px 40px; margin:0; text-align:justify;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Bonjour<br /><br />Nous vous pr&eacute;sentons toutes nos excuses, votre professeur n\'est plus disponible pour assurer vos cours, aussi nous vous avons  r&eacute;affect&eacute; &agrave;  un nouveau formateur : <b>%s</b> : %s ;<a style=\"color: #003399;text-decoration: underline;\" target=\"_blank\" href=\"mailto:%s\">%s</a><br /><br />D&egrave;s &agrave; pr&eacute;sent  nous vous proposons  de prendre RDV directement en vous connectant sur son planning : <a style=\"text-decoration:none;color:#003399;\" target=\"_blank\" href=\"%s\">Planning en ligne/Forma2+</a><br /><br /><br /><br /></font></p><ul><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><ul><li><b>Votre identifiant / <i>Your login</i> :</b>%s</li><li><b>Votre mot de passe / <i>Your password</i> :</b>%s</li></ul></font></ul><p><font size=\"2\" face=\"Verdana, Geneva, sans-serif\"><br /></font></p><p>&nbsp;</p><p style=\"padding:15px 40px; margin:0; text-align:left;\"><font size=\"2\" face=\"Verdana, Geneva, sans-serif\">Nous vous souhaitons un agr&eacute;able stage / We wish you a pleasant training.<br /><br /><b>Rappel :</b>Les cours individuels peuvent &ecirc;tre report&eacute;s et ne sont pas perdus si le stagiaire pr&eacute;vient <b><u>directement son professeur</u></b>, la veille avant 17h (jours ouvr&eacute;s) pour les cours t&eacute;l&eacute;phone ou tutor&eacute;s. Dans un souci d\'efficacit&eacute;, il est conseill&eacute; de le reporter la m&ecirc;me semaine ou la semaine suivante.<br /><br />A tr&egrave;s bient&ocirc;t, / <i>Best regards, we look forward to hearing from you soon</i><br /><br />Equipe Planning en ligne Forma2+ / <i>The Forma2+ R&eacute;servation Team/Department</i></font></p>');
insert into `modelmail` (`modelmail_id`, `modelmail_type`, `modelmail_objet`, `modelmail_label`, `modelmail_ident`, `modelmail_value`, `modelmail_content`) values('4','1',NULL,' Enregistrer et envoyer un mail personnalisé','sendMailPerso','4',NULL);

/*************02/10/2013************/

/*************13/11/2013 DOUBLON************/
ALTER TABLE `suiviestagiaire` DROP FOREIGN KEY `suiviestagiaire_ibfk_1` ;
ALTER TABLE suiviestagiaire DROP INDEX suiviestagiaire_numindividu;
/*************13/11/2013 DOUBLON************/

/*************22/01/2014 ************/
CREATE TABLE IF NOT EXISTS `clientsenvironnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientId` int(11) NOT NULL,
  `bureau` int(11) NOT NULL DEFAULT '0',
  `navigateur` varchar(255) DEFAULT NULL,
  `telFixe` varchar(255) DEFAULT NULL,
  `telMobile` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `casqueSkype` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `clientId` (`clientId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `clientsenvironnement`
  ADD CONSTRAINT `clientsenvironnement_ibfk_1` FOREIGN KEY (`clientId`) REFERENCES `clients` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
/*************22/01/2014 ************/

/*************19/02/2014 ************/
ALTER TABLE `evenement` ADD `evenement_solde` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `evenement_prevu` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `evenement_produit` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;

ALTER TABLE `validationcours` ADD `validationcours_eventSolde` VARCHAR( 255 ) NOT NULL AFTER `validationcours_eventDure` ,
ADD `validationcours_eventPrevu` VARCHAR( 255 ) NOT NULL AFTER `validationcours_eventSolde` ,
ADD `validationcours_eventProduit` VARCHAR( 255 ) NOT NULL AFTER `validationcours_eventPrevu` ;

ALTER TABLE `validationcours` CHANGE `validationcours_eventSolde` `validationcours_eventSolde` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `validationcours_eventPrevu` `validationcours_eventPrevu` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `validationcours_eventProduit` `validationcours_eventProduit` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;

ALTER TABLE `clientsenvironnement` CHANGE `clientId` `clientId` INT( 11 ) NULL ;
ALTER TABLE `clientsenvironnement` ADD `eventId` INT NULL AFTER `clientId` ;
ALTER TABLE `clientsenvironnement` ADD INDEX ( `eventId` ) ;
ALTER TABLE `clientsenvironnement` ADD FOREIGN KEY ( `eventId` ) REFERENCES `evenement` (`evenement_id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
/*************19/02/2014 LIVRE************/

/*************30/03/2014************/
ALTER TABLE `validationcours` 
ADD `validationcours_bureau` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_navigateur` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_telFixe` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_telMobile` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_skype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_casqueSkype` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_coursPrevus` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_coursProduit` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD `validationcours_soldeAvantSaisie` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
/*************30/03/2014************/
/*************20/08/2014************/
ALTER TABLE `validationcours` ADD `validationcours_compteurEncours` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `validationcours` ADD `validationcours_presence` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
/*************20/08/2014************/

/*************02/09/2014 ************/
ALTER TABLE `validationcours` DROP `validationcours_dateExport`, DROP `validationcours_heureExport` ;
/*************02/09/2014 ************/
/*************15/09/2014 ************/
ALTER TABLE `validationcours` ADD `validationcours_dateExport` DATE NULL DEFAULT NULL AFTER `validationcours_heure` ;
/*************15/09/2014 ************/

/*************10/03/2014************/
CREATE TABLE IF NOT EXISTS `clientsolde` (
  `clientsolde_id` int(11) NOT NULL AUTO_INCREMENT,
  `clientsolde_clientid` int(11) NOT NULL,
  `clientsolde_solde` int(11) NOT NULL,
  `clientsolde_prevu` int(11) NOT NULL,
  `clientsolde_produit` int(11) NOT NULL,
  PRIMARY KEY (`clientsolde_id`),
  KEY `clientsolde_clientid` (`clientsolde_clientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `clientsolde` ADD FOREIGN KEY ( `clientsolde_clientid` ) REFERENCES `clients` (`client_id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
ALTER TABLE `clientsolde` CHANGE `clientsolde_solde` `clientsolde_solde` INT( 11 ) NULL , CHANGE `clientsolde_prevu` `clientsolde_prevu` INT( 11 ) NULL , CHANGE `clientsolde_produit` `clientsolde_produit` INT( 11 ) NULL ;

ALTER TABLE `clientsolde` CHANGE `clientsolde_solde` `clientsolde_solde` DOUBLE NULL DEFAULT NULL ,
CHANGE `clientsolde_prevu` `clientsolde_prevu` DOUBLE NULL DEFAULT NULL ,
CHANGE `clientsolde_produit` `clientsolde_produit` DOUBLE NULL DEFAULT NULL ;

/*************10/03/2014************/
/*************22/03/2014  A LIVRE************/
ALTER TABLE `clientsolde` ADD `clientsolde_eventid` INT NULL AFTER `clientsolde_id` , ADD INDEX ( `clientsolde_eventid` ) ;
ALTER TABLE `clientsolde` ADD FOREIGN KEY ( `clientsolde_eventid` ) REFERENCES `evenement` ( `evenement_id` ) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE `clientsolde` DROP FOREIGN KEY `clientsolde_ibfk_2` , ADD FOREIGN KEY ( `clientsolde_eventid` ) REFERENCES `evenement` ( `evenement_id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;
/*************22/03/2014  A LIVRE************/


SET FOREIGN_KEY_CHECKS=1;