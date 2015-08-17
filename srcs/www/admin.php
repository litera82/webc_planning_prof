<?php
/**
* @package  reghalal
* @subpackage www
* @author
* @contributor
* @copyright
*/
@ini_set ("memory_limit", -1) ;

require ('../../../jelix/lib_1.1_a20080506/jelix/init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

require ('../application.init.php');

$config_file = 'admin/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());
?>