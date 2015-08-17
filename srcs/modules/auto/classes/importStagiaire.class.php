<?php
@ini_set ("memory_limit", -1) ;

jClasses::inc('commun~mailSrv') ;
jClasses::inc('client~clientSrv') ;
jClasses::inc('client~clientXmlSaveSrv') ;

class importStagiaire
{
    /**
     * importation d'un stagiaire via un flux xml
     * @param unknown_type $_zXmlImported
     */
    static function importXml ($_zXmlImported)
    {
//jLog::dump("CLASS importStagiaire") ; 
    	$oResult = 1 ;
		$oSimpleXml =  new SimpleXMLElement(utf8_encode($_zXmlImported)) ;
		$iSocieteId = 0;
//jLog::dump("oSimpleXml---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oSimpleXml) ; 

		foreach ($oSimpleXml->stagiaire as $oStagiaire)
		{
//jLog::dump("oStagiaire---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oStagiaire) ; 

			// --- Verifie si le stagiaire n'est pas déjà dans la base
			$oClient = clientSrv::getClientByNumIndividu($oStagiaire->num_individu) ;
			if (isset($oStagiaire->societe) && strlen(strval($oStagiaire->societe)) > 0){
		        $oCnx = jDb::getConnection () ;
				$zSqlSociete = 'SELECT * FROM societe WHERE societe.societe_zNom = "'.trim(strtolower($oStagiaire->societe)).'" LIMIT 0, 1';
				$Rs = $oCnx->query($zSqlSociete);
				$toResults = array ();
				if ($oRecord = $Rs->fetch()){
					array_push($toResults, $oRecord);
				}
				if (isset($toResults[0]->societe_id) && $toResults[0]->societe_id > 0){
					$iSocieteId = $toResults[0]->societe_id;
				}else{
					jClasses::inc('client~societeSrv');
					$toSociete['societe_id'] = 0;
					$toSociete['societe_zNom'] = $oStagiaire->societe;
					$toSociete['societe_iStatut'] = 1;
					$oRec = societeSrv::save($toSociete);  
					$iSocieteId = $oRec->societe_id; 
				}
			}
//jLog::dump("oClient---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oClient) ; 

			$oFac = jDao::get('commun~client');
			if (!$oClient){
				$oRecord = jDao::createRecord('commun~client');
			}else{
				$oRecord = $oFac->get($oClient->client_id) ;
			}

			$oRecord->client_iSociete = $iSocieteId ;
			$zCivilite = $oStagiaire->civilite;
			switch (strval($zCivilite)){
				case "M.":$iCivilite=1;break;
				case "Mme":$iCivilite=0;break;
				case "Mlle":$iCivilite=2;break;
				default:$iCivilite=0; 
			}
			$oRecord->client_iCivilite = $iCivilite ;
			$oRecord->client_iUtilisateurCreateurId = AUTOPLANNIFICATION_ID_CATRIONA ;
			$oRecord->client_zNom = utf8_decode($oStagiaire->nom) ;
			$oRecord->client_zPrenom = utf8_decode($oStagiaire->prenoms) ;
			$oRecord->client_zFonction = utf8_decode($oStagiaire->fonction) ;
			$oRecord->client_zMail = utf8_decode($oStagiaire->mail) ;
			$oRecord->client_zLogin = utf8_decode($oStagiaire->nom) ;
			$oRecord->client_zPass = $oStagiaire->num_individu ;
			$oRecord->client_zTel = $oStagiaire->telephone ;
			$oRecord->client_zPortable = utf8_decode($oStagiaire->portable) ;
			$oRecord->client_zRue = utf8_decode($oStagiaire->rue_stage) ;
			$oRecord->client_zVille = utf8_decode($oStagiaire->ville_stage) ;
			$oRecord->client_zCP = utf8_decode($oStagiaire->Cp_stage) ;
			$oRecord->client_iPays = 0 ;
			$oRecord->client_iNumIndividu = $oStagiaire->num_individu ;
			if (isset($oStagiaire->cryptedmd5) && $oStagiaire->cryptedmd5 != ""){
				$oRecord->client_zCryptedKey = $oStagiaire->cryptedmd5 ;
			}
			$oRecord->client_iStatut = STATUT_OK ;
			$oRecord->client_testDebut = 0 ;

			if ($oClient->client_id > 0){
//jLog::dump("oRecord UPDATE---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oRecord) ; 
				$oRecord->client_dateMaj = date("Y-m-d H:i:s") ;
				$iTest = $oFac->update($oRecord) ;
			}else{
//jLog::dump("oRecord INSERT---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oRecord) ; 
				$oRecord->client_dateCreation = date("Y-m-d H:i:s") ;
				$iTest = $oFac->insert($oRecord) ;
			}
		}
//jLog::dump("oResult---------------------------------------------------------------------------------------------") ; 
//jLog::dump($oResult) ; 
		return $oResult ;
    }


