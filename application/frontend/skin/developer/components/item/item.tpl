{**
 * Item
 *}

{$component = 'item'}

{block 'options'}
    {$image = $smarty.local.image}
    {$classes = $smarty.local.classes}
    {$mods = $smarty.local.mods}
    {$content = $smarty.local.content}
    {$desc = $smarty.local.desc}
    {$title = $smarty.local.title}
{/block}

<li class="{$component} {$smarty.local.classes} {cmods name=$component mods=$smarty.local.mods}" {cattr list=$smarty.local.attributes}>
    <a href="{$image[ 'url' ]}">
        <img src="{$image[ 'path' ]}" alt="{$image[ 'alt' ]}" title="{$image[ 'title' ]}" class="{$component}-image {$image[ 'classes' ]}">
    </a>

    <div class="{$component}-content js-{$component}-content">
        {if $title}
            <h3 class="{$component}-title">{$title}</h3>
        {/if}

        <div class="{$component}-description">
            {$desc}
        </div>

        {$content}
    </div>
</li>