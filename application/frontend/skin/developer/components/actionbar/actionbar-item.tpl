{**
 * Кнопка экшнбара
 *
 * @param array $item Массив с опциями кнопки
 *}

{$item = $smarty.local.item}

<li class="actionbar-item">
    {block 'actionbar_item'}
        {include 'components/button/button.tpl'
            sUrl        = $item['url']
            sClasses    = "actionbar-item-link {$item['classes']}"
            sText       = $item['text']
            sIcon       = $item['icon']
            sAttributes = $item['attributes']}
    {/block}
</li>