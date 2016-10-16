{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{capture toolbar_comments}
    <i class="comments-toolbar-update js-toolbar-comments-update" title="{lang 'comments.update'}"></i>
    <div class="comments-toolbar-count js-toolbar-comments-count" title="{lang 'comments.count_new'}"></div>
{/capture}

{component 'toolbar.item'
    html=$smarty.capture.toolbar_comments
    classes='js-comments-toolbar'
    mods='comments'}