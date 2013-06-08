{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.user_exit_notice}</h2>
{/block}