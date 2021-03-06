<?php
/**
* @package okboss
* @subpackage commun
* @version  1
* @author NEOV
*/

/**
 * Classe jResponseHtml
 */

require_once (JELIX_LIB_RESPONSE_PATH . 'jResponseHtml.class.php') ;

/**
* Classe Reponse commune surchargeant les réponses HTML standard
* et comprenant les traitement communs à toutes les pages stagiaire
*
* @package jelix-calendar
* @subpackage commun
*/
class stagiaireResponse extends jResponseHtml
{

    public $bodyTpl = 'stagiaire~layout' ;
    public $title	= 'Forma2+ - Autoplannification des stagiaires';
    
	/**
	* Menu actif
	*/
	/*var $menuActif;*/
	var $addLink;	
	/**
	* Constructeur. On place ici les addJS/addCSS qui doivent être fait avant le traitement de l'action
	* @param array $attributes
	*/
	public function __construct ($_tAttributes = array ())
	{
        parent::__construct ($_tAttributes) ;
		global $gJConfig ;
		$this->addJSCode("var j_basepath = '" . $gJConfig->urlengine['basePath'] . "';");

		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/js/jquery-1.5.1.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/js/jquery-ui-1.8.10.custom.min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/js/jquery.loader-min.js');
		$this->addJSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/js/script.js');
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/css/layout.css');
		$this->addCSSLink ($gJConfig->urlengine['basePath'] . 'design/stagiaire/css/redmond/jquery-ui-1.8.10.custom.css');

		// Etat par défaut du code d'erreur
        $this->assignErrorCode () ;
        $this->assignErrorMsg () ;

    }

	/**
	* Traitements communs aux actions utilisant cette reponses
	* On place ici les addJS/addCSS qui peuvent être fait après le traitement de l'action
	*/
	protected function _commonProcess ()
	{
		$this->body->assignZoneIfNone('header', 'stagiaire~header', array());
		$this->body->assignIfNone('content', '<p>no content</p>', array());
		$this->body->assignZoneIfNone('footer', 'auto~footer', array());
    }

   /**
    * Assgnation d'un code d'erreur
    *
    * @param int $_iErrorCode Le code d'erreur
    */
    public function assignErrorCode ($_iErrorCode = 0)
    {
        $this->body->assign ("iErrorCode", $_iErrorCode) ;
    }

   /**
    * Assgnation d'un code d'erreur
    *
    * @param int $_iErrorCode Le code d'erreur
    */
    public function assignErrorMsg ($_zErrorMsg = "")
    {
        $this->body->assign ("zErrorMsg", $_zErrorMsg) ;
    }

}
?>