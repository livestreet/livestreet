{extends file='layout.base.tpl'}

{block name='layout_page_title'}{$aLang.registration_confirm_header}{/block}

{block name='layout_content'}
	{$aLang.registration_confirm_text}<br /><br />

	<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a>
{/block}