{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{capture 'toolbar_item'}
    <div class="toolbar-comments-update js-toolbar-comments-update"><i></i></div>
    <div class="toolbar-comments-count js-toolbar-comments-count" style="display: none;" title="{$aLang.comments.comment.count_new}"></div>
{/capture}

{component 'toolbar' template='item'
    classes = 'js-comments-toolbar'
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
            text => '12'
        ]
    ]}