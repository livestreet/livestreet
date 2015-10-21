{**
 * Избранные топики пользователя
 *
 * @param array $topics
 * @param array $paging
 * @param array $activeFavouriteTag
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='user.favourites.title'}
{/block}

{block 'layout_content' append}
    {include 'navs/nav.user.favourite.tpl'}

    {* Блок с тегами избранного *}
    {if $oUserCurrent && $oUserCurrent->getId() == $oUserProfile->getId()}
        {insert name='block' block='tagsPersonalTopic' params=[
            'user' => $oUserProfile,
            'activeTag' => $activeFavouriteTag
        ]}
    {/if}

    {component 'topic' template='list' topics=$topics paging=$paging}
{/block}