<?php

/** 
 * Class de service
 *
 * @package jelix_webcalendar
 * @subpackage evenement
 * @author webi-fy <contact@webi-fy.net>
 * @magic Deraina Jesosy ...
 */
@ini_set ("memory_limit", -1) ;
class evenementCleanSrv 
{
	
	static function getListEventToClean (){
        jClasses::inc('commun~toolDate');	
		$zSql = "SELECT evenement.evenement_id as id 
		FROM evenement 
		WHERE evenement.evenement_zDateHeureDebut < '" . toolDate::getAddIntervalDateByIntervalMonth (date('Y-m-d 00:00:00'), 3) . "' 
		GROUP BY evenement.evenement_id";
		$oDBW	  = jDb::getDbWidget() ;
		return $oDBW->fetchAll($zSql) ;
	}
	
	static function clean (){
		$tEventId = self::getListEventToClean (); 
		$zEventId = "" ; 
		foreach($tEventId as $oEventId){
			if ($zEventId == ""){
				$zEventId .= $oEventId->id;
			}else{
				$zEventId .= ", " . $oEventId->id;
			}
		}

		if ($zEventId != ""){
			self::deleteEventIdByTableName($zEventId, "clientsenvironnement", "eventId");
			self::deleteEventIdByTableName($zEventId, "etatevenement", "etat_iEvenementId");
			self::deleteEventIdByTableName($zEventId, "evenementvalidation", "evenementvalidation_eventId");
			self::deleteEventIdByTableName($zEventId, "evenement");
		}
	}

	static function deleteEventIdByTableName($zEventId, $zTableName, $zFeldName = "evenement_id"){
		$zSql  = "DELETE FROM ".$zTableName." WHERE ".$zFeldName." IN (" . $zEventId . ")";
		$oCnx = jDb::getConnection();
		$oRes = $oCnx->exec($zSql);	
	}
}
?>