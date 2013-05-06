{include file='header.tpl'}
{include file='modals/modal.blog_delete.tpl'}

{assign var="oUserOwner" value=$oBlog->getOwner()}
{assign var="oVote" value=$oBlog->getVote()}


<script type="text/javascript">
	jQuery(function($){
		ls.lang.load({lang_load name="blog_fold_info,blog_expand_info"});
	});
</script>


<div class="blog-top">
	<h2 class="page-header">{$oBlog->getTitle()|escape:'html'} {if $oBlog->getType()=='close'} <i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>{/if}</h2>

	<div id="vote_area_blog_{$oBlog->getId()}" class="vote-topic 
															{if $oBlog->getRating() > 0}
																vote-count-positive
															{elseif $oBlog->getRating() < 0}
																vote-count-negative
															{elseif $oBlog->getRating() == 0}
																vote-count-zero
															{/if}
															
															{if $oVote} 
																voted 
																
																{if $oVote->getDirection() > 0}
																	voted-up
																{elseif $oVote->getDirection() < 0}
																	voted-down
																{/if}
															{else}
																not-voted
															{/if}
															
															{if ($oUserCurrent && $oUserOwner->getId() == $oUserCurrent->getId())}
																vote-nobuttons
															{/if}">
		<a href="#" class="vote-item vote-down" onclick="return ls.vote.vote({$oBlog->getId()},this,-1,'blog');"><span><i></i></span></a>
		<div class="vote-item vote-count" title="{$aLang.blog_vote_count}: {$oBlog->getCountVote()}"><span id="vote_total_blog_{$oBlog->getId()}">{if $oBlog->getRating() > 0}+{/if}{$oBlog->getRating()}</span></div>
		<a href="#" class="vote-item vote-up" onclick="return ls.vote.vote({$oBlog->getId()},this,1,'blog');"><span><i></i></span></a>
	</div>
</div>

<div class="blog-mini" id="blog-mini">
	<span id="blog_user_count_{$oBlog->getId()}">{$iCountBlogUsers}</span> {$iCountBlogUsers|declension:$aLang.reader_declension:'russian'},
	{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension:'russian'}
	<div class="fl-r" id="blog-mini-header">
		<a href="#" class="link-dotted" onclick="ls.blog.toggleInfo(); return false;">{$aLang.blog_expand_info}</a>
		<a href="{router page='rss'}blog/{$oBlog->getUrl()}/">RSS</a>
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
			<button type="submit"  class="button button-small" id="button-blog-join-first-{$oBlog->getId()}" data-button-additional="button-blog-join-second-{$oBlog->getId()}" data-only-text="1" onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</button>
		{/if}
	</div>
</div>



<div class="blog" id="blog" style="display: none">
	<div class="blog-inner">
		<header class="blog-header">
			<img src="{$oBlog->getAvatarPath(48)}" alt="avatar" class="avatar" />
			<span class="close" onclick="ls.blog.toggleInfo(); return false;"><a href="#" class="link-dotted">{$aLang.blog_fold_info}</a><i class="icon-synio-close"></i></span>
		</header>

		
		<div class="blog-content">
			<p class="blog-description">{$oBlog->getDescription()}</p>
		
			
			<ul class="blog-info">
				<li><span>{$aLang.infobox_blog_create}</span> <strong>{date_format date=$oBlog->getDateAdd() format="j F Y"}</strong></li>
				<li><span>{$aLang.infobox_blog_topics}</span> <strong>{$oBlog->getCountTopic()}</strong></li>
				<li><span><a href="{$oBlog->getUrlFull()}users/">{$aLang.infobox_blog_users}</a></span> <strong>{$oBlog->getCountUser()}</strong></li>
				<li class="rating"><span>{$aLang.infobox_blog_rating}</span> <strong>{$oBlog->getRating()}</strong></li>
			</ul>
			
			
			{hook run='blog_info_begin' oBlog=$oBlog}
			<strong>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators})</strong><br />
			<span class="user-avatar">
				<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" /></a>		
				<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
			</span>
			{if $aBlogAdministrators}			
				{foreach from=$aBlogAdministrators item=oBlogUser}
					{assign var="oUser" value=$oBlogUser->getUser()}  
					<span class="user-avatar">
						<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" /></a>		
						<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
					</span>
				{/foreach}	
			{/if}<br /><br />		

			
			<strong>{$aLang.blog_user_moderators} ({$iCountBlogModerators})</strong><br />
			{if $aBlogModerators}						
				{foreach from=$aBlogModerators item=oBlogUser}  
					{assign var="oUser" value=$oBlogUser->getUser()}							
					<span class="user-avatar">
						<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" /></a>		
						<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
					</span>
				{/foreach}							
			{else}
				<span class="notice-empty">{$aLang.blog_user_moderators_empty}</span>
			{/if}
			{hook run='blog_info_end' oBlog=$oBlog}
			
			
			
			{if $oUserCurrent and ($oUserCurrent->getId()==$oBlog->getOwnerId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() )}
				<br /><br />
				<ul class="actions">
					<li>
						<a href="{router page='blog'}edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}" class="edit">{$aLang.blog_edit}</a></li>
						{if $oUserCurrent->isAdministrator()}
							<li><a href="#" title="{$aLang.blog_delete}" data-type="modal-toggle" data-option-target="modal-blog-delete" class="delete">{$aLang.blog_delete}</a>
						{else}
							<a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.blog_delete}" onclick="return confirm('{$aLang.blog_admin_delete_confirm}');" >{$aLang.blog_delete}</a>
						{/if}
					</li>
				</ul>
			{/if}
		</div>
	</div>
	
	<footer class="blog-footer" id="blog-footer">
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
			<button type="submit"  class="button button-small" id="button-blog-join-second-{$oBlog->getId()}" data-button-additional="button-blog-join-first-{$oBlog->getId()}" data-only-text="1" onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</button>
		{/if}
		<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="rss">RSS</a>
		
		<div class="admin">
			{$aLang.blogs_owner} —
			<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
			<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
		</div>
	</footer>
</div>

{hook run='blog_info' oBlog=$oBlog}


{include file='navs/nav.blog_single.tpl'}


{if $bCloseBlog}
	{$aLang.blog_close_show}
{else}
	{include file='topics/topic_list.tpl'}
{/if}


{include file='footer.tpl'}