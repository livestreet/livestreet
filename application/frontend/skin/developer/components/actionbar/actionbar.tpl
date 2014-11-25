{**
 * Экшнбар
 *
 * @param array  $items Массив с кнопками
 * @param string $mods
 * @param string $classes
 * @param string $attributes
 *}

{$component = 'actionbar'}

{if $smarty.local.items}
    <ul class="{$component} clearfix {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
        {foreach $smarty.local.items as $item}
            {if $item[ 'html' ]}
                {$item[ 'html' ]}
            {else}
                {include './actionbar-item.tpl'
                    url        = $item[ 'url' ]
                    classes    = $item[ 'classes' ]
                    text       = $item[ 'text' ]
                    icon       = $item[ 'icon' ]
                    show       = $item[ 'show' ]
                    attributes = $item[ 'attributes' ]}
            {/if}
        {/foreach}
    </ul>
{/if}