<section class="block">
	<header class="block-header">
		<h3>{$aLang.topic_favourite_tags_block}</h3>
	</header>
	
	
	<div class="block-content">
		<ul class="nav nav-pills">
			<li id="block_favourite_topic_tags_item_all" class="active"><a href="#">{$aLang.topic_favourite_tags_block_all}</a></li>
			<li id="block_favourite_topic_tags_item_user"><a href="#">{$aLang.topic_favourite_tags_block_user}</a></li>

			{hook run='block_stream_nav_item'}
		</ul>
		<div id="block_favourite_topic_tags_content_all">
			{if $aFavouriteTopicTags}
				<ul class="tag-cloud">
					{foreach from=$aFavouriteTopicTags item=oTag}
						<li><a class="tag-size-{$oTag->getSize()} {if $sFavouriteTag==$oTag->getText()}tag-current{/if}" title="{$oTag->getCount()}" href="{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:'url'}/">{$oTag->getText()}</a></li>
					{/foreach}
				</ul>
			{else}
				<div class="notice-empty">{$aLang.block_empty_no_tags}</div>
			{/if}
		</div>
		<div id="block_favourite_topic_tags_content_user" style="display: none;">
		{if $aFavouriteTopicUserTags}
			<ul class="tag-cloud">
				{foreach from=$aFavouriteTopicUserTags item=oTag}
					<li><a class="tag-size-{$oTag->getSize()}" title="{$oTag->getCount()}" href="{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:'url'}/">{$oTag->getText()}</a></li>
				{/foreach}
			</ul>
			{else}
			<div class="notice-empty">{$aLang.block_empty_no_tags}</div>
		{/if}
		</div>
	</div>
</section>