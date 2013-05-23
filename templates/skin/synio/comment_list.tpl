{**
 * Список комментариев
 *
 * @styles css/comments.css
 *}

{foreach from=$aComments item=oComment}
	{include file='comment.tpl' bList=true}
{/foreach}

{include file='paging.tpl' aPaging=$aPaging}