    static function importXmlStagiaire ($_zXmlImported)
    {
    	$oResult = 1 ;
		$oSimpleXml =  new SimpleXMLElement($_zXmlImported) ;

		$iSocieteId = 0;
		foreach ($oSimpleXml->stagiaire as $oStagiaire)
		{
			// --- Verifie si le stagiaire n'est pas déjà dans la base
			$oClient = clientSrv::getClientByNumIndividu((string) $oStagiaire->num_individu) ;
			if (isset($oStagiaire->societe) && strlen(strval($oStagiaire->societe)) > 0){
		        $oCnx = jDb::getConnection () ;
				$zSqlSociete = 'SELECT * FROM societe WHERE societe.societe_zNom = "'.trim(strtolower((string) $oStagiaire->societe)).'" LIMIT 0, 1';
				$Rs = $oCnx->query($zSqlSociete);
				$toResults = array ();
				if ($oRecord = $Rs->fetch()){
					array_push($toResults, $oRecord);
				}
				if (isset($toResults[0]->societe_id) && $toResults[0]->societe_id > 0){
					$iSocieteId = $toResults[0]->societe_id;
				}else{
					jClasses::inc('client~societeSrv');
					$toSociete['societe_id'] = 0;
					$toSociete['societe_zNom'] = (string) $oStagiaire->societe;
					$toSociete['societe_iStatut'] = 1;
					$oRec = societeSrv::save($toSociete);  
					$iSocieteId = $oRec->societe_id; 
				}
			}

			$oFac = jDao::get('commun~client');
			//$oRecord = jDao::createRecord('commun~client');
			$bCreate = true;
			if(!$oClient) {
				$oRecord = jDao::createRecord('commun~client') ;
			}else{
				$bCreate = false;
				$oRecord = $oFac->get($oClient->client_id) ;
			}

			$oRecord->client_iSociete = $iSocieteId ;
			$zCivilite = (string) $oStagiaire->civilite;
			switch (strval($zCivilite)){
				case "M.":$iCivilite=1;break;
				case "Mme":$iCivilite=0;break;
				case "Mlle":$iCivilite=2;break;
				default:$iCivilite=0; 
			}
			$oRecord->client_iCivilite = $iCivilite ;
			$oRecord->client_iUtilisateurCreateurId = AUTOPLANNIFICATION_ID_CATRIONA ;

			/*****/
			////jLog::dump($oStagiaire) ; 
			if ((isset ($oStagiaire->prof->nom[0]) && strlen($oStagiaire->prof->nom[0]) > 0) || (isset ($oStagiaire->prof->prenom[0]) && strlen( $oStagiaire->prof->prenom[0]) > 0)){
				////jLog::dump("iciii") ; 
				////jLog::dump("NOM > " . (string) $oStagiaire->prof->nom[0]) ; 
				////jLog::dump("PRENOM > " . (string) $oStagiaire->prof->prenom[0]) ; 
				$oRecord->client_iUtilisateurCreateurId = clientXmlSaveSrv::getUtilisateurByNameProf((string) $oStagiaire->prof->nom[0], (string) $oStagiaire->prof->prenom[0]) ;
				////jLog::dump("ID > " . $oRecord->client_iUtilisateurCreateurId);
			}
			/*****/
			$oRecord->client_zNom = utf8_decode((string) $oStagiaire->nom) ;
			$oRecord->client_zPrenom = utf8_decode((string) $oStagiaire->prenoms) ;
			$oRecord->client_zFonction = utf8_decode((string) $oStagiaire->fonction) ;
			$oRecord->client_zMail = utf8_decode((string) $oStagiaire->mail) ;
			$oRecord->client_zLogin = utf8_decode((string) $oStagiaire->nom) ;
			$oRecord->client_zPass = (string) $oStagiaire->num_individu ;
			$oRecord->client_zTel = (string) $oStagiaire->telephone ;
			$oRecord->client_zPortable = utf8_decode((string) $oStagiaire->portable) ;
			$oRecord->client_zRue = utf8_decode((string) $oStagiaire->rue_stage) ;
			$oRecord->client_zVille = utf8_decode((string) $oStagiaire->ville_stage) ;
			$oRecord->client_zCP = utf8_decode((string) $oStagiaire->Cp_stage) ;
			$oRecord->client_iPays = 0 ;
			$oRecord->client_iNumIndividu = (string) $oStagiaire->num_individu ;
			if (isset($oStagiaire->cryptedmd5) && $oStagiaire->cryptedmd5 != ""){
				$oRecord->client_zCryptedKey = (string) $oStagiaire->cryptedmd5 ;
			}
			$oRecord->client_iStatut = STATUT_OK ;
			$oRecord->client_testDebut = 0 ;

			if ($bCreate){
				$iTest = $oFac->insert($oRecord) ;
			}else{
				$iTest = $oFac->update($oRecord) ;
			}
			
			if (!$oRecord->client_id)
			{
				$oResult = 0 ;
				break;
			}
			else 
			{
				$oFac = jDao::get('commun~client');
				$oRecord->client_zLogin = $oRecord->client_zLogin ;
				$oFac->update($oRecord) ;
				$_SESSION['JELIX_USER_AUTO'] = $oRecord ;
				
				// --- Envoye de mail au client
				//self::sendMailStagiaire($oRecord);
			}
		}
		return $oResult ;
    }

