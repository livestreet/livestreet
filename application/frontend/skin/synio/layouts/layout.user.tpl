{**
 * Базовый шаблон профиля пользователя
 *}

{extends './layout.base.tpl'}

{block 'layout_content_header' prepend}
    <h3 class="profile-page-header">
        {block 'layout_user_page_title'}{/block}
    </h3>
{/block}