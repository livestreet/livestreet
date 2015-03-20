{**
 * Комментарии
 *
 * @param array    $comments
 * @param integer  $count
 * @param integer  $targetId
 * @param string   $targetType
 * @param string   $dateReadLast
 * @param boolean  $forbidAdd
 * @param integer  $authorId
 * @param integer  $lastCommentId
 * @param array    $pagination
 * @param boolean  $isSubscribed
 *
 * @param array    $commentParams
 * @param boolean  $useSubscribe
 *
 * @param string   $forbidText
 * @param string   $authorText
 * @param string   $addCommentText
 * @param string   $titleLang
 *
 * @param string   $classes
 * @param array    $attributes
 * @param string   $mods
 *}

{$component = 'comments'}

{block 'comment-list-options'}
    {$mods         = $smarty.local.mods}
    {$targetId     = $smarty.local.targetId}
    {$targetType   = $smarty.local.targetType}
    {$count        = $smarty.local.count}
    {$forbidAdd    = $smarty.local.forbidAdd}
    {$isSubscribed = $smarty.local.isSubscribed}
    {$pagination   = $smarty.local.pagination}

    {if $forbidAdd}
        {$mods = "$mods forbid"}
    {/if}
{/block}

{if $oUserCurrent && ! $pagination['total']}
    {add_block group='toolbar' name='component@comment.toolbar'}
{/if}

<div class="{$component} js-comments {cmods name=$component mods=$mods} {$smarty.local.classes}"
    data-target-type="{$targetType}"
    data-target-id="{$targetId}"
    data-comment-last-id="{$smarty.local.lastCommentId}"
    {cattr list=$smarty.local.attributes}>
    {**
     * Заголовок
     *}
    <header class="{$component}-header">
        <h3 class="comments-title js-comments-title">
            {lang "{$smarty.local.titleLang|default:'comments.comments_declension'}" count=$count plural=true}
        </h3>
    </header>


    {**
     * Экшнбар
     *}

    {* Свернуть/развернуть все комментарии *}
    {$items = [[ 'buttons' => [[ 'classes' => 'js-comments-fold-all-toggle', 'text' => $aLang.comments.folding.fold_all ]]]]}

    {* Подписка на комментарии *}
    {if $smarty.local.useSubscribe && $oUserCurrent}
        {$items[] = [ 'buttons' => [[
            'classes' => "comments-subscribe js-comments-subscribe {if $isSubscribed}active{/if}",
            'text'    => ( $isSubscribed ) ? $aLang.comments.unsubscribe : $aLang.comments.subscribe
        ]]]}
    {/if}

    {* TODO: Добавить хук *}

    {component 'actionbar' items=$items classes='comments-actions'}


    {**
     * Комментарии
     *}
    <div class="comment-list js-comment-list">
        {include './comment-tree.tpl'
            comments      = $smarty.local.comments
            forbidAdd     = $forbidAdd
            authorid      = $smarty.local.authorid
            authorText    = $smarty.local.authorText
            dateReadLast  = $smarty.local.dateReadLast
            commentParams = $smarty.local.commentParams}
    </div>


    {**
     * Пагинация
     *}
    {component 'pagination' classes="{$component}-pagination" params=$pagination}


    {**
     * Форма добавления комментария
     *}

    {* Проверяем запрещено комментирование или нет *}
    {if $forbidAdd}
        {component 'alert' mods='info' text=$smarty.local.forbidText}

    {* Если разрешено то показываем форму добавления комментария *}
    {else}
        {if $oUserCurrent}
            {* Кнопка открывающая форму *}
            <h4 class="comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
                <a href="#" class="link-dotted">{$smarty.local.addCommentText|default:$aLang.comments.form.title}</a>
            </h4>
        {else}
            {component 'alert' mods='info' text=$aLang.comments.alerts.unregistered}
        {/if}
    {/if}

    {* Форма добавления комментария *}
    {if $oUserCurrent && ( ! $forbidAdd || ( $forbidAdd && $count ) )}
        {include './comment-form.tpl' classes='js-comment-form' targetType=$targetType targetId=$targetId}
    {/if}
</div>