{include file='header.tpl' menu='blog_edit' showWhiteBack=true}

	<h2>{$aLang.blog_admin}: <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlogEdit->getUrl()}/">{$oBlogEdit->getTitle()}</a></h2>

	{if $aBlogUsers}
		<form action="" method="POST" enctype="multipart/form-data">
			<table class="people">
				<thead>
					<tr>
						<td class="user">{$aLang.user}</td>
						<td class="option">{$aLang.blog_admin_users_administrator}</td>
						<td class="option">{$aLang.blog_admin_users_moderator}</td>
						<td class="option">{$aLang.blog_admin_users_reader}</td>
					</tr>
				</thead>
				<tbody>
					{foreach from=$aBlogUsers item=oBlogUser}
					<tr>
						<td class="user without-image"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></td>
						{if $oBlogUser->getUserId()==$oUserCurrent->getId()}
						<td colspan="3" align="center">{$aLang.blog_admin_users_current_administrator}</td>
						{else}
						<td class="option"><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if} /></td>
						<td class="option"><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if} /></td>
						<td class="option"><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="reader" {if !$oBlogUser->getIsAdministrator() and !$oBlogUser->getIsModerator()}checked{/if} /></td>
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