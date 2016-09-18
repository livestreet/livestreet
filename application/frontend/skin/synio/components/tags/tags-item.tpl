{**
 * Уведомления
 *
 * @param string  $text
 * @param string  $url
 *}

{* Название компонента *}
{$component = 'ls-tags-item'}
{component_define_params params=[ 'text', 'url', 'isFirst', 'isLast', 'mods', 'classes', 'attributes' ]}

{if $isLast}
    {$mods = "$mods last"}
{/if}

{block 'tags_item_options'}{/block}

{* Уведомление *}
<a class="{$component} {cmods name=$component mods=$mods} {$classes}" href="{$url}" rel="tag">{$text|escape}</a>{if ! $isLast}, {/if}