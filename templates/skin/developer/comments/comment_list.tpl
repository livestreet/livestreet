{**
 * Список комментариев
 *
 * @styles css/comments.css
 *}

{foreach from=$aComments item=oComment}
	{include file='comments/comment.tpl' bList=true}
{/foreach}

{include file='paging.tpl' aPaging=$aPaging}