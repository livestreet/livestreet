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
			{include 'components/more/more.tpl'
					 sClasses    = 'more-wall-comments js-more-wall-comments'
					 iCount      = $oPost->getCountReply() - Config::Get('module.wall.count_last_reply')
					 bAppend     = 'false'
					 sAttributes = "data-more-target=\".js-wall-entry-container[data-id={$oPost->getId()}]\"  data-proxy-i-last-id=\"{$aPostComments[0]->getId()}\" data-param-i-target-id=\"{$oPost->getId()}\" "
			}
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