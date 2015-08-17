<?php
/**
* @package   jelix_calendar
* @subpackage administrateurs
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class defaultCtrl extends jControllerCmdLine {
    /**
    * Exportation donnée evenements vers excel et envoi par email 
	* Ligne de commande
	* index.php?module=evenement&action=default:clean
	*/


	function clean (){
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;
		$oResp = $this->getResponse('cmdline') ;
		jClasses::inc ('evenement~evenementCleanSrv') ;
		evenementCleanSrv::clean();
		return $oResp ;
	}
}

