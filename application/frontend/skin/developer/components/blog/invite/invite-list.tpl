{**
 * Список пользователей с элементами управления / Пользователь
 * Расширяет основной шаблон с пользователем добавляя кнопку "Повторно отправить приглашение" в блоке "Пригласить пользователей в блог"
 *}

{extends 'components/user-list-add/list.tpl'}

{block 'user_list_add_item'}
    {include './invite-item.tpl' user=$user showActions=true}
{/block}