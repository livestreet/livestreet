{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_tags}{/block}
{block name='block_type'}tags{/block}

{block name='block_nav'}
	{include 'components/nav/nav.tabs.tpl' sName='block_tags' sActiveItem='all' sMods='pills' sClasses='' aItems=[
		[ 'name' => 'all', 'text' => $aLang.topic_favourite_tags_block_all,  'pane' => 'js-tab-pane-tags-all' ],
		[ 'name' => 'my',  'text' => $aLang.topic_favourite_tags_block_user, 'pane' => 'js-tab-pane-tags-my', 'is_enabled' => !! $oUserCurrent ]
	]}
{/block}

{block name='block_content'}
	{include 'forms/form.search.tags.tpl'}

	<div data-type="tab-panes">
		<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-all" style="display: block">
			{include 'components/tags/tag_cloud.tpl' aTags=$aTags sTagsUrl='{router page=\'tag\'}{$oTag->getText()|escape:\'url\'}/'}
		</div>

		{if $oUserCurrent}
			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-my">
				{include 'components/tags/tag_cloud.tpl' aTags=$aTagsUser sTagsUrl='{router page=\'tag\'}{$oTag->getText()|escape:\'url\'}/'}
			</div>
		{/if}
	</div>
{/block}