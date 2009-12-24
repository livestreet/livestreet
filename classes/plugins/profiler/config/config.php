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
$config['per_page']   = 15;  // Число profiler-отчетов на одну страницу

Config::Set('db.table.profiler', '___db.table.prefix___profiler');
Config::Set('router.page.profiler', 'PluginProfiler_ActionProfiler');

return $config;
?>