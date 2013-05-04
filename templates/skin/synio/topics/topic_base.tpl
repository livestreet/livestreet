{* 
	TOPIC BASE TEMPLATE

	Available options:
	------------------
	noContent (bool) - Hide content
	noFooter (bool) - Hide footer
*}

{block name='options'}{/block}

{assign var="oBlog" value=$oTopic->getBlog()}
{assign var="oUser" value=$oTopic->getUser()}
{assign var="oVote" value=$oTopic->getVote()}
{assign var="oFavourite" value=$oTopic->getFavourite()}

<div class="topic topic-type-{$oTopic->getType()} js-topic {block name='class'}{/block}" id="{block name='id'}{/block}" {block name='attributes'}{/block}>
	{* Header *}
	<header class="topic-header">
		<h1 class="topic-title word-wrap">
			{if $bTopicList}
				<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
			{else}
				{$oTopic->getTitle()|escape:'html'}
			{/if}

			{if $oTopic->getPublish() == 0}   
				<i class="icon-synio-topic-draft" title="{$aLang.topic_unpublish}"></i>
			{/if}
			
			{block name='icon'}{/block}
		</h1>


		<div class="topic-info">
			<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape:'html'}</a>

			{if $oBlog->getType() != 'personal'}
				<a href="#" data-type="popover-toggle" data-option-url="{router page='ajax'}infobox/info/blog/" data-param-i-blog-id="{$oBlog->getId()}" class="blog-list-info js-popover-blog-info"></a>
			{/if}
		</div>


		{if $oTopic->getIsAllowAction()}
			<ul class="topic-actions">								   
				{if $oTopic->getIsAllowEdit()}
					<li class="edit"><i class="icon-synio-actions-edit"></i><a href="{$oTopic->getUrlEdit()}" title="{$aLang.topic_edit}" class="actions-edit">{$aLang.topic_edit}</a></li>
				{/if}
				
				{if $oTopic->getIsAllowDelete()}
					<li class="delete"><i class="icon-synio-actions-delete"></i><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="actions-delete">{$aLang.topic_delete}</a></li>
				{/if}
			</ul>
		{/if}
	</header>
	
	{block name='header_after'}{/block}


	{* Content *}
	{if !$noContent}
		<div class="topic-content text">
			{hook run='topic_content_begin' topic=$oTopic bTopicList=$bTopicList}

			{block name='content'}{$oTopic->getText()}{/block}

			{hook run='topic_content_end' topic=$oTopic bTopicList=$bTopicList}
		</div>
	{/if}
	
	{block name='content_after'}{/block}


	{* Footer *}
	{if !$noFooter}
		<footer class="topic-footer">
			<ul class="topic-tags js-favourite-insert-after-form js-favourite-tags-topic-{$oTopic->getId()}">
				<li><i class="icon-synio-tags"></i></li>
				
				{strip}
					{if $oTopic->getTagsArray()}
						{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
							<li>{if !$smarty.foreach.tags_list.first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
						{/foreach}
					{else}
						<li>{$aLang.topic_tags_empty}</li>
					{/if}
					
					{if $oUserCurrent}
						{if $oFavourite}
							{foreach from=$oFavourite->getTagsArray() item=sTag name=tags_list_user}
								<li class="topic-tags-user js-favourite-tag-user">, <a rel="tag" href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
							{/foreach}
						{/if}
						
						<li class="topic-tags-edit js-favourite-tag-edit" {if !$oFavourite}style="display:none;"{/if}>
							<a href="#" onclick="return ls.favourite.showEditTags({$oTopic->getId()},'topic',this);" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
						</li>
					{/if}
				{/strip}
			</ul>
			
			
			{* Share block *}
			<div class="popover" data-type="popover-target" id="topic_share_{$oTopic->getId()}">
				<div class="popover-arrow"></div><div class="popover-arrow-inner"></div>
				<div class="popover-content" data-type="popover-content">
					{hookb run="topic_share" topic=$oTopic bTopicList=$bTopicList}
						<div class="yashare-auto-init" data-yashareTitle="{$oTopic->getTitle()|escape:'html'}" data-yashareLink="{$oTopic->getUrl()}" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus"></div>
					{/hookb}
				</div>
			</div>


			{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
				{assign var="bVoteInfoShow" value=true}
			{/if}

			<ul class="topic-info">
				<li class="topic-info-author">
					<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
					<a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
				<li class="topic-info-date">
					<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
						{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
					</time>
				</li>
				<li class="topic-info-share js-popover-default" data-type="popover-toggle" data-option-target="topic_share_{$oTopic->getId()}">
					<i class="icon-synio-share-blue" title="{$aLang.topic_share}"></i>
				</li>
				<li class="topic-info-favourite" onclick="return ls.favourite.toggle({$oTopic->getId()},$('#fav_topic_{$oTopic->getId()}'),'topic');">
					<i id="fav_topic_{$oTopic->getId()}" class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}"></i>
					<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}" {if ! $oTopic->getCountFavourite()}style="display: none"{/if}>{if $oTopic->getCountFavourite()>0}{$oTopic->getCountFavourite()}{/if}</span>
				</li>
			
				{if $bTopicList}
					<li class="topic-info-comments">
						{if $oTopic->getCountCommentNew()}
							<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}" class="new">
								<i class="icon-synio-comments-green-filled"></i>
								<span>{$oTopic->getCountComment()}</span>
								<span class="count">+{$oTopic->getCountCommentNew()}</span>
							</a>
						{else}
							<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">
								{if $oTopic->getCountComment()}
									<i class="icon-synio-comments-green-filled"></i>
								{else}
									<i class="icon-synio-comments-blue"></i>
								{/if}
								
								<span>{$oTopic->getCountComment()}</span>
							</a>
						{/if}
					</li>
				{/if}

				<li class="topic-info-vote">
					<div id="vote_area_topic_{$oTopic->getId()}" 
						 data-type="tooltip-toggle"
						 data-param-i-topic-id="{$oTopic->getId()}"
						 data-option-url="{router page='ajax'}vote/get/info/"
						 class="vote-topic
								{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
									{if $oTopic->getRating() > 0}
										vote-count-positive
									{elseif $oTopic->getRating() < 0}
										vote-count-negative
									{elseif $oTopic->getRating() == 0}
										vote-count-zero
									{/if}
								{/if}
								
								{if !$oUserCurrent or ($oUserCurrent && $oTopic->getUserId() != $oUserCurrent->getId())}
									vote-not-self
								{/if}
								
								{if $oVote} 
									voted
									
									{if $oVote->getDirection() > 0}
										voted-up
									{elseif $oVote->getDirection() < 0}
										voted-down
									{elseif $oVote->getDirection() == 0}
										voted-zero
									{/if}
								{else}
									not-voted
								{/if}
								
								{if (strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time') && !$oVote) || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId())}
									vote-nobuttons
								{/if}
								
								{if strtotime($oTopic->getDateAdd()) > $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
									vote-not-expired
								{/if}

								{if $bVoteInfoShow}js-tooltip-vote-topic{/if}">
						<div class="vote-item vote-down" onclick="return ls.vote.vote({$oTopic->getId()},this,-1,'topic');"><span><i></i></span></div>
						<div class="vote-item vote-count">
							<span id="vote_total_topic_{$oTopic->getId()}">
								{if $bVoteInfoShow}
									{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
								{else}
									<i onclick="return ls.vote.vote({$oTopic->getId()},this,0,'topic');"></i>
								{/if}
							</span>
						</div>
						<div class="vote-item vote-up" onclick="return ls.vote.vote({$oTopic->getId()},this,1,'topic');"><span><i></i></span></div>
					</div>
				</li>
				
				{hook run='topic_show_info' topic=$oTopic}
			</ul>

			
			{if !$bTopicList}
				{hook run='topic_show_end' topic=$oTopic}
			{/if}
		</footer>
		
		{block name='footer_after'}{/block}
	{/if}
</div>

{block name='topic_after'}{/block}