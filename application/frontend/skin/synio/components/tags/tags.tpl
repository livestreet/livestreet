{**
 * Список тегов
 *}

{$component = 'ls-tags'}
{component_define_params params=[ 'title', 'tags', 'mods', 'classes', 'attributes' ]}

{block 'tags_options'}{/block}

{if $tags}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {if $title}
            <span class="{$component}-item {$component}-title">
                {$title}
            </span>
        {/if}

        {block 'tags_list'}
            {foreach $tags as $tag}
                {component 'tags' template='item' text=$tag->getText() url=$tag->getUrl() isFirst=$tag@first isLast=$tag@last}
            {/foreach}
        {/block}
    </div>
{/if}