{**
 * Избранные теги пользователя.
 * Блок находится в профиле пользователя в разделе "Избранные топики".
 *
 * @styles css/common.css
 *}

<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_favourite_topic_content').toggle(); return false;"><span class="link-dotted">{$aLang.topic_favourite_tags_block}</span></h3>
	
	<div class="accordion-content" id="block_favourite_topic_content">
		<ul class="nav nav-pills" data-type="tabs">
			<li class="active" data-type="tab" data-option-target="js-tab-pane-tags-favourite-all"><a href="#">{$aLang.topic_favourite_tags_block_all}</a></li>
			<li data-type="tab" data-option-target="js-tab-pane-tags-favourite-my"><a href="#">{$aLang.topic_favourite_tags_block_user}</a></li>

			{hook run='block_favourite_topic_tags_nav_item'}
		</ul>
		
		<div data-type="tab-panes">
			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-favourite-all" style="display: block;">
				{if $aFavouriteTopicTags}
					<ul class="tag-cloud word-wrap">
						{foreach $aFavouriteTopicTags as $oTag}
							<li><a class="tag-size-{$oTag->getSize()} {if $sFavouriteTag==$oTag->getText()}tag-current{/if}" title="{$oTag->getCount()}" href="{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:'url'}/">{$oTag->getText()}</a></li>
						{/foreach}
					</ul>
				{else}
					<div class="notice-empty">{$aLang.block_tags_empty}</div>
				{/if}
			</div>
			
			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-favourite-my">
				{if $aFavouriteTopicUserTags}
					<ul class="tag-cloud word-wrap">
						{foreach $aFavouriteTopicUserTags as $oTag}
							<li><a class="tag-size-{$oTag->getSize()}" title="{$oTag->getCount()}" href="{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:'url'}/">{$oTag->getText()}</a></li>
						{/foreach}
					</ul>
				{else}
					<div class="notice-empty">{$aLang.block_tags_empty}</div>
				{/if}
			</div>
		</div>
	</div>
</div>