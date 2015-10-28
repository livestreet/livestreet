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
 * @param integer  $maxLevel
 *
 * @param array    $commentParams
 * @param boolean  $useSubscribe
 *
 * @param string   $forbidText
 * @param string   $authorText
 * @param string   $addCommentText
 * @param string   $title
 * @param string   $titleNoComments
 *
 * @param string   $classes
 * @param array    $attributes
 * @param string   $mods
 *}

{$component = 'ls-comments'}

{block 'comment-list-options'}
    {$mods         = $smarty.local.mods}
    {$targetId     = $smarty.local.targetId}
    {$targetType   = $smarty.local.targetType}
    {$count        = $smarty.local.count}
    {$forbidAdd    = $smarty.local.forbidAdd}
    {$isSubscribed = $smarty.local.isSubscribed}
    {$pagination   = $smarty.local.pagination}

    {* Максимальная вложенность *}
    {$maxLevel = $smarty.local.maxLevel|default:Config::Get('module.comment.max_tree')}

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

    {* @hook Начало блока с комментариями *}
    {hook run='comments_begin' params=$smarty.local.params}

    {**
     * Заголовок
     *}
    <header class="{$component}-header">
        <h3 class="comments-title js-comments-title">
            {if $count}
                {lang "{$smarty.local.title|default:'comments.comments_declension'}" count=$count plural=true}
            {else}
                {lang "{$smarty.local.titleNoComments|default:'comments.no_comments'}"}
            {/if}
        </h3>

        {* @hook Конец шапки *}
        {hook run='comments_header_end' params=$smarty.local.params}
    </header>


    {**
     * Экшнбар
     *}

    {$items = []}

    {* Свернуть/развернуть все комментарии *}
    {* Не показываем если древовидные комментарии отключены *}
    {if $maxLevel > 0}
        {$items[] = [ 'buttons' => [[
            'classes' => 'js-comments-fold-all-toggle',
            'text' => $aLang.comments.folding.fold_all
        ]]]}
    {/if}

    {* Подписка на комментарии *}
    {if $smarty.local.useSubscribe && $oUserCurrent}
        {$items[] = [ 'buttons' => [[
            'classes' => "{$component}-subscribe js-comments-subscribe {if $isSubscribed}active{/if}",
            'text'    => ( $isSubscribed ) ? $aLang.comments.unsubscribe : $aLang.comments.subscribe
        ]]]}
    {/if}

    {if $items}
        {component 'actionbar' name='comments_actionbar' items=$items classes="{$component}-actions"}
    {/if}

    {* @hook Хук перед списком комментариев *}
    {hook run='comments_list_before' params=$smarty.local.params}

    {**
     * Комментарии
     *}
    <div class="ls-comment-list js-comment-list" {if ! $smarty.local.comments}style="display: none"{/if}>
        {component 'comment' template='tree'
            comments      = $smarty.local.comments
            forbidAdd     = $forbidAdd
            maxLevel      = $maxLevel
            authorid      = $smarty.local.authorid
            authorText    = $smarty.local.authorText
            dateReadLast  = $smarty.local.dateReadLast
            commentParams = $smarty.local.commentParams}
    </div>

    {* @hook Хук после списка комментариев *}
    {hook run='comments_list_after' params=$smarty.local.params}


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
            <h4 class="ls-comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
                <a href="#" class="ls-link-dotted">{$smarty.local.addCommentText|default:$aLang.comments.form.title}</a>
            </h4>
        {else}
            {component 'alert' mods='info' text=$aLang.comments.alerts.unregistered}
        {/if}
    {/if}

    {* Форма добавления комментария *}
    {if $oUserCurrent && ( ! $forbidAdd || ( $forbidAdd && $count ) )}
        {component 'comment' template='form' classes='js-comment-form' targetType=$targetType targetId=$targetId}
    {/if}

    {* @hook Конец блока с комментариями *}
    {hook run='comments_end' params=$smarty.local.params}
</div>
