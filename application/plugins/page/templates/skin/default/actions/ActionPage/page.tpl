{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}{$oPage->getTitle()}{/block}

{block name='layout_content'}
	<div class="text">
		{if $oConfig->GetValue('view.tinymce')}
			{$oPage->getText()}
		{else}
			{if $oPage->getAutoBr()}
				{$oPage->getText()|nl2br}
			{else}
				{$oPage->getText()}
			{/if}
		{/if}
	</div>

	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		<br />
		<a href="{router page='page'}admin/edit/{$oPage->getId()}/">{$aLang.plugin.page.admin_action_edit}</a>
	{/if}
{/block}