{assign var="bNoSidebar" value=true}
{include file='header.tpl'}

<div class="topic page">
	<div class="content">
		<h2>{$oPage->getTitle()}</h2>
		
		{if $oConfig->GetValue('view.tinymce')}
			{$oPage->getText()}
		{else}
			{$oPage->getText()|nl2br}
		{/if}
	</div>
</div>

{include file='footer.tpl'}