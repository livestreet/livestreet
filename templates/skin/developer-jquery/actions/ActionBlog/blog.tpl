{include file='header.tpl' menu='blog'}
{assign var="oUserOwner" value=$oBlog->getOwner()}
{assign var="oVote" value=$oBlog->getVote()}



{if $oUserCurrent and $oUserCurrent->isAdministrator()}
	<form id="blog_delete_form" class="blog-delete-form" action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST">
		<a href="#" class="close jqmClose"></a>
	
		<p>{$aLang.blog_admin_delete_move}</p>
		<p><select name="topic_move_to">
			<option value="-1">{$aLang.blog_delete_clear}</option>
			{if $aBlogs}
				<optgroup label="{$aLang.blogs}">
					{foreach from=$aBlogs item=oBlogDelete}
						<option value="{$oBlogDelete->getId()}">{$oBlogDelete->getTitle()}</option>
					{/foreach}
				</optgroup>
			{/if}
		</select></p>
		
		<input type="hidden" value="{$LIVESTREET_SECURITY_KEY}" name="security_ls_key" />
		<input type="submit" value="{$aLang.blog_delete}" />
	</form>
{/if}



<div class="blog">
	<div id="vote_area_blog_{$oBlog->getId()}" class="voting {if $oBlog->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oBlog->getOwnerId()==$oUserCurrent->getId()}guest{/if} {if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
		<a href="#" class="plus" onclick="return ls.vote.vote({$oBlog->getId()},this,1,'blog');"></a>
		<div id="vote_total_blog_{$oBlog->getId()}" class="total" title="{$aLang.blog_vote_count}: {$oBlog->getCountVote()}">{$oBlog->getRating()}</div>
		<a href="#" class="minus" onclick="return ls.vote.vote({$oBlog->getId()},this,-1,'blog');"></a>
	</div>
	
	
	<h2><img src="{$oBlog->getAvatarPath(24)}" alt="avatar" class="avatar" /> {$oBlog->getTitle()|escape:'html'}</h2>
	
	<ul class="actions">
		<li><a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="rss">Rss</a></li>
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
			<li><a href="#" onclick="ls.blog.toggleJoin(this,{$oBlog->getId()}); return false;">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</a></li>
		{/if}
		{if $oUserCurrent and ($oUserCurrent->getId()==$oBlog->getOwnerId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() )}
			<li>
				<a href="{router page='blog'}edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}" class="edit">{$aLang.blog_edit}</a></li>
				{if $oUserCurrent->isAdministrator()}
					<li><a href="#" title="{$aLang.blog_delete}" id="blog_delete_show" class="delete">{$aLang.blog_delete}</a>
				{else}
					<a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.blog_delete}" onclick="return confirm('{$aLang.blog_admin_delete_confirm}');" >{$aLang.blog_delete}</a>
				{/if}
			</li>
		{/if}
	</ul>
	
	
	<p>{$oBlog->getDescription()|nl2br}</p>			
	
	
	<strong>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators}):</strong>							
	<a href="{$oUserOwner->getUserWebPath()}" class="user">{$oUserOwner->getLogin()}</a>
	{if $aBlogAdministrators}			
		{foreach from=$aBlogAdministrators item=oBlogUser}
			{assign var="oUser" value=$oBlogUser->getUser()}  									
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a>
		{/foreach}	
	{/if}<br />		

	
	<strong>{$aLang.blog_user_moderators} ({$iCountBlogModerators}):</strong>
	{if $aBlogModerators}						
		{foreach from=$aBlogModerators item=oBlogUser}  
		{assign var="oUser" value=$oBlogUser->getUser()}									
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a>
		{/foreach}							
	{else}
		{$aLang.blog_user_moderators_empty}
	{/if}<br />
	
	
	<strong>{$aLang.blog_user_readers} ({$iCountBlogUsers}):</strong>
	{if $aBlogUsers}
		{foreach from=$aBlogUsers item=oBlogUser}
		{assign var="oUser" value=$oBlogUser->getUser()}
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a>
		{/foreach}
		{if count($aBlogUsers)<$iCountBlogUsers}
			<br/><a href="{$oBlog->getUrlFull()}users/">{$aLang.blog_user_readers_all}</a>
		{/if}
	{else}
		{$aLang.blog_user_readers_empty}
	{/if}		
</div>


{if $bCloseBlog}
	{$aLang.blog_close_show}
{else}
	{include file='topic_list.tpl'}
{/if}


{include file='footer.tpl'}