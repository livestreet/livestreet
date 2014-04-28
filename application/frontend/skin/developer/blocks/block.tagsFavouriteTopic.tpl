{**
 * Избранные теги пользователя.
 * Блок находится в профиле пользователя в разделе "Избранные топики".
 *
 * @styles css/common.css
 *}

<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_favourite_topic_content').toggle(); return false;"><span class="link-dotted">{$aLang.topic_favourite_tags_block}</span></h3>

	<div class="accordion-content" id="block_favourite_topic_content">
		{include 'components/nav/nav.tabs.tpl' sName='block_tags_personal' sActiveItem='all' sMods='pills' sClasses='' aItems=[
			[ 'name' => 'all', 'text' => $aLang.topic_favourite_tags_block_all,  'pane' => 'js-tab-pane-tags-personal-all' ],
			[ 'name' => 'my',  'text' => $aLang.topic_favourite_tags_block_user, 'pane' => 'js-tab-pane-tags-personal-my', 'is_enabled' => !! $oUserCurrent ]
		]}

		<div data-type="tab-panes">
			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-personal-all" style="display: block;">
				{include 'components/tags/tag_cloud.tpl' 
						 aTags       = $aFavouriteTopicTags 
						 sTagsUrl    = '{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:\'url\'}/'
						 sTagsActive = $sFavouriteTag}
			</div>

			<div class="tab-pane" data-type="tab-pane" id="js-tab-pane-tags-personal-my">
				{include 'components/tags/tag_cloud.tpl' 
						 aTags       = $aFavouriteTopicUserTags 
						 sTagsUrl    = '{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:\'url\'}/'
						 sTagsActive = $sFavouriteTag}
			</div>
		</div>
	</div>
</div>