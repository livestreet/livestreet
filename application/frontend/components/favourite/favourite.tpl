{**
 * Добавление в избранное
 *
 * @param object  $target          Объект который добавляется в избранное
 * @param boolean $hideZeroCounter
 *}

{* Название компонента *}
{$component = 'ls-favourite'}
{component_define_params params=[ 'target', 'hideZeroCounter', 'mods', 'classes', 'attributes' ]}

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


<div class="{$component} {cmods name=$component mods=$mods} {if $isActive}active{/if} {$classes}"
     data-param-i-target-id="{$target->getId()}"
     title="{$aLang.favourite[ ($isActive) ? 'remove' : 'add' ]}"
     {cattr list=$attributes}>

    {* Кнопка добавления/удаления из избранного *}
    {component 'icon' icon='heart' classes="{$component}-toggle js-favourite-toggle"}

    {* Кол-во объектов в избранном *}
    {if isset( $count )}
        <span class="{$component}-count js-favourite-count" {if ! $count && $hideZeroCounter|default:true}style="display: none;"{/if}>
            {$count}
        </span>
    {/if}
</div>