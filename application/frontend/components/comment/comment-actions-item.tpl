{**
 * Действие
 *}

{$component = 'ls-comment-actions-item'}
{component_define_params params=[ 'text', 'link', 'mods', 'classes', 'attributes' ]}

<li class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {if $link}
        <a href="{$link.url|default:'#'}" class="ls-link-dotted {$link.classes}" {cattr list=$link.attributes}>
            {$text}
        </a>
    {else}
        {$text}
    {/if}
</li>