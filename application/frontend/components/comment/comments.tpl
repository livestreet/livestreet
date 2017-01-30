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
{component_define_params params=[ 'hookPrefix', 'hookPrefixComment', 'addCommentText', 'authorId', 'authorText', 'commentParams', 'comments', 'count', 'dateReadLast', 'forbidAdd',
    'forbidText', 'isSubscribed', 'lastCommentId', 'maxLevel', 'pagination', 'targetId', 'targetType', 'title', 'titleNoComments',
    'useSubscribe', 'mods', 'classes', 'attributes' ]}

{block 'comment-list-options'}
    {* Максимальная вложенность *}
    {$maxLevel = $maxLevel|default:Config::Get('module.comment.max_tree')}
    {$hookPrefix = $hookPrefix|default:'comments'}

    {if $forbidAdd}
        {$mods = "$mods forbid"}
    {/if}
{/block}

{if $oUserCurrent && ! $pagination['total']}
    {add_block group='toolbar' name='component@comment.toolbar'}
{/if}

<div class="{$component} js-comments {cmods name=$component mods=$mods} {$classes}"
    data-target-type="{$targetType}"
    data-target-id="{$targetId}"
    data-comment-last-id="{$lastCommentId}"
    {cattr list=$attributes}>

    {* @hook Начало блока с комментариями *}
    {hook run="{$hookPrefix}_begin" params=$params}

    {**
     * Заголовок
     *}
    <header class="{$component}-header">
        <h3 class="comments-title js-comments-title">
            {if $count}
                {lang "{$title|default:'comments.comments_declension'}" count=$count plural=true}
            {else}
                {lang "{$titleNoComments|default:'comments.no_comments'}"}
            {/if}
        </h3>

        {* @hook Конец шапки *}
        {hook run="{$hookPrefix}_header_end" params=$params}
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
    {if $useSubscribe && $oUserCurrent}
        {$items[] = [ 'buttons' => [[
            'classes' => "{$component}-subscribe js-comments-subscribe {if $isSubscribed}active{/if}",
            'text'    => ( $isSubscribed ) ? $aLang.comments.unsubscribe : $aLang.comments.subscribe
        ]]]}
    {/if}

    {if $items}
        {component 'actionbar' name='comments_actionbar' items=$items classes="{$component}-actions"}
    {/if}

    {* @hook Хук перед списком комментариев *}
    {hook run="{$hookPrefix}_list_before" params=$params}

    {**
     * Комментарии
     *}
    <div class="ls-comment-list js-comment-list" {if ! $comments}style="display: none"{/if}>
        {component 'comment' template='tree'
            comments      = $comments
            forbidAdd     = $forbidAdd
            forbidAdd     = $forbidAdd
            maxLevel      = $maxLevel
            authorId      = $authorId
            authorText    = $authorText
            dateReadLast  = $dateReadLast
            commentParams = $commentParams
            hookPrefixComment = $hookPrefixComment}
    </div>

    {* @hook Хук после списка комментариев *}
    {hook run="{$hookPrefix}_list_after" params=$params}


    {**
     * Пагинация
     *}
    {component 'pagination' classes="{$component}-pagination" params=$pagination}


    {**
     * Форма добавления комментария
     *}

    {* Проверяем запрещено комментирование или нет *}
    {if $forbidAdd}
        {component 'alert' mods='info' text=$forbidText}

    {* Если разрешено то показываем форму добавления комментария *}
    {else}
        {if $oUserCurrent}
            {* Кнопка открывающая форму *}
            <h4 class="ls-comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
                <a href="#" class="ls-link-dotted">{$addCommentText|default:$aLang.comments.form.title}</a>
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
    {hook run="{$hookPrefix}_end" params=$params}
</div>
