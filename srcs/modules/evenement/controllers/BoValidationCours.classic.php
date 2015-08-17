<?php
/**
* @package   jelix_calendar
* @subpackage evenement
* @author    webi-fy
* @copyright 2010 webi-fy
* @link      http://www.webi-fy.net
* @license    All right reserved
*/

class BoValidationCoursCtrl extends jController{
	public $pluginParams = array('*' => array('auth.required'=>true)) ;
    /**
    *
    */
    function index() {
        $oResp = $this->getResponse('BoHtml') ;
        $oResp->tiMenusActifs = array(BoHtmlResponse::MENU_VALIDATION_COURS, BoHtmlResponse::MENU_VALIDATION_COURS_EXPORT) ;
		$res = $this->param('res', '', true);

        $oResp->body->assignZone('zContent', 'evenement~BoValidationCours', array('res'=>$res)) ;
		return $oResp ;
    }
    function export() {
        $oResp = $this->getResponse('redirect') ;
		jClasses::inc ('evenement~evenementValidationSrv') ;
		evenementValidationSrv::export();

        $oResp->action = 'evenement~BoValidationCours:index' ;
		$oResp->params = array ('res'=>1003);	
		return $oResp ;
    }
}