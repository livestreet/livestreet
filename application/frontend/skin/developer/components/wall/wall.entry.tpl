{**
 * Стена / Запись (Пост / Комментарий)
 *
 * @param object  $entry     Комментарий
 * @param boolean $showReply Показывать или нет кнопку комментирования
 * @param string  $classes   Классы
 *}

{$entry = $smarty.local.entry}

{component 'comment'
    comment    = $entry
    showReply  = $smarty.local.showReply
    attributes = [ 'data-type' => $smarty.local.type, 'data-user-id' => $entry->getUser()->getId() ]
    classes    = "wall-comment js-wall-entry {$smarty.local.classes}"}