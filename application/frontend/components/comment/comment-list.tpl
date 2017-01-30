{**
 * Список комментариев
 *
 * @param array comments Комментарии
 *}

{$component = 'ls-comment-list'}
{component_define_params params=[ 'hookPrefixComment', 'comments', 'mods', 'classes', 'attributes' ]}

{if $comments}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {component 'comment' template='tree'
            hookPrefixComment = $hookPrefixComment
            comments      = $comments
            forbidAdd     = true
            maxLevel      = 0
            commentParams = [
                useFavourite => true,
                useEdit      => false,
                useVote      => false,
                useScroll    => false,
                showPath     => true
            ]}
    </div>
{else}
    {component 'blankslate' text=$aLang.common.empty}
{/if}