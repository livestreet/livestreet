{include file='header.tpl'}

<div class=topic>
	<div class="content">
		{if $BLOG_USE_TINYMCE}
			{$oPage->getText()}
		{else}
			{$oPage->getText()|nl2br}
		{/if}
	</div>
</div>

{include file='footer.tpl'}