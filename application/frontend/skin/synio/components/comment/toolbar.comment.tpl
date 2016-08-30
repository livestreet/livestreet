{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{component_define_params params=[ 'mods', 'classes', 'attributes' ]}

{component 'toolbar' template='item'
    classes = "{$classes} js-comments-toolbar"
    mods = 'comments'
    buttons = [
        [
            classes => 'ls-toolbar-comments-update js-toolbar-comments-update',
            attributes => [ 'title' => {lang 'comments.update'} ],
            icon => 'refresh'
        ],
        [
            classes => 'js-toolbar-comments-count',
            attributes => [ 'title' => {lang 'comments.count_new'} ],
            text => '0'
        ]
    ]}