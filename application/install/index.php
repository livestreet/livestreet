<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

require_once('bootstrap.php');

/**
 * Определяем группы с шагами
 */
$aGroups=array(
	'install'=>array(
		'install1','install2','install3','installcomplete'
	),

	'update'=>array(
		'update1','update2'
	),

);

$oInstall=new InstallCore($aGroups);
$oInstall->run();