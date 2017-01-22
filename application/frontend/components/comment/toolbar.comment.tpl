{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{capture toolbar_comments}
    <div class="ls-comments-toolbar-update js-toolbar-comments-update" title="{lang 'comments.update'}">
        {component 'icon' icon='refresh'}
    </div>
    <div class="ls-comments-toolbar-count js-toolbar-comments-count" title="{lang 'comments.count_new'}">0</div>
{/capture}

{component 'toolbar.item'
    html=$smarty.capture.toolbar_comments
    classes='js-comments-toolbar'
    mods='comments'}