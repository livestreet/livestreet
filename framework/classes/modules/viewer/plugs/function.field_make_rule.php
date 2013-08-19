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
 * Конвертирует PHP правила валидации в JS
 *
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_field_make_rule($params, &$smarty) {
	$aParamsReq=array('entity','field');
	foreach($aParamsReq as $sParam) {
		if (!array_key_exists($sParam, $params)) {
			trigger_error("json: missing '{$sParam}' parameter",E_USER_WARNING);
			return;
		}
	}
	$aResult=array();
	$sScenario=isset($params['scenario']) ? $params['scenario'] : '';
	$sField=$params['field'];
	$oEntity=$params['entity'];
	if (!($oEntity instanceof Entity)) {
		$oEntity=Engine::GetEntity($oEntity);
	}

	$aEntityRules=$oEntity->_getValidateRules();
	foreach($aEntityRules as $aEntityRule) {
		$oValidator=$oEntity->Validate_CreateValidator($aEntityRule[1],$oEntity,$aEntityRule[0],array_slice($aEntityRule,2));
		if (in_array($sField,$oValidator->fields) and (!$sScenario or isset($oValidator->on[$sScenario]))) {
			$sType=$oValidator->getTypeValidator();
			/**
			 * Конвертация строкового валидатора
			 */
			if ($sType=='string') {
				$aResult[]='rangelength="['.$oValidator->min.','.$oValidator->max.']" ';
				if (!$oValidator->allowEmpty) {
					$aResult[]='required="true" ';
				}
			}
		}
	}

	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'], $aResult);
	} else {
		return trim(join(' ',$aResult));
	}
}

?>