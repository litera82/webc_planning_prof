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

class clientsautoSrv 
{
	 /** Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~clientsauto') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'clientsauto_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM clientsauto " ;
		$zSql .= " WHERE 1 = 1 " ;
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
		    $oDaoFact = jDao::get('commun~clientsauto') ;
            $oRecord = null;
            $iId = isset($oInfos->clientsauto_id) ? $oInfos->clientsauto_id : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~clientsauto') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->clientsauto_clientid    = isset($oInfos->clientsauto_clientid) ? $oInfos->clientsauto_clientid : $oRecord->clientsauto_clientid ;
            $oRecord->clientsauto_dateinvit    = isset($oInfos->clientsauto_dateinvit) ? $oInfos->clientsauto_dateinvit : $oRecord->clientsauto_dateinvit ;
			$oRecord->clientsauto_auto     = isset($oInfos->clientsauto_auto) ? $oInfos->clientsauto_auto : $oRecord->clientsauto_auto ;

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
		$oDaoFact 		    = jDao::get('commun~clientsauto') ;
        $oDaoFact->delete($_iId) ;
	}

	/**
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getByClientId($_iClientId) 
	{
		$oFac = jDao::create('commun~clientsauto') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('clientsauto_clientid', '=', $_iClientId) ;
		return $oFac->findBy($oCond)->fetch() ;
	}

}