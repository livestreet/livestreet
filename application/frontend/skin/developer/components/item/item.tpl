{**
 * Item
 *}

{$component = 'item'}

{block 'options'}
    {$image = $smarty.local.image}
    {$classes = $smarty.local.classes}
    {$mods = $smarty.local.mods}
    {$content = $smarty.local.content}
    {$title = $smarty.local.title}
{/block}

<li class="{$component} {$smarty.local.classes} {mod name=$component mods=$smarty.local.mods}" {$smarty.local.attributes}>
    <img src="{$image[ 'path' ]}" alt="{$image[ 'alt' ]}" title="{$image[ 'title' ]}" class="{$component}-image {$image[ 'classes' ]}">

    <div class="{$component}-content js-{$component}-content">
        {if $title}
            <h3 class="{$component}-title">{$title}</h3>
        {/if}

        {$content}
    </div>
</li>