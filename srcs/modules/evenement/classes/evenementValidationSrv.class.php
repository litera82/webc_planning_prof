<?php
@ini_set ("memory_limit", -1) ;

class evenementValidationSrv 
{
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~evenementvalidation') ;
		$oEvent = $oFac->get($_iId) ;
		return $oEvent ;
	}
	/**
	*
	*
	*/
	static function getByEventId($_iEventId) 
	{
		$oFac = jDao::create('commun~evenementvalidation') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('evenementvalidation_eventId', '=', $_iEventId) ;
		return $oFac->findBy($oCond)->fetch() ;
	}

	static function getByEventId1($_iEventId) 
	{
		$zSql = " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenementvalidation " ;
		$zSql .= " INNER JOIN validation ON validation_id = evenementvalidation_validationId left join clientsenvironnement ON evenementvalidation.evenementvalidation_eventId = clientsenvironnement.eventId " ;
		$zSql .= " WHERE evenementvalidation_eventId = " . $_iEventId;

		$oDBW = jDb::getDbWidget() ;
		return $oDBW->fetchFirst($zSql) ;
	}

	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact = jDao::get('commun~evenementvalidation') ;
        $oDaoFact->delete($_iId) ;
	}
	/**
	*
	*
	*/
	static function save ($oValidation){
		$oDaoFact = jDao::get('commun~evenementvalidation') ;
        $oRecord = jDao::createRecord('commun~evenementvalidation') ;

		$oRecord->evenementvalidation_eventId = $oValidation->evenementvalidation_eventId;
		$oRecord->evenementvalidation_validationId = $oValidation->evenementvalidation_validationId ;
		$oRecord->evenementvalidation_skype = $oValidation->evenementvalidation_skype ;
		$oRecord->evenementvalidation_date = $oValidation->evenementvalidation_date ;
		$oRecord->evenementvalidation_commentaire = $oValidation->evenementvalidation_commentaire ;

		$oDaoFact->insert($oRecord);
		return $oRecord->evenementvalidation_id ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'evenementvalidation_eventId', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM evenementvalidation " ;
		$zSql .= " INNER JOIN validation ON validation_id = evenementvalidation_validationId " ;
		$zSql .= " WHERE 1 " ;

		if (isset($_toParams[0]->evenementvalidation_eventId) && $_toParams[0]->evenementvalidation_eventId > 0){
			$zSql .= " AND evenementvalidation_eventId = " . $_toParams[0]->evenementvalidation_eventId;	
		}
		if (isset($_toParams[0]->evenementvalidation_skype) && $_toParams[0]->evenementvalidation_skype > 0){
			$zSql .= " AND evenementvalidation_skype = " . $_toParams[0]->evenementvalidation_skype;	
		}
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;
		
		return $toResults ;
	}

	static function export() 
	{
		jClasses::inc('commun~toolDate');
		jClasses::inc('commun~tools');

		$zSql  = "SELECT *
					FROM evenementvalidation
					  LEFT JOIN evenement
						ON evenementvalidation.evenementvalidation_eventId = evenement.evenement_id
					  LEFT JOIN validation
						ON evenementvalidation.evenementvalidation_validationId = validation.validation_id
					  INNER JOIN typeevenements
						ON evenement.evenement_iTypeEvenementId = typeevenements.typeevenements_id
					  INNER JOIN duree
						ON duree.duree_id = evenement.evenement_iDureeTypeId
					  LEFT JOIN clients
						ON clients.client_id = evenement.evenement_iStagiaire
					  LEFT JOIN societe
						ON clients.client_iSociete = societe.societe_id
					  LEFT JOIN utilisateurs
						ON evenement.evenement_iUtilisateurId = utilisateurs.utilisateur_id
					  LEFT JOIN clientsenvironnement
						ON evenement.evenement_id = clientsenvironnement.eventId
					  INNER JOIN composant_cours
						ON (clients.client_iNumIndividu = composant_cours.NUMERO OR clients.client_iNumIndividu = composant_cours.CODE_STAGIAIRE_MIRACLE)
					WHERE evenementvalidation.evenementvalidation_date BETWEEN '".toolDate::getIntervalDateByIntervalDay(date('Y-m-d'), 60).' 00:00:00'."'
						AND '".date('Y-m-d').' 23:59:59'."' 
					GROUP BY evenement.evenement_id
					ORDER BY evenementvalidation.evenementvalidation_date DESC" ;
//						AND evenementvalidation.evenementvalidation_validationId = 1 

		$oDBW	  = jDb::getDbWidget() ;
		$toResults = $oDBW->fetchAll($zSql) ;
		if (sizeof ($toResults) > 0){
			// vider la table validationcours
			$zSql =  "TRUNCATE TABLE validationcours";
			$oCnx = jDb::getConnection('validation');
			$oCnx->exec($zSql);
			foreach($toResults as $oResult){
				if (isset($oResult->client_zNom) && !is_null($oResult->client_zNom) && isset($oResult->client_zPrenom) && !is_null($oResult->client_zPrenom)){
					$oDaoFact = jDao::get('commun~validationcours', 'validation') ;
					$oValidation = jDao::createRecord('commun~validationcours', 'validation') ;
					$oValidation->validationcours_id						= NULL ;

					$tEvenementValidationDate = explode (" ", $oResult->evenementvalidation_date);
					$oValidation->validationcours_date						= $tEvenementValidationDate[0] ;
					$oValidation->validationcours_heure						= $tEvenementValidationDate[1] ;
					$oValidation->validationcours_commentaire				= $oResult->evenementvalidation_commentaire ;
					$oValidation->validationcours_dateExport				= date('Y-m-d'); 
					//$oValidation->validationcours_heureExport				= date('H:i:s'); 
					$oValidation->validationcours_eventLibelle				= $oResult->typeevenements_zLibelle ;
					$oValidation->validationcours_eventDescription			= $oResult->evenement_zDescription ;

					$tEvenementDateHeureDebut = explode (" ", $oResult->evenement_zDateHeureDebut) ; 
					$oValidation->validationcours_eventDateDebut			= $tEvenementDateHeureDebut[0] ;
					$oValidation->validationcours_eventHeureDebut			= $tEvenementDateHeureDebut[1] ;

					$tEvenementDateHeureSaisie = explode (" ", $oResult->evenement_zDateHeureSaisie) ; 
					$oValidation->validationcours_eventDateSaisie			= $tEvenementDateHeureSaisie[0] ;
					$oValidation->validationcours_eventHeureSaisie			= $tEvenementDateHeureSaisie[1] ;
					$oValidation->validationcours_eventDure					= $oResult->evenement_iDuree;
					$oValidation->validationcours_eventSolde				= $oResult->evenement_solde;
					$oValidation->validationcours_eventPrevu				= $oResult->evenement_prevu;
					$oValidation->validationcours_eventProduit				= $oResult->evenement_produit;
					$oValidation->validationcours_stagiaireCivilite			= $oResult->client_iCivilite ;
					$oValidation->validationcours_stagiaireNom				= $oResult->client_zNom ;
					$oValidation->validationcours_stagiairePrenom			= $oResult->client_zPrenom ;
					$oValidation->validationcours_stagiaireFonction			= $oResult->client_zFonction ;
					$oValidation->validationcours_stagiaireMail				= $oResult->client_zMail ;
					$oValidation->validationcours_stagiaireTel				= $oResult->client_zTel . " / " . $oResult->client_zPortable ;
					$oValidation->validationcours_stagiaireAdresse			= $oResult->client_zRue . " / " . $oResult->client_zVille . " / " . $oResult->client_zCP;
					$oValidation->validationcours_stagiaireNumeroIndividu	= $oResult->client_iNumIndividu ;
					$oValidation->validationcours_stagiaireSociete			= $oResult->societe_zNom ;
					$oValidation->validationcours_profCivilite				= $oResult->utilisateur_iCivilite ;
					$oValidation->validationcours_profNom					= $oResult->utilisateur_zNom ;
					$oValidation->validationcours_profPrenom				= $oResult->utilisateur_zPrenom ;
					$oValidation->validationcours_profTel					= $oResult->utilisateur_zMail ;
					$oValidation->validationcours_profMail					= $oResult->utilisateur_zTel ;
			
					if (isset($oResult->bureau)){
						if ($oResult->bureau == 1){
							$oValidation->validationcours_bureau = "Bureau isolé - Oui";
						}else{
							$oValidation->validationcours_bureau = "Bureau isolé - Non";
						}
					}else{
						$oValidation->validationcours_bureau = NULL ;
					}
					if (isset($oResult->navigateur)){
						$oValidation->validationcours_navigateur = $oResult->navigateur;
					}else{
						$oValidation->validationcours_navigateur = NULL ;
					}
					if (isset($oResult->telFixe)){
						$oValidation->validationcours_telFixe = $oResult->telFixe;
					}else{
						$oValidation->validationcours_telFixe = NULL ;
					}
					if (isset($oResult->telMobile)){
						$oValidation->validationcours_telMobile = $oResult->telMobile;
					}else{
						$oValidation->validationcours_telMobile = NULL ;
					}
					if (isset($oResult->skype)){
						$oValidation->validationcours_skype = $oResult->skype;
					}else{
						$oValidation->validationcours_skype = NULL ;
					}
					if (isset($oResult->casqueSkype)){
						if ($oResult->casqueSkype == 1){
							$oValidation->validationcours_casqueSkype = "Casque skype - Oui";
						}else{
							$oValidation->validationcours_casqueSkype = "Casque skype - Non";
						}
					}else{
						$oValidation->validationcours_casqueSkype = NULL ;
					}

					if (isset($oResult->HEURES_PREVUES) && isset($oResult->HEURES_PRODUITES)){
						$oValidation->validationcours_coursPrevus = $oResult->HEURES_PREVUES ;
						$oValidation->validationcours_coursProduit = $oResult->HEURES_PRODUITES ;
						$oValidation->validationcours_soldeAvantSaisie = $oResult->HEURES_PREVUES - $oResult->HEURES_PRODUITES ;
					}else{
						$oValidation->validationcours_coursPrevus = NULL ;
						$oValidation->validationcours_coursProduit = NULL ;
						$oValidation->validationcours_soldeAvantSaisie = NULL ;
					}

					$oValidation->validationcours_compteurEncours			= $oResult->COMPTEUR ;
					$oValidation->validationcours_presence					= $oResult->validation_zLibelle ;

					$oDaoFact->insert($oValidation) ;										
				} // FIN IF 
			} // FIN FOREACH 
		}
		return true ;
	}
}
?>