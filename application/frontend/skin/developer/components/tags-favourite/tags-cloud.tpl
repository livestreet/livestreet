{**
 * Избранные теги пользователя.
 * Блок находится в профиле пользователя в разделе "Избранные топики".
 *
 * @styles css/common.css
 *}

<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_favourite_topic_content').toggle(); return false;">
		<span class="link-dotted">{lang 'favourite_tags.title'} {if $sFavouriteTag}({$sFavouriteTag}){/if}</span>
	</h3>

	<div class="accordion-content" id="block_favourite_topic_content">
		{include 'components/tags/tag_cloud.tpl'
				 aTags       = $aFavouriteTopicUserTags
				 sTagsUrl    = '{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:\'url\'}/'
				 sTagsActive = $sFavouriteTag}
	</div>
</div>