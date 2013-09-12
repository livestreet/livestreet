{**
 * Базовый шаблон модальных окон
 *}

{block name='modal_options'}{/block}


<div class="modal {block name='modal_class'}{/block}" id="{block name='modal_id'}{/block}" data-type="modal" {block name='modal_attributes'}{/block}>
	{* Header *}
	{block name='modal_title' hide}
		<header class="modal-header">
			<h3>{$smarty.block.child}</h3>
			<a href="#" class="modal-close" data-type="modal-close"></a>
		</header>
	{/block}

	{block name='modal_header_after'}{/block}

	{* Content *}
	{block name='modal_content' hide}
		<div class="modal-content">
			{$smarty.block.child}
		</div>
	{/block}

	{block name='modal_content_after'}{/block}

	{* Footer *}
	{block name='modal_footer'}
		<div class="modal-footer">
			{block name='modal_footer_begin'}{/block}

			{block name='modal_footer_cancel'}
				<button type="button" class="button" data-type="modal-close">{$aLang.favourite_form_tags_button_cancel}</button>
			{/block}
		</div>
	{/block}

	{block name='modal_footer_after'}{/block}
</div>