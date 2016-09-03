{**
 * Пользователь
 *}

{extends 'component@user-list-add.item'}

{block 'user_list_add_item_actions' prepend}
    {* Кнопка "Повторно отправить инвайт" *}
    <li class="js-blog-invite-user-repeat" title="{$aLang.blog.invite.repeat}" data-user-id="{$userId}">
        {component 'icon' icon='repeat'}
    </li>
{/block}