    static function importXmlFormationTuteur ($_zXmlImported)
    {
    	$oResult = 1 ;
		$oSimpleXml =  new SimpleXMLElement(utf8_encode($_zXmlImported)) ;
		$iSocieteId = 0;
		foreach ($oSimpleXml->stagiaire as $oStagiaire)
		{
			// --- Verifie si le stagiaire n'est pas déjà dans la base
			$oClient = clientSrv::getClientByNumIndividu($oStagiaire->num_individu) ;
			if (isset($oStagiaire->societe) && strlen(strval($oStagiaire->societe)) > 0){
		        $oCnx = jDb::getConnection () ;
				$zSqlSociete = 'SELECT * FROM societe WHERE societe.societe_zNom = "'.trim(strtolower($oStagiaire->societe)).'" LIMIT 0, 1';
				$Rs = $oCnx->query($zSqlSociete);
				$toResults = array ();
				if ($oRecord = $Rs->fetch()){
					array_push($toResults, $oRecord);
				}
				if (isset($toResults[0]->societe_id) && $toResults[0]->societe_id > 0){
					$iSocieteId = $toResults[0]->societe_id;
				}else{
					jClasses::inc('client~societeSrv');
					$toSociete['societe_id'] = 0;
					$toSociete['societe_zNom'] = $oStagiaire->societe;
					$toSociete['societe_iStatut'] = 1;
					$oRec = societeSrv::save($toSociete);  
					$iSocieteId = $oRec->societe_id; 
				}
			}

			if (!$oClient)
			{
				$oFac = jDao::get('commun~client');
				$oRecord = jDao::createRecord('commun~client');
				$oRecord->client_iSociete = $iSocieteId ;
				$zCivilite = $oStagiaire->civilite;
				switch (strval($zCivilite)){
					case "M.":$iCivilite=1;break;
					case "Mme":$iCivilite=0;break;
					case "Mlle":$iCivilite=2;break;
					default:$iCivilite=0; 
				}
				$oRecord->client_iCivilite = $iCivilite ;
				$oRecord->client_iUtilisateurCreateurId = FORMATION_TUTEUR_ID_MARIE_LUCE ;
				$oRecord->client_zNom = utf8_decode($oStagiaire->nom) ;
				$oRecord->client_zPrenom = utf8_decode($oStagiaire->prenoms) ;
				$oRecord->client_zFonction = utf8_decode($oStagiaire->fonction) ;
				$oRecord->client_zMail = utf8_decode($oStagiaire->mail) ;
				$oRecord->client_zLogin = utf8_decode($oStagiaire->nom) ;
				$oRecord->client_zPass = $oStagiaire->num_individu ;
				$oRecord->client_zTel = $oStagiaire->telephone ;
				$oRecord->client_zPortable = utf8_decode($oStagiaire->portable) ;
				$oRecord->client_zRue = utf8_decode($oStagiaire->rue_stage) ;
				$oRecord->client_zVille = utf8_decode($oStagiaire->ville_stage) ;
				$oRecord->client_zCP = utf8_decode($oStagiaire->Cp_stage) ;
				$oRecord->client_iPays = 0 ;
				$oRecord->client_iNumIndividu = $oStagiaire->num_individu ;
				if (isset($oStagiaire->cryptedmd5) && $oStagiaire->cryptedmd5 != ""){
					$oRecord->client_zCryptedKey = $oStagiaire->cryptedmd5 ;
				}
				$oRecord->client_iStatut = STATUT_OK ;
				$oRecord->client_testDebut = 0 ;
				//////jLog::dump($oRecord) ;
				$iTest = $oFac->insert($oRecord) ;
				
				if (!$oRecord->client_id)
				{
					$oResult = 0 ;
					break;
				}
				else 
				{
					$oFac = jDao::get('commun~client');
					$oRecord->client_zLogin = $oRecord->client_zLogin ;
					$oFac->update($oRecord) ;
					$_SESSION['JELIX_USER_AUTO'] = $oRecord ;
					
					// --- Envoye de mail au client
					//self::sendMailStagiaire($oRecord);
				}
			}
		}
		return $oResult ;
    }
    
