{**
 * Список комментариев
 *
 * @param array aComments Комментарии
 *}

{include './comment-tree.tpl'
	aComments      = $smarty.local.aComments
	bShowFavourite = true
	bForbidAdd     = true
	bShowVote      = false
	bShowScroll    = false
	iMaxLevel      = 0}