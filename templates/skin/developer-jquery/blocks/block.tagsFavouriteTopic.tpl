<section class="block block-type-foldable block-type-favourite-topic">
	<header class="block-header">
		<h3><a href="#" class="link-dotted" onclick="jQuery('#block_favourite_topic_content').toggle(); return false;">{$aLang.topic_favourite_tags_block}</a></h3>
	</header>
	
	
	<div class="block-content" id="block_favourite_topic_content">
		<ul class="nav nav-pills">
			<li id="block_favourite_topic_tags_item_all" class="active"><a href="#">{$aLang.topic_favourite_tags_block_all}</a></li>
			<li id="block_favourite_topic_tags_item_user"><a href="#">{$aLang.topic_favourite_tags_block_user}</a></li>

			{hook run='block_favourite_topic_tags_nav_item'}
		</ul>
		
		
		<div id="block_favourite_topic_tags_content_all">
			{if $aFavouriteTopicTags}
				<ul class="tag-cloud">
					{foreach from=$aFavouriteTopicTags item=oTag}
						<li><a class="tag-size-{$oTag->getSize()} {if $sFavouriteTag==$oTag->getText()}tag-current{/if}" title="{$oTag->getCount()}" href="{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:'url'}/">{$oTag->getText()}</a></li>
					{/foreach}
				</ul>
			{else}
				<div class="notice-empty">{$aLang.block_tags_empty}</div>
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
				<div class="notice-empty">{$aLang.block_tags_empty}</div>
			{/if}
		</div>
	</div>
</section>