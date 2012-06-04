{include file='header.tpl'}
{include file='menu.blog_edit.tpl'}



{if $aBlogUsers}
	<form method="post" enctype="multipart/form-data" class="mb-20">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		
		<table class="table table-users">
			<thead>
				<tr>
					<th class="cell-name">{$aLang.blog_admin_users}</th>
					<th class="ta-c">{$aLang.blog_admin_users_administrator}</th>
					<th class="ta-c">{$aLang.blog_admin_users_moderator}</th>
					<th class="ta-c">{$aLang.blog_admin_users_reader}</th>
					<th class="ta-c">{$aLang.blog_admin_users_bun}</th>
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$aBlogUsers item=oBlogUser}
					{assign var="oUser" value=$oBlogUser->getUser()}
					
					<tr>
						<td class="cell-name">
							<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
							<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
						</td>
						
						{if $oUser->getId()==$oUserCurrent->getId()}
							<td colspan="3">{$aLang.blog_admin_users_current_administrator}</td>
						{else}
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="reader" {if $oBlogUser->getUserRole()==$BLOG_USER_ROLE_USER}checked{/if} /></td>
							<td class="ta-c"><input type="radio" name="user_rank[{$oUser->getId()}]" value="ban" {if $oBlogUser->getUserRole()==$BLOG_USER_ROLE_BAN}checked{/if} /></td>
						{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>

		<button type="submit"  name="submit_blog_admin" class="button button-primary">{$aLang.blog_admin_users_submit}</button>
	</form>

	{include file='paging.tpl' aPaging=$aPaging}
{else}
	{$aLang.blog_admin_users_empty}
{/if}



{include file='footer.tpl'}