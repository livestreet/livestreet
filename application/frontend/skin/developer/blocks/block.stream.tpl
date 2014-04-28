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
	{include 'components/nav/nav.tabs.tpl' sName='block_activity' sActiveItem='comments' sMods='pills' sClasses='' aItems=[
		[ 'name' => 'comments', 'url' => "{router page='ajax'}stream/comment", 'text' => $aLang.block_stream_comments, 'pane' => 'js-tab-pane-stream' ],
		[ 'name' => 'topics',   'url' => "{router page='ajax'}stream/topic",   'text' => $aLang.block_stream_topics,   'pane' => 'js-tab-pane-stream' ]
	]}

	{**
	 * Выпадающее меню
	 * Показывается если в меню что выше пунктов больше установленного значения (по умолчанию - 2)
	 * TODO: dropdown tabs
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
	</ul>
{/block}

{* Контент *}
{block name='block_content_after'}
	<div class="tab-pane" id="js-tab-pane-stream" style="display: block">
		{$sStreamComments}
	</div>
{/block}