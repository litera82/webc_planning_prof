<?php
/**
* @package   jelix_calendar
* @subpackage client
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class FoClientCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function add() {
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->bodyTpl = "client~FoAjoutClient" ;
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/stagiaire.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iClientId = $this->param('iClientId', 0, true);
		$tEvent = $this->request->params; 
		if (isset ($_SESSION['tEvent'])){
			unset($tEvent);
		}
		if (isset($_SESSION['tEvent'])){
			$_SESSION['tEvent'] = $tEvent;
		}
		$oRep->body->assignZone('oZoneLegend', 'commun~FoLegende', array());
		$oRep->body->assignZone('oZoneAjoutClient', 'client~FoAjoutClient', array('iEvenementId'=> $iEvenementId, 'iClientId'=>$iClientId));
		return $oRep;
    }


	function autocompleteSociete(){
		$oRep = $this->getResponse('encodedJson');
		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql=sprintf("SELECT * FROM societe WHERE ");
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" societe_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql .= implode(" OR ", $t);
		$zSql .= " GROUP BY societe_id ORDER BY societe_zNom ASC ";  
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->query($zSql);
		$oRep->datas = $oRes->fetchAll();

		return $oRep;
	}


	function save() {
    	$toParams = $this->params() ;

		jClasses::inc('client~clientSrv');
 		jClasses::inc('client~societeSrv');

		if (isset($toParams['client_iSociete']) && $toParams['client_iSociete'] == 0 && isset($toParams['client_zSociete']) && $toParams['client_zSociete'] != ""){
			$toSociete['societe_zNom'] = $toParams['client_zSociete'];
			$toSociete['societe_iStatut'] = 1;
			$oNewSociete = societeSrv::save($toSociete) ;
			if (is_object ($oNewSociete) && isset($oNewSociete->societe_id) && $oNewSociete->societe_id > 0){
				$toParams['client_iSociete'] = $oNewSociete->societe_id ;
			}
		}

        $oclient = clientSrv::save($toParams) ;
		$iEvenementId = $toParams['iEvenementId'];
        $oResp = $this->getResponse('redirect') ;
		if (isset ($_SESSION['tEvent'])){
			$tEvent = $_SESSION['tEvent'];
			if (isset ($tEvent['dtcm_event_rdv'])){
				$tzdtcm_event_rdv = explode (' ', $tEvent['dtcm_event_rdv']);
				$tzDate = explode ('/', $tzdtcm_event_rdv[0]);
				$tzTime = explode (':', $tzdtcm_event_rdv[1]);
				
				$zDate = $tzDate[2].'-'.$tzDate[1].'-'.$tzDate[0];
				$iTime = $tzTime[0].':'.$tzTime[1];

				$oResp->action = 'evenement~FoEvenement:add' ;
				$oResp->params = array('iEvenementId'=>$iEvenementId, 'zDate'=>$zDate, 'iTime'=>$iTime);
			}else{
				$oResp->action = 'client~FoClient:getClientListing' ;
				$oResp->params = array();
			}
		}else{
			$oResp->action = 'client~FoClient:clientListing' ;
			$oResp->params = array();
		}
		return $oResp ;
    }

	function rechercherStagiaire (){
		/*global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$zStagiaire = $this->param('zStagiaire', "", true); 
        jClasses::inc('client~clientSrv');
		$toStagiaire = clientSrv::rechercherStagiaire ($zStagiaire);
		$oRep->datas = $toStagiaire;
		return $oRep;*/

		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$CritereNom = $this->param('zStagiaire','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql="SELECT * FROM clients WHERE 1=1 "; //AND client_iUtilisateurCreateurId = " . $iUtilisateurId . " AND (";
		if ($oUtilisateur != null && $oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR){
			$zSql.=" AND client_iUtilisateurCreateurId = " . $iUtilisateurId ;
		}
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" client_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
			$t[] = sprintf(" client_zPrenom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql.=" AND (";
		$zSql .= implode(" OR ", $t);
		//$zSql .= ") GROUP BY client_id ORDER BY client_zNom ASC ";  
		$zSql .= ") GROUP BY client_zNom, client_zPrenom ORDER BY client_dateMaj DESC ";  


		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$rep->datas  = $oRes->fetchAll();
		return $rep;
	}

	function chargeParId(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iStagiaireId = $this->param('iStagiaireId', 0, true); 
        jClasses::inc('client~clientSrv');
		$toParams = array();
		$toParams[0] = new stdClass();
		$toParams[0]->id = $iStagiaireId;
		$toParams[0]->composant_cours = 1;
		$toStagiaire = clientSrv::listCriteria($toParams);
		$tData = array ();
		if (isset ($toStagiaire['toListes']) && isset($toStagiaire['toListes'][0])){
			$tData = $toStagiaire['toListes'][0];
		}
		$oRep->datas = $tData;
		return $oRep;
	}

	function sendCoursStagiaireByMail(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iNombre = $this->param('iNombre', 0, true); 
		$iClientId = $this->param('iClientId', 0, true); 
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');
        jClasses::inc('commun~mailSrv');
        jClasses::inc('utilisateurs~typesSrv');
        jClasses::inc('client~clientSrv');
        jClasses::inc('utilisateurs~groupeSrv');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$oClient = clientSrv::getById ($iClientId);

		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;
		$toParams[0]->iTypeEvenement = 12;
		$toParams[0]->iStagiaire = $this->param('iClientId', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = 1;
		$toParams[0]->iEventListing = 1;

 		$toEvenement = evenementSrv::listCriteriaWithValidation($toParams, 'evenement_zDateHeureDebut', "ASC", 0, $iNombre);
		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		
		$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/eventToSendByMailStagiaire/" . "exportEvenement_".$oClient->client_zNom . $oClient->client_zPrenom . "_". date ("Ymd_His") . ".xls" ;

		// Debut Creation fichier Excel 
		if (isset ($oClient->client_zNom) && $oClient->client_zPrenom != "" && !is_null($oClient->client_zMail) && isset($toEvenement['toListes']) && sizeof($toEvenement['toListes']) > 0){
			evenementSrv::exportEventListingStagiaire($zExportsFullPath, $toEvenement, array(), $oClient);
			if (is_file ($zExportsFullPath) && file_exists($zExportsFullPath)) {
				if (is_file ($zExportsFullPath) ) {
					@chmod ($zExportsFullPath, 0777) ;			
				}

				$tplMail = new jTpl();
				
				$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
				$tplMail->assign ('oClient', $oClient) ;
				$tplMail->assign ('oProf', $oUtilisateur) ;
				$tplMail->assign ('zExportsFullPath', basename($zExportsFullPath)) ;

				$tpl = $tplMail->fetch ('evenement~corpsMailSendExportEventStagiaireByEmail') ;

				$tzPathAttachements = array() ;
				array_push ($tzPathAttachements, $zExportsFullPath) ;
				
				mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oClient->client_zMail, $oClient->client_zPrenom .' '.$oClient->client_zNom , MAIL_OBJECT_SEND_EXPORT_EVENT_BY_EMAIL, $tpl,  NULL, NULL, true, NULL, $tzPathAttachements, $oUtilisateur->utilisateur_zMail, NULL) ;		
				$zData = "Mail envoyé!!!";
			}else{
				$zData = "Erreur lors de la création du fichier xls!!!! Mail non envoyé!!!";
			}
		}else{
			$zData = "Aucune données a exporter!!!! Mail non envoyé!!!";
		}
		$oRep->datas = $zData;
		return $oRep;
	}

	function chargeParIdAndComposantCours(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$iStagiaireId = $this->param('iStagiaireId', 0, true); 
        jClasses::inc('client~clientSrv');
		$toParams = array();
		$toParams[0] = new stdClass();
		$toParams[0]->id = $iStagiaireId;
		$toStagiaire = clientSrv::listCriteriaComposantCours($toParams);
		$oRep->datas = $toStagiaire['toListes'];
		return $oRep;
	}
	
	function clientListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');
		
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "client~FoClientListing" ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');

		$toParamsUtilisateur['utilisateur_statut'] = 1;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
       		$toProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur);
		}else{
			$toProfesseur['toListes'] = array () ;
			$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
			array_push ($toProfesseur['toListes'], $oUtilisateur);
			if ((isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR) || (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR)){
				/*$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
				foreach($toTmpProfesseur as $oProfesseur){
					array_push ($toProfesseur['toListes'], $oProfesseur);
				}*/
				$toParamsUtilisateur['utilisateur_statut'] = 1;
				$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
				$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
				//$toTmpProfesseur = utilisateursSrv::getUtilisateurBySuperviseurId($iUtilisateurId) ;
				foreach($toTmpProfesseur['toListes'] as $oProfesseur){
					if ($oProfesseur->utilisateur_iTypeId == TYPE_UTILISATEUR_PROFESSEUR){
						array_push ($toProfesseur['toListes'], $oProfesseur);
					}
				}
			}
		}

		$oRep->body->assign('toProfesseur', $toProfesseur['toListes']);

		return $oRep;
	}

	function getClientListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "client~FoClientListingResult" ;
		
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');

		jClasses::inc ("commun~CNavBar") ;

		$iCurrentPage = $this->intParam("iCurrentPage",1,true);

		if ($iCurrentPage> 1){
			$iDebutListe = ($iCurrentPage-1) * 10; 
		}else{
			$iDebutListe = 0 ;
		}
		$oNavBar = new CNavBar (10, 5) ;
		$iNbRecs = 0;
		$oNavBar->iCurrPage = $iCurrentPage;


		$toParams[0] = new stdClass();
		$toParams[0]->nom = $this->param('client_zNom', '', true);
		$toParams[0]->prenom = $this->param('client_zPrenom', '', true);
		$toParams[0]->societe = $this->param('client_zSociete', '', true);
		$toParams[0]->client_iUtilisateurCreateurId = $this->param('client_iUtilisateurCreateurId', 0, true);
		$toParams[0]->client_testDebut = $this->param('client_testDebut', 2, true);
		$toParams[0]->stagiaire_actif = $this->param('stagiaire_actif', 0, true);
		$toParams[0]->fo = 1 ;

		$toStagiaire = clientSrv::listCriteria($toParams, 'client_zNom', 'ASC',$iDebutListe,10);

		$toParamsUtilisateur['utilisateur_statut'] = 1;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
       		$toProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur);
		}else{
			$toProfesseur['toListes'] = array () ;
			$oUtilisateur = utilisateursSrv::getById ($iUtilisateurId) ;
			array_push ($toProfesseur['toListes'], $oUtilisateur);
			if ((isset($oUtilisateur->utilisateur_bSuperviseur) && $oUtilisateur->utilisateur_bSuperviseur == UTILISATEUR_SUPERVISEUR) || (isset($oUtilisateur->utilisateur_iTypeId) && $oUtilisateur->utilisateur_iTypeId == TYPE_UTILISATEUR_ADLINISTRATEUR)){
				$toParamsUtilisateur['utilisateur_statut'] = 1;
				$toParamsUtilisateur['notinutilisateur'] = $iUtilisateurId;
				$toTmpProfesseur = utilisateursSrv::listCriteria($toParamsUtilisateur, 'utilisateur_zPrenom');
				foreach($toTmpProfesseur['toListes'] as $oProfesseur){
					if ($oProfesseur->utilisateur_iTypeId == TYPE_UTILISATEUR_PROFESSEUR){
						array_push ($toProfesseur['toListes'], $oProfesseur);
					}
				}
			}
		}

		//bSupprimable stagiaire 
		if (sizeof ($toStagiaire["toListes"]) <= 100){
			foreach ($toStagiaire["toListes"] as $oStagiaire){
				$oStagiaire->bSupprimable = 0 ;
				$iEventByClientId = clientSrv::countEventByClientId ($oStagiaire->client_id);
				$iClientsAutoByClientId = clientSrv::countClientsAutoByClientId ($oStagiaire->client_id);
				if ($iEventByClientId == 0 && $iClientsAutoByClientId == 0){
					$iClientsEnvironnementByClientId = clientSrv::countClientsEnvironnementByClientId ($oStagiaire->client_id);
					$oStagiaire->bSupprimable = 1 ;
				}
			}
		}
		$oNavBar->normalizeBar ($toStagiaire['iResTotal']) ;
		$oNavBar->mergeBar ();

		$oRep->body->assign('toStagiaire', $toStagiaire);
		$oRep->body->assign('toProfesseur', $toProfesseur['toListes']);
		$oRep->body->assign('toParams', $toParams);
		$oRep->body->assign ("iCurrentPage", $iCurrentPage) ;
		$oRep->body->assign ("oNavBar", $oNavBar) ;

		return $oRep;
	} 

	function refreshModelMailContainer (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc ('typeEvenement~typeEvenementsSrv');
    	jClasses::inc ('client~clientSrv');
    	jClasses::inc ('client~societeSrv');
    	jClasses::inc ('client~paysSrv');
    	jClasses::inc ('client~modelMailSrv');

		$iUtilisateurCreateurId = $this->param('iUtilisateurCreateurId', 0, true); 
		$iClientId = $this->param('iClientId', 0, true); 

        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$oNewUtilisateur			= utilisateursSrv::getById ($iUtilisateurCreateurId) ;

		$zUrlToIndexStagiaire = URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass ;

		$toModelMail = modelMailSrv::chargerByType (1) ;
		foreach ($toModelMail as $oModelMail){
			switch($oModelMail->modelmail_value){
				case 1:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,		$oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 2:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 3:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
			}
		}
		$oRep->datas = $toModelMail;
		return $oRep;
	}

	function resetModelMailContainer (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc ('typeEvenement~typeEvenementsSrv');
    	jClasses::inc ('client~clientSrv');
    	jClasses::inc ('client~societeSrv');
    	jClasses::inc ('client~paysSrv');
    	jClasses::inc ('client~modelMailSrv');

		$iClientId = $this->param('iClientId', 0, true); 
		$valueContent = $this->param('valueContent', 1, true); 

        $oClient 					= ($iClientId>0) ? ClientSrv::getById($iClientId) : jDao::createRecord('commun~client') ;
		$oNewUtilisateur			= utilisateursSrv::getById ($oClient->client_iUtilisateurCreateurId) ;

		$zUrlToIndexStagiaire = URL_TO_SITE . 'stag.php?module=stagiaire&action=default:stagiaire&x=' . $oClient->client_zLogin . '&y=' . $oClient->client_zPass ;

		$toModelMail = modelMailSrv::chargerByValue ($valueContent) ;
		foreach ($toModelMail as $oModelMail){
			switch($oModelMail->modelmail_value){
				case 1:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,		$oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 2:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
				case 3:
					$oModelMail->modelmail_content = sprintf($oModelMail->modelmail_content, 
															 $oNewUtilisateur->utilisateur_zNom.' '.$oNewUtilisateur->utilisateur_zPrenom,
															 $oNewUtilisateur->utilisateur_zTel,
															 $oNewUtilisateur->utilisateur_zMail,
															 $oNewUtilisateur->utilisateur_zMail,
															 'http://'.$zUrlToIndexStagiaire,
															 $oClient->client_zLogin,
															 $oClient->client_zPass);
				break;
			}
		}
		$oRep->datas = $toModelMail;
		return $oRep;
	}


	function delete (){
		jClasses::inc('client~clientSrv');
		$oResp = $this->getResponse('redirect') ;
		$iClientId = $this->param('iClientId', 0, true);

		$toParams[0] = new stdClass();
		$toParams[0]->nom = $this->param('client_zNom', '', true);
		$toParams[0]->prenom = $this->param('client_zPrenom', '', true);
		$toParams[0]->societe = $this->param('client_zSociete', '', true);
		$toParams[0]->client_iUtilisateurCreateurId = $this->param('client_iUtilisateurCreateurId', 0, true);
		$toParams[0]->client_testDebut = $this->param('client_testDebut', 2, true);
		$toParams[0]->stagiaire_actif = $this->param('stagiaire_actif', 0, true);

		if ($iClientId > 0){
			clientSrv::delete ($iClientId);
		}
	
		$oResp->action = 'client~FoClient:getClientListing' ;
		$oResp->params = array("client_zNom"=>$toParams[0]->nom, "client_zPrenom"=>$toParams[0]->prenom, "client_zSociete"=>$toParams[0]->societe, "client_iUtilisateurCreateurId"=>$toParams[0]->client_iUtilisateurCreateurId, "client_testDebut"=>$toParams[0]->client_testDebut, "stagiaire_actif"=>$toParams[0]->stagiaire_actif);

		return $oResp;
	}

}