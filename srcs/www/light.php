<?php
/**
* @package  jelix-calendar
* @subpackage www
* @author
* @contributor
* @copyright
*/

require ('../../../jelix/lib_1.1_a20080506/jelix/init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

require ('../application.init.php');

$config_file = 'light/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());