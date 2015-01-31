{**
 * Избранные теги пользователя
 *
 * @param array  $tags
 * @param object $user
 * @param object $activeTag
 *}

{$user = $smarty.local.user}
{$activeTag = $smarty.local.activeTag}

{component 'tags' template='cloud'
	tags   = $smarty.local.tags
	url    = '{$user->getUserWebPath()}favourites/topics/tag/{$tag->getText()|escape:\'url\'}/'
	active = $activeTag
	assign = tags}

{component 'accordion' classes='js-tags-favourite-accordion' items=[[
    'title' => "{lang 'favourite_tags.title'} {if $activeTag}({$activeTag}){/if}",
    'content' => $tags
]]}