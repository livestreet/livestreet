{extends file='layouts/layout.base.tpl'}

{block name='layout_page_title'}{$aLang.comments_all}{/block}

{block name='layout_content'}
	{include file='comments/comment_list.tpl'}
{/block}