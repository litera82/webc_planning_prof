<?php
/**
 * Plugin from smarty project and adapted for jtpl
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @version     $Id$
 * @author      NEOV
 */

/**
 * Type:        modifier
 * Name:        dateLong<br>
 * Purpose:     Escape the string according to escapement type
 * @param       string _zInputDate de forme 2009-09-29 00:14:00
 * @return string
 */
function jtpl_modifier_common_dateCourt ($_zInputDate)
{

		 $tab1 = explode(" ",$_zInputDate) ;
		 $tab2 = explode("-",$tab1[0]);
		 $tab3 = explode(":",$tab1[1]);
		 $zOutputDate = $tab2[2]."/".$tab2[1]."/".$tab2[0];
   
    return $zOutputDate ;

}

?>