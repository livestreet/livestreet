{* 
	MODAL BASE TEMPLATE

	Available options:
	------------------
	noTitle (bool) - Hide content
	noContent (bool) - Hide content
	noFooter (bool) - Hide footer
	noCancel (bool) - Hide cancel button
*}

{block name='options'}{/block}


<div class="modal {block name='class'}{/block}" id="{block name='id'}{/block}" data-type="modal" {block name='attributes'}{/block}>
	<header class="modal-header">
		{if !$noTitle}<h3>{block name='title'}Modal window{/block}</h3>{/if}
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
				<button type="button" class="button" data-type="modal-close">{$aLang.favourite_form_tags_button_cancel}</button>
			{/if}
		</div>
		
		{block name='footer_after'}{/block}
	{/if}
</div>