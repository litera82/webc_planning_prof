<?php
/**
* @package concours
* @subpackage projet
* @version  1
* @author NEOV
*/

/**
* Controleur pour les taches CRON
* @package concours
* @subpackage projet
*/

class sendExportEventByEmailCtrl extends jControllerCmdLine {
	//public $pluginParams = array('*' => array('auth.required'=>true)) ;

    /**
    * Exportation donnée evenements vers excel et envoi par email 
	* Ligne de commande
	* index.php?module=evenement&action=sendExportEventByEmail:sendExportEventBymailQuotidien
	* index.php?module=evenement&action=sendExportEventByEmail:sendExportEventByEmailHebdomadaire
	* index.php?module=evenement&action=sendExportEventByEmail:sendExportEventByEmailTwoWeek
	* index.php?module=evenement&action=sendExportEventByEmail:sendExportEventByEmailMounth
	*/
	

	/**
    * Exportation donnée evenements 
	*/	
	function sendExportEventByEmail ($toParams){
		jClasses::inc('evenement~evenementSrv');
		jClasses::inc('utilisateurs~utilisateursSrv') ;
        jClasses::inc('commun~toolDate');
		jClasses::inc('commun~mailSrv');

	    $toProf = utilisateursSrv::listCriteria($toParams) ;
		// Date de debut
		$toParams[0] = new stdClass ();

		$date = date('d-m-Y');	
		switch ($toParams['utilisateur_frequenceSendExcel']){
			case 1: // quotidien
				$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
				$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "3 DAY"));	
			break;
			case 2: // Hebdomadaire
				$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
				$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "1 WEEK"));	
			break;
			case 3: // Tous les 2 semaine
				$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
				$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "2 WEEK"));	
			break;
			case 4: // Tous les mois
				$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
				$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "4 WEEK"));	
			break;
			default: // quotidien 1 
				$toParams[0]->zDateDebut = toolDate::toDateFr(toolDate::toDateSQL($date)); 
				$toParams[0]->zDateFin = toolDate::toDateFr(toolDate::dateAdd(toolDate::toDateSQL($date), "3 DAY"));	
		}
		$toParams[0]->iTypeEvenement = 0 ;
		$toParams[0]->iCheckboxeAutoplanification = 0 ;

		// Date de fin 
		foreach ($toProf["toListes"] as $oProf){
			$toParams[0]->iUtilisateur = $oProf->utilisateur_id; 
			$toEvenement = self::listCriteria($toParams, 'evenement_zDateHeureDebut');
			foreach ($toEvenement['toListes'] as $oEvenement){
				$tzDateHeureDebut = explode (' ' ,$oEvenement->evenement_zDateHeureDebut);
				$oEvenement->evenement_zDateDebut = $tzDateHeureDebut[0]; 
				$tHeureDebut = explode (':', $tzDateHeureDebut[1]); 
				$oEvenement->evenement_zHeureDebut = $tHeureDebut[0].':'.$tHeureDebut[1];
				$oEvenement->evenement_zDateJoursDeLaSemaine = ucfirst(toolDate::jourEnTouteLettre($oEvenement->evenement_zDateHeureDebut, "DB"));
			}
			$zExportsFullPath = JELIX_APP_WWW_PATH . "userFiles/xls/eventToSendByMail/" . "exportEvenement_".$oProf->utilisateur_zPrenom . $oProf->utilisateur_zNom . "_". date ("Ymd_His") . ".xls" ;
			
			if (isset ($oProf->utilisateur_zMail) && $oProf->utilisateur_zMail != "" && !is_null($oProf->utilisateur_zMail) && isset($toEvenement['toListes']) && sizeof($toEvenement['toListes']) > 0){
				evenementSrv::exportEventListing($zExportsFullPath, $toEvenement, $toParams, array(), $oProf);
				if (is_file ($zExportsFullPath) && file_exists($zExportsFullPath)) {
					if (is_file ($zExportsFullPath) ) {
						@chmod ($zExportsFullPath, 0777) ;			
					}

					$tplMail = new jTpl();
					
					$tplMail->assign ('zUrlToSite', URL_TO_SITE) ;
					$tplMail->assign ('oProf', $oProf) ;
					$tplMail->assign ('zExportsFullPath', basename($zExportsFullPath)) ;

					$tpl = $tplMail->fetch ('evenement~corpsMailSendExportEventByEmail') ;

					$tzPathAttachements = array() ;
					array_push ($tzPathAttachements, $zExportsFullPath) ;
					mailSrv::envoiEmail (SENDER_MAIL, NAME_SENDER, $oProf->utilisateur_zMail, $oProf->utilisateur_zPrenom .' '.$oProf->utilisateur_zNom , MAIL_OBJECT_SEND_EXPORT_EVENT_BY_EMAIL, $tpl,  NULL, NULL, true, NULL, $tzPathAttachements, NULL, NULL) ;					
				}else{
					die('Erreur lors de la création du fichier xls');
				}
			}
		}
		return true ;
	}

    /**
    * Exportation donnée evenements Quotidien 
	*/
	function sendExportEventBymailQuotidien() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('cmdline');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 1 ;
        self::sendExportEventByEmail($toParams) ;
		return $oRep;
	}

    /**
    * Exportation donnée evenements 1 semaine 
	*/
	function sendExportEventByEmailHebdomadaire() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('cmdline');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 2 ;
        self::sendExportEventByEmail($toParams) ;
		return $oRep;
	}
    /**
    * Exportation donnée evenements 2 semaines
	*/
	function sendExportEventByEmailTwoWeek() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('cmdline');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 3 ;
        self::sendExportEventByEmail($toParams) ;
		return $oRep;
	}
    /**
    * Exportation donnée evenements 4 semaines
	*/
	function sendExportEventByEmailMounth() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;

		$oRep = $this->getResponse('cmdline');

		$toParams['utilisateur_statut'] = 1 ;
		$toParams['utilisateur_bSendExcel'] = 1 ;
		$toParams['utilisateur_frequenceSendExcel'] = 4 ;
        self::sendExportEventByEmail($toParams) ;
		return $oRep;
	}

	static function listCriteria($_toParams, $_zSortedField = 'evenement_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		jClasses::inc('commun~toolDate');
		jClasses::inc('utilisateurs~utilisateursSrv');

		if (!isset($_toParams[0]->iCheckDate)){
			$_toParams[0]->iCheckDate = 0;
		}

		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenement " ;
		$zSql .= " INNER JOIN utilisateurs ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id ";
		$zSql .= " INNER JOIN typeevenements ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id ";
		$zSql .= " LEFT JOIN clients ON evenement.evenement_iStagiaire = clients.client_id LEFT JOIN societe ON clients.client_iSociete = societe.societe_id ";
		$zSql .= " LEFT JOIN etatevenement ON evenement.evenement_id = etatevenement.etat_iEvenementId LEFT JOIN typeetat ON etatevenement.etat_iTypeEtatId = typeetat.typeetat_id "; 
		$zSql .= " WHERE 1 ";

		if (isset($_toParams[0]->libelle) && $_toParams[0]->libelle != ""){
            $zSql .= " AND evenement_zLibelle LIKE '%".$_toParams[0]->libelle."%'";	
		}
		if (isset($_toParams[0]->statut) && $_toParams[0]->statut != 3){
            $zSql .= " AND evenement_iStatut = " . $_toParams[0]->statut;	
		}

		if ($_toParams[0]->iCheckDate == 0 && isset($_toParams[0]->zDateDebut) && isset($_toParams[0]->zDateFin) && $_toParams[0]->zDateDebut != "" && $_toParams[0]->zDateFin != ""){
            $zSql .= " AND evenement_zDateHeureDebut BETWEEN DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateDebut)."','%Y/%m/%d 00:00:00') AND DATE_FORMAT('".toolDate::toDateSQL($_toParams[0]->zDateFin)."','%Y/%m/%d 23:59:59')";	
		}
		if (isset($_toParams[0]->iTypeEvenement) && $_toParams[0]->iTypeEvenement != 0){
			if ($_toParams[0]->iTypeEvenement == ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE || $_toParams[0]->iTypeEvenement == ID_TYPE_EVENEMENT_DISPONIBLE){
				$zSql .= " AND (evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . " OR evenement_iTypeEvenementId = " . ID_TYPE_EVENEMENT_DISPONIBLE . ") ";	
			}else{
				$zSql .= " AND evenement_iTypeEvenementId = " . $_toParams[0]->iTypeEvenement;	
			}
		}
		if (isset($_toParams[0]->evenement_origine) && $_toParams[0]->evenement_origine != 0){
            $zSql .= " AND evenement_origine = " . $_toParams[0]->evenement_origine;	
		}
		if (isset($_toParams[0]->evenement_zSociete) && $_toParams[0]->evenement_zSociete != ""){
			$tzSoc = explode(" ", trim($_toParams[0]->evenement_zSociete));
			$zCritSoc = "";	
			if (sizeof($tzSoc) > 0){
				for($i=0; $i<sizeof($tzSoc); $i++){
					if ($zCritSoc!=""){
						$zCritSoc .= " OR ";
					}
					$zCritSoc .= " societe_zNom LIKE '%" . $tzSoc[$i] . "%' " ;
				}
			}
			if ($zCritSoc != ''){
	            $zSql .= " AND (" . $zCritSoc . ") ";	
			}
		}
		if (isset($_toParams[0]->iStagiaire) && $_toParams[0]->iStagiaire != 0){
            $zSql .= " AND client_id = " . $_toParams[0]->iStagiaire;	
		}
		if (isset($_toParams[0]->iUtilisateur) && $_toParams[0]->iUtilisateur != 0 && isset($_toParams[0]->iCheckboxeAutoplanification) && $_toParams[0]->iCheckboxeAutoplanification == 0){
			$zSql .= " AND evenement_iUtilisateurId = " . $_toParams[0]->iUtilisateur;	
		}
		if (isset($_toParams[0]->iEventListing) && $_toParams[0]->iEventListing == 1){
            $zSql .= " AND evenement_iTypeEvenementId NOT IN ( " . ID_TYPE_EVENEMENT_DISPONIBLE . ", " . ID_TYPE_EVENEMENT_COUR_DISPONIBLE_PLANNIFIE . ") ";	
		}

		$zSql .= " GROUP BY evenement_id ";
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;
//echo $zSql; 
		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
}
?>