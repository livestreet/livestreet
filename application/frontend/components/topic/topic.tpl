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

{extends 'Component@article.article'}

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
	{$blogs = $topic->getBlogs()}

	{if ! $isPreview}
        {foreach $blogs as $blog}
            {if $blog->getType()!='personal'}
                <li class="{$component}-info-item {$component}-info-item--blog">
                    <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
                </li>
            {/if}
        {/foreach}
	{/if}
{/block}


{* Содержимое *}
{block 'article_body'}
	{* Превью *}
	{$previewImage = $topic->getPreviewImageWebPath('900x300crop')}

	{if $previewImage}
		<div class="topic-preview-image">
			<img src="{$previewImage}" />
		</div>
	{/if}

	{* Содержимое родительского шаблона *}
	{$smarty.block.parent}

	{* Дополнительные поля *}
	{block 'topic_content_properties'}
		{if ! $isList}
			{component 'property' template='output.list' properties=$topic->property->getPropertyList()}
		{/if}
	{/block}

	{* Опросы *}
	{block 'topic_content_polls'}
		{if ! $isList}
			{component 'poll' template='list' polls=$topic->getPolls()}
		{/if}
	{/block}
{/block}


{* Теги *}
{block 'article_footer'}
	{if ! $isList && $topic->getTypeObject()->getParam('allow_tags')}
		{$favourite = $topic->getFavourite()}

		{if ! $isPreview}
			{component 'tags-favourite'
				tags          = $topic->getTagsArray()
				tagsFavourite = ( $favourite ) ? $favourite->getTagsArray() : []
				isEditable    = ! $favourite
				targetType    = 'topic'
				targetId      = $topic->getId()}
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
			{$isExpired = strtotime($topic->getDatePublish()) < $smarty.now - Config::Get('acl.vote.topic.limit_time')}

			{component 'vote'
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
			{component 'favourite' classes="js-favourite-{$type}" target=$article}
		</li>

		{* Поделиться *}
		<li class="{$component}-info-item {$component}-info-item--share">
			<a href="#" class="icon-share js-popover-default" title="{$aLang.topic.share}" data-tooltip-target="#topic_share_{$topic->getId()}"></a>
		</li>
	{/if}
{/block}