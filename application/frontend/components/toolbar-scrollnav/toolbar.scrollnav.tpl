{**
 * Тулбар
 * Кнопка прокручивания к следующему/предыдущему топику
 *}

<div class="js-toolbar-topics">
    {component 'toolbar.item'
        icon='arrow-up'
        classes='js-toolbar-topics-prev'
        attributes=[ 'title' => {lang 'toolbar.topic_nav.prev'} ]}

    {component 'toolbar.item'
        icon='arrow-down'
        classes='js-toolbar-topics-next'
        attributes=[ 'title' => {lang 'toolbar.topic_nav.next'} ]}
</div>