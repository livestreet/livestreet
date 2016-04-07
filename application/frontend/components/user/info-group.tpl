{**
 * Блок с информацией
 *}

{$component = 'user-info-group'}
{component_define_params params=[ 'html', 'items', 'name', 'title', 'mods', 'classes', 'attributes' ]}

{hook run="{$component}-{$name}-before"}

{* Получаем пункты установленные плагинами *}
{hook run="{$component}-{$name}-items" assign='itemsHook' items=$items array=true}
{$items = $itemsHook|default:$items}

{if $html || $items}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        <h3 class="user-info-group-title">
            {$title}
        </h3>

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