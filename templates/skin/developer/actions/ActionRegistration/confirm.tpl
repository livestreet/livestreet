{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.registration_confirm_header}</h2>
	{$aLang.registration_confirm_text}<br /><br />

	<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a>
{/block}