{**
 * Стена / Запись (Пост / Комментарий)
 *
 * @param object  $entry     Комментарий
 * @param boolean $showReply Показывать или нет кнопку комментирования
 * @param string  $classes   Классы
 *}

{include 'components/comment/comment.tpl'
	oComment    = $smarty.local.entry
	bShowReply  = $smarty.local.showReply
	sAttributes = "data-type=\"{$smarty.local.type}\""
	sClasses    = "wall-comment js-wall-entry {$smarty.local.classes}"}