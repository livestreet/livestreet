<?php
/**
 * Smarty plugin - declension modifier
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Модификатор declension: склонение существительных по правилам английского языка
 *
 * @param array $forms (напр: 0 => article, 1 => articles)
 * @param int $count
 * @return string
 */
function smarty_modifier_declension_en($forms, $count)
{
	if ($count==1)
		return $forms[0];
	else
		return $forms[1];
}

/**
 * Модификатор declension: склонение существительных по правилам русского языка
 *
 * @param array $forms (напр: 0 => пень, 1 => пня, 2 => пней)
 * @param int $count
 * @return string
 */
function smarty_modifier_declension_ru($forms, $count)
{
	$mod100 = $count % 100;
	switch ($count%10) {
		case 1:
			if ($mod100 == 11)
				return $forms[2];
			else
				return $forms[0];
		case 2:
		case 3:
		case 4:
			if (($mod100 > 10) && ($mod100 < 20))
				return $forms[2];
			else
				return $forms[1];
		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
		case 0:
			return $forms[2];

	}
}

/**
 * Модификатор declension: склонение существительных
 *
 * @param int $count
 * @param string $forms
 * @param string $language
 * @return string
 */
function smarty_modifier_declension($count, $forms, $language='')
{
	if (!$language)
		$language = Engine::getInstance()->Lang_GetLang();

	$count = abs($count);

	// Выделяем отдельные словоформы
	$forms = explode(';', $forms);

	$fn = 'smarty_modifier_declension_'.$language;
	if (function_exists($fn))
	{
		// Есть персональная функция для текущего языка
		return $fn($forms, $count);
	} else {
		// Действуем по образу и подобию английского языка
		return smarty_modifier_declension_en($forms, $count);
	}
}
?>