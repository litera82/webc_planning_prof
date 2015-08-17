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

class clientsoldeSrv
{
	 /** Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~clientsolde') ;
		return $oFac->get($_iId) ;
	}

	static function listCriteria($_toParams, $_zSortedField = 'clientsolde_id', $_zSortedDirection = 'ASC', $_iStart = 0, $_iOffset = 0) 
	{
		$zSql  = "" ;
		$zSql .= " SELECT DISTINCT SQL_CALC_FOUND_ROWS * FROM clientsolde " ;
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
		    $oDaoFact = jDao::get('commun~clientsolde') ;
            $oRecord = null;
            $iId = isset($oInfos->clientsolde_id) ? $oInfos->clientsolde_id : 0 ;
            if($iId <= 0) // nouveau
            {
                $oRecord = jDao::createRecord('commun~clientsolde') ;
            }
            else // update
            {
                $oRecord = $oDaoFact->get($iId) ;
            }
            $oRecord->clientsolde_eventid    = isset($oInfos->clientsolde_eventid) ? $oInfos->clientsolde_eventid : $oRecord->clientsolde_eventid ;
            $oRecord->clientsolde_clientid    = isset($oInfos->clientsolde_clientid) ? $oInfos->clientsolde_clientid : $oRecord->clientsolde_clientid ;
            $oRecord->clientsolde_solde    = isset($oInfos->clientsolde_solde) ? $oInfos->clientsolde_solde : $oRecord->clientsolde_solde ;
            $oRecord->clientsolde_prevu    = isset($oInfos->clientsolde_prevu) ? $oInfos->clientsolde_prevu : $oRecord->clientsolde_prevu ;
            $oRecord->clientsolde_produit    = isset($oInfos->clientsolde_produit) ? $oInfos->clientsolde_produit : $oRecord->clientsolde_produit ;

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
		$oDaoFact 		    = jDao::get('commun~clientsolde') ;
        $oDaoFact->delete($_iId) ;
	}

	/**
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getByClientId($_iClientId) 
	{
		$oFac = jDao::create('commun~clientsolde') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('clientsolde_clientid', '=', $_iClientId) ;
		return $oFac->findBy($oCond)->fetch() ;
	}

}