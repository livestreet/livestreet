{**
 * Базовый шаблон топика
 *
 * @styles assets/css/topic.css
 * @scripts <framework>/js/livestreet/topic.js
 *}

{block 'entry_options'}{/block}

{$oUser = $oEntry->getUser()}

{block 'entry'}
	<article class="topic topic-type-{($oEntry->getType()) ? $oEntry->getType() : $sEntryType} js-topic {if ! $bTopicList}topic-single{/if} {block 'entry_class'}{/block}" 
			 id="{block 'entry_id'}{/block}" 
			 {block 'entry_attributes'}{/block}>
		{**
		 * Хидер
		 *}
		{block 'entry_header'}
			<header class="topic-header">
				{* Заголовок *}
				<h1 class="topic-title word-wrap">
					{block 'entry_title'}
						{if $bTopicList}
							<a href="{$oEntry->getUrl()}">{$oEntry->getTitle()|escape}</a>
						{else}
							{$oEntry->getTitle()|escape}
						{/if}
					{/block}
				</h1>

				{* Информация *}
				<ul class="topic-info">
					{block 'entry_header_info'}
						<li class="topic-info-item topic-info-item-date">
							<time datetime="{date_format date=$oEntry->getDateAdd() format='c'}" title="{date_format date=$oEntry->getDateAdd() format='j F Y, H:i'}">
								{date_format date=$oEntry->getDateAdd() format="j F Y, H:i"}
							</time>
						</li>
					{/block}
				</ul>

				{* Управление *}
				{if $oEntry->getIsAllowAction()}
					{$aActionbarItems = [
						[ 'icon' => 'icon-edit', 'url' => $oEntry->getUrlEdit(), 'text' => $aLang.common.edit, 'show' => $oEntry->getIsAllowEdit() ],
						[ 'icon' => 'icon-trash', 'url' => "{$oEntry->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => $aLang.common.remove, 'show' => $oEntry->getIsAllowDelete() ]
					]}

					{block 'entry_header_actions'}{/block}

					{include 'components/actionbar/actionbar.tpl' aItems=$aActionbarItems}
				{/if}
			</header>
		{/block}


		{**
		 * Текст
		 *}
		{block 'entry_body'}
			<div class="topic-content text">
				{block 'entry_content_text'}
					{if $bTopicList}
						{$oEntry->getTextShort()}

						{* Кат *}
						{if $oEntry->getTextShort() != $oEntry->getText()}
	                        <br/>
	                        <a href="{$oEntry->getUrl()}#cut" title="{$aLang.topic_read_more}">
								{$oEntry->getCutText()|default:$aLang.topic_read_more}
	                        </a>
						{/if}
					{else}
						{$oEntry->getText()}
					{/if}
				{/block}
			</div>
		{/block}


		{**
		 * Футер
		 *}
		{block 'entry_footer'}
			<footer class="topic-footer">
				{* Информация *}
				{block 'entry_footer_info'}
					<ul class="topic-info clearfix">
						{block 'entry_footer_info_items'}
							{* Автор топика *}
							<li class="topic-info-item topic-info-item-author">
								{include 'user_item.tpl' oUser=$oUser iUserItemAvatarSize=48 sUserItemStyle='rounded'}
							</li>

							{if ! $bTopicList}
								{* Избранное *}
								<li class="topic-info-item topic-info-item-favourite">
									{include 'favourite.tpl' sFavouriteType=$sEntryType oFavouriteObject=$oEntry}
								</li>
							{/if}

							{* Ссылка на комментарии *}
							{if $bTopicList}
								<li class="topic-info-item topic-info-item-comments">
									<a href="{$oEntry->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oEntry->getCountComment()} {$oEntry->getCountComment()|declension:$aLang.comments.comments_declension}</a>
									{if $oEntry->getCountCommentNew()}<span>+{$oEntry->getCountCommentNew()}</span>{/if}
								</li>
							{/if}
						{/block}
					</ul>
				{/block}
			</footer>
		{/block}
	</article>
{/block}