{assign var="bNoSidebar" value=true}
{include file='header.tpl'}

<div class=topic>
	<div class="content">
		{if $aConfig.view.tinymce}
			{$oPage->getText()}
		{else}
			{$oPage->getText()|nl2br}
		{/if}
	</div>
</div>

{include file='footer.tpl'}