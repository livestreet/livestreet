{assign var="noSidebar" value=true}
{include file='header.tpl'}

<div class="topic">
	<div class="topic-content text">
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
</div>

{include file='footer.tpl'}