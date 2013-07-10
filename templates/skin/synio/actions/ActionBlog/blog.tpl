{**
 * Блог
 *
 * bCloseBlog    true если блог закрытый
 *
 * @styles css/blog.css
 * @scripts _framework_/js/livestreet/blog.js
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_content'}
	{$oUserOwner = $oBlog->getOwner()}
	{$oVote = $oBlog->getVote()}

	<script>
		jQuery(function($){
			ls.lang.load({lang_load name="blog_fold_info,blog_expand_info"});
		});
	</script>

	{* Подключаем модальное окно удаления блога если пользователь админ *}
	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		{include file='modals/modal.blog_delete.tpl'}
	{/if}


	{**
	 * Шапка блога
	 *}
	<div class="blog" id="js-blog">
		<div class="blog-header">
			<img src="{$oBlog->getAvatarPath(48)}" alt="avatar" class="blog-avatar" />

			<h2 class="page-header blog-title">
				{$oBlog->getTitle()|escape:'html'} 

				{if $oBlog->getType() == 'close'}
					<i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>
				{/if}
			</h2>

			<div data-vote-type="blog"
				 data-vote-id="{$oBlog->getId()}"
				 class="vote-topic js-vote
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
				<a href="#" class="vote-item vote-down js-vote-down"><span><i></i></span></a>
				<div class="vote-item vote-count" title="{$aLang.blog_vote_count}: {$oBlog->getCountVote()}"><span class="js-vote-rating">{if $oBlog->getRating() > 0}+{/if}{$oBlog->getRating()}</span></div>
				<a href="#" class="vote-item vote-up js-vote-up"><span><i></i></span></a>
			</div>
		</div>


		{**
		 * Краткая информация о блоге
		 *}
		<div class="blog-short-info">
			<div class="blog-short-info-actions">
				<a href="#" class="link-dotted" id="js-blog-toggle" onclick="ls.blog.toggleInfo(); return false;">{$aLang.blog_expand_info}</a>

				{if $oUserCurrent and $oUserCurrent->getId() != $oBlog->getOwnerId()}
					<button type="submit" 
							class="button button-small" 
							id="button-blog-join-first-{$oBlog->getId()}" 
							data-button-additional="button-blog-join-second-{$oBlog->getId()}" 
							data-only-text="1" 
							onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;">
						{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}
					</button>
				{/if}
			</div>

			<span id="blog_user_count_{$oBlog->getId()}">{$iCountBlogUsers}</span> 
			{$iCountBlogUsers|declension:$aLang.reader_declension:'russian'},
			{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension:'russian'}
		</div>


		{**
		 * Полная информация о блоге
		 *}
		<div class="blog-full-info" id="js-blog-full-info">
			<div class="blog-content">
				<div class="blog-description text">{$oBlog->getDescription()}</div>
			
				
				<ul class="dotted-list blog-info">
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.infobox_blog_create}</span>
						<span class="dotted-list-item-value">{date_format date=$oBlog->getDateAdd() format="j F Y"}</span>
					</li>
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.infobox_blog_topics}</span>
						<span class="dotted-list-item-value">{$oBlog->getCountTopic()}</span>
					</li>
					<li class="dotted-list-item">
						<span class="dotted-list-item-label"><a href="{$oBlog->getUrlFull()}users/">{$aLang.infobox_blog_users}</a></span>
						<span class="dotted-list-item-value">{$oBlog->getCountUser()}</span>
					</li>
					<li class="dotted-list-item blog-info-rating">
						<span class="dotted-list-item-label">{$aLang.infobox_blog_rating}</span>
						<span class="dotted-list-item-value">{$oBlog->getRating()}</span>
					</li>
				</ul>
				
				
				{hook run='blog_info_begin' oBlog=$oBlog}

				<h4>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators})</h4>

				{* Создатель блога *}
				<span class="user-avatar">
					<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" /></a>		
					<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
				</span>

				{* Список администраторов блога *}
				{if $aBlogAdministrators}			
					{foreach $aBlogAdministrators as $oBlogUser}
						{$oUser = $oBlogUser->getUser()}

						<span class="user-avatar">
							<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" /></a>		
							<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
						</span>
					{/foreach}	
				{/if}<br /><br />		

				
				{* Список модераторов блога *}
				<h4>{$aLang.blog_user_moderators} ({$iCountBlogModerators})</h4>

				{if $aBlogModerators}						
					{foreach $aBlogModerators as $oBlogUser}  
						{$oUser = $oBlogUser->getUser()}

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
			

			<footer class="blog-footer">
				<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="blog-rss">RSS</a>
				
				<div class="blog-admin">
					{$aLang.blogs_owner} —
					<span class="user-avatar">
						<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
						<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
					</span>
				</div>
			</footer>
		</div>
	</div>


	{hook run='blog_info' oBlog=$oBlog}

	{include file='navs/nav.blog.tpl'}


	{if $bCloseBlog}
		{$aLang.blog_close_show}
	{else}
		{include file='topics/topic_list.tpl'}
	{/if}
{/block}