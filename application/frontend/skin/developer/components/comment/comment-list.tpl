{**
 * Список комментариев
 *
 * @param array comments Комментарии
 *}

{include './comment-tree.tpl'
    comments      = $smarty.local.comments
    forbidAdd     = true
    useFavourite  = true
    useVote       = false
    showScroll    = false
    maxLevel      = 0}