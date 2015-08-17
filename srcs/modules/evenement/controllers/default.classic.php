<?php
/**
* @package   jelix_calendar
* @subpackage administrateurs
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        return $rep;
    }


	function clean (){
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('evenement~evenementCleanSrv') ;
		evenementCleanSrv::clean();
        $oResp->action = 'evenement~evenement:clean' ;
		$oResp->params = array ('res'=>1003);	
		return $oResp ;
	}
}

