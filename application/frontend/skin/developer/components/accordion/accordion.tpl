{**
 * Accordion
 *}

{$component = 'accordion'}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
    {foreach $items as $item}
        <h3>{$item[ 'title' ]}</h3>
        <div>{$item[ 'content' ]}</div>
    {/foreach}
</div>