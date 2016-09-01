{**
 * Блок с информацией
 *}

{$component = 'user-info-group'}
{component_define_params params=[ 'url', 'count', 'html', 'items', 'name', 'title', 'mods', 'classes', 'attributes' ]}

{hook run="{$component}-{$name}-before"}

{* Получаем пункты установленные плагинами *}
{hook run="{$component}-{$name}-items" assign='itemsHook' items=$items array=true}
{$items = ($itemsHook) ? $itemsHook : $items}

{if $html || $items}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {if $title}
            <h3 class="{$component}-title ls-heading">
                {if $url}
                    <a href="{$url}">{$title}</a>
                {else}
                    {$title}
                {/if}

                {if $count}
                    <span class="user-info-group-count">{$count}</span>
                {/if}
            </h3>
        {/if}

        <div class="user-info-group-content">
            {if $html}
                {$html}
            {else}
                {component 'info-list' list=$items classes='user-info-group-items'}
            {/if}
        </div>
    </div>
{/if}

{hook run="{$component}-{$name}-after"}