{include file='header.tpl' menu='blog' showWhiteBack=true}

	<h1>{$aLang.blog_user_readers_all} ({$iCountBlogUsers}): <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a></h1>
	
	<div class="page people">
		{if $aBlogUsers}	
			<table>
				<thead>
					<tr>
						<td class="user">{$aLang.user}</td>
						<td class="strength">{$aLang.user_skill}</td>
						<td class="rating">{$aLang.user_rating}</td>
					</tr>
				</thead>					
				<tbody>
				{foreach from=$aBlogUsers item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}
					<tr>
						<td class="user"><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{$oUser->getUserWebPath()}" class="link">{$oUser->getLogin()}</a></td>							
						<td class="strength">{$oUser->getSkill()}</td>							
						<td class="rating"><strong>{$oUser->getRating()}</strong></td>
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