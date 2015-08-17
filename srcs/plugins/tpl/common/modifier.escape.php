<?php
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @version    $Id$
 * @author      Njaka MISAHARISON
 */

/**
 * Type:     modifier<br>
 * Name:     escape<br>
 * Purpose:  Escape the string according to escapement type
 * @param string
 * @param html|htmlall|url|quotes|hex|hexentity|javascript
 * @return string
 */
function jtpl_modifier_common_escape($string, $esc_type = 'html')
{
    switch ($esc_type) {
        case 'html':
            return htmlspecialchars($string, ENT_QUOTES);
        case 'htmlall':
            return htmlentities($string, ENT_QUOTES);
		case 'htmldecode':
			return htmlspecialchars($string, ENT_QUOTES);
        case 'url':
            return urlencode($string);

        case 'quotes':
            // escape unescaped single quotes
            return preg_replace("%(?<!\\\\)'%", "\\'", $string);

        case 'hex':
            // escape every character into hex
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '%' . bin2hex($string[$x]);
            }
            return $return;
            
        case 'hexentity':
            $return = '';
            for ($x=0; $x < strlen($string); $x++) {
                $return .= '&#x' . bin2hex($string[$x]) . ';';
            }
            return $return;

        case 'javascript':
            // escape quotes and backslashes and newlines
            return strtr($string, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n'));

		case 'addslashes':
		    return addslashes($string);	
		case 'stripslashes':
		    return stripslashes($string);	
		case 'nl2br':
		    return nl2br($string);	
        case 'UTF8dec' :
            return utf8_decode ($string) ;
        case 'UTF8en' :
            return utf8_encode ($string) ;
		 case 'toUpper' :
            return strtoupper($string);
		 case 'strval' :
            return strval($string);
		 case 'intval' :
            return intval($string);
		case 'ucfirst':
		    return ucfirst($string);	
		case 'fichierExiste' :
			if(is_file($string) && file_exists($string)){
				return 1;
			}else {
				return 0;
			}
		default:
			return $string;
    }
}

?>