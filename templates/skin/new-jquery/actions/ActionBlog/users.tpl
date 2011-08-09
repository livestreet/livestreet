{include file='header.tpl'}

<h1>{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a></h1>
	
<div class="page people">
	{if $aBlogUsers}

		<table class="table">
			<thead>
				<tr>
					<td>{$aLang.user}</td>
					<td align="center" width="60">{$aLang.user_skill}</td>
					<td align="center" width="60">{$aLang.user_rating}</td>
				</tr>
			</thead>
			
			<tbody>
			{foreach from=$aBlogUsers item=oBlogUser}
			{assign var="oUser" value=$oBlogUser->getUser()}
				<tr>
					<td><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>
					<td align="center">{$oUser->getSkill()}</td>							
					<td align="center"><strong>{$oUser->getRating()}</strong></td>
				</tr>
			{/foreach}						
			</tbody>
		</table>
					
		<br/>
		{include file='paging.tpl' aPaging=$aPaging}
	{else}
   	 	{$aLang.blog_user_readers_empty}
    {/if}
</div>


{include file='footer.tpl'}