    static function sendMailStagiaire ($oClient)
    {
			jClasses::inc('client~clientSrv');
			jClasses::inc('commun~mailSrv');
	
			$oClient = clientSrv::getById($oClient->client_id) ;
	
			$tplMail = new jTpl();
			
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('oClient', $oClient) ;
	
			$tpl = $tplMail->fetch ('auto~mailAutoPlanification') ;
	
			mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , 'Format2plus : Vos coordonnées pour votre evaluation', $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
	}	

	static function sendMailReservation ($_tEvent, $p=1, $m=0){
		jClasses::inc('client~clientSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');
		jClasses::inc('commun~toolDate') ;

		$tzDateHeure = explode(' ', $_tEvent['evenement_zDateHeureDebut']);
		$tzDateMysql = explode(' ', $_tEvent['zDateMysql']);
		$zDateFr = toolDate::formatToLongDate($tzDateMysql[0], "-", "fr");
		$zDateEn = toolDate::formatToLongDate($tzDateMysql[0], "-", "en");
		$zDate = $tzDateHeure[0];
		$zHeure = $tzDateHeure[1];
		if (isset ($_tEvent['evenement_iStagiaire']) && intval($_tEvent['evenement_iStagiaire']) > 0){
			$oClient = clientSrv::getById($_tEvent['evenement_iStagiaire']); 

			$oTypeEvenement = typeEvenementsSrv::getById($_tEvent['evenement_iTypeEvenementId']); 
			$oUtilisateur = utilisateursSrv::getById($_tEvent['evenement_iUtilisateurId']); 
			$zUrlModif = "http://" . $_SERVER["HTTP_HOST"] . jUrl::get('auto~default:stagiaire') . '&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass . '&p=' . $p ;

			$tplMail = new jTpl();
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('zUrlModif', $zUrlModif) ;
			$tplMail->assign ('oClient', $oClient) ;
			$tplMail->assign ('oTypeEvenement', $oTypeEvenement) ;
			$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
			$tplMail->assign ('zDate', $zDate) ;
			$tplMail->assign ('p', $p) ;
			$tplMail->assign ('zDateFr', $zDateFr) ;
			$tplMail->assign ('zDateEn', $zDateEn) ;
			$tplMail->assign ('zHeure', $zHeure) ;
			$tplMail->assign ('tEvent', $_tEvent) ;
			$tplMail->assign ('zTelResa', $_tEvent['evenement_zContactTel']) ;

			$tpl = $tplMail->fetch ('auto~corpsMailConfirmationTestDebut') ;

			if ($m == 1){
				$zSujet = "Confirmation de la modification de votre reservation de test de debut stage / Confirmation of the change in your reservation early placement test";
			}else{
				$zSujet = "Confirmation de votre reservation de test de debut stage / Confirmation of your booking early placement test";
			}

			mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $zSujet, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
		}
	}

