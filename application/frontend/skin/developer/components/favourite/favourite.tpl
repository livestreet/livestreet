{**
 * Добавление в избранное
 *
 * @param object $oObject    Объект сущности
 * @param string $sType      Название сущности (blog, topic и т.д.)
 * @param string $sClasses
 * @param string $sAttributes
 * @param string $oUserCurrent         Текущий залогиненый пользователь
 * @param boolean $bHideZeroCounter (true)
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/favourite.js
 *
 * TODO: Текстовая версия
 *}

{* Название компонента *}
{$_sComponentName = 'favourite'}

{* Переменные *}
{$sMods = $smarty.local.sMods}

{* True если объект находится в избранном *}
{$_bIsActive = $oUserCurrent && $oObject->getIsFavourite()}

{* Кол-во объектов в избранном *}
{$_iCount = $oObject->getCountFavourite()}

{**
 * Добавляем модификаторы
 *}

{if $_iCount}
    {$sMods = "$sMods has-counter"}
{/if}

{if $_bIsActive}
    {$sMods = "$sMods added"}
{/if}


<div class="{$_sComponentName} {mod name=$_sComponentName mods=$sMods} {if $_bIsActive}active{/if} {$smarty.local.sClasses}"
	 data-param-i-target-id="{$oObject->getId()}"
	 title="{$aLang.$_sComponentName[ ($_bIsActive) ? 'remove' : 'add' ]}"
	 {$smarty.local.sAttributes}>

	{* Кнопка добавления/удаления из избранного *}
	<div class="{$_sComponentName}-toggle js-{$_sComponentName}-toggle"></div>

	{* Кол-во объектов в избранном *}
	{if isset($_iCount)}
		<span class="{$_sComponentName}-count js-{$_sComponentName}-count" {if ! $_iCount && $smarty.local.bHideZeroCounter|default:true}style="display: none;"{/if}>
			{$_iCount}
		</span>
	{/if}
</div>