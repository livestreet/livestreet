{**
 * Список комментариев
 *
 * @param array comments Комментарии
 *
 * TODO: Добавить путь до комментария
 *}

{include './comment-tree.tpl'
    comments     = $smarty.local.comments
    forbidAdd    = true
    useFavourite = true
    useVote      = false
    useScroll    = false
    maxLevel     = 0}