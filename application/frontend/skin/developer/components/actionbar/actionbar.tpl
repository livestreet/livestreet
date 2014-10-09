{**
 * Экшнбар
 *
 * @param array $items Массив с кнопками
 *}

{* Название компонента *}
{$component = 'actionbar'}

{if $smarty.local.items}
    <ul class="{$component} clearfix {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
        {foreach $smarty.local.items as $item}
            {if $item['html']}
                {$item['html']}
            {else}
                {if $item['show']|default:true}
                    {include './actionbar-item.tpl' item=$item}
                {/if}
            {/if}
        {/foreach}
    </ul>
{/if}