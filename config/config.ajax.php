<?
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
error_reporting(E_ALL);
define('SYS_HACKER_CONSOLE',false);
require_once("./config/config.php");
require_once("./include/function.php");
require_once("./classes/engine/Router.class.php");
require_once("./classes/lib/external/JsHttpRequest/JsHttpRequest.php");
$JsHttpRequest = new JsHttpRequest("UTF-8");
$oEngine=Engine::getInstance();
$oEngine->InitModules();
?>