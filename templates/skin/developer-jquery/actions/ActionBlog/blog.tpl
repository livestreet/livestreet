{include file='header.tpl' menu='blog'}
{assign var="oUserOwner" value=$oBlog->getOwner()}
{assign var="oVote" value=$oBlog->getVote()}



{if $oUserCurrent and $oUserCurrent->isAdministrator()}
	<div id="blog_delete_form" class="modal">
		<header>
			<h3>{$aLang.blog_admin_delete}</h3>
			<a href="#" class="close jqmClose"></a>
		</header>
		
		
		<form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST">
			<p><label for="topic_move_to">{$aLang.blog_admin_delete_move}:</label>
			<select name="topic_move_to" id="topic_move_to" class="input-width-full">
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
			<button class="button button-primary">{$aLang.blog_delete}</button>
		</form>
	</div>
{/if}



<div class="blog">
	<header class="blog-header">
		<div id="vote_area_blog_{$oBlog->getId()}" class="vote {if $oBlog->getRating() > 0}vote-count-positive{elseif $oBlog->getRating() < 0}vote-count-negative{/if} {if $oVote} voted {if $oVote->getDirection()>0}voted-up{elseif $oVote->getDirection()<0}voted-down{/if}{/if}">
			<a href="#" class="vote-up" onclick="return ls.vote.vote({$oBlog->getId()},this,1,'blog');"></a>
			<div id="vote_total_blog_{$oBlog->getId()}" class="vote-count" title="{$aLang.blog_vote_count}: {$oBlog->getCountVote()}">{$oBlog->getRating()}</div>
			<a href="#" class="vote-down" onclick="return ls.vote.vote({$oBlog->getId()},this,-1,'blog');"></a>
		</div>
		
		
		<img src="{$oBlog->getAvatarPath(48)}" alt="avatar" class="avatar" />
		
		
		<h2 class="page-header">{$oBlog->getTitle()|escape:'html'}</h2>
		
		
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
	</header>
	
	
	<p class="blog-description">{$oBlog->getDescription()|nl2br}</p>			
	
	
	<footer class="blog-footer">
		<strong>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators}):</strong>							
		<a href="{$oUserOwner->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUserOwner->getLogin()}</a>
		{if $aBlogAdministrators}			
			{foreach from=$aBlogAdministrators item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}  									
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}	
		{/if}<br />		

		
		<strong>{$aLang.blog_user_moderators} ({$iCountBlogModerators}):</strong>
		{if $aBlogModerators}						
			{foreach from=$aBlogModerators item=oBlogUser}  
				{assign var="oUser" value=$oBlogUser->getUser()}									
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}							
		{else}
			{$aLang.blog_user_moderators_empty}
		{/if}<br />
		
		
		<strong>{$aLang.blog_user_readers} ({$iCountBlogUsers}):</strong>
		{if $aBlogUsers}
			{foreach from=$aBlogUsers item=oBlogUser}
				{assign var="oUser" value=$oBlogUser->getUser()}
				<a href="{$oUser->getUserWebPath()}" class="user"><i class="icon-user"></i>{$oUser->getLogin()}</a>
			{/foreach}
			
			{if count($aBlogUsers) < $iCountBlogUsers}
				<br /><a href="{$oBlog->getUrlFull()}users/">{$aLang.blog_user_readers_all}</a>
			{/if}
		{else}
			{$aLang.blog_user_readers_empty}
		{/if}
	</footer>
</div>


{if $bCloseBlog}
	{$aLang.blog_close_show}
{else}
	{include file='topic_list.tpl'}
{/if}


{include file='footer.tpl'}