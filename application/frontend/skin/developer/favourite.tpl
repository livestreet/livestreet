{**
 * Добавление в избранное
 *
 * @param object $oFavouriteObject    Объект сущности
 * @param string $sFavouriteType      Название сущности (blog, topic и т.д.)
 * @param string $oUserCurrent        Текущий залогиненый пользователь
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/favourite.js
 *}

{$bFavouriteIsActive = $oFavouriteObject->getIsFavourite()}
{$iFavouriteCount = $oFavouriteObject->getCountFavourite()}

<div class="favourite js-favourite" data-favourite-type="{$sFavouriteType}" data-favourite-id="{$oFavouriteObject->getId()}">
	<div class="favourite-toggle {if $oUserCurrent && $bFavouriteIsActive}active{/if} js-favourite-toggle"
		 title="{if $bFavouriteIsActive}{$aLang.favourite.remove}{else}{$aLang.favourite.add}{/if}"></div>

	{if isset($iFavouriteCount)}
		<span class="favourite-count js-favourite-count" {if $iFavouriteCount == 0}style="display: none;"{/if}>{$iFavouriteCount}</span>
	{/if}
</div>