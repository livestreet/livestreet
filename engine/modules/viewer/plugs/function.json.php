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
 * Позволяет транслировать данные в json
 *
 * @param unknown_type $params
 * @param unknown_type $smarty
 * @return unknown
 */
function smarty_function_json($params, &$smarty)
{

    if (!array_key_exists('var', $params)) {
        $smarty->trigger_error("json: missing 'var' parameter");
        return;
    }
    
    $var = $params['var'];
    
    $_contents = json_encode($var);

    if (!empty($params['assign'])) {
        $smarty->assign($params['assign'], $_contents);
    } else {
        return $_contents;
    }
}

?>