{**
 * Избранные теги пользователя.
 * Блок находится в профиле пользователя в разделе "Избранные топики".
 *
 * @styles css/common.css
 *}

{include 'components/tags/tag_cloud.tpl'
    aTags       = $aFavouriteTopicUserTags
    sTagsUrl    = '{$oFavouriteUser->getUserWebPath()}favourites/topics/tag/{$oTag->getText()|escape:\'url\'}/'
    sTagsActive = $sFavouriteTag
    assign      = tags}

{include 'components/accordion/accordion.tpl' classes='js-tags-favourite-accordion' items=[[
    'title' => "{lang 'favourite_tags.title'} {if $sFavouriteTag}({$sFavouriteTag}){/if}",
    'content' => $tags
]]}