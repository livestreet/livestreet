{**
 * Базовый шаблон топика
 *
 * Доступные опции:
 *     noTopicHeader (bool)  - Не выводить шапку
 *     noTopicContent (bool) - Не выводить контент
 *     noTopicFooter (bool)  - Не выводить подвал
 *}

{block name='topic_options'}{/block}

{assign var="oBlog" value=$oTopic->getBlog()}
{assign var="oUser" value=$oTopic->getUser()}
{assign var="oVote" value=$oTopic->getVote()}
{assign var="oFavourite" value=$oTopic->getFavourite()}

<div class="topic topic-type-{$oTopic->getType()} js-topic {block name='topic_class'}{/block}" id="{block name='topic_id'}{/block}" {block name='topic_attributes'}{/block}>
	{* Header *}
	{if !$noTopicHeader}
		<header class="topic-header">
			<h1 class="topic-title word-wrap">
				{if $oTopic->getPublish() == 0}   
					<i class="icon-file" title="{$aLang.topic_unpublish}"></i>
				{/if}
				
				{block name='topic_icon'}{/block}
				
				{if $bTopicList}
					<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
				{else}
					{$oTopic->getTitle()|escape:'html'}
				{/if}
			</h1>
			
			<div class="topic-info">
				<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape:'html'}</a>
				
				<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
					{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}
				</time>

				{if $oTopic->getIsAllowAction()}
					<ul class="actions">
						{if $oTopic->getIsAllowEdit()}
							<li><a href="{$oTopic->getUrlEdit()}" title="{$aLang.topic_edit}" class="actions-edit">{$aLang.topic_edit}</a></li>
						{/if}

						{if $oTopic->getIsAllowDelete()}
							<li><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="actions-delete">{$aLang.topic_delete}</a></li>
						{/if}
					</ul>
				{/if}
			</div>
		</header>
	{/if}

	{block name='topic_header_after'}{/block}


	{* Content *}
	{if !$noTopicContent}
		<div class="topic-content text">
			{hook run='topic_content_begin' topic=$oTopic bTopicList=$bTopicList}

			{block name='topic_content'}{$oTopic->getText()}{/block}

			{hook run='topic_content_end' topic=$oTopic bTopicList=$bTopicList}
		</div>
	{/if}
	
	{block name='topic_content_after'}{/block}


	{* Footer *}
	{if !$noTopicFooter}
		<footer class="topic-footer">
			{block name='topic_footer_begin'}{/block}

			<ul class="topic-tags js-favourite-insert-after-form js-favourite-tags-topic-{$oTopic->getId()}">
				<li>{$aLang.topic_tags}:</li>
				
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
				<li id="vote_area_topic_{$oTopic->getId()}"
					data-type="tooltip-toggle"
					data-param-i-topic-id="{$oTopic->getId()}"
					data-option-url="{router page='ajax'}vote/get/info/"
					class="vote 
							{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
								{if $oTopic->getRating() > 0}
									vote-count-positive
								{elseif $oTopic->getRating() < 0}
									vote-count-negative
								{/if}
							{/if}
							
							{if $oVote} 
								voted
								
								{if $oVote->getDirection() > 0}
									voted-up
								{elseif $oVote->getDirection() < 0}
									voted-down
								{/if}
							{/if}

							{if $bVoteInfoShow}js-tooltip-vote-topic{/if}">
					<div class="vote-up" onclick="return ls.vote.vote({$oTopic->getId()},this,1,'topic');"></div>
					<div class="vote-count" id="vote_total_topic_{$oTopic->getId()}">
						{if $bVoteInfoShow}
							{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
						{else} 
							<a href="#" onclick="return ls.vote.vote({$oTopic->getId()},this,0,'topic');">?</a> 
						{/if}
					</div>
					<div class="vote-down" onclick="return ls.vote.vote({$oTopic->getId()},this,-1,'topic');"></div>
				</li>

				<li class="topic-info-author"><a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
				<li class="topic-info-favourite">
					<div onclick="return ls.favourite.toggle({$oTopic->getId()},this,'topic');" class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}"></div>
					<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}" {if ! $oTopic->getCountFavourite()}style="display: none"{/if}>{$oTopic->getCountFavourite()}</span>
				</li>
				<li class="topic-info-share"><a href="#" class="icon-share js-popover-default" title="{$aLang.topic_share}" data-type="popover-toggle" data-option-target="topic_share_{$oTopic->getId()}"></a></li>
				
				{if $bTopicList}
					<li class="topic-info-comments">
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()} {$oTopic->getCountComment()|declension:$aLang.comment_declension:'russian'}</a>
						{if $oTopic->getCountCommentNew()}<span>+{$oTopic->getCountCommentNew()}</span>{/if}
					</li>
				{/if}

				{block name='topic_footer_info_end'}{/block}
				
				{hook run='topic_show_info' topic=$oTopic}
			</ul>

			
			{if !$bTopicList}
				{hook run='topic_show_end' topic=$oTopic}
			{/if}

			{block name='topic_footer_end'}{/block}
		</footer>
	{/if}
	
	{block name='topic_footer_after'}{/block}
</div>

{block name='topic_topic_after'}{/block}