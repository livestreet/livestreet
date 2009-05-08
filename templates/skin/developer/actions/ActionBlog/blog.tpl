{include file='header.tpl' menu='blog'}


<div class="profile-blog">
	<div class="voting {if $oBlog->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oBlog->getOwnerId()==$oUserCurrent->getId()}guest{/if} {if $oBlog->getUserIsVote()} voted {if $oBlog->getUserVoteDelta()>0}plus{elseif $oBlog->getUserVoteDelta()<0}minus{/if}{/if}">
		<a href="#" class="plus" onclick="lsVote.vote({$oBlog->getId()},this,1,'blog'); return false;"></a>
		<span class="total">{if $oBlog->getRating()>0}+{/if}{$oBlog->getRating()}</span>
		<a href="#" class="minus" onclick="lsVote.vote({$oBlog->getId()},this,-1,'blog'); return false;"></a>
		
		{$aLang.blog_vote_count}: <strong class="count">{$oBlog->getCountVote()}</strong>
	</div>

	
	<img src="{$oBlog->getAvatarPath(24)}" alt="avatar" class="avatar" />
	<h2><a href="#">{$oBlog->getTitle()|escape:'html'}</a></h2>
	
	
	<ul class="action">
		{if $oUserCurrent and ($oUserCurrent->getId()==$oBlog->getOwnerId() or $oUserCurrent->isAdministrator() or ($oBlogUser and $oBlogUser->getIsAdministrator()) )}
			<li class="edit"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}">{$aLang.blog_edit}</a></li>
		{/if}
		<li class="rss"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_RSS}/blog/{$oBlog->getUrl()}/">rss</a></li>					
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
			<li class="join {if !$bNeedJoin}active{/if}"><a href="#" onclick="ajaxJoinLeaveBlog(this,{$oBlog->getId()}); return false;"></a></li>
		{/if}
	</ul>
	
	
	<div class="about">
		<h3>{$aLang.blog_about}</h3>
		<p>{$oBlog->getDescription()|nl2br}</p>					
		
		<div class="float-block">
			<h3>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators})</h3>								
			<ul>				
				<li>
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlog->getUserLogin()}/"><img src="{$oBlog->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlog->getUserLogin()}"/></a><br />
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlog->getUserLogin()}/">{$oBlog->getUserLogin()}</a>
				</li>
				{if $aBlogAdministrators}			
				{foreach from=$aBlogAdministrators item=oBlogAdministrator}  									
				<li>
					<dl>
						<dt><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogAdministrator->getUserLogin()}/"><img src="{$oBlogAdministrator->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlogAdministrator->getUserLogin()}"/></a></dt>
						<dd><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogAdministrator->getUserLogin()}/">{$oBlogAdministrator->getUserLogin()}</a></dd>
					</dl>
				</li>
				{/foreach}	
				{/if}						
			</ul>	
		</div>

		<div class="float-block">
			<h3>{$aLang.blog_user_moderators} ({$iCountBlogModerators})</h3>
			{if $aBlogModerators}
			<ul>							
				{foreach from=$aBlogModerators item=oBlogModerator}  									
				<li>
					<dl>
						<dt><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogModerator->getUserLogin()}/"><img src="{$oBlogModerator->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlogModerator->getUserLogin()}"/></a></dt>
						<dd><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogModerator->getUserLogin()}/">{$oBlogModerator->getUserLogin()}</a></dd>
					</dl>
				</li>
				{/foreach}							
			</ul>
			{else}
				{$aLang.blog_user_moderators_empty}
			{/if}
		</div>
	</div>
		
		
	<h3>{$aLang.blog_user_readers} ({$iCountBlogUsers})</h3>
	{if $aBlogUsers}
	<ul class="reader-list">
		{foreach from=$aBlogUsers item=oBlogUser}
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></li>
		{/foreach}							
	</ul>
	{else}
		{$aLang.blog_user_readers_empty}
	{/if}
	
</div>



{include file='topic_list.tpl'}

{include file='footer.tpl'}

