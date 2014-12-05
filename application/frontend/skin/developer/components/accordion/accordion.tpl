{**
 * Accordion
 *
 * @param array  $items
 * @param string $mods
 * @param string $classes
 * @param array  $attributes
 *}

{$component = 'accordion'}

<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
    {foreach $smarty.local.items as $item}
        <h3 class="{$component}-title">{$item[ 'title' ]}</h3>
        <div class="{$component}-content">{$item[ 'content' ]}</div>
    {/foreach}
</div>