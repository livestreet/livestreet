{**
 * Базовый шаблон модальных окон
 *}

{block 'modal_options'}{/block}


<div class="modal {block 'modal_class'}{/block}" id="{block 'modal_id'}{/block}" data-type="modal" {block 'modal_attributes'}{/block}>
	{* Header *}
	{block 'modal_title' hide}
		<header class="modal-header">
			<h3 class="modal-title">{$smarty.block.child}</h3>
			<span class="modal-close" data-type="modal-close"></span>
		</header>
	{/block}

	{block 'modal_header_after'}{/block}

	{* Content *}
	{block 'modal_content' hide}
		<div class="modal-content">
			{$smarty.block.child}
		</div>
	{/block}

	{block 'modal_content_after'}{/block}

	{* Footer *}
	{block 'modal_footer'}
		<div class="modal-footer">
			{block 'modal_footer_begin'}{/block}

			{block 'modal_footer_cancel'}
				<button type="button" class="button" data-type="modal-close">{$aLang.common.cancel}</button>
			{/block}
		</div>
	{/block}

	{block 'modal_footer_after'}{/block}
</div>