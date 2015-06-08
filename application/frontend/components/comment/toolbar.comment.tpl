{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{component 'toolbar' template='item'
    classes = "{$smarty.local.classes} js-comments-toolbar"
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