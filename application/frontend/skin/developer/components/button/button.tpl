{**
 * Кнопка
 *
 * @param sType       string  ('submit') Тип кнопки (submit, button)
 * @param sText       string  (null)     Текст кнопки
 * @param sUrl        string  (null)     Ссылка
 * @param sId         string  (null)     Атрибут id
 * @param sName       string  (null)     Атрибут name
 * @param bIsDisabled boolean (false)    Атрибут disabled
 * @param sForm       string  (null)     Селектор формы для сабмита
 * @param sIcon       string  (null)     Класс иконки
 * @param sClasses    string  (null)     Дополнительные классы (указываются через пробел)
 * @param sMods       string  (null)     Список классов-модификаторов (указываются через пробел)
 * @param sAttributes string  (null)     Атрибуты (указываются через пробел)
 *
 * @styles <framework>/css/button.css
 *}

{* Название компонента *}
{$_sComponentName = 'button'}

{* Если указана ссылка sUrl то заменяем тег <button> на <a> *}
<{($smarty.local.sUrl) ? 'a' : 'button'}
		{if ! $smarty.local.sUrl}
			type="{($smarty.local.sType) ? $smarty.local.sType : 'submit'}"
	    	value="{if $smarty.local.sValue}{$smarty.local.sValue}{elseif isset($_aRequest[$smarty.local.sName])}{$_aRequest[$smarty.local.sName]}{/if}"
	    	{if $smarty.local.bIsDisabled}disabled{/if}
	    	{if $smarty.local.sForm}data-button-submit-form="{$smarty.local.sForm}"{/if}
	    {else}
	    	href="{$smarty.local.sUrl}"
		{/if}
	    {if $smarty.local.sId}id="{$smarty.local.sId}"{/if}
	    {if $smarty.local.sName}name="{$smarty.local.sName}"{/if}
	    class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses}"
	    {$smarty.local.sAttributes}>
	{* Иконка *}
	{if $smarty.local.sIcon}
		<i class="{$smarty.local.sIcon}"></i>
	{/if}

	{* Текст *}
	{$smarty.local.sText}
</{($smarty.local.sUrl) ? 'a' : 'button'}>