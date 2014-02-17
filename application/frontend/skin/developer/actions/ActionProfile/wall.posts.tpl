{**
 * Список постов на стене
 *
 * @param array $aWall Список постов
 *}

{foreach $aWall as $oPost}
	{$aPostComments = $oPost->getLastReplyWall()}

	{* Запись *}
	{include 'actions/ActionProfile/wall.entry.tpl' oWallEntry=$oPost bWallEntryShowReply=!$aPostComments sWallEntryClasses='wall-post'}

	<div class="wall-comments js-wall-comment-wrapper" data-id="{$oPost->getId()}">
		{* Кнопка подгрузки комментариев *}
		{if count($aPostComments) < $oPost->getCountReply()}
			<div class="get-more get-more-wall-comments js-wall-get-more" data-id="{$oPost->getId()}">
				{$aLang.wall_load_reply_more} <span class="js-wall-get-more-count">{$oPost->getCountReply()}</span> {$oPost->getCountReply()|declension:$aLang.comments.comments_declension}
			</div>
		{/if}

		{* Комментарии *}
		<div class="js-wall-entry-container" data-id="{$oPost->getId()}">
			{if $aPostComments}
				{include 'actions/ActionProfile/wall.comments.tpl' aReplyWall=$aPostComments}
			{/if}
		</div>

		{* Форма добавления комментария *}
		{if $oUserCurrent}
			{include 'actions/ActionProfile/wall.form.tpl' iWallFormId=$oPost->getId() bWallFormDisplay=$aPostComments sWallFormPlaceholder=$aLang.wall_reply_placeholder}
		{/if}
	</div>
{/foreach}