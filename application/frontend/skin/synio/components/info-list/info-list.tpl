{**
 * Список с информацией
 *
 * @param array $list (null) Массив в формате [ label, content ]
 * @param string $title (null) Заголовок
 *}

{* Название компонента *}
{$component = 'ls-info-list'}
{component_define_params params=[ 'url', 'count', 'title', 'list', 'mods', 'classes', 'attributes' ]}

{block 'info_list_options'}{/block}

{if $list}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {* Заголовок *}
        {if $title}
            <h2 class="{$component}-title">
                {if $url}
                    <a href="{$url}">{$title}</a>
                {else}
                    {$title}
                {/if}

                {if $count}
                    <span class="user-info-group-count">{$count}</span>
                {/if}
            </h2>
        {/if}

        {* Список *}
        <ul class="{$component}-list">
            {foreach $list as $item}
                <li class="{$component}-item {$item['classes']}" {cattr list=$item['attributes']}>
                    <div class="{$component}-item-label">
                        <span class="{$component}-item-label-text">{$item['label']}</span>
                    </div>

                    <div class="{$component}-item-content">
                        {$item['content']}
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}