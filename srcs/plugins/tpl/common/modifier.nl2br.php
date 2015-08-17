<?php
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @version    $Id$
 * @author      Tojo Michael
 */

/**
 * Type:     modifier<br>
 * Name:     ucfirst<br>
 * Purpose:  put only the first word at upper 
 * @param string
 * @return string
 */
function jtpl_modifier_common_nl2br($string)
{
      return nl2br($string);

}

?>