{**
 * Список комментариев
 *
 * @styles css/comments.css
 *}

{foreach $aComments as $oComment}
	{include file='comments/comment.tpl' bList=true}
{/foreach}

{include 'components/pagination/pagination.tpl' aPaging=$aPaging}