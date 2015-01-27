{**
 * Список комментариев
 *
 * @param array comments Комментарии
 *
 * TODO: Добавить путь до комментария
 *}

{$component = 'comment-list'}

<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
    {include './comment-tree.tpl'
        comments      = $smarty.local.comments
        forbidAdd     = true
        maxLevel      = 0
        commentParams = [
            useFavourite => true,
            useEdit      => false,
            useVote      => false,
            useScroll    => false
        ]}
</div>