	static function sendMailReservationModifContact ($iEventId){
		jClasses::inc('client~clientSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');
		jClasses::inc('commun~toolDate') ;
		jClasses::inc('evenement~evenementSrv');

		$_tEvent = array (evenementSrv::getById ($iEventId)) ;

		$tzDateHeure = explode(' ', $_tEvent[0]->evenement_zDateHeureDebut);
		$_tEvent[0]->zDateMysql = $_tEvent[0]->evenement_zDateHeureDebut ;
		$tzDateMysql = explode(' ', $_tEvent[0]->zDateMysql);
		$zDateFr = toolDate::formatToLongDate($tzDateMysql[0], "-", "fr");
		$zDateEn = toolDate::formatToLongDate($tzDateMysql[0], "-", "en");
		$zDate = $tzDateHeure[0];
		$zHeure = $tzDateHeure[1];
		if (isset ($_tEvent[0]->evenement_iStagiaire) && intval($_tEvent[0]->evenement_iStagiaire) > 0){
			$oClient = clientSrv::getById($_tEvent[0]->evenement_iStagiaire); 

			$oTypeEvenement = typeEvenementsSrv::getById($_tEvent[0]->evenement_iTypeEvenementId); 
			$oUtilisateur = utilisateursSrv::getById($_tEvent[0]->evenement_iUtilisateurId); 
			$zUrlModif = "http://" . $_SERVER["HTTP_HOST"] . jUrl::get('auto~default:stagiaire') . '&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass . '&p=' . ID_TYPE_EVENEMENT_TEST_DEBUT ;

			$tplMail = new jTpl();
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('zUrlModif', $zUrlModif) ;
			$tplMail->assign ('oClient', $oClient) ;
			$tplMail->assign ('oTypeEvenement', $oTypeEvenement) ;
			$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
			$tplMail->assign ('zDate', $zDate) ;
			$tplMail->assign ('p', ID_TYPE_EVENEMENT_TEST_DEBUT) ;
			$tplMail->assign ('zDateFr', $zDateFr) ;
			$tplMail->assign ('zDateEn', $zDateEn) ;
			$tplMail->assign ('zHeure', $zHeure) ;
			$tplMail->assign ('tEvent', $_tEvent) ;
			$tplMail->assign ('zTelResa', $_tEvent[0]->evenement_zContactTel) ;

			$tpl = $tplMail->fetch ('auto~corpsMailConfirmationTestDebut') ;

			$zSujet = "Modification du numero de téléphone - Confirmation de votre reservation de test de debut stage / Changing the phone number - Confirmation of your booking early placement test";

			mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $zSujet, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
		}
	}
	
