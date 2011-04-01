{include file='header.tpl'}
{include file='menu.blog_edit.tpl'}

{if $aBlogUsers}
	<form action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		<table class="table">
			<thead>
				<tr>
					<td>{$aLang.blog_admin_users}</td>
					<td width="10%">{$aLang.blog_admin_users_administrator}</td>
					<td width="10%">{$aLang.blog_admin_users_moderator}</td>
					<td width="10%">{$aLang.blog_admin_users_reader}</td>
					<td width="10%">{$aLang.blog_admin_users_bun}</td>
				</tr>
			</thead>
			<tbody>
				{foreach from=$aBlogUsers item=oBlogUser}
					{assign var="oUser" value=$oBlogUser->getUser()}
					<tr>
						<td><a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>
						{if $oUser->getId()==$oUserCurrent->getId()}
							<td colspan="3" align="center">{$aLang.blog_admin_users_current_administrator}</td>
						{else}
							<td align="center"><input type="radio" name="user_rank[{$oUser->getId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if} /></td>
							<td align="center"><input type="radio" name="user_rank[{$oUser->getId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if} /></td>
							<td align="center"><input type="radio" name="user_rank[{$oUser->getId()}]" value="reader" {if $oBlogUser->getUserRole()==$BLOG_USER_ROLE_USER}checked{/if} /></td>
							<td align="center"><input type="radio" name="user_rank[{$oUser->getId()}]" value="ban" {if $oBlogUser->getUserRole()==$BLOG_USER_ROLE_BAN}checked{/if} /></td>
						{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>

		<input type="submit" name="submit_blog_admin" value="{$aLang.blog_admin_users_submit}" />
	</form>
{else}
	{$aLang.blog_admin_users_empty}
{/if}


{include file='footer.tpl'}