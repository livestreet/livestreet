{**
 * Добавление в избранное
 *
 * @param object  $target                  Объект сущности
 * @param string  $type                    Название сущности (blog, topic и т.д.)
 * @param string  $classes
 * @param string  $attributes
 * @param string  $isActive
 * @param boolean $hideZeroCounter (true)
 *
 * TODO: Текстовая версия
 *}

{* Название компонента *}
{$component = 'favourite'}

{* Переменные *}
{$mods = $smarty.local.mods}
{$target = $smarty.local.target}

{* True если объект находится в избранном *}
{$isActive = $target && $target->getIsFavourite()}

{* Кол-во объектов в избранном *}
{$count = $target->getCountFavourite()}

{* Добавляем модификаторы *}
{if $count}
    {$mods = "$mods has-counter"}
{/if}

{if $isActive}
    {$mods = "$mods added"}
{/if}


<div class="{$component} {cmods name=$component mods=$mods} {if $isActive}active{/if} {$smarty.local.classes}"
	 data-param-i-target-id="{$target->getId()}"
	 title="{$aLang.$component[ ($isActive) ? 'remove' : 'add' ]}"
	 {cattr list=$smarty.local.attributes}>

	{* Кнопка добавления/удаления из избранного *}
	<div class="icon-heart {$component}-toggle js-{$component}-toggle"></div>

	{* Кол-во объектов в избранном *}
	{if isset( $count )}
		<span class="{$component}-count js-{$component}-count" {if ! $count && $smarty.local.hideZeroCounter|default:true}style="display: none;"{/if}>
			{$count}
		</span>
	{/if}
</div>