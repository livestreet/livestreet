{**
 * Базовый шаблон модальных окон
 *}

{$component = 'modal'}

{block 'modal_options'}
	{$id = $smarty.local.id}
	{$title = $smarty.local.title}
	{$content = $smarty.local.content}
	{$options = $smarty.local.options}
	{$classes = $smarty.local.classes}
	{$mods = $smarty.local.mods}
	{$attributes = $smarty.local.attributes}
{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}
	id="{$id}"
	data-type="modal"
	{cattr prefix='data-lsmodal-' list=$options}>

	{* Шапка *}
	{block 'modal_title'}
		<header class="modal-header">
			{* Заголовок *}
			<h3 class="modal-title">{$title}</h3>

			{* Кнопка закрытия *}
			<button class="modal-close" data-type="modal-close">
				{component 'icon' icon='remove' attributes=[ 'aria-hidden' => 'true' ]}
			</button>
		</header>
	{/block}

	{block 'modal_header_after'}{/block}

	{* Содержимое *}
	{block 'modal_content' hide}
		<div class="modal-body">
			{$content}{$smarty.block.child}
		</div>
	{/block}

	{block 'modal_content_after'}{/block}

	{* Подвал *}
	{block 'modal_footer'}
		<div class="modal-footer">
			{block 'modal_footer_begin'}{/block}

			{block 'modal_footer_cancel'}
				<button type="button" class="button" data-type="modal-close">{$aLang.common.cancel}</button>
			{/block}
		</div>
	{/block}
</div>