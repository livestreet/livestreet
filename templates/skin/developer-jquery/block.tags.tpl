<section class="block">
	<header class="block-header">
		<h3>{$aLang.block_tags}</h3>
	</header>
	
	
	<div class="block-content">
		<ul class="nav nav-pills">
			<li id="block_tags_item_all" class="active"><a href="#">{$aLang.topic_favourite_tags_block_all}</a></li>
			{if $oUserCurrent}
				<li id="block_tags_item_user"><a href="#">{$aLang.topic_favourite_tags_block_user}</a></li>
			{/if}

			{hook run='block_tags_nav_item'}
		</ul>

		<form action="" method="GET" class="js-tag-search-form">
			<input type="text" name="tag" value="" class="autocomplete-tags js-tag-search" />
		</form>

		<div id="block_tags_content_all">
			{if $aTags}
				<ul class="tag-cloud">
					{foreach from=$aTags item=oTag}
						<li><a class="tag-size-{$oTag->getSize()}" href="{router page='tag'}{$oTag->getText()|escape:'url'}/">{$oTag->getText()|escape:'html'}</a></li>
					{/foreach}
				</ul>
			{else}
				<div class="notice-empty">{$aLang.block_empty_no_tags}</div>
			{/if}
		</div>

		{if $oUserCurrent}
			<div id="block_tags_content_user" style="display: none;">
				{if $aTagsUser}
					<ul class="tag-cloud">
						{foreach from=$aTagsUser item=oTag}
							<li><a class="tag-size-{$oTag->getSize()}" href="{router page='tag'}{$oTag->getText()|escape:'url'}/">{$oTag->getText()|escape:'html'}</a></li>
						{/foreach}
					</ul>
					{else}
					<div class="notice-empty">{$aLang.block_empty_no_tags}</div>
				{/if}
			</div>
		{/if}
	</div>
</section>