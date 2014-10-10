{**
 * Выпадающее меню
 *
 * @param string text
 * @param string activeItem
 * @param array  menu
 *}

{* Название компонента *}
{$component = 'dropdown'}

{* Уникальный ID для привязки кнопки к меню *}
{$uid = "dropdown{rand( 0, 10e10 )}"}

{* Кнопка *}
{include 'components/button/button.tpl'
    type       = 'button'
    classes    = "{$component}-toggle {$smarty.local.classes}"
    attributes = "data-{$component}-target=\"{$uid}\" {$smarty.local.attributes}"
    text       = $smarty.local.text}

{* Меню *}
{include './dropdown.menu.tpl'
    id         = $uid
    activeItem = $smarty.local.activeItem
    items      = $smarty.local.menu}