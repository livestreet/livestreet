{**
 * Список комментариев
 *
 * @styles css/comments.css
 *}

{foreach $aComments as $oComment}
	{include file='comments/comment.tpl' bList=true}
{/foreach}

{include file='pagination.tpl' aPaging=$aPaging}