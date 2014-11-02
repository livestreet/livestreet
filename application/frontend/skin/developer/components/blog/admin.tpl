{**
 * Управление пользователями блога
 *}

{if $aBlogUsers}
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
				{foreach $aBlogUsers as $oBlogUser}
					{$oUser = $oBlogUser->getUser()}

					<tr>
						<td class="cell-name">
							{include 'components/user/user-item.tpl' oUser=$oUser}
						</td>

						{if $oUser->getId() == $oUserCurrent->getId()}
							<td colspan="10" class="ta-c">&mdash;</td>
						{else}
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="reader" {if $oBlogUser->getUserRole() == $BLOG_USER_ROLE_USER}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="ban" {if $oBlogUser->getUserRole() == $BLOG_USER_ROLE_BAN}checked{/if} /></td>
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

	{include 'components/pagination/pagination.tpl' paging=$aPaging}
{else}
	{include 'components/alert/alert.tpl' text=$aLang.blog.admin.alerts.empty mods='empty'}
{/if}