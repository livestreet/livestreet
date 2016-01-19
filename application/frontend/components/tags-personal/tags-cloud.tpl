{**
 * Избранные теги пользователя
 *
 * @param array  $tags
 * @param object $activeTag
 *}

{component_define_params params=[ 'activeTag', 'tags' ]}

{component 'details'
    classes = 'js-tags-favourite-cloud'
    title   = "{lang 'tags_personal.title'} {if $activeTag}({$activeTag}){/if}"
    content = {component 'tags' template='cloud' tags=$tags active=$activeTag}}