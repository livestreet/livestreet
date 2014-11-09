{**
 * Список пользователей с элементами управления / Пользователь
 * Расширяет основной шаблон с пользователем добавляя кнопку "Повторно отправить приглашение" в блоке "Пригласить пользователей в блог"
 *}

{extends 'components/user-list-add/user-list-add.tpl'}

{block 'user_list_add_list'}
    {include './invite-list.tpl'
        hideableEmptyAlert = true
        users              = $smarty.local.users
        showActions        = true
        show               = !! $smarty.local.users
        classes            = "js-$component-users"
        itemClasses        = "js-$component-user"}
{/block}