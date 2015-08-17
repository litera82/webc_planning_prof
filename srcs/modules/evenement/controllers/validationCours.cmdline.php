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

class validationCoursCtrl extends jControllerCmdLine {

    /**
    * Exportation donnée evenements 
	* cmdline.php?module=evenement&action=validationCours:export
	*
	*/
	function export() {
		set_time_limit(3600);
		@ini_set ("memory_limit", "-1") ;
		$oRep = $this->getResponse('cmdline');
		// Charger la liste des PROF
		jClasses::inc ('evenement~evenementValidationSrv') ;
		evenementValidationSrv::export();
		return $oRep;
	}
}
?>