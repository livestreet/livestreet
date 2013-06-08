{extends file='layout.base.tpl'}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.comments_all}</h2>

	{include file='comments/comment_list.tpl'}
{/block}