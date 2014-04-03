{**
 * Добавление в избранное
 *
 * @param object $oFavouriteObject    Объект сущности
 * @param string $sFavouriteType      Название сущности (blog, topic и т.д.)
 * @param string $sFavouriteClasses
 * @param string $sFavouriteAttributes
 * @param string $oUserCurrent         Текущий залогиненый пользователь
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/favourite.js
 *}

{* Название компонента *}
{$_sComponentName = 'favourite'}

{* True если объект находится в избранном *}
{$bIsActive = $oUserCurrent && $oFavouriteObject->getIsFavourite()}

{* Кол-во объектов в избранном *}
{$iCount = $oFavouriteObject->getCountFavourite()}

<div class="{$_sComponentName} js-{$_sComponentName} {if $bIsActive}active{/if} {$sFavouriteClasses}"
	 data-favourite-type="{$sFavouriteType}"
	 data-favourite-id="{$oFavouriteObject->getId()}"
	 {$sFavouriteAttributes}>

	{* Кнопка добавления/удаления из избранного *}
	<div class="{$_sComponentName}-toggle  js-{$_sComponentName}-toggle"
		 title="{($bIsActive) ? $aLang.$_sComponentName.remove : $aLang.$_sComponentName.add}"></div>

	{* Кол-во объектов в избранном *}
	{if isset($iCount)}
		<span class="{$_sComponentName}-count js-{$_sComponentName}-count" {if ! $iCount}style="display: none;"{/if}>{$iCount}</span>
	{/if}
</div>