	static function sendMailPourInformerReservation ($_tEvent, $p=1, $m=0){
		jClasses::inc('client~clientSrv');
		jClasses::inc('client~societeSrv');
		jClasses::inc('client~paysSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$tzDateHeure = explode(' ', $_tEvent['evenement_zDateHeureDebut']);
		$zDate = $tzDateHeure[0];
		$zHeure = $tzDateHeure[1];
		if (isset ($_tEvent['evenement_iStagiaire']) && intval($_tEvent['evenement_iStagiaire']) > 0){
			$oSociete = new StdClass ();
			$oPays = new StdClass ();
			$oClient = clientSrv::getById($_tEvent['evenement_iStagiaire']); 

			if (isset($oClient->client_iSociete) && $oClient->client_iSociete > 0){
				$oSociete = societeSrv::getById($oClient->client_iSociete); 
			}

			if (isset($oClient->client_iPays) && $oClient->client_iPays > 0){
				$oPays = paysSrv::getById($oClient->client_iPays); 
			}

			$oTypeEvenement = typeEvenementsSrv::getById($_tEvent['evenement_iTypeEvenementId']); 
			$oUtilisateur = utilisateursSrv::getById($_tEvent['evenement_iUtilisateurId']); 

			$tplMail = new jTpl();
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('oClient', $oClient) ;
			$tplMail->assign ('oSociete', $oSociete) ;
			$tplMail->assign ('oPays', $oPays) ;
			$tplMail->assign ('oTypeEvenement', $oTypeEvenement) ;
			$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
			$tplMail->assign ('zDate', $zDate) ;
			$tplMail->assign ('zHeure', $zHeure) ;
			$tplMail->assign ('tEvent', $_tEvent) ;
			$tplMail->assign ('zTelResa', $_tEvent['evenement_zContactTel']) ;

			$tpl = $tplMail->fetch ('auto~corpsMailPourInformerTestDebut') ;

			if ($oClient->client_iCivilite == CIVILITE_HOMME){
				$zCivilite = "Mr"; 
			}elseif($oClient->client_iCivilite == CIVILITE_MADEMOISELLE){
				$zCivilite = "Mlle";
			}else{
				$zCivilite = "Mme";
			}

			if ($m == 1){
				$zSujet = "Modification de la réservation de " . $zCivilite . " " . addslashes($oClient->client_zNom) . " " . addslashes($oClient->client_zPrenom) . " - " . addslashes($oSociete->societe_zNom). " pour un test de début de stage prévu le " . $zDate . " à "  . $zHeure;
			}else{
				$zSujet = "Réservation de " . $zCivilite . " " . addslashes($oClient->client_zNom) . " " . addslashes($oClient->client_zPrenom) . " - " . addslashes($oSociete->societe_zNom). " pour un test de début de stage prévu le " . $zDate . " à "  . $zHeure;
			}
			$zToMail = array (MAIL_TESTORALDEBUT_PROPOSITION, "copiereservations@forma2plus.com");

			mailSrv::envoiEmail ($oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom, $zToMail, "Forma2+" , $zSujet, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;

		}
	}
	static function sendMailPourInformerReservationModifContact ($iEventId){
		jClasses::inc('client~clientSrv');
		jClasses::inc('client~societeSrv');
		jClasses::inc('client~paysSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');
		jClasses::inc('evenement~evenementSrv');

		$_tEvent = array (evenementSrv::getById ($iEventId)) ;

		$tzDateHeure = explode(' ', $_tEvent[0]->evenement_zDateHeureDebut);
		$zDate = $tzDateHeure[0];
		$zHeure = $tzDateHeure[1];
		if (isset ($_tEvent[0]->evenement_iStagiaire) && intval($_tEvent[0]->evenement_iStagiaire) > 0){
			$oSociete = new StdClass ();
			$oPays = new StdClass ();
			$oClient = clientSrv::getById($_tEvent[0]->evenement_iStagiaire); 

			if (isset($oClient->client_iSociete) && $oClient->client_iSociete > 0){
				$oSociete = societeSrv::getById($oClient->client_iSociete); 
			}

			if (isset($oClient->client_iPays) && $oClient->client_iPays > 0){
				$oPays = paysSrv::getById($oClient->client_iPays); 
			}

			$oTypeEvenement = typeEvenementsSrv::getById($_tEvent[0]->evenement_iTypeEvenementId); 
			$oUtilisateur = utilisateursSrv::getById($_tEvent[0]->evenement_iUtilisateurId); 

			$tplMail = new jTpl();
			$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
			$tplMail->assign ('oClient', $oClient) ;
			$tplMail->assign ('oSociete', $oSociete) ;
			$tplMail->assign ('oPays', $oPays) ;
			$tplMail->assign ('oTypeEvenement', $oTypeEvenement) ;
			$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
			$tplMail->assign ('zDate', $zDate) ;
			$tplMail->assign ('zHeure', $zHeure) ;
			$tplMail->assign ('tEvent', $_tEvent) ;
			$tplMail->assign ('zTelResa', $_tEvent[0]->evenement_zContactTel) ;
			$tplMail->assign ('modifTel', 1) ;

			$tpl = $tplMail->fetch ('auto~corpsMailPourInformerTestDebut') ;

			if ($oClient->client_iCivilite == CIVILITE_HOMME){
				$zCivilite = "Mr"; 
			}elseif($oClient->client_iCivilite == CIVILITE_MADEMOISELLE){
				$zCivilite = "Mlle";
			}else{
				$zCivilite = "Mme";
			}

			$zSujet = "Modification du numero de téléphone - Réservation de " . $zCivilite . " " . addslashes($oClient->client_zNom) . " " . addslashes($oClient->client_zPrenom) . " - " . addslashes($oSociete->societe_zNom). " pour un test de début de stage prévu le " . $zDate . " à "  . $zHeure;

			$zToMail = array (MAIL_TESTORALDEBUT_PROPOSITION, "copiereservations@forma2plus.com");

			mailSrv::envoiEmail ($oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom, $zToMail, "Forma2+" , $zSujet, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;

		}
	}

	//Stagiaire => Ajouter un champs permettant de savoir s'il a deja reservé une plage pour le test de debut ou pas
	static function majReservationClient ($_iClientId, $_iParam){
		$zQuery="UPDATE clients SET client_testDebut = " . $_iParam . " WHERE client_id = ".$_iClientId;
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zQuery);			
	}
}