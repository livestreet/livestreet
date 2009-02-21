{include file='header.tpl' menu='blog_edit' showWhiteBack=true}



			<h1>{$aLang.blog_admin}: <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlogEdit->getUrl()}/">{$oBlogEdit->getTitle()}</a></h1>

		{if $aBlogUsers}
			<form action="" method="POST" enctype="multipart/form-data">
				<table class="table-blog-users">
					<thead>
						<tr>
							<td></td>
							<td width="10%">{$aLang.blog_admin_users_administrator}</td>
							<td width="10%">{$aLang.blog_admin_users_moderator}</td>
							<td width="10%">{$aLang.blog_admin_users_reader}</td>
						</tr>
					</thead>
					<tbody>
						{foreach from=$aBlogUsers item=oBlogUser}
						<tr>
							<td class="username"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></td>
							{if $oBlogUser->getUserId()==$oUserCurrent->getId()}
							<td colspan="3" align="center">{$aLang.blog_admin_users_current_administrator}</td>
							{else}
							<td><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]"  value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if}/></td>
							<td><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]"  value="moderator" {if $oBlogUser->getIsModerator()}checked{/if}/></td>
							<td><input type="radio" name="user_rank[{$oBlogUser->getUserId()}]"  value="reader" {if !$oBlogUser->getIsAdministrator() and !$oBlogUser->getIsModerator()}checked{/if}/></td>
							{/if}
						</tr>
						{/foreach}						
					</tbody>
				</table>
								
				<input type="submit" name="submit_blog_admin" value="{$aLang.blog_admin_users_submit}">
			</form>
		{else}
			{$aLang.blog_admin_users_empty} 
		{/if} 


{include file='footer.tpl'}