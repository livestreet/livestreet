{**
 * Блок сортировки
 *
 * @param array   $items
 * @param string  $label
 * @param boolean $showLabel
 *}

{$component = 'sort'}
{$uid = "sort{rand( 0, 10e10 )}"}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}">
    {if $smarty.local.showLabel|default:true}
        <div class="sort-label">{$smarty.local.label|default:$aLang.sort.label}</div>
    {/if}

    <div class="dropdown dropdown-toggle js-dropdown-default" data-dropdown-target="{$uid}" data-dropdown-selectable="true">...</div>

    <ul class="nav nav--stacked nav--dropdown dropdown-menu js-search-sort-menu" id="{$uid}">
        {foreach $smarty.local.items as $item}
            <li class="nav-item sort-item {if $item@index == 0}active{/if}"
                data-name="sort_by"
                data-value="{$item[ 'name' ]}"
                data-order="{$item[ 'order' ]|default:'desc'}">

                <a href="#">
                    {$item['text']}
                </a>
            </li>
        {/foreach}
    </ul>
</div>