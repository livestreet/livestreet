{* 
	MODAL BASE TEMPLATE

	Available options:
	------------------
	noTitle (bool) - Hide content
	noContent (bool) - Hide content
	noFooter (bool) - Hide footer
	noCancel (bool) - Hide cancel button
*}

{block name='modal_options'}{/block}


<div class="modal {block name='modal_class'}{/block}" id="{block name='modal_id'}{/block}" data-type="modal" {block name='modal_attributes'}{/block}>
	<header class="modal-header">
		{if !$noTitle}<h3>{block name='modal_title'}Modal window{/block}</h3>{/if}
		<a href="#" class="modal-close" data-type="modal-close"></a>
	</header>
	
	{block name='modal_header_after'}{/block}
	

	{if !$noContent}
		<div class="modal-content">
			{block name='modal_content'}{/block}
		</div>
	{/if}
	
	{block name='modal_content_after'}{/block}


	{if !$noFooter}
		<div class="modal-footer">
			{block name='modal_footer'}{/block}
			
			{if !$noCancel}
				<button type="button" class="button" data-type="modal-close">{$aLang.favourite_form_tags_button_cancel}</button>
			{/if}
		</div>	
	{/if}
	
	{block name='modal_footer_after'}{/block}
</div>