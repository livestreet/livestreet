{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{component 'toolbar' template='item'
    classes = "{$smarty.local.classes} js-comments-toolbar"
    mods = 'comments'
    buttons = [
        [
            classes => 'toolbar-comments-update js-toolbar-comments-update',
            attributes => [ 'title' => {lang 'comments.comment.count_new'} ],
            icon => 'comment-update'
        ],
        [
            classes => 'js-toolbar-comments-count',
            attributes => [ 'title' => {lang 'comments.comment.count_new'} ],
            text => '0'
        ]
    ]}