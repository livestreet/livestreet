{**
 * Список комментариев
 *
 * @param array comments Комментарии
 *
 * TODO: Добавить путь до комментария
 *}

{$component = 'ls-comment-list'}

<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
    {component 'comment' template='tree'
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