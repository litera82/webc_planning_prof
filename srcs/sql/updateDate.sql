SET FOREIGN_KEY_CHECKS = 0; 
SELECT *  FROM `clients` WHERE `client_zNom` LIKE '%erreur%' OR `client_zPrenom` LIKE '%erreur%' OR `client_zNom` LIKE '%doublon%' OR `client_zPrenom` LIKE '%doublon%';
DELETE FROM `clients` WHERE `client_zNom` LIKE '%erreur%' OR `client_zPrenom` LIKE '%erreur%' OR `client_zNom` LIKE '%doublon%' OR `client_zPrenom` LIKE '%doublon%';
DELETE FROM evenement WHERE evenement.evenement_iStagiaire NOT IN (SELECT client_id FROM clients GROUP BY client_id ORDER BY client_id)
DELETE FROM `evenement` WHERE `evenement_zDateHeureDebut`='0000-00-00 00:00:00' OR evenement_zDateHeureDebut 
SET FOREIGN_KEY_CHECKS = 1; 
UPDATE clients set `client_zLogin` = TRIM(replace(UPPER(`client_zNom`), ' ', '')) where `client_zLogin` LIKE "%@%";
UPDATE clients SET `client_zLogin` = TRIM(client_zNom) WHERE `client_zLogin` LIKE "%@%" OR client_zLogin =NULL OR client_zLogin='';