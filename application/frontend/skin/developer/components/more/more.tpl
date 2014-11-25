{**
 * Подгрузка контента
 *
 * @param string  $text
 * @param string  $target
 * @param integer $count
 * @param boolean $append
 *}

{* Название компонента *}
{$component = 'more'}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}"
     data-more-append="{$smarty.local.append|default:true}"
     {if $smarty.local.target}data-more-target="{$smarty.local.target}"{/if}
     {cattr list=$smarty.local.attributes}>

    {* Текст *}
    {$smarty.local.text|default:{lang 'more.text'}}

    {* Счетчик *}
    {if $smarty.local.count}
        (<span class="js-more-count">{$smarty.local.count}</span>)
    {/if}
</div>