{**
 * Избранные теги пользователя
 *
 * @param array  $tags
 * @param object $activeTag
 *}

{$activeTag = $smarty.local.activeTag}

{component 'details'
    classes = 'js-tags-favourite-cloud'
    title   = "{lang 'tags_personal.title'} {if $activeTag}({$activeTag}){/if}"
    content = {component 'tags' template='cloud' tags=$smarty.local.tags active=$activeTag}}