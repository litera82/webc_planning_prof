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

class clientCtrl extends jControllerCmdLine {

    /**
    * Exportation donnée evenements 
	* cmdline.php?module=client&action=client:exportSuivieStagiaire
	*/
	function exportSuivieStagiaire() {
		global $gJCoord;
		global $gJConfig;
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;
		$oRep = $this->getResponse('cmdline');
		jClasses::inc('client~clientSrv');
		clientSrv::exportSuivieStagiaire();
		return $oRep;
	}
}
?>