{**
 * Управление пользователями блога
 *
 * @param object $users
 * @param array  $pagination
 *}

{component_define_params params=[ 'users', 'pagination' ]}

{if $users}
    <form method="post" enctype="multipart/form-data">
        <table class="ls-table">
            <thead>
                <tr>
                    <th class="cell-name"></th>
                    <th class="ls-ta-c">{$aLang.blog.admin.role_administrator}</th>
                    <th class="ls-ta-c">{$aLang.blog.admin.role_moderator}</th>
                    <th class="ls-ta-c">{$aLang.blog.admin.role_reader}</th>
                    <th class="ls-ta-c">{$aLang.blog.admin.role_banned}</th>
                </tr>
            </thead>

            <tbody>
                {foreach $users as $blogUser}
                    {$user = $blogUser->getUser()}

                    <tr>
                        <td class="cell-name">
                            {component 'user' template='avatar' user=$user mods='inline' size='xxsmall'}
                        </td>

                        {if $user->getId() == $oUserCurrent->getId()}
                            <td colspan="10" class="ls-ta-c">&mdash;</td>
                        {else}
                            <td class="ls-ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="administrator" {if $blogUser->getIsAdministrator()}checked{/if} /></td>
                            <td class="ls-ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="moderator" {if $blogUser->getIsModerator()}checked{/if} /></td>
                            <td class="ls-ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="reader" {if $blogUser->getUserRole() == $BLOG_USER_ROLE_USER}checked{/if} /></td>
                            <td class="ls-ta-c"><input type="radio" name="user_rank[{$user->getId()}]" value="ban" {if $blogUser->getUserRole() == $BLOG_USER_ROLE_BAN}checked{/if} /></td>
                        {/if}
                    </tr>
                {/foreach}
            </tbody>
        </table>

        {* Скрытые поля *}
        {component 'field' template='hidden.security-key'}

        {* Кнопки *}
        {component 'button' name='submit_blog_admin' text=$aLang.common.save mods='primary'}
    </form>

    {component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/{$pagination.sGetParams}"}
{else}
    {component 'blankslate' text=$aLang.blog.admin.alerts.empty}
{/if}