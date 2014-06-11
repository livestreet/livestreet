{**
 * Стена / Запись (Пост / Комментарий)
 *
 * @param object  $oWallEntry          Комментарий
 * @param boolean $bWallEntryShowReply Показывать или нет кнопку комментирования
 * @param string  $sWallEntryClasses   Классы
 *}

{include 'components/comment/comment.tpl'
	oComment   = $oWallEntry
	bShowReply = $bWallEntryShowReply
	sClasses   = "wall-comment js-wall-comment $sWallEntryClasses"}