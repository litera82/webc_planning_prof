<?php
class FoEvenementExcelCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
		global $gJConfig ;
    	jClasses::inc('evenement~evenementSrv');
    	jClasses::inc('evenement~evenementExcelSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');
		jClasses::inc('utilisateurs~groupeSrv');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);
		if ($oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR){
			$oRep = $this->getResponse('redirect');
			$oRep->action = 'evenement~FoEvenement:getEventListing' ;
		}else{
			$date = date('d-m-Y');	
			list($day, $month, $year) = explode('-', $date); 
			$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
			$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
			$zDatefinC      = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;

			$toParams[0] = new stdClass();

			$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
			$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);

			$toParams[0]->groupe_id = $this->param('groupe_id', 0, true);
			$toParams[0]->professeurs = $this->param('professeurs', 0, true);

			if ($toParams[0]->zDateFin == 0){
				$toParams[0]->zDateFin = toolDate::getDateDebutPlusDeuxMois($toParams[0]->zDateDebut);
			}

			$oRep = $this->getResponse('FoHtml');
			$oRep->bodyTpl = "evenement~FoEvenementExcel" ;

			$toGroupe = groupeSrv::listCriteria(array());
			$toCriteria = array ();
			$toCriteria['utilisateur_statut'] = 1 ;
			$toUtilisateur = utilisateursSrv::listCriteria($toCriteria);
			$oRep->body->assign('toGroupe', $toGroupe['toListes']);
			$oRep->body->assign('toUtilisateur', $toUtilisateur['toListes']);
			$oRep->body->assign('toParams', $toParams);

		}
		return $oRep;
	}

	function export() {
		global $gJConfig ;
    	jClasses::inc('evenement~evenementSrv');
    	jClasses::inc('evenement~evenementExcelSrv');
		jClasses::inc ('utilisateurs~utilisateursSrv') ;
		jClasses::inc('typeEvenement~typeEvenementsSrv');
        jClasses::inc('commun~toolDate');
		jClasses::inc('utilisateurs~groupeSrv');

		// identifie l'utilisateur connecté
		$oUser = jAuth::getUserSession();
		$iUtilisateurId = utilisateursSrv::getUtilisateurConnecter($oUser->login, $oUser->password);
		$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($iUtilisateurId);
		if ($oUtilisateur->utilisateur_iTypeId != TYPE_UTILISATEUR_ADLINISTRATEUR){
			$oRep = $this->getResponse('redirect');
			$oRep->action = 'evenement~FoEvenement:getEventListing' ;
		}else{
			$oRep = $this->getResponse('binary');
			$oRep->action = 'evenement~FoEvenementExcel:index' ;

			$date = date('d-m-Y');	
			list($day, $month, $year) = explode('-', $date); 
			$num_day      = date('w', mktime(0,0,0,$month,$day,$year));
			$zDatedebC      = toolDate::toDateFr(toolDate::toDateSQL($date)); // Date du jour
			$zDatefinC      = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), '7 DAY')) ;

			$toParams[0] = new stdClass();

			$toParams[0]->zDateDebut = $this->param('dtcm_event_rdv', $zDatedebC, true);
			$toParams[0]->zDateFin = $this->param('dtcm_event_rdv1', $zDatefinC, true);

			$toParams[0]->groupe_id = $this->param('groupe_id', 0, true);
			$toParams[0]->professeurs = $this->param('professeurs', 0, true);

			$toGroupe = groupeSrv::listCriteria(array());
			$toCriteria = array ();
			$toCriteria['utilisateur_statut'] = 1 ;
			$toUtilisateur = utilisateursSrv::listCriteria($toCriteria);

			$toEvenement = evenementExcelSrv::export($toParams);

			$zExportsFileName = "eventPlannified_". date ("Ymd_His") . ".xls" ;
			$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/eventPlannified/" . $zExportsFileName ;
			
			foreach ($toEvenement['toListes'] as $oEvenement){
				$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
				$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
				$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
				$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
				$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
			}

			$oUtilisateur = new StdClass ();
			if ($toParams[0]->professeurs > 0){
				$oUtilisateur = utilisateursSrv::chargeUnUtilisateur($toParams[0]->professeurs);
			}
			evenementExcelSrv::exportEventPlan($zExportsFullPath, $toEvenement, $toParams, $oUtilisateur) ;

			if (is_file ($zExportsFullPath) ) {
				$oRep->fileName = $zExportsFullPath ;
				$oRep->outputFileName = $zExportsFileName ;
				$oRep->doDownload = true ;
			}else{
				die('Erreur lors de la création du fichier xls');
			}
		}
		return $oRep;
	}


}
?>