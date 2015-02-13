{**
 * Теги
 *}

{component 'block'
    title   = {lang 'tags.block_tags.title'}
    classes = 'js-block-default'
    footer  = {component 'tags' template='search-form' mods='light'}
    tabs    = [
        'tabs' => [
            [
                'text' => {lang 'tags.block_tags.nav.all'},
                'content' => {component 'tags' template='cloud' tags=$smarty.local.tags url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/'}
            ],
            [
                'text' => {lang 'tags.block_tags.nav.favourite'},
                'content' => {component 'tags' template='cloud' tags=$smarty.local.tagsUser url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/'},
                'is_enabled' => !! $oUserCurrent
            ]
        ]
    ]}