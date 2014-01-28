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
				{if $oTopic->getPublish() == 0}
					<i class="icon-file" title="{$aLang.topic_unpublish}"></i>
				{/if}

				{block name='topic_icon'}{/block}

				{if $bTopicList}
					<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape}</a>
				{else}
					{$oTopic->getTitle()|escape}
				{/if}
			</h1>

			{* Информация *}
			<div class="topic-info">
				<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape}</a>

				<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
					{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}
				</time>
			</div>

			{* Управление *}
			{if $oTopic->getIsAllowAction()}
				<ul class="actions">
					{if $oTopic->getIsAllowEdit()}
						<li>
							<i class="icon-edit"></i>
							<a href="{$oTopic->getUrlEdit()}" title="{$aLang.topic_edit}">{$aLang.topic_edit}</a>
						</li>
					{/if}

					{if $oTopic->getIsAllowDelete()}
						<li>
							<i class="icon-trash"></i>
							<a href="{$oTopic->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}"
							   title="{$aLang.topic_delete}"
							   onclick="return confirm('{$aLang.topic_delete_confirm}');"
							   class="actions-delete">{$aLang.topic_delete}</a>
						</li>
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

			{block name='topic_content_text'}
				{if $bTopicList}
					{$oTopic->getTextShort()}

					{* Кат *}
					{if $oTopic->getTextShort() != $oTopic->getText()}
                        <br/>
                        <a href="{$oTopic->getUrl()}#cut" title="{$aLang.topic_read_more}">
							{$oTopic->getCutText()|default:$aLang.topic_read_more}
                        </a>
					{/if}
				{else}
					{$oTopic->getText()}
				{/if}
			{/block}

			{* Дополнительные поля *}
			{block name='topic_content_properties'}
				{if ! $bTopicList}
					{$aProperties = $oTopic->getPropertyList()}

					{foreach $aProperties as $oProperty}
						<br/>
						{$mValue = $oProperty->getValue()->getValueForDisplay()}

						<b>{$oProperty->getTitle()}</b>: {$mValue}
					{/foreach}
				{/if}
			{/block}

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
			{if ! $bTopicList}
				<ul class="tag-list tag-list-topic js-tags-topic-{$oTopic->getId()}">
					<li class="tag-list-item tag-list-item-label">{$aLang.topic_tags}:</li>

					{strip}
						{foreach $oTopic->getTagsArray() as $sTag}
							<li class="tag-list-item tag-list-item-tag">
								{if ! $sTag@first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape}</a>
							</li>
						{foreachelse}
							<li class="tag-list-item tag-list-item-empty">{$aLang.topic_tags_empty}</li>
						{/foreach}

						{* Персональные теги *}
						{if $oUserCurrent}
							{if $oFavourite}
								{foreach $oFavourite->getTagsArray() as $sTag}
									<li class="tag-list-item tag-list-item-tag tag-list-item-tag-personal js-tag-list-item-tag-personal">
										, <a href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/" 
										     rel="tag" 
										     class="">{$sTag|escape}</a>
									</li>
								{/foreach}
							{/if}

							<li class="tag-list-item tag-list-item-edit js-favourite-tag-edit" data-id="{$oTopic->getId()}" data-type="topic" {if ! $oFavourite}style="display:none;"{/if}>
								<a href="#" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
							</li>
						{/if}
					{/strip}
				</ul>
			{/if}

			{* Информация *}
			<ul class="topic-info clearfix">
				{* Голосование *}
				<li class="topic-info-item topic-info-item-vote">
					{include 'vote.tpl' 
							 sVoteType       = 'topic' 
							 oVoteObject     = $oTopic 
							 sVoteClasses    = 'vote-small vote-white'
							 bVoteIsLocked   = $oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()
							 bVoteShowRating = $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now - $oConfig->GetValue('acl.vote.topic.limit_time')}
				</li>

				{* Автор топика *}
				<li class="topic-info-item topic-info-item-author">
					{include 'user_item.tpl' oUser=$oUser iUserItemAvatarSize=48 sUserItemStyle='rounded'}
				</li>

				{if ! $bTopicList}
					{* Избранное *}
					<li class="topic-info-item topic-info-item-favourite">
						{include 'favourite.tpl' sFavouriteType='topic' oFavouriteObject=$oTopic}
					</li>

					{* Поделиться *}
					<li class="topic-info-item topic-info-item-share"><a href="#" class="icon-share js-popover-default" title="{$aLang.topic_share}" data-tooltip-target="#topic_share_{$oTopic->getId()}"></a></li>
				{/if}

				{* Ссылка на комментарии *}
				{if $bTopicList}
					<li class="topic-info-item topic-info-item-comments">
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()} {$oTopic->getCountComment()|declension:$aLang.comment_declension}</a>
						{if $oTopic->getCountCommentNew()}<span>+{$oTopic->getCountCommentNew()}</span>{/if}
					</li>
				{/if}

				{block name='topic_footer_info_end'}{/block}
				{hook run='topic_show_info' topic=$oTopic}
			</ul>


			{* Всплывающий блок появляющийся при нажатии на кнопку Поделиться *}
			<div class="tooltip" id="topic_share_{$oTopic->getId()}">
				<div class="tooltip-content js-tooltip-content">
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