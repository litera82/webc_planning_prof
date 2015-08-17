 <?php
/**
* @package prospecteo
* @subpackage html_plugins
* @author NEOV
*/

/**
 * function plugin :  FCKeditor
 *
 * example : {fckeditor 'zDesc', 'Basic', '100%', 200, 'contenu'}
 * @param string $tpl nom du template
 * @param string $instanceName nom de l'instance de l'editeur
 * @param string $toolbar nom de la barre d'outils � utiliser
 * @param int $Width longueur de l'editeur
 * @param int $Height largeur de l'editeur
 * @param string $contenu contenu de l'editeur
 */

  require_once (JELIX_APP_WWW_PATH.'FCKeditor/fckeditor.php');
 
  function jtpl_function_html_fckeditor($tpl, $instanceName, $toolbar, $Width, $Height, $contenu=NULL) {
	GLOBAL $gJConfig;
	$oFCKeditor = new FCKeditor($instanceName);
	$oFCKeditor->BasePath = $gJConfig->urlengine['basePath'].'FCKeditor/';
	$oFCKeditor->ToolbarSet	= $toolbar;
	$oFCKeditor->Config = array('EditorAreaCSS'=> $gJConfig->urlengine['basePath'].'design/back/css/fckeditor_adm.css');
    $oFCKeditor->Value = $contenu;
	echo $oFCKeditor->CreateHtml($instanceName, $Width, $Height);
  }
  ?>