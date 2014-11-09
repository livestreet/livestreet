{**
 * Управление пользователями блога
 *
 * @param object $users
 * @param array  $pagination
 *}

{if $smarty.local.users}
    <form method="post" enctype="multipart/form-data">
        <table class="table">
            <thead>
                <tr>
                    <th class="cell-name"></th>
                    <th class="ta-c">{$aLang.blog.admin.role_administrator}</th>
                    <th class="ta-c">{$aLang.blog.admin.role_moderator}</th>
                    <th class="ta-c">{$aLang.blog.admin.role_reader}</th>
                    <th class="ta-c">{$aLang.blog.admin.role_banned}</th>
                </tr>
            </thead>

            <tbody>
                {foreach $smarty.local.users as $blogUser}
                    {$user = $blogUser->getUser()}

                    <tr>
                        <td class="cell-name">
                            {include 'components/user/user-item.tpl' user=$user}
                        </td>

                        {if $user->getId() == $oUserCurrent->getId()}
                            <td colspan="10" class="ta-c">&mdash;</td>
                        {else}
                            <td class="ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="administrator" {if $user->getIsAdministrator()}checked{/if} /></td>
                            <td class="ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="moderator" {if $user->getIsModerator()}checked{/if} /></td>
                            <td class="ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="reader" {if $user->getUserRole() == $BLOG_USER_ROLE_USER}checked{/if} /></td>
                            <td class="ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="ban" {if $user->getUserRole() == $BLOG_USER_ROLE_BAN}checked{/if} /></td>
                        {/if}
                    </tr>
                {/foreach}
            </tbody>
        </table>

        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include 'components/button/button.tpl' name='submit_blog_admin' text=$aLang.common.save mods='primary'}
    </form>

    {include 'components/pagination/pagination.tpl' paging=$smarty.local.pagination}
{else}
    {include 'components/alert/alert.tpl' text=$aLang.blog.admin.alerts.empty mods='empty'}
{/if}