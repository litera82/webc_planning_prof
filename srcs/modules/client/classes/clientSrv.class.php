<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage client
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
@ini_set ("memory_limit", -1) ;
@ini_set ("max_execution_time", 100000); 
class clientSrv 
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~client') ;
		return $oFac->get($_iId) ;
	}
	
	/**
	 * Creation d'un tableau d'objet selon critère
	 * @param array $_toParams tableau des parametres
	 * @param string $_zSortedField champ de trie (colone d'une table mysql)
	 * @param string $_zSortedDirection direction du trie
	 * @param int $_iStart premier enregistrement
	 * @param int $_iOffset nombre d'enregistrement affiché
	 *  @return array
	 */
	static function listCriteria($_toParams, $_zSortedField = 'client_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		if (isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUser = jAuth::getUserSession();
			$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		}

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * " ;
		$zSql .= " FROM clients ";
		$zSql .= " LEFT JOIN pays ";
		$zSql .= " ON (client_iPays = pays_id) ";
		$zSql .= " LEFT JOIN societe ON (client_iSociete = societe_id) ";
		$zSql .= " LEFT JOIN utilisateurs ON (client_iUtilisateurCreateurId = utilisateur_id) ";
		if ((isset($_toParams[0]->composant_cours) && $_toParams[0]->composant_cours == 1) || (isset($_toParams[0]->stagiaire_actif) && $_toParams[0]->stagiaire_actif == 1)){
			$zSql .= " LEFT JOIN composant_cours ON clients.client_iNumIndividu = composant_cours.NUMERO  " ;	
		}

		$zSql .= " WHERE 1 ";
		if (isset($_toParams[0]->id) && $_toParams[0]->id != ""){
			$zSql .= " AND client_id = ".$_toParams[0]->id;	
		}
		if (isset($_toParams[0]->nom) && $_toParams[0]->nom != ""){
			$zSql .= " AND client_zNom LIKE '%".addslashes($_toParams[0]->nom)."%'";	
		}
		if (isset($_toParams[0]->prenom) && $_toParams[0]->prenom != ""){
			$zSql .= " AND client_zPrenom LIKE '%".addslashes($_toParams[0]->prenom)."%'";	
		}
		if (isset($_toParams[0]->client_iUtilisateurCreateurId) && $_toParams[0]->client_iUtilisateurCreateurId != 0){
			$zSql .= " AND client_iUtilisateurCreateurId = " . $_toParams[0]->client_iUtilisateurCreateurId;	
		}elseif (isset($_toParams[0]->client_iUtilisateurCreateurId) && $_toParams[0]->client_iUtilisateurCreateurId == 0 && isset($_toParams[0]->fo) && $_toParams[0]->fo == 1){
			$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
			$zProf = $iUtilisateurId ;
			if (isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR){
				/*$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
				foreach($toTmpProfesseur as $oProfesseur){
					$zProf .= ",".$oProfesseur->utilisateur_id ;					
				}*/
				$toParamsUtilisateur['utilisateur_statut'] = 1;
				$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
				$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
				foreach($toTmpProfesseur['toListes'] as $oProfesseur){
					$zProf .= ",".$oProfesseur->utilisateur_id ;
				}
			}
			if ($zProf != ""){
				$zSql .= " AND client_iUtilisateurCreateurId IN (" . $zProf . ")";	
			}
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
			$zSql .= " AND client_iStatut = " . $_toParams[0]->statut;	
		}

		if (isset($_toParams[0]->client_testDebut) && $_toParams[0]->client_testDebut != 2){
			$zSql .= " AND client_testDebut = " . $_toParams[0]->client_testDebut;	
		}

		if (isset($_toParams[0]->societe) && $_toParams[0]->societe != ""){
			$_zSociete = trim($_toParams[0]->societe); 
			$tzSociete = explode (" ", $_zSociete);
			if (sizeof($tzSociete) == 1){
				$zSql .= " AND societe_zNom LIKE '%" . $tzSociete[0] ."%' "; 
			}else{
				if (sizeof($tzSociete) > 0){
					$zSql .= " AND " ;
					for($i=0; $i<sizeof($tzSociete); $i++){
						if ($i == 0){
							$zSql .= "(societe_zNom LIKE '%" . $tzSociete[$i] ."%' "; 
						}elseif ($i == sizeof($tzSociete)-1){
							$zSql .= " OR societe_zNom LIKE '%" . $tzSociete[$i] ."%')"; 
						}else{
							$zSql .= " OR societe_zNom LIKE '%" . $tzSociete[$i] ."%' "; 
						}
					}
				}
			}
		}
		if (isset($_toParams[0]->stagiaire_actif) && $_toParams[0]->stagiaire_actif == 1){
			$zSql .= " AND composant_cours.HEURES_PREVUES > composant_cours.HEURES_PRODUITES ";
		}

		$zSql .= " GROUP BY client_id ";
		//$zSql .= " GROUP BY client_zNom, client_zPrenom ";
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;

		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	static function listCriteriaComposantCours($_toParams, $_zSortedField = 'client_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		$zSql  = "" ;
		$zSql .= " SELECT *, DATE_FORMAT(composant_cours.Date_max_validation, '%d/%m/%Y') AS Date_max_validation_format " ;
		$zSql .= " FROM clients ";
		$zSql .= " LEFT JOIN pays ";
		$zSql .= " ON (client_iPays = pays_id) ";
		$zSql .= " LEFT JOIN societe ON (client_iSociete = societe_id) ";
		$zSql .= " LEFT JOIN utilisateurs ON (client_iUtilisateurCreateurId = utilisateur_id) ";
		$zSql .= " LEFT JOIN clientsolde ON (clientsolde_clientid = client_id) ";
		$zSql .= " LEFT JOIN composant_cours ON (clients.client_iNumIndividu = composant_cours.NUMERO OR clients.client_iNumIndividu = composant_cours.CODE_STAGIAIRE_MIRACLE) AND (TYPEPRODUCTION LIKE '%Tutorat'
            OR TYPEPRODUCTION LIKE '%téléphone') " ;	
		$zSql .= " WHERE 1 ";
		if (isset($_toParams[0]->id) && $_toParams[0]->id != ""){
			$zSql .= " AND client_id = ".$_toParams[0]->id;	
		}
