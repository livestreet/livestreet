{**
 * Список комментариев к записи на стене
 *
 * @param array $aReplyWall Список комментариев
 *}

{foreach $aReplyWall as $oWallComment}
	{include 'actions/ActionProfile/wall.entry.tpl' oWallEntry=$oWallComment bWallEntryShowReply=false sWallEntryClasses='wall-comment'}
{/foreach}