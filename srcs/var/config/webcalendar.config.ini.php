<?php
// Pagination
define('PAGINATION_NB_ITEM_PER_PAGE', 10);
define('PAGINATION_NB_ITEM_PER_PAGE_FRONT', 10);

// Status en general
define('STATUT_PUBLIE', 1);
define('STATUT_OK', 1);
define('STATUT_NON_PUBLIE', 2);
define('STATUT_DESACTIVE', 0);
//civilités
define('CIVILITE_FEMME', 0);
define('CIVILITE_HOMME', 1);
define('CIVILITE_MADEMOISELLE', 2);
//Type Utilisateur
define('TYPE_UTILISATEUR_ADLINISTRATEUR', 2);

define('SENDER_MAIL', 'evaluation@forma2plus.info');
define('NAME_SENDER', 'webcalandar forma2plus');
define('MAIL_OBJECT_CONFIRMATION_CREATION_EVENEMENT', 'Confirmation de votre cours');
define('MAIL_OBJECT_CONFIRMATION_CREATION_COMPTE', 'Vous êtes inscrit(e) en tant que stagiaire');
define('URL_TO_SITE', 'localhost/webcalendar/srcs/www/');
define('ID_UTILISATEUR_CREATEUR_IMPORT_XML', 2);

/**
* BEGIN Constante specifique selon le serveur
* A verifier
*/
//Type devenement specifique
define('ID_TYPE_EVENEMENT_DISPONIBLE', 13); // disponible Test de debut de stage catriona 20mn 

define('TYPE_UTILISATEUR_PROFESSEUR', 3);
define('ID_TYPE_EVENEMENT_TEST_DEBUT', 10);
define('ID_TYPE_EVENEMENT_ALO', 9);

define('MAIL_TESTORALDEBUT', 'litera82@yahoo.fr'); 
define('MAIL_TESTORALDEBUT_PROPOSITION', 'litera82@yahoo.fr'); 

define('AUTOPLANNIFICATION_ID_CATRIONA', 6); 
define('AUTOPLANNIFICATION_ID_FARAH', 8); 
define('FORMATION_TUTEUR_ID_MARIE_LUCE', 7); 

//Approche par liste 
define('ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE', 18); // disponible cours 30mn 
define('ID_PAYS_FRANCE', 64);

define('ID_TYPE_EVENEMENT_AUDIT', 19); 
define('AUDIT_ID_CATRIONA', 6); 
define('AUDIT_ID_FARAH', 8); 

/**
* END Constante specifique selon le serveur
* A verifier
*/

define ('XML_PATH_PORTEFEUILLE_PROF', 'userFiles/xml/portefeuille_prof/');
define ('XML_FILE_PORTEFEUILLE_PROF', 'portefeuille_prof');

define ('ID_TYPE_EVENEMENT_COUR_TELEPHONE', 12);

/**
* Statut des evennement
*/
define ('COURS_PRODUIT', 1);
define ('COURS_ANNULE', 2);
define ('COURS_DEPLACE', 3);

define('UTILISATEUR_SUPERVISEUR', 1);
define('UTILISATEUR_NON_SUPERVISEUR', 0);

define('MAIL_OBJECT_SEND_EXPORT_EVENT_BY_EMAIL', 'Une copie de votre planning en ligne sur Webcalendar');


define('MAIL_TESTORALDEBUT_PROPOSITION_1', 'litera82@yahoo.fr'); 
define('ID_TYPE_EVENEMENT_INDISPONIBLE', 14);

define('MAIL_TESTORALDEBUT_PROPOSITION_2', 'litera82@yahoo.fr'); 
define('MAIL_OBJECT_PROPOSITION_AUTOPLANNIFICATION', 'Forma2+ vous invite à planifier votre premier cours / Forma2+ invites you to plan your first lesson');
define('MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1', 'litera82@yahoo.fr'); // CCi copiedesemailsenvoyes1@forma2plus.com en PROD
define('MAIL_AUTOCOURS_COPIEDESRESERVATIONSWEBCALENDAR', 'litera82@yahoo.fr'); // CCi copiedesreservationswebcalendar@forma2plus.com en PROD

define('LOGIN_ADMIN', 'admin'); 

define('URL_CODE_ANOMALIE', 'http://extranet.forma2plus.com/stagiaires/stagiaireWebcalendar.asp?stagiaireID=%s');
