{* 
	MODAL BASE TEMPLATE

	Available options:
	------------------
	noContent (bool) - Don't show content
	noFooter (bool) - Don't show footer
	noCancel (bool) - Don't show cancel button
*}

{block name='options'}{/block}


<div class="modal {block name='class'}{/block}" id="{block name='id'}{/block}" data-type="modal">
	<header class="modal-header">
		<h3>{block name='title'}Modal window{/block}</h3>
		<a href="#" class="modal-close" data-type="modal-close"></a>
	</header>
	
	{block name='header_after'}{/block}

	{if !$noContent}
		<div class="modal-content">
			{block name='content'}{/block}
		</div>
	{/if}
	
	{block name='content_after'}{/block}

	{if !$noFooter}
		<div class="modal-footer">
			{block name='footer'}{/block}
			
			{if !$noCancel}
				<button type="button" class="button" data-type="modal-close" />{$aLang.favourite_form_tags_button_cancel}</button>
			{/if}
		</div>
		
		{block name='footer_after'}{/block}
	{/if}
</div>