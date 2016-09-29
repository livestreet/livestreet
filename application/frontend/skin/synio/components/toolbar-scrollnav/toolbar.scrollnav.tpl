{**
 * Тулбар
 * Кнопка прокручивания к следующему/предыдущему топику
 *}

{capture toolbar_scroll_nav}
    <i class="ls-toolbar-icon-prev js-toolbar-topics-prev" title="{lang 'toolbar.topic_nav.prev'}"></i>
    <i class="ls-toolbar-icon-next js-toolbar-topics-next" title="{lang 'toolbar.topic_nav.next'}"></i>
{/capture}

{component 'toolbar.item'
    html=$smarty.capture.toolbar_scroll_nav
    classes='js-toolbar-topics'
    mods='topic'}