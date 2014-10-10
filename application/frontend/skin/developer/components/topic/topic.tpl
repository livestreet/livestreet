{**
 * Базовый шаблон топика
 * Используется также для отображения превью топика
 *
 * @param object  $topic
 * @param boolean $isList
 * @param boolean $isPreview
 *
 * @styles assets/css/topic.css
 * @scripts <framework>/js/livestreet/topic.js
 *}

{extends 'components/article/article.tpl'}

{block 'article_options'}
	{$article = $smarty.local.topic}
	{$topic = $article}

	{$smarty.block.parent}

	{$classes = "{$classes} topic js-topic"}
{/block}


{* Иконки в заголовке топика *}
{block 'article_title' prepend}
	{if $topic->getPublish() == 0}
		<i class="icon-file" title="{$aLang.topic.is_draft}"></i>
	{/if}
{/block}


{* Название блога *}
{block 'article_header_info' prepend}
	{$blog = $topic->getBlog()}

	{if ! $isPreview}
		<li class="{$component}-info-item {$component}-info-item--blog">
			<a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
		</li>
	{/if}
{/block}


{* Название блога *}
{block 'article_body' append}
	{* Дополнительные поля *}
	{block 'topic_content_properties'}
		{if ! $isList}
			{$properties = $topic->property->getPropertyList()}
			{$info = []}

			{foreach $properties as $property}
				{$mValue = $property->getValue()->getValueForDisplay()}

				{$info[] = [
					'label'   => $property->getTitle(),
					'content' => $property->getValue()->getValueForDisplay()
				]}
			{/foreach}

			{include 'components/info-list/info-list.tpl' aInfoList=$info}
		{/if}
	{/block}

	{* Опросы *}
	{block 'topic_content_polls'}
		{if ! $isList}
			{include 'components/poll/poll.list.tpl' polls=$topic->getPolls()}
		{/if}
	{/block}
{/block}


{* Теги *}
{block 'article_footer'}
	{if ! $isList and $topic->getTypeObject()->getParam('allow_tags')}
		{$favourite = $topic->getFavourite()}

		{if ! $isPreview}
			{include 'components/tags/tag_list.tpl'
				aTags              = $topic->getTagsArray()
				bTagsUseFavourite  = true
				showEditButton     = ! $favourite
				aTagsFavourite     = ($favourite) ? $favourite->getTagsArray() : []
				sTagsFavouriteType = 'topic'
				iTagsFavouriteId   = $topic->getId()}
		{/if}
	{/if}

	{$smarty.block.parent}

	{* Всплывающий блок появляющийся при нажатии на кнопку Поделиться *}
	{if ! $isPreview}
		<div class="tooltip" id="topic_share_{$topic->getId()}">
			<div class="tooltip-content js-tooltip-content">
				{hookb run="topic_share" topic=$topic isList=$isList}
					<div class="yashare-auto-init" data-yashareTitle="{$topic->getTitle()|escape}" data-yashareLink="{$topic->getUrl()}" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus"></div>
				{/hookb}
			</div>
		</div>
	{/if}
{/block}


{* Информация *}
{block 'article_footer_info_items'}
	{* Голосование *}
	{if ! $isPreview}
		<li class="{$component}-info-item {$component}-info-item--vote">
			{$isExpired = strtotime($topic->getDateAdd()) < $smarty.now - Config::Get('acl.vote.topic.limit_time')}

			{include 'components/vote/vote.tpl'
					 target     = $topic
					 classes    = 'js-vote-topic'
					 mods       = 'small white topic'
					 useAbstain = true
					 isLocked   = ( $oUserCurrent && $topic->getUserId() == $oUserCurrent->getId() ) || $isExpired
					 showRating = $topic->getVote() || ($oUserCurrent && $topic->getUserId() == $oUserCurrent->getId()) || $isExpired}
		</li>
	{/if}

	{$smarty.block.parent}

	{if ! $isList && ! $isPreview}
		{* Избранное *}
		<li class="{$component}-info-item {$component}-info-item--favourite">
			{include 'components/favourite/favourite.tpl' classes="js-favourite-{$type}" target=$article}
		</li>

		{* Поделиться *}
		<li class="{$component}-info-item {$component}-info-item--share">
			<a href="#" class="icon-share js-popover-default" title="{$aLang.topic.share}" data-tooltip-target="#topic_share_{$topic->getId()}"></a>
		</li>
	{/if}
{/block}