<?php
/**
 * Zone affichant le  left du backoffice
 * 
* @package		atsikaty
* @subpackage	commun
* @version  	1
* @author 		Tahiry RANDRIAMBOLA <t.randriambola@gmail.com>
*/

class BoCleanEventZone extends jZone 
{
	protected $_tplname = 'evenement~BoCleanEvent.zone' ;

    /**
    * Chargement des données pour affichage
    */
    protected function _prepareTpl()
    {
		$res = $this->getParam("res", "");
		$this->_tpl->assign('x', $res);
	}
}