{**
 * Блок со списоком блогов
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='title'}{$aLang.block_blogs}{/block}
{block name='type'}blogs{/block}

{block name='nav'}
	<ul class="nav nav-pills js-block-nav" data-type="tabs">
		<li data-type="tab" data-option-url="{router page='ajax'}blogs/top" data-option-target="js-tab-pane-blogs" class="active"><a href="#">{$aLang.block_blogs_top}</a></li>

		{if $oUserCurrent}
			<li data-type="tab" data-option-url="{router page='ajax'}blogs/join" data-option-target="js-tab-pane-blogs"><a href="#">{$aLang.block_blogs_join}</a></li>
			<li data-type="tab" data-option-url="{router page='ajax'}blogs/self" data-option-target="js-tab-pane-blogs"><a href="#">{$aLang.block_blogs_self}</a></li>
		{/if}
	</ul>
{/block}

{block name='content'}
	<div id="js-tab-pane-blogs">
		{$sBlogsTop}
	</div>
{/block}

{block name='footer'}
	<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
{/block}