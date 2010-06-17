{assign var="noSidebar" value=true}
{include file='header.tpl'}

<div>
	<div>
		{if $oConfig->GetValue('view.tinymce')}
			{$oPage->getText()}
		{else}
			{$oPage->getText()|nl2br}
		{/if}
	</div>
</div>

{include file='footer.tpl'}