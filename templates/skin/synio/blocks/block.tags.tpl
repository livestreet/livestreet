{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
{/block}

{block name='title'}{$aLang.block_tags}{/block}
{block name='type'}tags{/block}

{block name='nav'}
	<ul class="nav nav-pills" data-type="tabs">
		<li class="active" data-type="tab" data-option-target="js-tab-pane-all"><a href="#">{$aLang.topic_favourite_tags_block_all}</a></li>
		{if $oUserCurrent}
			<li data-type="tab" data-option-target="js-tab-pane-my"><a href="#">{$aLang.topic_favourite_tags_block_user}</a></li>
		{/if}

		{hook run='block_tags_nav_item'}
	</ul>
{/block}

{block name='content'}
	<form action="" method="GET" class="js-tag-search-form search-form-wrapper">
		<div class="search-input-wrapper">
			<input type="text" name="tag" placeholder="{$aLang.block_tags_search}" value="" class="input-text input-width-full autocomplete-tags js-tag-search" />
			<div class="input-submit" onclick="jQuery('.js-tag-search-form').submit()"></div>
		</div>
	</form>

	<div data-type="tab-content">
		<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-all" style="display: block">
			{if $aTags}
				<ul class="tag-cloud word-wrap">
					{foreach from=$aTags item=oTag}
						<li><a class="tag-size-{$oTag->getSize()}" href="{router page='tag'}{$oTag->getText()|escape:'url'}/">{$oTag->getText()|escape:'html'}</a></li>
					{/foreach}
				</ul>
			{else}
				<div class="notice-empty">{$aLang.block_tags_empty}</div>
			{/if}
		</div>

		{if $oUserCurrent}
			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-my">
				{if $aTagsUser}
					<ul class="tag-cloud word-wrap">
						{foreach from=$aTagsUser item=oTag}
							<li><a class="tag-size-{$oTag->getSize()}" href="{router page='tag'}{$oTag->getText()|escape:'url'}/">{$oTag->getText()|escape:'html'}</a></li>
						{/foreach}
					</ul>
				{else}
					<div class="notice-empty">{$aLang.block_tags_empty}</div>
				{/if}
			</div>
		{/if}
	</div>
{/block}