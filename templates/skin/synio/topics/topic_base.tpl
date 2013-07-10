{**
 * Базовый шаблон топика
 *
 * @styles assets/css/topic.css
 * @scripts <framework>/js/livestreet/topic.js
 *}

{block name='topic_options'}{/block}

{$oBlog = $oTopic->getBlog()}
{$oUser = $oTopic->getUser()}
{$oVote = $oTopic->getVote()}
{$oFavourite = $oTopic->getFavourite()}

<article class="topic topic-type-{$oTopic->getType()} js-topic {if ! $bTopicList}topic-single{/if} {block name='topic_class'}{/block}" id="{block name='topic_id'}{/block}" {block name='topic_attributes'}{/block}>
	{**
	 * Хидер
	 *}
	{block name='topic_header'}
		<header class="topic-header">
			{* Заголовок *}
			<h1 class="topic-title word-wrap">
				{if $bTopicList}
					<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape}</a>
				{else}
					{$oTopic->getTitle()|escape}
				{/if}

				{if $oTopic->getPublish() == 0}   
					<i class="icon-synio-topic-draft" title="{$aLang.topic_unpublish}"></i>
				{/if}
				
				{block name='topic_icon'}{/block}
			</h1>

			{* Информация *}
			<div class="topic-info">
				<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape}</a>

				{if $oBlog->getType() != 'personal'}
					<a href="#" data-type="popover-toggle" data-option-url="{router page='ajax'}infobox/info/blog/" data-param-i-blog-id="{$oBlog->getId()}" class="blog-list-info js-popover-blog-info"></a>
				{/if}
			</div>

			{* Управление *}
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
	{/block}
	
	{block name='topic_header_after'}{/block}


	{**
	 * Текст
	 *}
	{block name='topic_content'}
		<div class="topic-content text">
			{hook run='topic_content_begin' topic=$oTopic bTopicList=$bTopicList}

			{block name='topic_content_text'}{$oTopic->getText()}{/block}

			{hook run='topic_content_end' topic=$oTopic bTopicList=$bTopicList}
		</div>
	{/block}
	
	{block name='topic_content_after'}{/block}


	{**
	 * Футер
	 *}
	{block name='topic_footer'}
		<footer class="topic-footer">
			{block name='topic_footer_begin'}{/block}
			
			{* Теги *}
			<ul class="topic-tags js-favourite-insert-after-form js-favourite-tags-topic-{$oTopic->getId()}">
				<li><i class="icon-synio-tags"></i></li>
				
				{strip}
					{if $oTopic->getTagsArray()}
						{foreach $oTopic->getTagsArray() as $sTag}
							<li>{if ! $sTag@first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape}</a></li>
						{/foreach}
					{else}
						<li>{$aLang.topic_tags_empty}</li>
					{/if}
					
					{if $oUserCurrent}
						{if $oFavourite}
							{foreach $oFavourite->getTagsArray() as $sTag}
								<li class="topic-tags-user js-favourite-tag-user">, <a rel="tag" href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/">{$sTag|escape}</a></li>
							{/foreach}
						{/if}
						
						<li class="topic-tags-edit js-favourite-tag-edit" {if !$oFavourite}style="display:none;"{/if}>
							<a href="#" onclick="return ls.favourite.showEditTags({$oTopic->getId()},'topic',this);" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
						</li>
					{/if}
				{/strip}
			</ul>


			{* Информация *}
			<ul class="topic-info">
				{* Автор топика *}
				<li class="topic-info-author">
					<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
					<a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>

				{* Дата публикации *}
				<li class="topic-info-date">
					<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
						{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
					</time>
				</li>

				{* Поделиться *}
				<li class="topic-info-share js-popover-default" data-type="popover-toggle" data-option-target="topic_share_{$oTopic->getId()}">
					<i class="icon-synio-share-blue" title="{$aLang.topic_share}"></i>
				</li>

				{* Избранное *}
				<li class="topic-info-favourite" onclick="return ls.favourite.toggle({$oTopic->getId()},$('#fav_topic_{$oTopic->getId()}'),'topic');">
					<i id="fav_topic_{$oTopic->getId()}" 
					   class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}" 
					   title="{if $oTopic->getIsFavourite()}{$aLang.talk_favourite_del}{else}{$aLang.talk_favourite_add}{/if}"></i>
					<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}" {if ! $oTopic->getCountFavourite()}style="display: none"{/if}>{if $oTopic->getCountFavourite()>0}{$oTopic->getCountFavourite()}{/if}</span>
				</li>
			
				{* Ссылка на комментарии *}
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

				{block name='topic_footer_info_end'}{/block}

				{* Голосование *}
				<li class="topic-info-vote">
					{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
						{$bShowVoteInfo = true}
					{/if}

					<div data-type="tooltip-toggle"
						 data-param-i-topic-id="{$oTopic->getId()}"
						 data-option-url="{router page='ajax'}vote/get/info/"
						 data-vote-type="topic"
						 data-vote-id="{$oTopic->getId()}"
						 class="vote-topic js-vote 
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

								{if $bShowVoteInfo}js-tooltip-vote-topic{/if}">
						<div class="vote-item vote-down js-vote-down"><span><i></i></span></div>
						<div class="vote-item vote-count">
							<span class="js-vote-rating">
								{if $bShowVoteInfo}
									{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
								{else}
									<i class="js-vote-abstain"></i>
								{/if}
							</span>
						</div>
						<div class="vote-item vote-up js-vote-up"><span><i></i></span></div>
					</div>
				</li>

				{block name='topic_footer_end'}{/block}
				
				{hook run='topic_show_info' topic=$oTopic}
			</ul>
			
			
			{* Всплывающий блок появляющийся при нажатии на кнопку Поделиться *}
			<div class="popover" data-type="popover-target" id="topic_share_{$oTopic->getId()}">
				<div class="popover-arrow"></div><div class="popover-arrow-inner"></div>
				<div class="popover-content" data-type="popover-content">
					{hookb run="topic_share" topic=$oTopic bTopicList=$bTopicList}
						<div class="yashare-auto-init" data-yashareTitle="{$oTopic->getTitle()|escape}" data-yashareLink="{$oTopic->getUrl()}" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus"></div>
					{/hookb}
				</div>
			</div>

			
			{if ! $bTopicList}
				{hook run='topic_show_end' topic=$oTopic}
			{/if}

			{block name='topic_footer_end'}{/block}
		</footer>
	{/block}
	
	{block name='topic_footer_after'}{/block}
</article>

{block name='topic_topic_after'}{/block}