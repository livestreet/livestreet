{**
 * Тулбар
 * Кнопка прокрутки страницы вверх
 *}

{component 'toolbar' template='item'
    classes='toolbar-item--scrollup js-toolbar-scrollup'
    attributes=[ 'style' => 'display: none' ]
    buttons=[[
        'icon' => 'chevron-up',
        'attributes' => [ 'title' => {lang 'toolbar.scrollup.title'}, 'id' => 'toolbar_scrollup' ]
    ]]}