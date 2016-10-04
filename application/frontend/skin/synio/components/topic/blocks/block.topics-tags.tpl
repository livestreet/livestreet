{**
 * Теги
 *}

{component_define_params params=[ 'tags', 'tagsUser' ]}

{component 'block'
    title   = {lang 'tags.block_tags.title'}
    classes = 'js-block-default'
    tabs    = [
        'tabs' => [
            [
                'text' => {lang 'tags.block_tags.nav.all'},
                'content' => {component 'tags' template='cloud' tags=$tags}
            ],
            [
                'text' => {lang 'tags.block_tags.nav.favourite'},
                'content' => {component 'tags' template='cloud' tags=$tagsUser},
                'is_enabled' => !! $oUserCurrent
            ]
        ]
    ]}