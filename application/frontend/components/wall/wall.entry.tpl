{**
 * Стена / Запись (Пост / Комментарий)
 *
 * @param object  $entry     Комментарий
 * @param boolean $showReply Показывать или нет кнопку комментирования
 * @param string  $classes   Классы
 *}

{component_define_params params=[ 'entry', 'type', 'showReply', 'classes' ]}

{component 'comment'
    hookPrefix = 'wall_entry'
    comment    = $entry
    showReply  = $showReply
    useScroll  = false
    attributes = [ 'data-type' => $type, 'data-user-id' => $entry->getUser()->getId() ]
    classes    = "wall-comment js-wall-entry {$classes}"}