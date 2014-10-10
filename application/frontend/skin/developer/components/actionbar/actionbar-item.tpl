{**
 * Кнопка экшнбара
 *
 * @param array $item Массив с опциями кнопки
 *}

{$item = $smarty.local.item}

<li class="actionbar-item">
    {block 'actionbar_item'}
        {include 'components/button/button.tpl'
            url        = $item['url']
            classes    = "actionbar-item-link {$item['classes']}"
            text       = $item['text']
            icon       = $item['icon']
            attributes = $item['attributes']}
    {/block}
</li>