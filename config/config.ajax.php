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

/**
 * Подключает необходимые расширения для работы Аякса
 */
//error_reporting(E_ALL);
define('SYS_HACKER_CONSOLE',false);
require_once("loader.php");
require_once(Config::Get('path.root.engine')."/classes/Engine.class.php");
require_once(Config::Get('path.root.engine')."/lib/external/JsHttpRequest/JsHttpRequest.php");
$JsHttpRequest = new JsHttpRequest("UTF-8");
ProfilerSimple::getInstance(Config::Get('path.root.server').'/logs/'.Config::Get('sys.logs.profiler_file'),Config::Get('sys.logs.profiler'));
$oEngine=Engine::getInstance();
$oEngine->Init();
$oEngine->Security_ValidateSendForm();
$oEngine->Shutdown();
?>