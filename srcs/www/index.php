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

/*
 * N'oubliez pas l'appel au classe nacl_userRecord pour la mise en session de l'utilisateur connecté
*/
require (JELIX_LIB_PATH.'../../neov/neov-modules/nacl2/classes/nacl_userRecord.class.php');

$config_file = 'index/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());
?>