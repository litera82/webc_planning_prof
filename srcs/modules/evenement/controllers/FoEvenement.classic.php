<?php
/**
* @package   jelix_calendar
* @subpackage evenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/
require_once(JELIX_APP_MODULE_PATH.'commun/classes/calendar.class.php');

class FoEvenementCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function add() {
		global $gJConfig ;

        $oRep = $this->getResponse('FoHtml');
		
		$oRep->bodyTpl = "evenement~FoEditEvenement" ;

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/addEvenement.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/selectBoxEditable.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$zDate = $this->param('zDate', '', true);
		$iTime = $this->param('iTime', 0, true);
		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);

		$prec = $this->param('prec', 0, true);
		$debut = $this->param('debut', "", true);
		$fin = $this->param('fin', "", true);

		$x = $this->param('x', 0, true);
		$tEvent = array ();
		if (isset ($_SESSION['tEvent'])){
			$tEvent = $_SESSION['tEvent']; 
			unset($_SESSION['tEvent']);
		}		

		$oRep->body->assignZone('oZoneLegend', 'commun~FoLegende', array());
		$oRep->body->assignZone('oZoneEditEvenement', 'evenement~FoEditEvenement', array('iEvenementId'=> $iEvenementId, 'zDate'=>$zDate, 'iTime'=>$iTime, 'iAffichage'=>$iAffichage, 'tEvent'=>$tEvent, 'x'=>$x, 'prec'=>$prec, 'debut'=>$debut, 'fin'=>$fin));
		return $oRep;
    }

	function testEventExist(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDate = $this->param('zDate', "", true); 
		$iTime = $this->param('iTime', "", true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExist($zDate, $iTime);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}

	function validateEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEventId = $this->param('iEventId', 0, true); 
		$iValidationId = $this->param('iValidationId', 0, true);
		$zComment = $this->param('zComment', '', true);
		$iSkype = $this->param('iSkype', null, true);

		$bureau = $this->param('bureau', 0, true);
		$navigateur = $this->param('navigateur', null, true);
		$telFixe = $this->param('telFixe', null, true);
		$telMobile = $this->param('telMobile', null, true);
		$skype = $this->param('skype', null, true);
		$casqueSkype = $this->param('casqueSkype', 0, true);
		
    	jClasses::inc('evenement~evenementValidationSrv');
    	jClasses::inc('client~clientsenvironnementSrv');
		$oValidationExist = evenementValidationSrv::getByEventId ($iEventId);
		if ($oValidationExist != null && $oValidationExist->evenementvalidation_id > 0){
			evenementValidationSrv::delete ($oValidationExist->evenementvalidation_id); 
		}
		$oValidation = new StdClass(); 
		$oValidation->evenementvalidation_eventId = $iEventId;
		$oValidation->evenementvalidation_validationId = $iValidationId;
		$oValidation->evenementvalidation_date = date("Y-m-d H:i:s");
		$oValidation->evenementvalidation_skype = $iSkype;
		$oValidation->evenementvalidation_commentaire = htmlentities($zComment);

		$oClientsEnvironnementExist = clientsenvironnementSrv::getByEventId ($iEventId);
		if ($oClientsEnvironnementExist != null && $oClientsEnvironnementExist->id > 0){
			clientsenvironnementSrv::delete ($oClientsEnvironnementExist->id); 
		}
		$oClientsEnvironnement = new StdClass ();
		$oClientsEnvironnement->clientId = null;
		$oClientsEnvironnement->eventId = $iEventId;
		$oClientsEnvironnement->bureau = $bureau ;
		$oClientsEnvironnement->navigateur = $navigateur ;
		$oClientsEnvironnement->telFixe = $telFixe ;
		$oClientsEnvironnement->telMobile = $telMobile ;
		$oClientsEnvironnement->skype = $skype ;
		$oClientsEnvironnement->casqueSkype = $casqueSkype ;
		clientsenvironnementSrv::save($oClientsEnvironnement);
		$oRep->datas = evenementValidationSrv::save($oValidation);

		return $oRep;
	}

	function invalidateEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iValidationEventId = $this->param('iValidationEventId', 0, true); 
		
    	jClasses::inc('evenement~evenementValidationSrv');
    	jClasses::inc('client~clientsenvironnementSrv');
		$oValidationExist = evenementValidationSrv::getById ($iValidationEventId);
		if ($oValidationExist != null && $oValidationExist->evenementvalidation_id > 0){
			$oClientsEnvironnementExist = clientsenvironnementSrv::getByEventId ($oValidationExist->evenementvalidation_eventId);
			if ($oClientsEnvironnementExist != null && $oClientsEnvironnementExist->id > 0){
				clientsenvironnementSrv::delete ($oClientsEnvironnementExist->id); 
			}
			evenementValidationSrv::delete ($oValidationExist->evenementvalidation_id); 
		}
		$oRep->datas = 1;
		return $oRep;
	}

	function chargerValidateEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEventId = $this->param('iEventId', 0, true); 
    	jClasses::inc('evenement~evenementValidationSrv');
		//$oRep->datas = evenementValidationSrv::getByEventId ($iEventId);
		$oRep->datas = evenementValidationSrv::getByEventId1 ($iEventId);
		return $oRep;
	}

	function getListeTypeEvenementUilisateur(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$user = $this->param('user', AUDIT_ID_CATRIONA, true);
		
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');

		$toResults = utilisateursSrv::getListeTypeEvenementUilisateur($user);
		if (sizeof($toResults) == 0){
			$toResult = typeEvenementsSrv::listCriteria();	
			$toResults = $toResult['toListes'] ;
		}
		$oRep->datas = $toResults;

		return $oRep;
	}	
	function testEventExistEditionIsTypeEventDisponible(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExistEditionIsTypeEventDisponible($zDateTime, $iEvenementId);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}	
	function desactiverEventDispo(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$oEvent = evenementSrv::desactiverEventDispo($zDateTime, $iEvenementId);

		$oRep->datas = $oEvent;

		return $oRep;
	}	
	function testEventExistEdition(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$zDateTime = $this->param('zDateTime', "", true); 
		$iEvenementId = $this->param('iEvenementId', 0, true);
		
    	jClasses::inc('evenement~evenementSrv');
		$iNbreEvent = evenementSrv::testEventExistEdition($zDateTime, $iEvenementId);

		$oRep->datas = $iNbreEvent;

		return $oRep;
	}	

	function saveEventRapid (){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$oUser = jAuth::getUserSession();
		$zDate = $this->param('zDate', 0, true); 
		$iTime = $this->param('iTime', 0, true); 
		$tDate = explode ("/", $zDate) ;
		$zDescription = $this->param('zDescription', "", true) ;

	    jClasses::inc('typeEvenement~typeEvenementsSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

		$oEvent = new StdClass () ;
		$oEvent->evenement_iTypeEvenementId = $this->param('iTypeEvenementId', 0, true); 
		$oTypeEvenements = typeEvenementsSrv::getById ($oEvent->evenement_iTypeEvenementId) ;
		$oEvent->evenement_zLibelle = $oTypeEvenements->typeevenements_zLibelle ;
		if ($zDescription != ""){
			$oEvent->evenement_zDescription = $zDescription ;
		}else{
			$oEvent->evenement_zDescription = $oTypeEvenements->typeevenements_zLibelle ;
		}
		$oEvent->evenement_iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oEvent->evenement_iStagiaire = $this->param('iStagiaire', 0, true); 
		$oEvent->evenement_zDateHeureDebut = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0] . ' ' . $iTime . ':00' ;
		$oEvent->evenement_zDateHeureSaisie = date("Y-m-d H:i:s") ;
		$oEvent->evenement_iStatut = 1 ;
    	jClasses::inc('evenement~evenementSrv');
		$iRes = evenementSrv::saveEventRapid($oEvent);
		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iRes)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;

		return $oRep;
	}
	function collerEvent (){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$oUser = jAuth::getUserSession();
		$iEventToCopy = $_SESSION['EVENT_TO_COPY']; 
		$zDate = $this->param('zDate', date("Y-m-d"), true); 
		$zTime = $this->param('zTime', date("H:i:s"), true); 

    	jClasses::inc('evenement~evenementSrv');

		$iRes = 0 ;
		if ($iEventToCopy > 0){
			$oEvent = evenementSrv::getById($iEventToCopy);
			$oNewEvent = new StdClass ();

			$oNewEvent->evenement_iTypeEvenementId = $oEvent->evenement_iTypeEvenementId;
			$oNewEvent->evenement_iUtilisateurId = $oEvent->evenement_iUtilisateurId;
			$oNewEvent->evenement_zLibelle = $oEvent->evenement_zLibelle;
			$oNewEvent->evenement_zDescription = $oEvent->evenement_zDescription;
			$oNewEvent->evenement_iStagiaire = $oEvent->evenement_iStagiaire;
			$oNewEvent->evenement_zContactTel = $oEvent->evenement_zContactTel;
			$oNewEvent->evenement_zDateHeureDebut = $zDate ." ". $zTime ; 
			$oNewEvent->evenement_zDateHeureSaisie = date("Y-m-d H:i:s");
			$oNewEvent->evenement_iDuree = $oEvent->evenement_iDuree;
			$oNewEvent->evenement_iDureeTypeId = $oEvent->evenement_iDureeTypeId;
			$oNewEvent->evenement_iPriorite = $oEvent->evenement_iPriorite;
			$oNewEvent->evenement_iRappel = $oEvent->evenement_iRappel;
			$oNewEvent->evenement_iTypeRappelId = $oEvent->evenement_iTypeRappelId;
			$oNewEvent->evenement_iStatut = 1;
			$oNewEvent->evenement_origine = $oEvent->evenement_origine;
			$iRes = evenementSrv::collerEvent($oNewEvent); 

			// Supprimer event disponible 
			evenementSrv::supprimerEventDisponible($oUser->id, $oNewEvent->evenement_zDateHeureDebut, $iRes); 

			if (isset($_SESSION['EVENT_TO_COPY_TYPE']) && $_SESSION['EVENT_TO_COPY_TYPE'] == 2){
				if (isset($_SESSION['EVENT_TO_COPY']) && $_SESSION['EVENT_TO_COPY'] > 0){
					$iEventToCopy = $_SESSION['EVENT_TO_COPY'];
					evenementSrv::delete($iEventToCopy) ;
					unset($_SESSION['EVENT_TO_COPY']) ;
					unset($_SESSION['EVENT_TO_COPY_TYPE']) ;
				}
			}
		}

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iRes)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;

		return $oRep;
	}
	function deleteEventRapid(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		if ($iEventId > 0){
			jClasses::inc('evenement~evenementSrv');
			jClasses::inc('evenement~evenementValidationSrv');
			jClasses::inc('client~clientsenvironnementSrv');
			$oValidation = evenementValidationSrv::getByEventId($iEventId);		
			if ($oValidation != null && $oValidation->evenementvalidation_id > 0){
				evenementValidationSrv::delete($oValidation->evenementvalidation_id);
			}
			$oClientEnv = clientsenvironnementSrv::getByEventId ($iEventId);
			if ($oClientEnv != null && $oClientEnv->eventId > 0){
				clientsenvironnementSrv::delete($oClientEnv->id);
			}
			evenementSrv::delete($iEventId);		
		}
		$oRep->content = ' ' ;

		return $oRep;
	}
	function copierEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		$zDate = $this->param('date', '', true); 

		if (isset ($_SESSION['EVENT_TO_COPY'])){
			unset($_SESSION['EVENT_TO_COPY']) ;	
		}
		$_SESSION['EVENT_TO_COPY'] = $iEventId;
		$_SESSION['EVENT_TO_COPY_TYPE'] = 1;

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		return $oRep;
	}
	function saveDescEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 
		$zDesc = $this->param('desc', '', true); 
        jClasses::inc('evenement~evenementSrv');
		evenementSrv::saveDescEvent($iEventId, $zDesc) ;
		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		return $oRep;
	}
	function checkEvetToCopyExist (){
		$oRep = $this->getResponse('encodedJson');
		$iRes = 0 ;
		if (isset ($_SESSION['EVENT_TO_COPY_TYPE'])){
			if ($_SESSION['EVENT_TO_COPY_TYPE'] > 0){
				$iRes = 1 ;	
			}
		}
		$oRep->datas = $iRes;
		return $oRep;
	}
	function couperEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('text');
		$iEventId = $this->param('iEventId', 0, true); 

		if (isset ($_SESSION['EVENT_TO_COPY_TYPE'])){
			unset($_SESSION['EVENT_TO_COPY_TYPE']) ;	
		}
		
		if (isset ($_SESSION['EVENT_TO_COPY'])){
			unset($_SESSION['EVENT_TO_COPY']) ;	
		}
		$_SESSION['EVENT_TO_COPY'] = $iEventId;
		$_SESSION['EVENT_TO_COPY_TYPE'] = 2;

		$oNewTpl = new jTpl () ;
		$oNewTpl->assignZone ('planning', 'jelix_calendar~FoPlanningAjax', array('iEventId'=>$iEventId)) ;       
		$oRep->content = $oNewTpl->get ('planning') ;
		
		return $oRep;
	}

	function save() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
        jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		
		$prec = $this->param('prec', 0, true);
		$debut = $this->param('debut', "", true);
		$fin = $this->param('fin', "", true);

		$toParams = $this->params() ;

		$toParams['evenement_zDateHeureDebut'] = $toParams['dtcm_event_rdv'];

		$toParams['x'] = $toParams['x'];
		$toParams['evenement_iUtilisateurId'] = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($toParams['evenement_iRappel'] == 1){
			if (isset($toParams['evenement_iRappelJour']) && $toParams['evenement_iRappelJour'] > 0){
				$toParams['evenement_iTypeRappelId'] = 1; 
			}elseif (isset($toParams['evenement_iRappelHeure']) && $toParams['evenement_iRappelHeure'] > 0){
				$toParams['evenement_iTypeRappelId'] = 2; 
			}else{
				$toParams['evenement_iTypeRappelId'] = 3; 
			}
		}else{
			$toParams['evenement_iTypeRappelId'] = NULL; 
		}
		if ($toParams['evenement_iDuree'] && ($toParams['evenement_iDuree'] != '' || !is_null($toParams['evenement_iDuree']))){
			$tzEvenement_iDuree = explode(' ', $toParams['evenement_iDuree']);
			$toParams['evenement_iDuree']		= $tzEvenement_iDuree [0]; 
			if ($tzEvenement_iDuree[1] == 'minutes'){
				$toParams['evenement_iDureeTypeId'] = 2; 
			}else{
				$toParams['evenement_iDureeTypeId'] = 1; 
			}
		}else{
			$toParams['evenement_iDuree'] = 0; 
			$toParams['evenement_iDureeTypeId'] = 1; 
		}

		$oNewEvenement = evenementSrv::save($toParams) ;

		if (isset ($toParams['evenement_iDupliquer']) && $toParams['evenement_iDupliquer'] == 1){
			$tDateFinal = array ();
			if (isset($toParams['choixperiode'])){
				if ($toParams['choixperiode'] == 1){//Quotidienne
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
					$tzHeureDureeRendezVous = explode(' ', $toParams['evenement_heureDureeRendezVous']);
					$toParams['evenement_iDuree']		= $tzHeureDureeRendezVous[0]; 
					if ($tzHeureDureeRendezVous[1] == 'minutes'){
						$toParams['evenement_iDureeTypeId'] = 2; 
					}else{
						$toParams['evenement_iDureeTypeId'] = 1; 
					}

					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){//par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateNombreOccurence($toParams['evenement_periodiciteQuotidienne'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut']);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleQuotidienneParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin
						if (isset ($toParams['dtcm_event_rdv_periodiciteFin']) && ($toParams['dtcm_event_rdv_periodiciteFin'] != '' || !is_null($toParams['dtcm_event_rdv_periodiciteFin']))){
							$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']);
							$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']);
							$tDates = toolDate::getDatesBetween($zDateDebut, $zDateFin);
							$tDateFinal = toolDate::periodiciteQuotidienneGetDateParDateDeFin($toParams['evenement_periodiciteQuotidienne'], $tDates, $zDateDebut); 
							if (sizeof ($tDateFinal) > 0){
								evenementSrv::saveMultipleQuotidienneParDateDefin ($tDateFinal, $toParams, $oNewEvenement) ;
							}
						}
					}
				}elseif ($toParams['choixperiode'] == 2){//Hebdomadaire
					$toParams['evenement_iLundi'] = isset($toParams['evenement_iLundi']) ? 1 : 0;
					$toParams['evenement_iMardi'] = isset($toParams['evenement_iMardi']) ? 1 : 0;
					$toParams['evenement_iMercredi'] = isset($toParams['evenement_iMercredi']) ? 1 : 0;
					$toParams['evenement_iJeudi'] = isset($toParams['evenement_iJeudi']) ? 1 : 0;
					$toParams['evenement_iVendredi'] = isset($toParams['evenement_iVendredi']) ? 1 : 0;
					$toParams['evenement_iSamedi'] = isset($toParams['evenement_iSamedi']) ? 1 : 0;
					$toParams['evenement_iDimanche'] = isset($toParams['evenement_iDimanche']) ? 1 : 0;
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';


					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParOccurence($toParams['evenement_periodiciteHebdomadaire'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut'], $toParams);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleHebdomadaireParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin 
						$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$tDates = toolDate::getDatesBetween (toolDate::getDateFormatYYYYMMDD($zDateDebut), toolDate::getDateFormatYYYYMMDD($zDateFin));

						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($toParams['evenement_periodiciteHebdomadaire'], $tDates, toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams);
						if (sizeof ($tDateFinal) > 0){
							evenementSrv::saveMultipleHebdomadaireParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
						}						
					}

				}else{//Mensuelle
					if (isset($toParams['evenement_periodiciteMensuel1'])){
						$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';

						if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParOccurence ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}else{//Par date de fin 
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									evenementSrv::saveMultipleMensuelleParDateDeFin ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}
					}
				}
			}
		}
		// VALIDATION DU COURS 
		$iValidationId = $this->param('validationpresence', 0, true);
		$zComment = $this->param('validationcomment', '', true);
		$iSkype = $this->param('evenementvalidation_skype', null, true);
		if ($iValidationId != 0 && $toParams['evenement_id'] != 0){
			jClasses::inc('evenement~evenementValidationSrv');
			$oValidationExist = evenementValidationSrv::getByEventId ($toParams['evenement_id']);
			if ($oValidationExist != null && $oValidationExist->evenementvalidation_id > 0){
				evenementValidationSrv::delete ($oValidationExist->evenementvalidation_id); 
			}
			$oValidation = new StdClass(); 
			$oValidation->evenementvalidation_eventId = $toParams['evenement_id'];
			$oValidation->evenementvalidation_validationId = $iValidationId;
			$oValidation->evenementvalidation_date = date("Y-m-d H:i:s");
			$oValidation->evenementvalidation_skype = $iSkype;
			$oValidation->evenementvalidation_commentaire = htmlentities($zComment);
			evenementValidationSrv::save($oValidation);
		}
		// VALIDATION DU COURS 

		// ENVIRONNEMENT CLIENT EVENT 
		if ($toParams['evenement_id'] > 0){
			jClasses::inc('client~clientsenvironnementSrv');
			$oEnvExist = clientsenvironnementSrv::getByEventId ($toParams['evenement_id']);
			if ($oEnvExist != null && isset ($oEnvExist->id) && $oEnvExist->id > 0){
				clientsenvironnementSrv::delete ($oEnvExist->id); 
			}

			$oEnvironnement = new StdClass ();
			$oEnvironnement->eventId = $toParams['evenement_id'];
			$oEnvironnement->bureau = $this->param('bureau', 0, true);
			$oEnvironnement->navigateur = $this->param('navigateur', 0, true);
			$oEnvironnement->telFixe = $this->param('telFixe', '', true);
			$oEnvironnement->telMobile = $this->param('telMobile', '', true);
			$oEnvironnement->skype = $this->param('skype', '', true);
			$oEnvironnement->casqueSkype = $this->param('casqueSkype', 0, true);
			clientsenvironnementSrv::save ($oEnvironnement);
		}
		// ENVIRONNEMENT CLIENT EVENT 

		if (isset($toParams['prec']) && $toParams['prec'] == 1){
			$oResp->action = 'evenement~FoEvenement:getEventListing' ;
			$oResp->params = array ('dtcm_event_rdv'=>$toParams['debut'], 'dtcm_event_rdv1'=> $toParams['fin']);	
		}elseif (isset($toParams['prec']) && $toParams['prec'] == 2){
			$oResp->action = 'evenement~FoEvenement:getEventListingDispo' ;
			$oResp->params = array ('dtcm_event_rdv'=>$toParams['debut'], 'dtcm_event_rdv1'=> $toParams['fin']);	
		}else{
			$oResp->action = 'jelix_calendar~FoCalendar:index' ;
			$oResp->params = array ('date'=>$toParams['zDate'], 'iAffichage'=> $toParams['iAffichage']);	
        }

		return $oResp ;
    }

	function saveAffectation() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
        jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		
		$toParams = $this->params() ;

		$toParams['evenement_zDateHeureDebut'] = $toParams['dtcm_event_rdv'];
		$toParams['x'] = $toParams['x'];
		$toParams['evenement_iUtilisateurId'] = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($toParams['evenement_iRappel'] == 1){
			if (isset($toParams['evenement_iRappelJour']) && $toParams['evenement_iRappelJour'] > 0){
				$toParams['evenement_iTypeRappelId'] = 1; 
			}elseif (isset($toParams['evenement_iRappelHeure']) && $toParams['evenement_iRappelHeure'] > 0){
				$toParams['evenement_iTypeRappelId'] = 2; 
			}else{
				$toParams['evenement_iTypeRappelId'] = 3; 
			}
		}else{
			$toParams['evenement_iTypeRappelId'] = NULL; 
		}
		if ($toParams['evenement_iDuree'] && ($toParams['evenement_iDuree'] != '' || !is_null($toParams['evenement_iDuree']))){
			$tzEvenement_iDuree = explode(' ', $toParams['evenement_iDuree']);
			$toParams['evenement_iDuree']		= $tzEvenement_iDuree [0]; 
			if ($tzEvenement_iDuree[1] == 'minutes'){
				$toParams['evenement_iDureeTypeId'] = 2; 
			}else{
				$toParams['evenement_iDureeTypeId'] = 1; 
			}
		}else{
			$toParams['evenement_iDuree'] = 0; 
			$toParams['evenement_iDureeTypeId'] = 1; 
		}

		$oNewEvenement = evenementSrv::save($toParams) ;

		if (isset ($toParams['evenement_iDupliquer']) && $toParams['evenement_iDupliquer'] == 1){
			//jLog::dump($toParams);
			$tDateFinal = array ();
			if (isset($toParams['choixperiode'])){
				if ($toParams['choixperiode'] == 1){//Quotidienne
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
					$tzHeureDureeRendezVous = explode(' ', $toParams['evenement_heureDureeRendezVous']);
					$toParams['evenement_iDuree']		= $tzHeureDureeRendezVous[0]; 
					if ($tzHeureDureeRendezVous[1] == 'minutes'){
						$toParams['evenement_iDureeTypeId'] = 2; 
					}else{
						$toParams['evenement_iDureeTypeId'] = 1; 
					}

					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){//par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateNombreOccurence($toParams['evenement_periodiciteQuotidienne'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut']);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin
						if (isset ($toParams['dtcm_event_rdv_periodiciteFin']) && ($toParams['dtcm_event_rdv_periodiciteFin'] != '' || !is_null($toParams['dtcm_event_rdv_periodiciteFin']))){
							$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']);
							$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']);
							$tDates = toolDate::getDatesBetween($zDateDebut, $zDateFin);
							$tDateFinal = toolDate::periodiciteQuotidienneGetDateParDateDeFin($toParams['evenement_periodiciteQuotidienne'], $tDates, $zDateDebut); 
							if (sizeof ($tDateFinal) > 0){
								$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParDateDefinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
							}
						}
					}
				}elseif ($toParams['choixperiode'] == 2){//Hebdomadaire
					$toParams['evenement_iLundi'] = isset($toParams['evenement_iLundi']) ? 1 : 0;
					$toParams['evenement_iMardi'] = isset($toParams['evenement_iMardi']) ? 1 : 0;
					$toParams['evenement_iMercredi'] = isset($toParams['evenement_iMercredi']) ? 1 : 0;
					$toParams['evenement_iJeudi'] = isset($toParams['evenement_iJeudi']) ? 1 : 0;
					$toParams['evenement_iVendredi'] = isset($toParams['evenement_iVendredi']) ? 1 : 0;
					$toParams['evenement_iSamedi'] = isset($toParams['evenement_iSamedi']) ? 1 : 0;
					$toParams['evenement_iDimanche'] = isset($toParams['evenement_iDimanche']) ? 1 : 0;
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';


					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParOccurence($toParams['evenement_periodiciteHebdomadaire'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut'], $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin 
						$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$tDates = toolDate::getDatesBetween (toolDate::getDateFormatYYYYMMDD($zDateDebut), toolDate::getDateFormatYYYYMMDD($zDateFin));

						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($toParams['evenement_periodiciteHebdomadaire'], $tDates, toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}						
					}

				}else{//Mensuelle
					if (isset($toParams['evenement_periodiciteMensuel1'])){
						$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';

						if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}else{//Par date de fin 
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}
					}
				}
			}
		}
		$oResp->action = 'evenement~FoEvenement:getEventListingDispo' ;

		$oResp->params = array ('dtcm_event_rdv'=> $toParams['criteria_datedebut'], 'bAffectation'=>1, 'dtcm_event_rdv1'=>$toParams['criteria_datefin']);	
        return $oResp ;
    }


	function saveAffectationCoursPlannifie() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
        jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		
		$toParams = $this->params() ;

		$toParams['evenement_zDateHeureDebut'] = $toParams['dtcm_event_rdv'];
		$toParams['x'] = $toParams['x'];
		$toParams['iClientId'] = $toParams['evenement_iStagiaire'];
		$toParams['evenement_iUtilisateurId'] = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		if ($toParams['evenement_iRappel'] == 1){
			if (isset($toParams['evenement_iRappelJour']) && $toParams['evenement_iRappelJour'] > 0){
				$toParams['evenement_iTypeRappelId'] = 1; 
			}elseif (isset($toParams['evenement_iRappelHeure']) && $toParams['evenement_iRappelHeure'] > 0){
				$toParams['evenement_iTypeRappelId'] = 2; 
			}else{
				$toParams['evenement_iTypeRappelId'] = 3; 
			}
		}else{
			$toParams['evenement_iTypeRappelId'] = NULL; 
		}
		if ($toParams['evenement_iDuree'] && ($toParams['evenement_iDuree'] != '' || !is_null($toParams['evenement_iDuree']))){
			$tzEvenement_iDuree = explode(' ', $toParams['evenement_iDuree']);
			$toParams['evenement_iDuree']		= $tzEvenement_iDuree [0]; 
			if ($tzEvenement_iDuree[1] == 'minutes'){
				$toParams['evenement_iDureeTypeId'] = 2; 
			}else{
				$toParams['evenement_iDureeTypeId'] = 1; 
			}
		}else{
			$toParams['evenement_iDuree'] = 0; 
			$toParams['evenement_iDureeTypeId'] = 1; 
		}

		$oNewEvenement = evenementSrv::save($toParams) ;

		if (isset ($toParams['evenement_iDupliquer']) && $toParams['evenement_iDupliquer'] == 1){
			//jLog::dump($toParams);
			$tDateFinal = array ();
			if (isset($toParams['choixperiode'])){
				if ($toParams['choixperiode'] == 1){//Quotidienne
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
					$tzHeureDureeRendezVous = explode(' ', $toParams['evenement_heureDureeRendezVous']);
					$toParams['evenement_iDuree']		= $tzHeureDureeRendezVous[0]; 
					if ($tzHeureDureeRendezVous[1] == 'minutes'){
						$toParams['evenement_iDureeTypeId'] = 2; 
					}else{
						$toParams['evenement_iDureeTypeId'] = 1; 
					}

					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){//par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateNombreOccurence($toParams['evenement_periodiciteQuotidienne'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut']);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin
						if (isset ($toParams['dtcm_event_rdv_periodiciteFin']) && ($toParams['dtcm_event_rdv_periodiciteFin'] != '' || !is_null($toParams['dtcm_event_rdv_periodiciteFin']))){
							$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']);
							$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']);
							$tDates = toolDate::getDatesBetween($zDateDebut, $zDateFin);
							$tDateFinal = toolDate::periodiciteQuotidienneGetDateParDateDeFin($toParams['evenement_periodiciteQuotidienne'], $tDates, $zDateDebut); 
							if (sizeof ($tDateFinal) > 0){
								$tEventNonCreer = evenementSrv::saveMultipleQuotidienneParDateDefinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
							}
						}
					}
				}elseif ($toParams['choixperiode'] == 2){//Hebdomadaire
					$toParams['evenement_iLundi'] = isset($toParams['evenement_iLundi']) ? 1 : 0;
					$toParams['evenement_iMardi'] = isset($toParams['evenement_iMardi']) ? 1 : 0;
					$toParams['evenement_iMercredi'] = isset($toParams['evenement_iMercredi']) ? 1 : 0;
					$toParams['evenement_iJeudi'] = isset($toParams['evenement_iJeudi']) ? 1 : 0;
					$toParams['evenement_iVendredi'] = isset($toParams['evenement_iVendredi']) ? 1 : 0;
					$toParams['evenement_iSamedi'] = isset($toParams['evenement_iSamedi']) ? 1 : 0;
					$toParams['evenement_iDimanche'] = isset($toParams['evenement_iDimanche']) ? 1 : 0;
					$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';


					if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParOccurence($toParams['evenement_periodiciteHebdomadaire'], $toParams['evenement_finPeriodiciteOccurence1'], $toParams['evenement_zDateHeureDebut'], $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}
					}else{//Par date de fin 
						$zDateDebut = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$zDateFin = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';
						$tDates = toolDate::getDatesBetween (toolDate::getDateFormatYYYYMMDD($zDateDebut), toolDate::getDateFormatYYYYMMDD($zDateFin));

						$tDateFinal = toolDate::periodiciteQuotidienneGetDateHebdomadaireParDateDeFin($toParams['evenement_periodiciteHebdomadaire'], $tDates, toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams);
						if (sizeof ($tDateFinal) > 0){
							$tEventNonCreer = evenementSrv::saveMultipleHebdomadaireParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
						}						
					}

				}else{//Mensuelle
					if (isset($toParams['evenement_periodiciteMensuel1'])){
						$toParams['evenement_zDateHeureDebut'] = toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']) . " " . $toParams['evenement_heureDebutRendezVous'].':00';

						if ($toParams['evenement_finPeriodiciteOccurence'] == 1){// par nombre d'occurence
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParOccurence1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), $toParams['evenement_finPeriodiciteOccurence1']);
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParOccurenceAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}else{//Par date de fin 
							if ($toParams['evenement_periodiciteMensuel1'] == 1){//Le tous les mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin($toParams['evenement_periodiciteMensuel11'], $toParams['evenement_periodiciteMensuel12'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}else{//Le 1er Mardi tous les X mois 
								$tDateFinal = toolDate::periodiciteQuotidienneGetDateMensuelleParDateDeFin1($toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel21'], $toParams['evenement_periodiciteMensuel23'], toolDate::toDateSQL($toParams['dtcm_event_rdv_periodicite']), toolDate::toDateSQL($toParams['dtcm_event_rdv_periodiciteFin']));
								if (sizeof ($tDateFinal) > 0){
									$tEventNonCreer = evenementSrv::saveMultipleMensuelleParDateDeFinAffectation ($tDateFinal, $toParams, $oNewEvenement) ;
								}
							}
						}
					}
				}
			}
		}
		$oResp->action = 'evenement~FoEvenement:getEventListingCreneauPlannifie' ;

		$oResp->params = array ('dtcm_event_rdv'=> $toParams['criteria_datedebut'], 'bAffectation'=>1, 'dtcm_event_rdv1'=>$toParams['criteria_datefin'], 'iClientId'=>$toParams['iClientId']);	
        return $oResp ;
    }

	function eventListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "evenement~FoEventListing" ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		//Date Année Header 
		$iAnnee = date('Y');
		$tiAnnee = array ();
		for ($i=$iAnnee-10; $i<=$iAnnee+20; $i++){
			array_push ($tiAnnee, $i);
		}

		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toStagiaire = clientSrv::listCriteria($toParamsClient);

		$oRep->body->assign('now', date('d/m/Y'));
		$oRep->body->assign('tiAnnee', $tiAnnee);
		$oRep->body->assign('toTypeEvenement', $oTypeEvenement['toListes']);
		$oRep->body->assign('toStagiaire', $toStagiaire['toListes']);

		return $oRep;
	}
	function getEventListing (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');
        jClasses::inc('utilisateurs~typesSrv');
        jClasses::inc('client~clientSrv');
        jClasses::inc('utilisateurs~groupeSrv');
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

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;
		
		/****************/
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		//$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		//$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); // Date debut semaine
		$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour

		/*$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		echo '<br>'.$zDatefinC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));*/
		$zDatefinC      = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;
		/****************/
		
		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams[0]->cours_produit = $this->param('cours_produit', 0, true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);

		$toParams[0]->groupe_id = $this->param('groupe_id', 0, true);
		$toParams[0]->professeurs = $this->param('professeurs', 0, true);

		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}
		$toParams[0]->iTypeEvenement = $this->param('evenement_iTypeEvenementId', 0, true);
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);
		$toParams[0]->iAfficheNomStagiaire = $this->param('z', 0, true);
		$toParams[0]->evenement_zSociete = $this->param('evenement_zSociete', "", true);
		$toParams[0]->iEventListing = 1;

 		$toEvenement = evenementSrv::listCriteriaWithValidation($toParams, 'evenement_zDateHeureDebut', "ASC", $iDebutListe,10);

		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
			$iMounth = date('m'); 
			$tzDate = explode ('-' ,$oEvenement->evenement_zDateDebut);
			$oEvenement->devalidable = 0;
			if (date('m') == $tzDate[1]){
				$oEvenement->devalidable = 1;
			}
			$oEvenement->url_code_anomalie = "";
			$oEvenement->plannifie = 0 ;
			if (isset ($oEvenement->evenement_iStagiaire) && $oEvenement->evenement_iStagiaire > 0){
				$oClient = clientSrv::getById($oEvenement->evenement_iStagiaire);
				if (isset($oClient) && isset ($oClient->client_iNumIndividu) && $oClient->client_iNumIndividu > 0){
					$iNumero = clientSrv::getClientCodeStagiaireMiracle($oClient->client_iNumIndividu) ;
					if ($iNumero > 0){
						$oEvenement->url_code_anomalie = sprintf(URL_CODE_ANOMALIE, $iNumero); 
					}
				}
				$oEvenement->plannifie = evenementSrv::getEventPlanifie ($oEvenement->evenement_iStagiaire, $oEvenement->evenement_zDateHeureDebut) ;
			}
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);

		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
		
		/***PRINT***/
		$toParams1[0] = new stdClass();
		$toParams1[0]->statut = 1;
		$toParams1[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams1[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		if ($toParams1[0]->zDateFin == 0){
			$toParams1[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams1[0]->zDateDebut);
		}
		$toParams1[0]->iTypeEvenement = $this->param('evenement_iTypeEvenementId', 0, true);
		$toParams1[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams1[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams1[0]->iUtilisateur = $iUtilisateurId; 
		$toParams1[0]->iCheckboxeAutoplanification = 0;
		$toParams1[0]->iCheckDate = $this->param('iCheckDate', 0, true);
		$toParams1[0]->evenement_zSociete = $this->param('evenement_zSociete', "", true);

 		$toEvenementPrint = evenementSrv::listCriteria($toParams1, 'evenement_zDateHeureDebut');
		foreach ($toEvenementPrint['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		/***PRINT***/

		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}
		/**********************************************************************************************************/
		jClasses::inc('utilisateurs~groupeSrv');
		$toGroupe = groupeSrv::listCriteria(array());
		$toCriteria = array ();
		$toCriteria['utilisateur_statut'] = 1 ;
		$toUtilisateur = utilisateursSrv::listCriteria($toCriteria);
		/**********************************************************************************************************/
		$oClientAffiche = new StdClass ();
		if (isset($toParams1[0]->iStagiaire) && $toParams1[0]->iStagiaire > 0){
			$oClientAffiche = clientSrv::getById($toParams1[0]->iStagiaire);
		}
		/**********************************************************************************************************/
		$oNavBar->normalizeBar ($toEvenement['iResTotal']) ;
		$oNavBar->mergeBar ();

		$oRep->body->assign('toGroupe', $toGroupe['toListes']);
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);
		$oRep->body->assign('toUtilisateur', $toUtilisateur['toListes']);
		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('toEvenementPrint', $toEvenementPrint['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$oRep->body->assign('oClient', $oClientAffiche);
		$oRep->body->assign ("iCurrentPage", $iCurrentPage) ;
		$oRep->body->assign ("oNavBar", $oNavBar) ;

		$oRep->bodyTpl = "evenement~FoEventListingResult" ;
		return $oRep;
	}	

	function getEventListingApprocheListe (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-1.5.1.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-ui-1.8.10.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/script.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/affecter.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/light/css/redmond/jquery-ui-1.8.10.custom.css');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;

		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', date('d/m/Y'), true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', 0, true);
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}

		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_DISPONIBLE;
		}else{
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE;
		}
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);

 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);

		$tEventNonCreer = $this->param('tEventNonCreer', array(), true) ;
		$bAffectation = $this->param('bAffectation', 1, true) ;
		if ($bAffectation > 0){
			if (sizeof($tEventNonCreer)){
				$zEvenementId = "";
				foreach ($tEventNonCreer as $oEventNonCreer){
					if ($zEvenementId == ""){
						$zEvenementId = $oEventNonCreer->evenement_id;
					}else{
						$zEvenementId .= ",".$oEventNonCreer->evenement_id;
					}
				}
				if ($zEvenementId != ""){
					$tResult = evenementSrv::findEventByListEventId ($zEvenementId) ;
					$oRep->body->assign('tResult', $tResult);
				}
			} 
		}
		$oRep->body->assign('bAffectation', $bAffectation);
		
		$oRep->bodyTpl = "evenement~FoEventListingResultCourDisponiblePlannifie" ;
		return $oRep;
	}

	function suppressionMultipleEvent (){
		global $gJConfig ;
		$oResp = $this->getResponse('redirect') ;
    	
		jClasses::inc('evenement~evenementSrv');
		$zListeEvenementId = $this->param('zListeEvenementId', 0, true);
		$iTypeEvenement = $this->param('iTypeEvenement', 0, true);
		$zDateDebut = $this->param('zDateDebut', '', true);
		$zDateFin = $this->param('zDateFin', '', true);
		$iStagiaire = $this->param('iStagiaire', '', true);


 		evenementSrv::suppressionMultipleEvent($zListeEvenementId);
		if ($zDateDebut == ''){
			$zDateDebut = date('d').'/'.date('m').'/'.date('Y') ;
		}
		if ($zDateFin == ''){
			$zDateFin = date('d').'/'.date('m').'/'.date('Y') ;
		}
		$tzDateDebut	= explode('/', $zDateDebut);
		$tzDateFin		= explode('/', $zDateFin);

		$oResp->action = 'evenement~FoEvenement:getEventListing' ;
		$oResp->params = array ('dtcm_event_rdv'=> $zDateDebut, 
								'dtcm_event_rdv1'=>$zDateFin, 
								'evenement_stagiaire'=>$iStagiaire,
								'evenement_iTypeEvenementId'=>$iTypeEvenement
								);	
        return $oResp ;
	}
	function libererEvent (){
		global $gJConfig ;
		$oResp = $this->getResponse('redirect') ;
    	
		jClasses::inc('evenement~evenementSrv');
		$iEventId = $this->param('evenement_id', 0, true);

		$oCurrentEvent = evenementSrv::getById($iEventId) ;
        $oCurrentUser = jAuth::getUserSession() ;
        if ($iEventId)
        {
            $toEvents['evenement_id'] = $iEventId ;
        	$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ;
        	$toEvents['evenement_iUtilisateurId'] = $oCurrentEvent->evenement_iUtilisateurId ;
        	$toEvents['evenement_zLibelle'] = '' ;
        	$toEvents['evenement_zDescription'] = '' ;
        	$toEvents['evenement_iStagiaire'] = 0 ;
            $tzDateHeure = explode(" ", $oCurrentEvent->evenement_zDateHeureDebut);
            $tzDate = explode("-", $tzDateHeure[0]);
            $oCurrentEvent->evenement_zDateHeureDebut = $tzDate[2] . "/" . $tzDate[1] . "/" . $tzDate[0] . " " . $tzDateHeure[1];
        	$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
        	$toEvents['evenement_iDuree'] = $oCurrentEvent->evenement_iDuree ;
        	$toEvents['evenement_iPriorite'] = 0 ;
        	$toEvents['evenement_iRappel'] = 0 ;
        	$toEvents['evenement_iStatut'] = STATUT_PUBLIE ;
        	$toEvents['evenement_origine'] = 2 ;
            evenementSrv::save($toEvents) ;
		}

		$oResp->action = 'jelix_calendar~FoCalendar:index' ;

		$date = $this->param('date', "", true);
		if ($date != date('Y-m-d')){
			$tDate = explode ("-", $date); 
			$date = $tDate[2] . "-" . $tDate[1] . "-" . $tDate[0]; 
		}
		$iTypeEvenementId = $this->param('iTypeEvenementId', 0, true);
		$iUtilisateurId1 = $this->param('iUtilisateurId1', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);
		$iGroupeId = $this->param('iGroupeId', 0, true);

		$oResp->params = array ('date'=> $date, 
								'iTypeEvenementId'=>$iTypeEvenementId, 
								'iUtilisateurId1'=>$iUtilisateurId1,
								'iGroupeId'=>$iGroupeId,
								'iAffichage'=>$iAffichage
								);
        return $oResp ;
	}

	function libererCreneauPlannifie (){
		global $gJConfig ;
		$oResp = $this->getResponse('encodedJson') ;
    	
		jClasses::inc('evenement~evenementSrv');
		$iEventId = $this->param('eventId', 0, true);

		$oCurrentEvent = evenementSrv::getById($iEventId) ;
        $oCurrentUser = jAuth::getUserSession() ;
        if ($iEventId)
        {
            $toEvents['evenement_id'] = $iEventId ;
        	$toEvents['evenement_iTypeEvenementId'] = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE ;
        	$toEvents['evenement_iUtilisateurId'] = $oCurrentEvent->evenement_iUtilisateurId ;
        	$toEvents['evenement_zLibelle'] = '' ;
        	$toEvents['evenement_zDescription'] = '' ;
        	$toEvents['evenement_iStagiaire'] = 0 ;
            $tzDateHeure = explode(" ", $oCurrentEvent->evenement_zDateHeureDebut);
            $tzDate = explode("-", $tzDateHeure[0]);
            $oCurrentEvent->evenement_zDateHeureDebut = $tzDate[2] . "/" . $tzDate[1] . "/" . $tzDate[0] . " " . $tzDateHeure[1];
        	$toEvents['evenement_zDateHeureDebut'] = $oCurrentEvent->evenement_zDateHeureDebut ;
        	$toEvents['evenement_iDuree'] = $oCurrentEvent->evenement_iDuree ;
        	$toEvents['evenement_iPriorite'] = 0 ;
        	$toEvents['evenement_iRappel'] = 0 ;
        	$toEvents['evenement_iStatut'] = STATUT_PUBLIE ;
        	$toEvents['evenement_origine'] = 2 ;
            evenementSrv::save($toEvents) ;
		}

		$oResp->datas = 1;
        return $oResp ;
	}

	function deleteEvent (){
		global $gJConfig ;
		$oResp = $this->getResponse('redirect') ;
    	
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('evenement~evenementValidationSrv');
        jClasses::inc('commun~toolDate');

		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);
		$date = $this->param('date', date('Y-m-d'), true);

		$iOption = $this->param('iOption', 0, true);
		$iTypeEvenement = $this->param('iTypeEvenement', 0, true);
		$iStagiaire = $this->param('iStagiaire', 0, true);
		$zDateDebut = $this->param('zDateDebut', '', true);
		$zDateFin = $this->param('zDateFin', '', true);

		if ($date != "" && $iAffichage < 3){
			$tDate = explode ('-', $date);//11-04-2011
			$zDate = $tDate[2] . '-' . $tDate[1] . '-' . $tDate[0];
		}else{
			$zDate = $date ;
		}
		if ($iEvenementId > 0){
			$oValidation = evenementValidationSrv::getByEventId($iEvenementId);		
			//$oValidation = evenementValidationSrv::getByEventId($iEvenementId);		
			if ($oValidation != null && $oValidation->evenementvalidation_id > 0){
				evenementValidationSrv::delete($oValidation->evenementvalidation_id);
			}
			evenementSrv::delete($iEvenementId);		
		}

		if ($iOption == 1){

			if ($zDateDebut == ''){
				$zDateDebut = date('d').'/'.date('m').'/'.date('Y') ;
			}
			if ($zDateFin == ''){
				$zDateFin = date('d').'/'.date('m').'/'.date('Y') ;
			}
			$tzDateDebut	= explode('/', $zDateDebut);
			$tzDateFin		= explode('/', $zDateFin);

			$oResp->action = 'evenement~FoEvenement:getEventListing' ;
			$oResp->params = array ('dtcm_event_rdv'=> $zDateDebut, 
									'dtcm_event_rdv1'=>$zDateFin, 
									'evenement_stagiaire'=>$iStagiaire,
									'evenement_iTypeEvenementId'=>$iTypeEvenement
									);	
		}else{
			$oResp->action = 'jelix_calendar~FoCalendar:index' ;
			$oResp->params = array ('date'=> $zDate, 'iAffichage'=>$iAffichage);	
		}
        return $oResp ;
	}

	function chargeEvenementParId (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEvenementId = $this->param('iEvenementId', 0, true); 
    	jClasses::inc('evenement~evenementSrv');
		$oEvenement = evenementSrv::getById($iEvenementId);

		$oRep->datas = $oEvenement;

		return $oRep;
	}

	function chargeTypeEvenementParEventId (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iEvenementId = $this->param('iEvenementId', 0, true); 
    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc('typeEvenement~typeEvenementsSrv');

		$oEvenement = evenementSrv::getById($iEvenementId);
		$oTypeEvenement = new stdClass () ;
		if ($oEvenement->evenement_iTypeEvenementId > 0){
			$oTypeEvenement = typeEvenementsSrv::getById($oEvenement->evenement_iTypeEvenementId);
		}
		$oRep->datas = $oTypeEvenement->typeevenements_zLibelle;

		return $oRep;
	}

	function calculDateDiff (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		jClasses::inc('evenement~evenementSrv');
        jClasses::inc('commun~toolDate');
		
		$zDebut = $this->param('zDebut', 0, true); 
		$zFin = $this->param('zFin', 0, true); 
		if ($zDebut == 0 || $zFin == 0){
			$oRep->datas = -1;
		}else{
			$iDiff = toolDate::date_diff (toolDate::toDateSQL($zDebut).' 00:00:00', toolDate::toDateSQL($zFin).' 00:00:00');
			$oRep->datas = $iDiff ;
		}
		return $oRep;
	}

	function autocompleteStagiaire(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$CritereNom = $this->param('q','',true);
		$iOption = $this->param('o',0,true);
		$tCritere = explode(' ', trim($CritereNom));

		if ($iOption == 0){
			$zSql="SELECT * FROM clients ";
			$zSql.="INNER JOIN composant_cours ON clients.client_iNumIndividu = composant_cours.CODE_STAGIAIRE_MIRACLE ";
			$zSql.="WHERE 1=1 ";
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
			//$zSql .= ") GROUP BY client_zNom, client_zPrenom ORDER BY client_dateMaj DESC ";  
			$zSql .= ") GROUP BY client_id ORDER BY client_dateMaj DESC ";  

			$cnx        = jDb::getConnection();
			$oRes       = $cnx->query($zSql);
			$tPersonne = $oRes->fetchAll();
			if (sizeof($tPersonne) == 0){
				$zSql="SELECT * FROM clients ";
				$zSql.="WHERE 1=1 ";
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
				//$zSql .= ") GROUP BY client_zNom, client_zPrenom ORDER BY client_dateMaj DESC ";  
				$zSql .= ") GROUP BY client_id ORDER BY client_dateMaj DESC ";  

				$cnx        = jDb::getConnection();
				$oRes       = $cnx->query($zSql);
				$tPersonne = $oRes->fetchAll();
			}				
		}else{
			$zSql="SELECT * FROM clients ";
			$zSql.="WHERE 1=1 ";
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
			//$zSql .= ") GROUP BY client_zNom, client_zPrenom ORDER BY client_dateMaj DESC ";  
			$zSql .= ") GROUP BY client_id ORDER BY client_dateMaj DESC ";  

			$cnx        = jDb::getConnection();
			$oRes       = $cnx->query($zSql);
			$tPersonne = $oRes->fetchAll();
		}
		$rep->datas  = $tPersonne;
		return $rep;
	}

	function autocompleteSociete(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql="SELECT * FROM clients INNER JOIN societe ON client_iSociete = societe_id WHERE 1=1 ";
		if ($oUtilisateur != null && $oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR){
			$zSql.=" AND client_iUtilisateurCreateurId = " . $iUtilisateurId ;
		}
		$t = array();
		foreach ($tCritere as $zCritere) {
			$t[] = sprintf(" societe_zNom like '%%%s%%' ", trim(addslashes($zCritere)) );
		}
		$zSql.=" AND (";
		$zSql .= implode(" OR ", $t);
		$zSql .= ") GROUP BY societe_zNom ORDER BY societe_zNom ASC ";  

		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tSoc = $oRes->fetchAll();	
		$rep->datas  = $tSoc;
		return $rep;
	}

	function autocompleteStagiaireAffectation(){
		$rep        = $this->getResponse('encodedJson');
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$CritereNom = $this->param('q','',true);
		$tCritere = explode(' ', trim($CritereNom));
		$zSql="SELECT * FROM clients WHERE 1=1 ";
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
		//$zSql .= ") GROUP BY client_zNom, client_zPrenom ORDER BY client_dateMaj DESC ";  
		$zSql .= ") GROUP BY client_id ORDER BY client_dateMaj DESC ";  



		$cnx        = jDb::getConnection();
		$oRes       = $cnx->query($zSql);
		$tPersonne = $oRes->fetchAll();

		$rep->datas  = $tPersonne;
		return $rep;
	}
	function getTypeEvenement (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$iTypeEvenementId = $this->param('iTypeEvenementId', 0, true); 
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
		$oTypeEvenementSrv = typeEvenementsSrv::getById($iTypeEvenementId);
		$oRep->datas = $oTypeEvenementSrv;
		return $oRep;
	}


	
	function exportEventListing (){
		@ini_set ("memory_limit", "-1") ;
		global $gJConfig;
		$oRep = $this->getResponse('binary');
		$zExportsFileName = "exportEvenement_". date ("Ymd_His") . ".xls" ;
		$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/evenement/" . $zExportsFileName ;

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		$toParams[0] = new stdClass ();
		$toParams[0]->zDateDebut = $this->param('zDateDebut','',true);
		$toParams[0]->zDateFin = $this->param('zDateFin','',true);
		$toParams[0]->iTypeEvenement = $this->param('iTypeEvenement','',true);
		$toParams[0]->iStagiaire = $this->param('iStagiaire','',true);
		$toParams[0]->evenement_origine = $this->param('evenement_origine','',true);
		$toParams[0]->cours_produit = $this->param('iCoursProduit',0,true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		if ($toParams[0]->cours_produit == 1){
	 		$toEvenement = evenementSrv::listCriteriaWithValidation($toParams, 'evenement_zDateHeureDebut');
		}else{
	 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');
		}

		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;
		
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		evenementSrv::exportEventListing($zExportsFullPath, $toEvenement, $toParams, $toTypeEvenement, $oUtilisateur, $toTypeEvenementSelected);
		if (is_file ($zExportsFullPath) ) {
			$oRep->fileName = $zExportsFullPath ;
			$oRep->outputFileName = $zExportsFileName ;
			$oRep->doDownload = true ;
		}else{
			die('Erreur lors de la création du fichier xls');
		}

		return $oRep;
	}

	function exportIcsEventListing (){
		@ini_set ("memory_limit", "-1") ;
		global $gJConfig;
		$oRep = $this->getResponse('binary');

		jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);
		if (isset ($oUtilisateur->utilisateur_id) && $oUtilisateur->utilisateur_id > 0){
			$zExportsFileName = "exportIcsEvenement_".$oUtilisateur->utilisateur_zPrenom."_". date ("Ymd_His") . ".ics" ;
			$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/ics/evenement/" . $zExportsFileName ;

			$toParams[0] = new stdClass ();
			$toParams[0]->zDateDebut = $this->param('zDateDebut','',true);
			$toParams[0]->zDateFin = $this->param('zDateFin','',true);
			$toParams[0]->iTypeEvenement = $this->param('iTypeEvenement','',true);
			$toParams[0]->iStagiaire = $this->param('iStagiaire','',true);
			$toParams[0]->evenement_origine = $this->param('evenement_origine','',true);
			$toParams[0]->iCheckboxeAutoplanification = 0;
			$toParams[0]->iUtilisateur = $iUtilisateurId; 
			$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');

			foreach ($toEvenement['toListes'] as $oEvenement){
				$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
				$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
				$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
				$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
				$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
			}

			evenementSrv::exportIcsEventListing($zExportsFullPath, $toEvenement);
			if (is_file ($zExportsFullPath) ) {
				$oRep->fileName = $zExportsFullPath ;
				$oRep->outputFileName = $zExportsFileName ;
				$oRep->doDownload = true ;
			}else{
				die('Erreur lors de la création du fichier ics');
			}
		}else{
			die('Erreur lors de la création du fichier ics');
		}

		return $oRep;
	}

	function approcheParListeGetEvent(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$id = $this->param('id', 0, true); 
		
    	jClasses::inc('evenement~evenementSrv');
		$oRep->datas = evenementSrv::getById($id);
		return $oRep;
	}	

	function approcheParListeGetPeriodicite(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$toPeriodicite = array ('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');
		$oRep->datas = $toPeriodicite;
		return $oRep;

	}
	function approcheParListeGetDurePeriodicite(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$toDurePeriodicite = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');
		$oRep->datas = $toDurePeriodicite;
		return $oRep;
	}
	function changeEtat (){
        $oRep = $this->getResponse('redirect');

		$date = $this->param('date', date('Y-m-d'), true);
		$iEvenementId = $this->param('iEvenementId', 0, true);
		$iTypeEtat = $this->param('typeetat', 0, true);
		$iAffichage = $this->param('iAffichage', 1, true);

		$oEtatEvenement = new StdClass () ;

		$oEtatEvenement->etat_iEvenementId = $iEvenementId ;
		$oEtatEvenement->etat_iTypeEtatId = $iTypeEtat ;
		$oEtatEvenement->etat_zCommentaire = "" ;
		$oEtatEvenement->etat_zDateSaisie = date("Y-m-d H:i:s") ;

    	jClasses::inc('evenement~etatEvenementSrv');
		$oRep->datas = etatEvenementSrv::save($oEtatEvenement);
		$oRep->action = 'jelix_calendar~FoCalendar:index' ;
		$oRep->params = array ('date'=>$date, 'iAffichage'=>$iAffichage);	
		return $oRep;
	}

	function eventListingEditEvent (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
    	jClasses::inc('client~societeSrv');

		$iEvenementId 					= $this->param('iEvenementId',0);  

		$bEdit 							= ($iEvenementId>0) ? true : false ;
        $oEvenement 					= ($iEvenementId>0) ? evenementSrv::getById($iEvenementId) : jDao::createRecord('commun~evenement') ;
        $oStagiaire 					= ($iEvenementId>0) ? clientSrv::getById($oEvenement->evenement_iStagiaire) : jDao::createRecord('commun~client') ;

		$oParamsTypeevent				= new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

		if ($bEdit && $oEvenement->evenement_zDateHeureDebut){
			$tzDateHeur = explode (' ', $oEvenement->evenement_zDateHeureDebut);
			$tzDate = explode ('-', $tzDateHeur[0]); 
			$tzHeure = explode (':', $tzDateHeur[1]); 
			$oEvenement->evenement_zDateDebut = $tzDate[2] . '/' . $tzDate[1] . '/' . $tzDate[0];
			$oEvenement->evenement_zHeureDebut = $tzHeure[0] . ':' . $tzHeure[1];
		}
		if ($oEvenement->evenement_iStagiaire){
			$toParams = array();
			$toParams[0] = new stdClass();
			$toParams[0]->id = $oEvenement->evenement_iStagiaire;
			$toStagiaire = clientSrv::listCriteria($toParams);
			$oEvenement->evenement_zStagiaire = $toStagiaire['toListes'][0]->client_zNom . ' ' .  $toStagiaire['toListes'][0]->client_zPrenom . '  [' .  $toStagiaire['toListes'][0]->client_zTel . ']  [' .  $toStagiaire['toListes'][0]->societe_zNom . ']  [' .  $toStagiaire['toListes'][0]->client_zVille . ']';
		}
		if ($oStagiaire->client_iSociete > 0){
			$oSociete = societeSrv::getById($oStagiaire->client_iSociete) ;
		}
		$oEvenement->evenement_zDateHeureDebutFr = '' ;
		if ($oEvenement->evenement_zDateHeureDebut != ""){
			$tzDate = explode (' ', $oEvenement->evenement_zDateHeureDebut);
			$tDate = explode ('-', $tzDate[0]);
			$tTime = explode (':', $tzDate[1]);
			$oEvenement->evenement_zDateHeureDebutFr = $tDate[2].'/'.$tDate[1].'/'.$tDate[0].' ' .$tTime[0].':'.$tTime[1];
		}

		if ($oEvenement->evenement_iTypeEvenementId > 0){
			$oTypeEvenementEvent = typeEvenementsSrv::getById ($oEvenement->evenement_iTypeEvenementId) ;
		}

       	$toParams['iEvenementId'] 		= $iEvenementId ;
       	$toParams['oEvenement'] 		= $oEvenement ;
       	$toParams['oStagiaire'] 		= $oStagiaire ;
		$toParams['toTypeEvenement'] 	= $oTypeEvenement['toListes'];
		$toParams['oSociete'] 			= $oSociete;
		$toParams['oTypeEvenement'] 	= $oTypeEvenementEvent;
//print_r($toParams) ; die;
		$oRep->datas = $toParams ;
		return $oRep;
	}

	function savePopEventListing(){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');

		$iEventId 					= $this->param('iEventId',0);  
		$iTypeEventId 				= $this->param('iTypeEventId',0);  
		$zEventDesc 				= $this->param('zEventDesc',"");  
		$iEventStagiaireId 			= $this->param('iEventStagiaireId',0);  
		

		jClasses::inc ('evenement~evenementSrv') ;
		evenementSrv::savePopEventListing($iEventId, $iTypeEventId, $zEventDesc, $iEventStagiaireId) ;

		/*$oRep->action = 'evenement~FoEvenement:getEventListing' ;
		$oRep->params = array ('dtcm_event_rdv'=>$this->param('dtcm_event_rdv'), 
								'dtcm_event_rdv1'=>$this->param('dtcm_event_rdv1'), 
								'evenement_origine'=>$this->param('evenement_origine'), 
								'evenement_iTypeEvenementId'=>$this->param('evenement_iTypeEvenementId'),
								'evenement_stagiaire'=>$this->param('evenement_stagiaire')
								);	*/
		$oRep->datas = $iEventId ;
		return $oRep;
	}

	function eventListingDispo (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/timepicker.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/popup.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->bodyTpl = "evenement~FoEventListingDispo" ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;

       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvenement					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvenement) && sizeof ($toTypeEvenement) > 0){
			$oTypeEvenement = array();
			$oTypeEvenement['iResTotal'] = sizeof ($toTypeEvenement) ;
			$oTypeEvenement['toListes']  = $toTypeEvenement ;
		}else{
			$oTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  

 		//$toTypeEvenement = typeEvenementsSrv::listCriteria($oParamsTypeevent);

		//Date Année Header 
		$iAnnee = date('Y');
		$tiAnnee = array ();
		for ($i=$iAnnee-10; $i<=$iAnnee+20; $i++){
			array_push ($tiAnnee, $i);
		}

		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toStagiaire = clientSrv::listCriteria($toParamsClient);

		$oRep->body->assign('now', date('d/m/Y'));
		$oRep->body->assign('tiAnnee', $tiAnnee);
		$oRep->body->assign('toTypeEvenement', $oTypeEvenement['toListes']);
		$oRep->body->assign('toStagiaire', $toStagiaire['toListes']);

		return $oRep;
	}

	function getEventListingDispo (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-1.5.1.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-ui-1.8.10.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/script.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/affecter.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/light/css/redmond/jquery-ui-1.8.10.custom.css');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');

    	jClasses::inc('evenement~evenementSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		/****************
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); 

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		$zDatefinC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));
		****************/
		/****************/
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$zDatedebC    = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
		$zDatefinC    = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;
		/****************/


		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;
		$toParams[0]->groupe_id = $this->param('groupe_id', 0, true);
		$toParams[0]->professeurs = $this->param('professeurs', 0, true);
		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}
		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_DISPONIBLE;
		}else{
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE;
		}
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);
 		$toEvenement = evenementSrv::listCriteria($toParams, 'evenement_zDateHeureDebut');

		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}

		$tEventNonCreer = $this->param('tEventNonCreer', array(), true) ;
		$bAffectation = $this->param('bAffectation', 1, true) ;
		if ($bAffectation > 0){
			if (sizeof($tEventNonCreer)){
				$zEvenementId = "";
				foreach ($tEventNonCreer as $oEventNonCreer){
					if ($zEvenementId == ""){
						$zEvenementId = $oEventNonCreer->evenement_id;
					}else{
						$zEvenementId .= ",".$oEventNonCreer->evenement_id;
					}
				}
				if ($zEvenementId != ""){
					$tResult = evenementSrv::findEventByListEventId ($zEvenementId) ;
					$oRep->body->assign('tResult', $tResult);
				}
			} 
		}
		
		/**********************************************************************************************************/
		jClasses::inc('utilisateurs~groupeSrv');
		$toGroupe = groupeSrv::listCriteria(array());
		$toCriteria = array ();
		$toCriteria['utilisateur_statut'] = 1 ;
		$toUtilisateur = utilisateursSrv::listCriteria($toCriteria);
		/**********************************************************************************************************/

		//Periodicité 
		$toPeriodicite = array ('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');

		$toDurePeriodicite = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');


		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$oRep->body->assign('toGroupe', $toGroupe['toListes']);
		$oRep->body->assign('toUtilisateur', $toUtilisateur['toListes']);
		$oRep->body->assign('bAffectation', $bAffectation);
		$oRep->body->assign('toPeriodicite', $toPeriodicite);
		$oRep->body->assign('toDurePeriodicite', $toDurePeriodicite);

		$oRep->bodyTpl = "evenement~FoEventListingResultDispo" ;

		return $oRep;
	}

	function getEventListingCreneauPlannifie (){
		global $gJConfig ;
        $oRep = $this->getResponse('FoHtml');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-1.3.2.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery-ui-1.7.2.custom.min.js');

		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/layout.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/commun.css');
		//$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/home.css');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery-ui-1.7.2.custom.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.autocomplete.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/jquery.maskedinput-1.2.2.min.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/front/css/jquery.autocomplete.css');

		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-1.5.1.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery-ui-1.8.10.custom.min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/jquery.loader-min.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/script.js');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/front/js/affecter.js');
		$oRep->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/light/css/redmond/jquery-ui-1.8.10.custom.css');
		$oRep->addJSLink ($gJConfig->urlengine['basePath'] . 'design/light/js/timepicker.js');

    	jClasses::inc('evenement~evenementSrv');
    	jClasses::inc('client~clientSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);

		/****************
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$premier_jour = mktime(0,0,0, $month,$day-(!$num_day?7:$num_day)+1,$year);
		$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $premier_jour))); 

		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$dernier_jour = mktime(0,0,0, $month,7-(!$num_day?7:$num_day)+$day,$year);
		$zDatefinC      = toolDate::toDateFr(toolDate::toDateSQL(date('d-m-Y', $dernier_jour)));
		****************/
		/****************/
		$date = date('d-m-Y');	
		list($day, $month, $year) = explode('-', $date); 
		$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
		$zDatedebC    = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
		$zDatefinC    = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;
		/****************/


		$toParams[0] = new stdClass();
		$toParams[0]->statut = 1;
		$toParams[0]->groupe_id = $this->param('groupe_id', 0, true);
		$toParams[0]->professeurs = $this->param('professeurs', 0, true);
		$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
		$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);
		$toParams[0]->iClientId = $this->param('iClientId', 0, true);
		$toParams[0]->iCreneauPlannifie = 1;
		if ($toParams[0]->zDateFin == 0){
			$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
		}
		if ($iUtilisateurId == AUDIT_ID_CATRIONA){
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_DISPONIBLE;
		}else{
			$toParams[0]->iTypeEvenement = ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE;
		}
		$toParams[0]->evenement_origine = $this->param('evenement_origine', 0, true);
		$toParams[0]->iStagiaire = $this->param('evenement_stagiaire', 0, true);
		$toParams[0]->iUtilisateur = $iUtilisateurId; 
		$toParams[0]->iCheckboxeAutoplanification = 0;
		$toParams[0]->iCheckDate = $this->param('iCheckDate', 0, true);
 		$toEvenement = evenementSrv::listCriteriaCreueauPlannifie($toParams, 'evenement_zDateHeureDebut');

		foreach ($toEvenement['toListes'] as $oEvenement){
			$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
			$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
			$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
			$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
			$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
		}
		
       	$oParamsTypeevent = new stdClass();
		$oParamsTypeevent->typeevenements_iStatut = STATUT_PUBLIE;

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$toTypeEvent					= utilisateursSrv::getListeTypeEvenementUilisateur ($iUtilisateurId);
		if (is_array($toTypeEvent) && sizeof ($toTypeEvent) > 0){
			$toTypeEvenement = array();
			$toTypeEvenement['iResTotal'] = sizeof ($toTypeEvent) ;
			$toTypeEvenement['toListes']  = $toTypeEvent ;
		}else{
			$toTypeEvenement					= typeEvenementsSrv::listCriteria($oParamsTypeevent);
		}  
		$toTypeEvenementSelected = array();
		if ($toParams[0]->iTypeEvenement > 0){
			foreach ($toTypeEvenement['toListes'] as $oTypeEvenement){
				if ($oTypeEvenement->typeevenements_id == $toParams[0]->iTypeEvenement){
					array_push ($toTypeEvenementSelected, $oTypeEvenement);					
				}
			}
		}

		$tEventNonCreer = $this->param('tEventNonCreer', array(), true) ;
		$bAffectation = $this->param('bAffectation', 1, true) ;
		if ($bAffectation > 0){
			if (sizeof($tEventNonCreer)){
				$zEvenementId = "";
				foreach ($tEventNonCreer as $oEventNonCreer){
					if ($zEvenementId == ""){
						$zEvenementId = $oEventNonCreer->evenement_id;
					}else{
						$zEvenementId .= ",".$oEventNonCreer->evenement_id;
					}
				}
				if ($zEvenementId != ""){
					$tResult = evenementSrv::findEventByListEventId ($zEvenementId) ;
					$oRep->body->assign('tResult', $tResult);
				}
			} 
		}
		
		/**********************************************************************************************************/
		jClasses::inc('utilisateurs~groupeSrv');
		$toGroupe = groupeSrv::listCriteria(array());
		$toCriteria = array ();
		$toCriteria['utilisateur_statut'] = 1 ;
		$toUtilisateur = utilisateursSrv::listCriteria($toCriteria);
		/**********************************************************************************************************/

		//Periodicité 
		$toPeriodicite = array ('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');

		$toDurePeriodicite = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');
		
		$oClient = clientSrv::getById($toParams[0]->iClientId); 


		$oRep->body->assign('toTypeEvenement', $toTypeEvenement['toListes']);
		$oRep->body->assign('oUtilisateur', $oUtilisateur);
		$oRep->body->assign('toTypeEvenementSelected', $toTypeEvenementSelected);
		$oRep->body->assign('toEvenement', $toEvenement['toListes']);
		$oRep->body->assign('iResTotal', $toEvenement['iResTotal']);
		$oRep->body->assign('toParams', $toParams);
		$oRep->body->assign('toGroupe', $toGroupe['toListes']);
		$oRep->body->assign('toUtilisateur', $toUtilisateur['toListes']);
		$oRep->body->assign('bAffectation', $bAffectation);
		$oRep->body->assign('toPeriodicite', $toPeriodicite);
		$oRep->body->assign('toDurePeriodicite', $toDurePeriodicite);
		$oRep->body->assign('oClient', $oClient);
//print_r($oClient);die;
		$oRep->body->assign('iClientId', $toParams[0]->iClientId);

		$oRep->bodyTpl = "evenement~FoEventListingResultCreneauPlannifie" ;

		return $oRep;
	}

	function getDefaultTypeEvenement (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('utilisateurs~utilisateursSrv');

		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oRep->datas = utilisateursSrv::getDefaultTypeEvenementUilisateur ($iUtilisateurId) ;
		return $oRep;
	}

	function chargeProfParGroupId (){
		global $gJConfig;
		$oRep = $this->getResponse('encodedJson');
		$toParams['groupe_id'] = $this->param('groupe_id', 0, true); 
		$toParams['utilisateur_statut'] = 1 ;
        jClasses::inc('utilisateurs~utilisateursSrv');
		$toUtilisateur = utilisateursSrv::listCriteria($toParams);
		$oRep->datas = $toUtilisateur['toListes'];
		return $oRep;
	}

}
?>