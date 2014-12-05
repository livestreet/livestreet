{**
 * Стена
 *}

{extends 'layouts/layout.user.tpl'}

{block 'layout_user_page_title'}
    {lang name='wall.title'}
{/block}

{block 'layout_content' append}
    {insert name='block' block='wall' params=[
        'classes' => 'js-wall-default',
        'user_id' => $oUserProfile->getId()
    ]}
{/block}