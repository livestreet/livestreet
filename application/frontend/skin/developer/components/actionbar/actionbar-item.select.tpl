{**
 * Экшнбар / Контрол выбора объектов
 *
 * @param string $target
 * @param string $items
 *
 * @extends {actionbar}/actionbar-item
 *}

{$target = $smarty.local.target}

{* Дефолтные пункты меню *}
{$menu = [
    [ 'name' => 'all',      'text' => {lang 'actionbar.select.menu.all'},      'data' => [ 'select-item' => $target ] ],
    [ 'name' => 'deselect', 'text' => {lang 'actionbar.select.menu.deselect'}, 'data' => [ 'select-item' => $target, 'select-filter' => ':not(*)' ] ],
    [ 'name' => 'invert',   'text' => {lang 'actionbar.select.menu.invert'},   'data' => [ 'select-item' => $target, 'select-filter' => ':not(.selected)' ] ],
    [ 'name' => '-',        'is_enabled' => !! $smarty.local.items ]
]}

{* Добавляем кастомные пункты меню *}
{foreach $smarty.local.items as $item}
    {$menu[] = [
        'text' => $item['text'],
        'data' => [ 'select-item' => $target, 'select-filter' => $item['filter'] ]
    ]}
{/foreach}

{* Выпадающее меню *}
{component 'dropdown'
    classes = "actionbar-item-link {$smarty.local.classes}"
    text    = {lang 'actionbar.select.title'}
    menu    = $menu
    params  = $smarty.local.params}