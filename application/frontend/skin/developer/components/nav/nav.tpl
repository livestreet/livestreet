{**
 * Навигация
 *
 * @param string  $name
 * @param array   $items
 * @param string  $activeItem
 * @param boolean $is_enabled
 * @param string  $mods
 * @param string  $classes
 * @param string  $attributes
 * @param array   $data
 * @param integer $count
 * @param integer $hookParams
 *}

{* Название компонента *}
{$component = 'nav'}

{* Уникальное имя меню *}
{$name = ( $smarty.local.name ) ? $smarty.local.name : rand(0, 9999999)}

{* Получаем пункты установленные плагинами *}
{hook run="{$component}_{$name}" assign='itemsHook' params=$smarty.local.hookParams items=$smarty.local.items array=true}

{$items = ( $itemsHook ) ? $itemsHook : $smarty.local.items}

{* Считаем кол-во неактивных пунктов *}
{$disabledItemsCounter = 0}

{foreach $items as $item}
    {$disabledItemsCounter = $disabledItemsCounter + ( ! $item['is_enabled']|default:true && $item['name'] != '-' )}
{/foreach}

{* Отображем меню только если есть активные пункты *}
{if count( $items ) - $disabledItemsCounter - ( ( $smarty.local.hideAlone|default:true ) ? 1 : 0 )}
    <ul class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
        {foreach $items as $item}
            {$isEnabled = $item[ 'is_enabled' ]}
            {$isDropdown = isset( $item[ 'menu' ] )}

            {if $isEnabled|default:true}
                {if $item['name'] != '-'}
                    {* Пункт меню *}
                    <li class="{$component}-item
                               {if $smarty.local.activeItem && $smarty.local.activeItem == $item['name']}active{/if}
                               {if isset($item['count'])}{$component}-item--has-counter{/if}
                               {if $isDropdown}{$component}-item--has-children{/if}
                               {$item['classes']}"
                        role="menuitem"
                        {if isset($item['title'])}title="{$item['title']}"{/if}
                        {cattr list=$item['attributes']}
                        {foreach $item['data'] as $data}
                            data-{$data@key}={$data@value}
                        {/foreach}>

                        {* Ссылка *}
                        <a href="{if $item['url']}{$item['url']}{else}#{/if}">
                            {$item['text']}

                            {* Счетчик *}
                            {if isset($item['count']) && ( $smarty.local.showZeroCounter || ( ! $smarty.local.showZeroCounter && $item['count'] > 0 ) )}
                                <span class="badge">{$item['count']}</span>
                            {/if}
                        </a>

                        {* Подменю *}
                        {if $isDropdown}
                            {include './nav.tpl'
                                name          = $item['name']
                                activeItem    = $smarty.local.activeItem
                                classes       = "nav--stacked nav--dropdown {$item['classes']}"
                                attributes    = $item['attributes']
                                items         = $item['menu']}
                        {/if}
                    </li>
                {else}
                    {* Разделитель *}
                    <li class="{$component}-separator"></li>
                {/if}
            {/if}
        {/foreach}
    </ul>
{/if}