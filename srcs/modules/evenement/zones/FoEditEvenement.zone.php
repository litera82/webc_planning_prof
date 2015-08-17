<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Tahiry RANDRIAMBOLA <t.randriambola@gmail.com>
*/

class FoEditEvenementZone extends jZone 
{
 
    protected $_tplname		= 'evenement~FoEditEvenement.zone' ;
	protected $_useCache	= false ;

	/**
	* Chargement des données pour affichage
	*/
	protected function _prepareTpl()
	{
		jClasses::inc ('commun~toolDate') ;
		jClasses::inc ('evenement~evenementSrv') ;
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
    	jClasses::inc('typeEvenement~typeEvenementsSrv');
    	jClasses::inc('client~clientSrv');
 
		$zDate 							= $this->getParam('zDate',date('Y-m-d'));  
		$tEvent 						= $this->getParam('tEvent','');  
		$iAffichage 					= $this->getParam('iAffichage',1);  
		$x			 					= $this->getParam('x',0);  
		
		$prec = $this->getParam('prec', 0, true);
		$debut = $this->getParam('debut', "", true);
		$fin = $this->getParam('fin', "", true);

		if ($zDate != ""){
			$tzDate = explode ('-', $zDate);
			$zNewDate = $tzDate[2].'/'.$tzDate[1].'/'.$tzDate[0];
		}else{
			$zNewDate = "";
		}
		$iTime 							= $this->getParam('iTime', date('H:i'));  
		if ($iTime == 0){
			$iTime = date('H:i');
		}
		if ($zDate == ""){
			$zDate = date('Y-m-d');
		}

		$tTime = explode (':',$iTime);
		$zDateDefaultEvent				= toolDate::toDateFR($zDate).' ' .$tTime[0].':'.$tTime[1];
		$iEvenementId 					= $this->getParam('iEvenementId',0);  

		$bEdit 							= ($iEvenementId>0) ? true : false ;
        //$oEvenement 					= ($iEvenementId>0) ? evenementSrv::getById($iEvenementId) : jDao::createRecord('commun~evenement') ;
        $oEvenement 					= ($iEvenementId>0) ? evenementSrv::getEventAndComposantCoursById($iEvenementId) : jDao::createRecord('commun~evenement') ;
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

		$toParamsClient[0] = new stdClass();
		$toParamsClient[0]->statut = 1;
		$toTmpStagiaire					= clientSrv::listCriteria($toParamsClient);

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

		//Periodicité 
		$toPeriodicite = array ('00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30', '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30', '23:00', '23:30');

		$toDurePeriodicite = array ('0 minutes', '5 minutes', '10 minutes', '15 minutes', '20 minutes', '25 minutes', '30 minutes', '35 minutes', '40 minutes', '45 minutes', '50 minutes', '55 minutes', '1 heures', '2 heures', '3 heures', '4 heures', '5 heures', '6 heures', '7 heures', '8 heures', '9 heures', '10 heures');

		$zDurePeriodicite = '0 minutes;5 minutes;10 minutes;15 minutes;20 minutes;25 minutes;30 minutes;35 minutes;40 minutes;45 minutes;50 minutes;55 minutes;1 heures;2 heures;3 heures;4 heures;5 heures;6 heures;7 heures;8 heures;9 heures;10 heures';		

		if ($iEvenementId > 0) {
			$x = 10 ;
		}

		/***************VALIDATION**************/
		if ($iEvenementId > 0){
			jClasses::inc('evenement~evenementValidationSrv');
			$toParams = array ();
			$toParams[0] = new StdClass ();
			$toParams[0]->evenementvalidation_eventId = $iEvenementId;
			
			$toValidation = evenementValidationSrv::listCriteria($toParams);

			if (sizeof($toValidation['iResTotal']) > 0 && isset($toValidation['toListes'][0])){
				$oEvenement->validation_validationId = $toValidation['toListes'][0]->evenementvalidation_validationId; 
				$oEvenement->validation_zLibelle = $toValidation['toListes'][0]->validation_zLibelle; 
				$oEvenement->evenementvalidation_skype = $toValidation['toListes'][0]->evenementvalidation_skype; 
				$oEvenement->validation_zComment = $toValidation['toListes'][0]->evenementvalidation_commentaire; 
			}else{
				$oEvenement->validation_zLibelle = ''; 
				$oEvenement->evenementvalidation_skype = 0; 
				$oEvenement->validation_zComment = ''; 
			}
		}
		/***************VALIDATION**************/

		/***************ENV CLIENT**************/
		if ($iEvenementId > 0){
			jClasses::inc('client~clientsenvironnementSrv');
			$toParams = array ();
			$toParams[0] = new StdClass ();
			$toParams[0]->eventId = $iEvenementId;
			
			$toEnv = clientsenvironnementSrv::listCriteria($toParams);

			if (sizeof($toEnv['iResTotal']) > 0 && isset($toEnv['toListes'][0])){
				$oEvenement->eventId = $toEnv['toListes'][0]->eventId; 
				$oEvenement->bureau = $toEnv['toListes'][0]->bureau; 
				$oEvenement->navigateur = $toEnv['toListes'][0]->navigateur; 
				$oEvenement->telFixe = $toEnv['toListes'][0]->telFixe; 
				$oEvenement->telMobile = $toEnv['toListes'][0]->telMobile; 
				$oEvenement->skype = $toEnv['toListes'][0]->skype; 
				$oEvenement->casqueSkype = $toEnv['toListes'][0]->casqueSkype; 
			}else{
				$oEvenement->eventId = 0; 
				$oEvenement->bureau = 0; 
				$oEvenement->navigateur = 0; 
				$oEvenement->telFixe = ''; 
				$oEvenement->telMobile = ''; 
				$oEvenement->skype = ''; 
				$oEvenement->casqueSkype = ''; 
			}
		}

		/***************ENV CLIENT**************/
		$zUrlCodeAnomalie = "" ;
		if ($bEdit && isset($oStagiaire->client_id) && $oStagiaire->client_id > 0){
			if (isset($oStagiaire->client_iNumIndividu) && $oStagiaire->client_iNumIndividu > 0){
				$iNumero = clientSrv::getClientCodeStagiaireMiracle($oStagiaire->client_iNumIndividu) ;
				if ($iNumero > 0){
					$zUrlCodeAnomalie = sprintf(URL_CODE_ANOMALIE, $iNumero); 
				}
			}
		}

		$toParams['currentDate'] 		= date("Y-m-d H:i:s");
		$toParams['bEdit'] 				= $bEdit ;
		$toParams['x'] 					= $x ;
       	$toParams['zDate'] 				= $zDate ;
       	$toParams['zNewDate'] 			= $zNewDate ;
       	$toParams['iTime'] 				= $iTime;
       	$toParams['iEvenementId'] 		= $iEvenementId ;
       	$toParams['oEvenement'] 		= $oEvenement ;
       	$toParams['oStagiaire'] 		= $oStagiaire ;
		$toParams['toTypeEvenement'] 	= $oTypeEvenement['toListes'];
		$toParams['toStagiaire'] 		= $toTmpStagiaire['toListes'];
		$toParams['zDateDefaultEvent'] 	= $zDateDefaultEvent;
		$toParams['toPeriodicite'] 		= $toPeriodicite;
		$toParams['toDurePeriodicite'] 	= $toDurePeriodicite;
		$toParams['zDurePeriodicite'] 	= $zDurePeriodicite;
		$toParams['tEvent']				= $tEvent;
		$toParams['iAffichage']			= $iAffichage;
		$toParams['zUrlCodeAnomalie']	= $zUrlCodeAnomalie;

		$toParams['prec']				= $prec;
		$toParams['debut']				= $debut;
		$toParams['fin']				= $fin;
		$this->_tpl->assign($toParams);
	}
}
?>