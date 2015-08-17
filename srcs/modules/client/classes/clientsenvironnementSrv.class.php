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

class clientsenvironnementSrv
{
		 /** Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~clientsenvironnement') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM clientsenvironnement " ;
		$zSql .= " WHERE 1 = 1 " ;
		if (isset($_toParams[0]->eventId) && $_toParams[0]->eventId > 0){
			$zSql .= " AND eventId = " . $_toParams[0]->eventId;	
		}
		$zSql .= " ORDER BY " . $_zSortedField . " " . $_zSortedDirection ;  
		$zSql .= ($_iOffset) ? " LIMIT  " . $_iStart . ",  " . $_iOffset . " " : " " ;

		$oDBW	  = jDb::getDbWidget() ;
		$toResults['toListes'] = $oDBW->fetchAll($zSql) ;
		$oCount = $oDBW->fetchFirst("SELECT FOUND_ROWS() AS iResTotal") ;
		$toResults['iResTotal'] = $oCount->iResTotal ;

		return $toResults ;
	}
	/**
	 * Sauvegarde et modification
	 * @param array $oInfos les parametre à modifier ou à insserer
	 * @return object
	 */
	static function save($oInfos) 
	{		
		    $oDaoFact = jDao::get('commun~clientsenvironnement') ;
            $oRecord = null;
            $iId = isset($oInfos->id) ? $oInfos->id : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~clientsenvironnement') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->clientId    = isset($oInfos->clientId) ? $oInfos->clientId : $oRecord->clientId ;
            $oRecord->eventId    = isset($oInfos->eventId) ? $oInfos->eventId : $oRecord->eventId ;
            $oRecord->bureau    = isset($oInfos->bureau) ? $oInfos->bureau : $oRecord->bureau ;
			$oRecord->navigateur     = isset($oInfos->navigateur) ? $oInfos->navigateur : $oRecord->navigateur ;
			$oRecord->telFixe     = isset($oInfos->telFixe) ? $oInfos->telFixe : $oRecord->telFixe ;
			$oRecord->telMobile     = isset($oInfos->telMobile) ? $oInfos->telMobile : $oRecord->telMobile ;
			$oRecord->skype     = isset($oInfos->skype) ? $oInfos->skype : $oRecord->skype ;
			$oRecord->casqueSkype     = isset($oInfos->casqueSkype) ? $oInfos->casqueSkype : $oRecord->casqueSkype ;

			if($iId <= 0)
            {
            	$oDaoFact->insert($oRecord) ;
            } 
            if($iId > 0)
            {
                $oDaoFact->update($oRecord);
            }
            return $oRecord ;
	}
	
	/**
	 * Suppression d'un enregistrement
	 * @param int $_iId identifiant de l'objet
	 * @return boolean
	 */
	static function delete($_iId) 
	{
		$oDaoFact 		    = jDao::get('commun~clientsenvironnement') ;
        $oDaoFact->delete($_iId) ;
	}

	/**
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getByClientId($_iClientId) 
	{
		$oFac = jDao::create('commun~clientsenvironnement') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('clientId', '=', $_iClientId) ;
		return $oFac->findBy($oCond)->fetch() ;
	}
	static function getByEventId($_iEventId) 
	{
		$oFac = jDao::create('commun~clientsenvironnement') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('eventId', '=', $_iEventId) ;
		return $oFac->findBy($oCond)->fetch() ;
	}

}
?>