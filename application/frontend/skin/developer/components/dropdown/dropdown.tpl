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
    attributes = array_merge( $smarty.local.attributes|default:[], [ 'data-dropdown-target' => $uid ] )
    text       = $smarty.local.text}

{* Меню *}
{include './dropdown.menu.tpl'
    id         = $uid
    activeItem = $smarty.local.activeItem
    items      = $smarty.local.menu}