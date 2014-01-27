{**
 * Прямой эфир
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}<a href="{router page='comments'}" title="{$aLang.block_stream_comments_all}">{$aLang.block_stream}</a>{/block}
{block name='block_type'}stream{/block}
{block name='block_class'}block-nopadding{/block}

{* Кнопка обновления *}
{block name='block_header_end'}
	<div class="block-update" id="js-stream-update"></div>
{/block}

{* Навигация *}
{block name='block_nav'}
	{hook run='block_stream_nav_item' assign="sItemsHook"}

	<ul class="nav nav-pills js-block-nav" data-type="tabs" id="js-stream-tabs" {if $sItemsHook}style="display: none;"{/if}>
		<li data-type="tab" data-tab-url="{router page='ajax'}stream/comment" data-tab-target="js-tab-pane-stream" data-name="block-stream-comments" class="active"><a href="#">{$aLang.block_stream_comments}</a></li>
		<li data-type="tab" data-tab-url="{router page='ajax'}stream/topic" data-tab-target="js-tab-pane-stream"><a href="#">{$aLang.block_stream_topics}</a></li>

		{$sItemsHook}
	</ul>
	
	{** 
	 * Выпадающее меню 
	 * Показывается если в меню что выше пунктов больше установленного значения (по умолчанию - 2)
	 *}
	<div
		class="dropdown dropdown-toggle js-dropdown-default"
		id="js-stream-dropdown"
		data-type="dropdown-toggle" 
		data-dropdown-target="js-dropdown-menu-stream"
		data-dropdown-selectable="true"
		{if !$sItemsHook}style="display: none;"{/if}>{$aLang.block_stream_comments}</div>
	
	<ul class="dropdown-menu js-block-nav" id="js-dropdown-menu-stream" data-type="tabs">
		<li class="active" data-type="tab" data-tab-url="{router page='ajax'}stream/comment" data-tab-target="js-tab-pane-stream" data-name="block-stream-comments"><a href="#">{$aLang.block_stream_comments}</a></li>
		<li data-type="tab" data-tab-url="{router page='ajax'}stream/topic" data-tab-target="js-tab-pane-stream"><a href="#">{$aLang.block_stream_topics}</a></li>

		{$sItemsHook}
	</ul>
{/block}

{* Контент *}
{block name='block_content_after'}
	<div class="tab-pane" id="js-tab-pane-stream" style="display: block">
		{$sStreamComments}
	</div>
{/block}