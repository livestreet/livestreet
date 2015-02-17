{**
 * Тулбар
 * Кнопка прокручивания к следующему/предыдущему топику
 *}

{component 'toolbar' template='item'
    classes = 'js-toolbar-topics'
    mods = 'topic'
    buttons = [
        [
            classes => 'toolbar-topic-prev js-toolbar-topics-prev',
            attributes => [ 'title' => {lang 'toolbar.topic_nav.prev'} ],
            icon => 'arrow-up'
        ],
        [
            classes => 'toolbar-topic-next js-toolbar-topics-next',
            attributes => [ 'title' => {lang 'toolbar.topic_nav.next'} ],
            icon => 'arrow-down'
        ]
    ]}