//$zSql .= " AND (TYPEPRODUCTION LIKE '%Tutorat' OR TYPEPRODUCTION LIKE '%téléphone')";
//$zSql .= " AND composant_cours.Date_max_validation < NOW() LIMIT 0,1";
		$zSql .= " ORDER BY Date_max_validation_format DESC "; 
		$zSql .= " LIMIT 0,1";
//echo $zSql; die;                

		$oDBW	  = jDb::getDbWidget() ;

		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	static function getByNomPrenomEmail($zNom = "", $zPrenom = "", $zEMail = "") {
		jClasses::inc('commun~toolDate');

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * " ;
		$zSql .= " FROM clients ";
		$zSql .= " WHERE 1 ";
		if (isset($zNom) && $zNom != ""){
			$zSql .= " AND client_zNom = '".addslashes(trim($zNom))."'";	
		}
		if (isset($zPrenom) && $zPrenom != ""){
			$zSql .= " AND client_zPrenom = '".addslashes(trim($zPrenom))."'";	
		}
		if (isset($zEMail) && $zEMail != ""){
			$zSql .= " AND client_zMail = '".addslashes(trim($zEMail))."'";	
		}

		$zSql .= " GROUP BY client_id ";
		$zSql .= " ORDER BY client_id ASC ";  
		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}

	static function getListClientByNameFirstnameEmail($_zName, $_zFirstname, $_zEmail) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT * " ;
		$zSql .= " FROM clients ";
		$zSql .= " WHERE 1 ";
		if (isset($_zName) && $_zName != ""){
			$zSql .= " AND client_zNom = '".$_zName."'";	
		}
		if (isset($_zFirstname) && $_zFirstname != ""){
			$zSql .= " AND client_zPrenom = '".$_zFirstname."'";	
		}
		if (isset($_zEmail) && $_zEmail != ""){
			$zSql .= " AND client_zPrenom = '".$_zEmail."'";	
		}

		$zSql .= " GROUP BY client_id ";
		$zSql .= " ORDER BY client_id " ;  
		
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchFirst($zSql) ;

		return $toResults ;
	}
	/**
	 * Sauvegarde et modification
	 * @param array $_toParams les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($toInfos) 
	{		
		jClasses::inc('commun~tools');

		$oDaoFact = jDao::get('commun~client') ;
		$oRecord = null;
		$iId = isset($toInfos['client_id']) ? $toInfos['client_id'] : 0 ;
		if($iId <= 0) // nouveau
		{
			$oRecord = jDao::createRecord('commun~client') ;
		}
		else // update
		{
			$oRecord = $oDaoFact->get($iId) ;
		}

		$oRecord->client_iSociete		= isset($toInfos['client_iSociete']) ? $toInfos['client_iSociete'] : $oRecord->client_iSociete ;
		$oRecord->client_iSociete        = isset($toInfos['client_iSociete']) ? $toInfos['client_iSociete'] : $oRecord->client_iSociete ;
		$oRecord->client_iCivilite     = isset($toInfos['client_iCivilite']) ? $toInfos['client_iCivilite'] : $oRecord->client_iCivilite ;
		$oRecord->client_iUtilisateurCreateurId     = isset($toInfos['client_iUtilisateurCreateurId']) ? $toInfos['client_iUtilisateurCreateurId'] : $oRecord->client_iUtilisateurCreateurId ;
		$oRecord->client_zNom       = isset($toInfos['client_zNom']) ? $toInfos['client_zNom'] : $oRecord->client_zNom ;
		$oRecord->client_zPrenom      = isset($toInfos['client_zPrenom']) ? $toInfos['client_zPrenom'] : $oRecord->client_zPrenom ;
		$oRecord->client_zFonction        = isset($toInfos['client_zFonction']) ? $toInfos['client_zFonction'] : $oRecord->client_zFonction ;
		$oRecord->client_zMail     = isset($toInfos['client_zMail']) ? $toInfos['client_zMail'] : $oRecord->client_zMail ;
		$oRecord->client_zLogin     = isset($toInfos['client_zLogin']) ? $toInfos['client_zLogin'] : $oRecord->client_zLogin ;
		$oRecord->client_zPass     = isset($toInfos['client_zPass']) ? $toInfos['client_zPass'] : $oRecord->client_zPass ;
		$oRecord->client_zTel     = isset($toInfos['client_zTel']) ? $toInfos['client_zTel'] : $oRecord->client_zTel ;
		$oRecord->client_zPortable     = isset($toInfos['client_zPortable']) ? $toInfos['client_zPortable'] : $oRecord->client_zPortable ;
		$oRecord->client_zRue     = isset($toInfos['client_zRue']) ? $toInfos['client_zRue'] : $oRecord->client_zRue ;
		$oRecord->client_zVille     = isset($toInfos['client_zVille']) ? $toInfos['client_zVille'] : $oRecord->client_zVille ;
		$oRecord->client_zCP     = isset($toInfos['client_zCP']) ? $toInfos['client_zCP'] : $oRecord->client_zCP ;
		$oRecord->client_iPays     = isset($toInfos['client_iPays']) ? $toInfos['client_iPays'] : $oRecord->client_iPays ;
		$oRecord->client_iNumIndividu     = isset($toInfos['client_iNumIndividu']) ? $toInfos['client_iNumIndividu'] : $oRecord->client_iNumIndividu ;
		$oRecord->client_iRefIndividu     = isset($toInfos['client_iRefIndividu']) ? $toInfos['client_iRefIndividu'] : $oRecord->client_iRefIndividu ;
		$oRecord->client_zCryptedKey     = isset($toInfos['client_zCryptedKey']) ? $toInfos['client_zCryptedKey'] : $oRecord->client_zCryptedKey ;
		$oRecord->client_iStatut     = isset($toInfos['client_iStatut']) ? $toInfos['client_iStatut'] : $oRecord->client_iStatut ;
		$oRecord->client_testDebut     = isset($toInfos['client_testDebut']) ? $toInfos['client_testDebut'] : $oRecord->client_testDebut ;
		
		if (is_null($oRecord->client_zPrenom) || $oRecord->client_zPrenom == ""){
			$oRecord->client_zPrenom = $oRecord->client_zNom ;
		}

		if($iId <= 0)
		{
			$oRecord->client_dateCreation = date('Y-m-d H:i:s');

			$oProfil = jDb::getProfil(); 
			$bTestProfil = tools::testProfil($oProfil); 
			if (!$bTestProfil){tools::createConnector($oProfil);} 
			$oCnx = jDb::getConnection(); 

			$oDaoFact->insert($oRecord) ;
		} 
		if($iId > 0)
		{
			if (isset($oRecord->client_dateCreation) && is_null($oRecord->client_dateCreation)){
				$oRecord->client_dateCreation = date('Y-m-d H:i:s');
			}
			$oRecord->client_dateMaj = date('Y-m-d H:i:s');
			$oProfil = jDb::getProfil(); 
			$bTestProfil = tools::testProfil($oProfil); 
			if (!$bTestProfil){tools::createConnector($oProfil);} 
			$oCnx = jDb::getConnection(); 

			$oDaoFact->update($oRecord);
		}

		if (isset($toInfos['sendMail']) && $toInfos['sendMail'] == 1){
			self::sendMailClient($oRecord);
		}
		
		$bInvit = false ;

		if (isset($toInfos['sendMailRelanceAuto']) && $toInfos['sendMailRelanceAuto'] == 2 && isset($toInfos['content_mail_relance']) && $toInfos['content_mail_relance'] != ''){
			self::sendMailClientPropositionRelanceAutoplannification($oRecord, $toInfos);
			$bInvit = true;
		}else if (isset($toInfos['sendMailAuto']) && $toInfos['sendMailAuto'] == 1 && isset($toInfos['content_mail_auto']) && $toInfos['content_mail_auto'] != ''){
			self::sendMailClientPropositionAutoplannificationPremierCours($oRecord, $toInfos);
			$bInvit = true;
		}else if (isset($toInfos['sendMailChangeProf']) && $toInfos['sendMailChangeProf'] == 3 && isset($toInfos['content_mail_changeprof']) && $toInfos['content_mail_changeprof'] != ''){
			self::sendMailClientPropositionChangeProfAutoplannification($oRecord, $toInfos);
			//$bInvit = true;
		}else if (isset($toInfos['sendMailPerso']) && $toInfos['sendMailPerso'] == 4 && isset($toInfos['content_mail_perso']) && $toInfos['content_mail_perso'] != ''){
			self::sendMailClientPerso($oRecord, $toInfos);
			//$bInvit = true;
		}

		if ($bInvit) {
			$iClientId = 0 ;
			if ($iId >0){
				$iClientId = $iId ;
			}elseif (isset($oRecord->client_id) && $oRecord->client_id > 0){
				$iClientId = $oRecord->client_id ;
			} 
			if ($iClientId > 0){
				jClasses::inc('client~clientsautoSrv');
				$oClientAuto = clientsautoSrv::getByClientId ($iClientId);
				if ($oClientAuto != null && isset ($oClientAuto->clientsauto_id) && $oClientAuto->clientsauto_id > 0){
					$oClientAuto->clientsauto_dateinvit = date ('Y-m-d') ;
				}else{
					$oClientAuto = new StdClass  ();
					$oClientAuto->clientsauto_clientid = $iClientId ;
					$oClientAuto->clientsauto_dateinvit = date ('Y-m-d') ;
					$oClientAuto->clientsauto_auto = NULL ;
				}
				clientsautoSrv::save ($oClientAuto) ;
			}
		}
		// Save environnement stagiaire
		if (isset ($oRecord->client_id) && $oRecord->client_id > 0){
			$oEnvClient = new StdClass ();
			$oEnvClient->id				= $toInfos['id'];;
			$oEnvClient->clientId		= $oRecord->client_id;
			$oEnvClient->bureau			= $toInfos['bureau'];
			$oEnvClient->navigateur		= $toInfos['navigateur'];
			$oEnvClient->telFixe		= $toInfos['telFixe'];
			$oEnvClient->telMobile		= $toInfos['telMobile'];
			$oEnvClient->skype			= $toInfos['skype'];
			$oEnvClient->casqueSkype	= $toInfos['casqueSkype'];

			jClasses::inc('client~clientsenvironnementSrv');
			clientsenvironnementSrv::save($oEnvClient) ;
		}
		return $oRecord ;
	}

	
	static function sendMailClient ($oClient){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$oUtilisateur = utilisateursSrv::getById($oClient->client_iUtilisateurCreateurId); 
		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('oClient', $oClient) ;
		$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'auto.php') ;

		$tpl = $tplMail->fetch ('client~corpsMailConfirmationCreationStagiaire') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , MAIL_OBJECT_CONFIRMATION_CREATION_COMPTE, $tpl,  NULL, NULL, true, NULL, NULL, NULL, NULL) ;
	}	

	static function sendMailClientPropositionAutoplannification ($oClient){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$oUtilisateur = utilisateursSrv::getById($oClient->client_iUtilisateurCreateurId); 
		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('oClient', $oClient) ;
		$tplMail->assign ('oUtilisateur', $oUtilisateur) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;

		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass) ;

		$tpl = $tplMail->fetch ('client~corpsMailPropositionAutoplannification') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , MAIL_OBJECT_PROPOSITION_AUTOPLANNIFICATION, $tpl,  NULL, NULL, true, NULL, NULL, NULL, MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1) ;
	}	

	static function sendMailClientPropositionRelanceAutoplannification ($oClient, $toInfos){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zContentMail', $toInfos["content_mail_relance"]) ;

		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass) ;

		$tpl = $tplMail->fetch ('client~corpsMailPropositionAutoplannificationRelance') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $toInfos["objet_sendMailRelanceAuto"], $tpl,  NULL, NULL, true, NULL, NULL, NULL, array("helene.schandeler@forma2plus.com", MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1)) ;
	}	

	static function sendMailClientPropositionAutoplannificationPremierCours ($oClient, $toInfos){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zContentMail', $toInfos["content_mail_auto"]) ;

		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass) ;

		$tpl = $tplMail->fetch ('client~corpsMailPropositionAutoplannificationRelance') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $toInfos["objet_sendMailAuto"], $tpl,  NULL, NULL, true, NULL, NULL, NULL, MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1) ;
	}	

	static function sendMailClientPropositionChangeProfAutoplannification ($oClient, $toInfos){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zContentMail', $toInfos["content_mail_changeprof"]) ;

		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass) ;

		$tpl = $tplMail->fetch ('client~corpsMailPropositionAutoplannificationRelance') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $toInfos["objet_sendMailChangeProf"], $tpl,  NULL, NULL, true, NULL, NULL, NULL, MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1) ;
	}	
	static function sendMailClientPerso ($oClient, $toInfos){
		jClasses::inc('utilisateurs~utilisateursSrv');
		jClasses::inc('commun~mailSrv');

		$zUrlToIndex = jUrl::get('jelix_calendar~FoCalendar:index');

		$tplMail = new jTpl();
		$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
		$tplMail->assign ('zUrlToIndex', $zUrlToIndex) ;
		$tplMail->assign ('zContentMail', $toInfos["content_mail_perso"]) ;

		$tplMail->assign ('zUrlToIndexStagiaire', URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass) ;

		$tpl = $tplMail->fetch ('client~corpsMailPropositionAutoplannificationRelance') ;

		mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zNom .' '.$oClient->client_zPrenom , $toInfos["objet_sendMailPerso"], $tpl,  NULL, NULL, true, NULL, NULL, NULL, MAIL_AUTOCOURS_COPIEDESEMAILSENVOYES1) ;
	}	

	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact = jDao::get('commun~client') ;
        $oDaoFact->delete($_iId) ;
	}
	
	static function rechercherStagiaire ($_zStagiaire){
		jClasses::inc('commun~toolDate');
        $oCnx = jDb::getConnection () ;
        $toResults = array();
		$_zStagiaire = trim($_zStagiaire); 
		$tzStagiaire = explode (" ", $_zStagiaire);
		$zSql = " SELECT * FROM clients, societe " ;
		$zSql .= " WHERE client_iSociete = societe_id " ;
		$zSql .= " AND "; 
		$zCritere = "";
		if (sizeof($tzStagiaire) == 1){
			$zCritere .= "(client_zNom LIKE '%" . $tzStagiaire[0] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[0] ."%')"; 
		}else{
			for($i=0; $i<sizeof($tzStagiaire); $i++){
				if ($i == 0){
					$zCritere .= "(client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%'"; 
				}elseif ($i == sizeof($tzStagiaire)-1){
					$zCritere .= " OR client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%')"; 
				}else{
					$zCritere .= " OR client_zNom LIKE '%" . $tzStagiaire[$i] ."%' OR client_zPrenom LIKE '%" . $tzStagiaire[$i] ."%'"; 
				}
			}
		}
		if ($zCritere != ""){
		$zSql .= $zCritere;
		}
		$zSql .= " ORDER BY client_zNom ASC ";  

	    $Rs = $oCnx->query($zSql);

        while($oRecord = $Rs->fetch())
		{
			array_push($toResults,$oRecord);
		}
		return $toResults;
	}	
        
        
	/**
	 * Creation de l'objet en fonction de son zCryptedKey
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getByCryptedKey($_zCryptedKey) 
	{
		$oFac = jDao::create('commun~client') ;
                $oCond = jDao::createConditions() ;
                $oCond->addCondition('client_zCryptedKey', '=', $_zCryptedKey) ;
		return $oFac->findBy($oCond)->fetch() ;
	}
        
        
	/**
	 * Creation de l'objet en fonction de son numero individu
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getClientByNumIndividu($_iNumIndividu) 
	{
		jClasses::inc('commun~tools');

		$oFac = jDao::create('commun~client') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('client_iNumIndividu', '=', $_iNumIndividu) ;

		$oProfil = jDb::getProfil(); 
		$bTestProfil = tools::testProfil($oProfil); 
		if (!$bTestProfil){tools::createConnector($oProfil);} 
		$oCnx = jDb::getConnection(); 

		return $oFac->findBy($oCond)->fetch() ;
	}

	/**
	 * Creation de l'objet en fonction de son login et motde passe
	 * @param int $_zLogin 
	 * @param int $_zPwd
	 * @return object
	 */
	static function getClientByLoginPassword($_zLogin, $_zPwd) 
	{
		$zSql = "SELECT * FROM clients WHERE client_zLogin = '".trim(addslashes($_zLogin))."' AND  client_zPass = '".trim(addslashes($_zPwd))."'";
		$oDBW	  = jDb::getDbWidget() ;
		$oClient = $oDBW->fetchFirst($zSql) ;
		return $oClient; 
	}
	static function getClientCodeStagiaireMiracle($_iNumIndividu) 
	{
		$zSql = "SELECT CODE_STAGIAIRE_MIRACLE as code
		FROM clients
		  INNER JOIN composant_cours
			ON clients.client_iNumIndividu = composant_cours.NUMERO
		WHERE client_iNumIndividu = '" . $_iNumIndividu . "'";
		$oDBW	  = jDb::getDbWidget() ;
		$oClient = $oDBW->fetchFirst($zSql) ;
		if (isset ($oClient) && isset ($oClient->code) && $oClient->code > 0){
			return $oClient->code ;
		}else{
			return null;
		}
	}

	static function exportSuivieStagiaire1() 
	{

		$zSql = "SELECT *
		FROM clients
		INNER JOIN evenement
		ON clients.client_id = evenement.evenement_iStagiaire
		INNER JOIN clientsauto ON clients.client_id = clientsauto.clientsauto_clientid 
		LEFT JOIN utilisateurs
		ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
		WHERE evenement.evenement_zDateHeureDebut > DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
		GROUP BY client_id  ORDER BY utilisateur_id ASC ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		$toSuivieStagiaire = array () ;

		foreach ($toResults as $oResults){
			$oSuivieStagiaire = new StdClass ();
			$oSuivieStagiaire->suiviestagiaire_dateprevcours = "0000-00-00" ;
			$oSuivieStagiaire->suiviestagiaire_dateauto = "0000-00-00" ; 
			$oSuivieStagiaire->suiviestagiaire_dateinvit = "0000-00-00" ; 
			$oSuivieStagiaire->suiviestagiaire_heureprevcours = "00:00:00" ; 

			if (isset ($oResults->client_id) && $oResults->client_id > 0){
				$zSql1 = "SELECT evenement_zDateHeureDebut
				FROM clients
				INNER JOIN evenement
				ON clients.client_id = evenement.evenement_iStagiaire
				WHERE evenement.evenement_zDateHeureDebut < DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
				AND client_id IN(".$oResults->client_id.")
				GROUP BY client_id	";	
				$oDBW	  = jDb::getDbWidget() ;
				$oDate = $oDBW->fetchFirst($zSql1) ;

				if (isset($oDate->evenement_zDateHeureDebut) && $oDate->evenement_zDateHeureDebut != null){
					$tDatePrevCours = explode (" ", $oDate->evenement_zDateHeureDebut); 
					$oSuivieStagiaire->suiviestagiaire_dateprevcours = $tDatePrevCours[0];
					$oSuivieStagiaire->suiviestagiaire_heureprevcours = $tDatePrevCours[1];

				}

				$zSql2 = "SELECT
				clientsauto.*
				FROM clients
				INNER JOIN clientsauto
				ON clients.client_id = clientsauto.clientsauto_clientid
				AND client_id IN(".$oResults->client_id.") ";
				$oDBW	  = jDb::getDbWidget() ;
				$oDate1 = $oDBW->fetchFirst($zSql2) ;

				if (isset($oDate1) && $oDate1 != null){
					$oSuivieStagiaire->suiviestagiaire_dateauto = $oDate1->clientsauto_auto;
					$oSuivieStagiaire->suiviestagiaire_dateinvit = $oDate1->clientsauto_dateinvit;
				}
			}
			
			$tDateNextCours = explode (" ", $oResults->evenement_zDateHeureDebut);

			$oSuivieStagiaire->suiviestagiaire_numindividu			= $oResults->client_iNumIndividu ;
			$oSuivieStagiaire->suiviestagiaire_nom					= $oResults->client_zNom ;
			$oSuivieStagiaire->suiviestagiaire_prenom				= $oResults->client_zPrenom ;
			$oSuivieStagiaire->suiviestagiaire_datenextcours		= $tDateNextCours[0] ;
			$oSuivieStagiaire->suiviestagiaire_heurenextcours		= $tDateNextCours[1] ;
			$oSuivieStagiaire->suiviestagiaire_prof					= $oResults->utilisateur_zPrenom . " " . $oResults->utilisateur_zNom ;
			$oSuivieStagiaire->suiviestagiaire_proftel				= $oResults->utilisateur_zTel;

			array_push($toSuivieStagiaire, $oSuivieStagiaire); 
		}
		$zMediaValues = "";
		$iSize = sizeof($toSuivieStagiaire); 
		$iCpt = 0;

		foreach ($toSuivieStagiaire as $oTmpSuivieStagiaire){
			$zMediaValues .= ($iCpt < 2)? "(" : ",( ";
			$zMediaValues .= "'".  nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_numindividu)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_nom)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prenom)) . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateinvit . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateauto . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heureprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_datenextcours . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heurenextcours . "'"; 
			$zMediaValues .= ", '".  nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prof)) . "'";  
			$zMediaValues .= ", '".  nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_proftel)) . "')";  
			$zMediaValues .= ($iCpt == 0 && $iSize > 1)? "," : " ";
			$iCpt ++;
		}
		if ($zMediaValues != ""){
			$zSqlInsert = "INSERT INTO suiviestagiaire (
							suiviestagiaire_numindividu
							, suiviestagiaire_nom
							, suiviestagiaire_prenom
							, suiviestagiaire_dateinvit
							, suiviestagiaire_dateauto
							, suiviestagiaire_dateprevcours
							, suiviestagiaire_heureprevcours
							, suiviestagiaire_datenextcours
							, suiviestagiaire_heurenextcours
							, suiviestagiaire_prof
							, suiviestagiaire_proftel
			) VALUES " . $zMediaValues ;
			$zSqlTruncate=" TRUNCATE TABLE suiviestagiaire ";

			$oCnx = jDb::getConnection();
			$bOk = 1001;
			try{
				$oCnx->exec($zSqlTruncate);	
				$oRes = $oCnx->exec($zSqlInsert);	
				$bOk = 1003;
			}catch(Exception $e){
				$e->getMessage();
			}
			return $bOk;
		}
		return 1003;
	}



	static function exportSuivieStagiaire2() 
	{

		$zSql = "SELECT *
					FROM clients
					  INNER JOIN evenement
						ON clients.client_id = evenement.evenement_iStagiaire
					  LEFT JOIN clientsauto
						ON clients.client_id = clientsauto.clientsauto_clientid
					  LEFT JOIN utilisateurs
						ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
					WHERE evenement.evenement_zDateHeureDebut BETWEEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 365 DAY), '%Y-%m-%d 00:00:00')
						AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 365 DAY), '%Y-%m-%d 00:00:00')
						 OR clientsauto.clientsauto_dateinvit IS NOT NULL
						 OR clientsauto.clientsauto_auto IS NOT NULL
					GROUP BY client_id
					ORDER BY clientsauto_dateinvit DESC, evenement_zDateHeureDebut DESC ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		$toSuivieStagiaire = array () ;
		foreach ($toResults as $oResults){
			$zSql1 = "SELECT evenement_zDateHeureDebut, 
			FROM clients
			INNER JOIN evenement
			ON clients.client_id = evenement.evenement_iStagiaire
			WHERE evenement.evenement_zDateHeureDebut < DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
			AND client_id IN(".$oResults->client_id.")
			GROUP BY client_id	";	
			$oDBW	  = jDb::getDbWidget() ;
			$oDate = $oDBW->fetchFirst($zSql1) ;

			$zSql2 = "SELECT evenement_zDateHeureDebut
			FROM clients
			INNER JOIN evenement
			ON clients.client_id = evenement.evenement_iStagiaire
			WHERE evenement.evenement_zDateHeureDebut >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
			AND client_id IN(".$oResults->client_id.")
			GROUP BY client_id	";	
			$oDBW	  = jDb::getDbWidget() ;
			$oDateNextCours = $oDBW->fetchFirst($zSql2) ;
			
			$oSuivieStagiaire = new StdClass ();

			$oSuivieStagiaire->suiviestagiaire_numindividu			= $oResults->client_iNumIndividu ;
			$oSuivieStagiaire->suiviestagiaire_nom					= $oResults->client_zNom ;
			$oSuivieStagiaire->suiviestagiaire_prenom				= $oResults->client_zPrenom ;
			$oSuivieStagiaire->suiviestagiaire_dateinvit			= $oResults->clientsauto_dateinvit;
			$oSuivieStagiaire->suiviestagiaire_dateauto				= $oResults->clientsauto_auto;

			$oSuivieStagiaire->suiviestagiaire_dateprevcours = null;
			$oSuivieStagiaire->suiviestagiaire_heureprevcours = null;

			if (isset($oDate->evenement_zDateHeureDebut) && $oDate->evenement_zDateHeureDebut != null){
				$tDatePrevCours = explode (" ", $oDate->evenement_zDateHeureDebut); 
				$oSuivieStagiaire->suiviestagiaire_dateprevcours = $tDatePrevCours[0];
				$oSuivieStagiaire->suiviestagiaire_heureprevcours = $tDatePrevCours[1];
			}

			$oSuivieStagiaire->suiviestagiaire_datenextcours		= null;
			$oSuivieStagiaire->suiviestagiaire_heurenextcours		= null;

			if (isset($oDateNextCours->evenement_zDateHeureDebut) && $oDateNextCours->evenement_zDateHeureDebut != null){
				$tDateNextCours = explode (" ", $oDateNextCours->evenement_zDateHeureDebut);
				$oSuivieStagiaire->suiviestagiaire_datenextcours		= $tDateNextCours[0] ;
				$oSuivieStagiaire->suiviestagiaire_heurenextcours		= $tDateNextCours[1] ;
			}
			$oSuivieStagiaire->suiviestagiaire_prof					= $oResults->utilisateur_zPrenom . " " . $oResults->utilisateur_zNom ;
			$oSuivieStagiaire->suiviestagiaire_proftel				= $oResults->utilisateur_zTel;

			array_push($toSuivieStagiaire, $oSuivieStagiaire); 
		}
		$zMediaValues = "";
		$iSize = sizeof($toSuivieStagiaire); 
		$iCpt = 0;

		foreach ($toSuivieStagiaire as $oTmpSuivieStagiaire){
			$zMediaValues .= ($iCpt < 2)? "(" : ",( ";
			$zMediaValues .= "'".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_numindividu))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_nom))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prenom))) . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateinvit . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateauto . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heureprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_datenextcours . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heurenextcours . "'"; 
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prof))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_proftel))) . "')";  
			$zMediaValues .= ($iCpt == 0 && $iSize > 1)? "," : " ";
			$iCpt ++;
		}
		if ($zMediaValues != ""){
			$zSqlInsert = "INSERT INTO suiviestagiaire (
							suiviestagiaire_numindividu
							, suiviestagiaire_nom
							, suiviestagiaire_prenom
							, suiviestagiaire_dateinvit
							, suiviestagiaire_dateauto
							, suiviestagiaire_dateprevcours
							, suiviestagiaire_heureprevcours
							, suiviestagiaire_datenextcours
							, suiviestagiaire_heurenextcours
							, suiviestagiaire_prof
							, suiviestagiaire_proftel
			) VALUES " . $zMediaValues ;
			$zSqlTruncate=" TRUNCATE TABLE suiviestagiaire ";

			$oCnx = jDb::getConnection();
			$bOk = 1001;
			try{
				$oCnx->exec($zSqlTruncate);	
				$oRes = $oCnx->exec($zSqlInsert);	
				$bOk = 1003;
			}catch(Exception $e){
				$e->getMessage();
			}
			return $bOk;
		}
		return 1003;
	}

	/**
	*
	*
	*
	*
	*/
	static function exportSuivieStagiaire() 
	{

		$zSql = "SELECT *
					FROM clientsauto
					  INNER JOIN clients
						ON clientsauto.clientsauto_clientid = clients.client_id
					WHERE 1 = 1
						AND clientsauto.clientsauto_dateinvit BETWEEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
						AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
						 OR clientsauto.clientsauto_auto BETWEEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
						AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
					ORDER BY clientsauto_dateinvit ASC ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults0 = $oDBW->fetchAll($zSql) ;


		$zSql1 = "SELECT
					  clients.*,
					  clientsauto.*
					FROM evenement
					  INNER JOIN utilisateurs
						ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
					  INNER JOIN clients
						ON evenement.evenement_iStagiaire = clients.client_id
					  LEFT JOIN clientsauto
						ON clientsauto.clientsauto_clientid = clients.client_id
					WHERE 1 = 1
						AND evenement.evenement_zDateHeureDebut BETWEEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
						AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 120 DAY), '%Y-%m-%d 00:00:00')
						AND evenement.evenement_iStagiaire NOT IN(SELECT
																	clientsauto_clientid
																  FROM clientsauto)
					GROUP BY client_id
					ORDER BY evenement_id ASC ";
		$oDBW	  = jDb::getDbWidget() ;
		$toResults1 = $oDBW->fetchAll($zSql1) ;
		$toResults = array_merge ($toResults0, $toResults1);
		$toSuivieStagiaire = array () ;

		foreach ($toResults as $oResults){
			$zSql1 = "SELECT evenement_zDateHeureDebut, evenement_iUtilisateurId
			FROM evenement 
			WHERE evenement.evenement_zDateHeureDebut < DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
			AND evenement_iStagiaire IN(".$oResults->client_id.")
			GROUP BY evenement_iStagiaire ";	
			$oDBW	  = jDb::getDbWidget() ;
			$oDate = $oDBW->fetchFirst($zSql1) ;

			$zSql2 = "SELECT evenement_zDateHeureDebut
			FROM evenement
			WHERE evenement.evenement_zDateHeureDebut >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')
			AND evenement_iStagiaire IN(".$oResults->client_id.")
			GROUP BY evenement_iStagiaire	";	
			$oDBW	  = jDb::getDbWidget() ;
			$oDateNextCours = $oDBW->fetchFirst($zSql2) ;
			
			$oSuivieStagiaire = new StdClass ();

			$oSuivieStagiaire->suiviestagiaire_numindividu			= $oResults->client_iNumIndividu ;
			$oSuivieStagiaire->suiviestagiaire_nom					= $oResults->client_zNom ;
			$oSuivieStagiaire->suiviestagiaire_prenom				= $oResults->client_zPrenom ;
			$oSuivieStagiaire->suiviestagiaire_dateinvit			= $oResults->clientsauto_dateinvit;
			$oSuivieStagiaire->suiviestagiaire_dateauto				= $oResults->clientsauto_auto;

			$oSuivieStagiaire->suiviestagiaire_dateprevcours = null;
			$oSuivieStagiaire->suiviestagiaire_heureprevcours = null;

			if (isset($oDate->evenement_zDateHeureDebut) && $oDate->evenement_zDateHeureDebut != null){
				$tDatePrevCours = explode (" ", $oDate->evenement_zDateHeureDebut); 
				$oSuivieStagiaire->suiviestagiaire_dateprevcours = $tDatePrevCours[0];
				$oSuivieStagiaire->suiviestagiaire_heureprevcours = $tDatePrevCours[1];
			}

			$oSuivieStagiaire->suiviestagiaire_datenextcours		= null;
			$oSuivieStagiaire->suiviestagiaire_heurenextcours		= null;

			if (isset($oDateNextCours->evenement_zDateHeureDebut) && $oDateNextCours->evenement_zDateHeureDebut != null){
				$tDateNextCours = explode (" ", $oDateNextCours->evenement_zDateHeureDebut);
				$oSuivieStagiaire->suiviestagiaire_datenextcours		= $tDateNextCours[0] ;
				$oSuivieStagiaire->suiviestagiaire_heurenextcours		= $tDateNextCours[1] ;
			}

			$oSuivieStagiaire->suiviestagiaire_prof					= null ;
			$oSuivieStagiaire->suiviestagiaire_proftel				= null ;
			$iProf = 0 ;
			if (isset($oDate->evenement_iUtilisateurId) && $oDate->evenement_iUtilisateurId != null){
				$iProf = $oDate->evenement_iUtilisateurId;
			}else if (isset($oResults->client_iUtilisateurCreateurId) && $oResults->client_iUtilisateurCreateurId != null){
				$iProf = $oResults->client_iUtilisateurCreateurId;
			}
			if ($iProf > 0){
				jClasses::inc ('utilisateurs~utilisateursSrv') ;
				$oUtilisateurs = utilisateursSrv::getById ($iProf) ;
				$oSuivieStagiaire->suiviestagiaire_prof				= $oUtilisateurs->utilisateur_zPrenom . " " . $oUtilisateurs->utilisateur_zNom ;
				$oSuivieStagiaire->suiviestagiaire_proftel			= $oUtilisateurs->utilisateur_zTel;
			}
			array_push($toSuivieStagiaire, $oSuivieStagiaire); 
		}
		$zMediaValues = "";
		$iSize = sizeof($toSuivieStagiaire); 
		$iCpt = 0;

		foreach ($toSuivieStagiaire as $oTmpSuivieStagiaire){
			$zMediaValues .= ($iCpt < 2)? "(" : ",( ";
			$zMediaValues .= "'".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_numindividu))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_nom))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prenom))) . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateinvit . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateauto . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_dateprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heureprevcours . "'";  
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_datenextcours . "'"; 
			$zMediaValues .= ", '". $oTmpSuivieStagiaire->suiviestagiaire_heurenextcours . "'"; 
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_prof))) . "'";  
			$zMediaValues .= ", '".  trim(nl2br(addslashes($oTmpSuivieStagiaire->suiviestagiaire_proftel))) . "')";  
			$zMediaValues .= ($iCpt == 0 && $iSize > 1)? "," : " ";
			$iCpt ++;
		}
		if ($zMediaValues != ""){
			$zSqlInsert = "INSERT INTO suiviestagiaire (
							suiviestagiaire_numindividu
							, suiviestagiaire_nom
							, suiviestagiaire_prenom
							, suiviestagiaire_dateinvit
							, suiviestagiaire_dateauto
							, suiviestagiaire_dateprevcours
							, suiviestagiaire_heureprevcours
							, suiviestagiaire_datenextcours
							, suiviestagiaire_heurenextcours
							, suiviestagiaire_prof
							, suiviestagiaire_proftel
			) VALUES " . $zMediaValues ;
			$zSqlTruncate=" TRUNCATE TABLE suiviestagiaire ";

			$oCnx = jDb::getConnection();
			$bOk = 1001;
			try{
				$oCnx->exec($zSqlTruncate);	
				$oRes = $oCnx->exec($zSqlInsert);	
				$bOk = 1003;
			}catch(Exception $e){
				$e->getMessage();
			}
			return $bOk;
		}
		return 1003;
	}

	static function countEventByClientId ($iClientId){
		$zSql = "SELECT COUNT(*) AS iResTotal FROM evenement WHERE evenement.evenement_iStagiaire = " . $iClientId ; 
		$oDBW	  = jDb::getDbWidget();
		$oCount = $oDBW->fetchFirst($zSql) ;
		return $oCount->iResTotal ;
	}
	static function countClientsAutoByClientId ($iClientId){
		$zSql = "SELECT COUNT(*) AS iResTotal FROM clientsauto WHERE clientsauto.clientsauto_clientid = " . $iClientId ; 
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
		return $oCount->iResTotal ;
	}
	static function countClientsEnvironnementByClientId ($iClientId){
		$zSql = "SELECT COUNT(*) AS iResTotal FROM clientsenvironnement WHERE clientsenvironnement.clientId = " . $iClientId ; 
		$oDBW	  = jDb::getDbWidget() ;
		$oCount = $oDBW->fetchFirst($zSql) ;
		if ($oCount->iResTotal > 0){
			$zQuery="DELETE FROM clientsenvironnement WHERE clientsenvironnement.clientId = " .$iClientId ;
			$oCnx = jDb::getConnection();
			$oRes = $oCnx->exec($zQuery);	
		}
		return 1 ;
	}
	static function setSoldeClient($iStagiaire, $iEventId){
		jClasses::inc('client~clientsoldeSrv');
		jClasses::inc('client~clientSrv');

		$oClientSolde = clientsoldeSrv::getByClientId($iStagiaire) ;
		$toParams = array();
		$toParams[0] = new stdClass();
		$toParams[0]->id = $iStagiaire;
		$toCC = clientSrv::listCriteriaComposantCours($toParams);
  
		if (isset($oClientSolde->clientsolde_id) && $oClientSolde->clientsolde_id > 0){
			// MAJ 
			if (isset($toCC['iResTotal']) && $toCC['iResTotal'] > 0){
				$oClientSolde->clientsolde_prevu  = $toCC['toListes'][0]->HEURES_PREVUES;
			}
			$oClientSolde->clientsolde_solde -= 0.5; 
			$oClientSolde->clientsolde_produit += 0.5; 
			$oClientSolde->clientsolde_eventid = $iEventId; 
			clientsoldeSrv::save($oClientSolde);
		}else{
			if (isset($toCC['iResTotal']) && $toCC['iResTotal'] > 0){
				$oNewClientSolde = new stdClass ();
				$oNewClientSolde->clientsolde_id = NULL;
				$oNewClientSolde->clientsolde_clientid = $toParams[0]->id; 
				$oNewClientSolde->clientsolde_eventid = $iEventId; 
				$oNewClientSolde->clientsolde_solde = $toCC['toListes'][0]->HEURES_PREVUES - $toCC['toListes'][0]->HEURES_PRODUITES - 0.5;
				$oNewClientSolde->clientsolde_prevu  = $toCC['toListes'][0]->HEURES_PREVUES;
				$oNewClientSolde->clientsolde_produit  = $toCC['toListes'][0]->HEURES_PRODUITES + 0.5; 
				clientsoldeSrv::save($oNewClientSolde);
			}
		}
	}
}