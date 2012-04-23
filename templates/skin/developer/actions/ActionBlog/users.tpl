{include file='header.tpl'}



<h2 class="page-header">{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a></h2>
	

{if $aBlogUsers}
	<table class="table table-users">
		<thead>
			<tr>
				<th>{$aLang.user}</th>
				<th>{$aLang.user_skill}</th>
				<th>{$aLang.user_rating}</th>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aBlogUsers item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}
				<tr>
					<td><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>
					<td>{$oUser->getSkill()}</td>							
					<td>{$oUser->getRating()}</td>
				</tr>
			{/foreach}						
		</tbody>
	</table>

	{include file='paging.tpl' aPaging=$aPaging}
{else}
	{$aLang.blog_user_readers_empty}
{/if}



{include file='footer.tpl'}