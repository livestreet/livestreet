<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

header('Content-Type: text/html; charset=utf-8');

require_once('bootstrap.php');

/**
 * Определяем группы с шагами
 */
$aGroups = array(
    'install' => array(
        'checkRequirements',
        'installDb',
        'installAdmin',
        'installComplete'
    ),
    'update'  => array(
        'checkRequirements',
        'updateDb' => array('hide_create_db' => true),
        'updateVersion',
        'updateComplete'
    ),

);

$oInstall = new InstallCore($aGroups);
$oInstall->run();