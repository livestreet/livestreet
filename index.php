<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');

// Получаем объект конфигурации
$sPathToFramework=dirname(__FILE__).'/framework/';
require_once("{$sPathToFramework}/config/loader.php");
require_once(Config::Get('path.framework.server')."/classes/engine/Engine.class.php");

$oRouter=Router::getInstance();
$oRouter->Exec();