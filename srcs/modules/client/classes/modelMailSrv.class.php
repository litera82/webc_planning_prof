<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage societe
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
class modelMailSrv
{
	
	/**
	 * Creationn de l'objet en fonction de son Id
	 * @param int $_iId identifiant de l'objet
	 * @return object
	 */
	static function getById($_iId) 
	{
		$oFac = jDao::create('commun~modelmail') ;
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
	static function chargerTous($_zSortField="modelmail_id", $_zSortDirection="ASC") 
	{

		$oDao = jDao::get('commun~modelmail') ;
		
		$oConditions = jDao::createConditions();
		$oConditions->addItemOrder($_zSortField, $_zSortDirection);
		
		$oDao = $oDao->findBy($oConditions);
        $toModelMails = $oDao->fetchAll();
		
		return $toModelMails;
	}

	static function chargerByType($_iType, $_zSortField="modelmail_id", $_zSortDirection="ASC") 
	{
		$oFac = jDao::create('commun~modelmail') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('modelmail_type', '=', $_iType) ;
		$oCond->addItemOrder($_zSortField, $_zSortDirection);
		return $oFac->findBy($oCond)->fetchAll() ;
	}	
	static function chargerByValue($_iValue) 
	{
		$oFac = jDao::create('commun~modelmail') ;
		$oCond = jDao::createConditions() ;
		$oCond->addCondition('modelmail_value', '=', $_iValue) ;
		return $oFac->findBy($oCond)->fetchAll() ;
	}
}