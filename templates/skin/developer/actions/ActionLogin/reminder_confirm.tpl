{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_content'}
	<h2 class="page-header">{$aLang.password_reminder}</h2>
	{$aLang.password_reminder_send_password}
{/block}