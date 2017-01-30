{**
 * Комментарий
 *
 * @param object   $comment          Комментарий
 * @param boolean  $useVote          (true) Показывать или нет голосование
 * @param boolean  $useFavourite
 * @param boolean  $useScroll
 * @param boolean  $showReply        (true) Показывать или нет кнопку Ответить
 * @param integer  $authorId
 * @param string   $authorText
 * @param string   $dateReadLast
 *
 * @param string   $classes          Дополнительные классы
 * @param string   $attributes       Атрибуты
 * @param string   $mods             Модификаторы
 *}

{* Название компонента *}
{$component = 'ls-comment'}
{component_define_params params=[ 'hookPrefix', 'dateReadLast', 'showPath', 'showReply', 'authorId', 'comment', 'useFavourite', 'useScroll', 'useVote', 'useEdit', 'mods', 'classes', 'attributes' ]}

{* Переменные *}
{$useEdit = $useEdit|default:true}
{$hookPrefix = $hookPrefix|default:'comment'}
{$isDeleted = $comment->getDelete()}
{$user      = $comment->getUser()}
{$commentId = $comment->getId()}
{$target    = $comment->getTarget()}

{* Получаем ссылку на комментарий *}
{* TODO: Вынести в бэкенд *}
{$permalink = ( Config::Get('module.comment.use_nested') ) ? "{router page='comments'}{$commentId}" : "{if $target}{$target->getUrl()}{/if}#comment{$commentId}"}

{**
 * Добавляем модификаторы
 *}

{* Комментарий с отрицательным рейтингом *}
{if $useVote && $comment->isBad()}
    {$mods = "$mods bad"}
{/if}

{* Автор комментария является автором объекта к которому оставлен комментарий *}
{if $authorId == $user->getId()}
    {$mods = "$mods author"}
{/if}

{* Комментарий удален *}
{if $isDeleted}
    {$mods = "$mods deleted"}

{* Комментарий текущего залогиненого пользователя *}
{elseif $oUserCurrent && $comment->getUserId() == $oUserCurrent->getId()}
    {$mods = "$mods self"}

{* Непрочитанный комментарий *}
{elseif $dateReadLast && strtotime($dateReadLast) <= strtotime($comment->getDate())}
    {$mods = "$mods new"}
{/if}


{**
 * Комментарий
 * Атрибут id используется для ссылки на комментарий через хэш в урл (например #comment123)
 *}
<section class   = "{$component} {cmods name=$component mods=$mods} {$classes} open js-comment"
         id      = "comment{$commentId}"
         data-id = "{$commentId}"
         data-parent-id = "{$comment->getPid()}"
         {cattr list=$attributes}>
    {* @hook Начало комментария *}
    {hook run="{$hookPrefix}_comment_begin" params=$params}

    {* Путь до комментария *}
    {if $showPath}
        <div class="{$component}-path">
            {$target = $comment->getTarget()}

            <a href="{$target->getUrl()}" class="{$component}-path-target">{$target->getTitle()|escape}</a>
            <a href="{$target->getUrl()}#comments" class="{$component}-path-comments">({$target->getCountComment()})</a>
        </div>
    {/if}

    {* Показываем удаленные комментарии только администраторам *}
    {if ! $isDeleted || ( $oUserCurrent && $oUserCurrent->isAdministrator() )}
        {* Аватар пользователя *}
        <a href="{$user->getUserWebPath()}" class="{$component}-avatar">
            <img src="{$user->getProfileAvatarPath(64)}" alt="{$user->getDisplayName()}" />
        </a>

        {* Избранное *}
        {if $oUserCurrent && $useFavourite}
            {component 'favourite' classes="{$component}-favourite js-comment-favourite" target=$comment}
        {/if}

        {* Информация *}
        <ul class="{$component}-info ls-clearfix">
            {* @hook Начало блока с информацией *}
            {hook run="{$hookPrefix}_info_begin" params=$params}

            {* Автор комментария *}
            {component 'comment.info-item'
                classes="{$component}-username"
                link=[ url => $user->getUserWebPath() ]
                text=$user->getDisplayName()}

            {* Дата добавления комментария *}
            {* Так же является ссылкой на комментарий *}
            <li class="{$component}-date">
                <a href="{$permalink}" title="{$aLang.comments.comment.url}">
                    <time datetime="{date_format date=$comment->getDate() format='c'}" title="{date_format date=$comment->getDate() format="j F Y, H:i"}">
                        {date_format date=$comment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                    </time>
                </a>
            </li>

            {* Прокрутка к родительскому комментарию *}
            {if $useScroll|default:true}
                {if $comment->getPid()}
                    <li class="{$component}-scroll-to {$component}-scroll-to-parent js-comment-scroll-to-parent"
                        title="{$aLang.comments.comment.scroll_to_parent}">↑</li>
                {/if}

                {* Прокрутка к дочернему комментарию *}
                <li class="{$component}-scroll-to {$component}-scroll-to-child js-comment-scroll-to-child"
                    title="{$aLang.comments.comment.scroll_to_child}">↓</li>
            {/if}

            {* Голосование *}
            {if $useVote}
                <li>
                    {* Блокируем голосование для гостей или если залогиненый пользователь является автором комментария*}
                    {component 'vote'
                        classes  = "{$component}-vote js-comment-vote"
                        target   = $comment
                        isLocked = ($oUserCurrent && $oUserCurrent->getId() == $user->getId()) || strtotime($comment->getDate()) < $smarty.now - Config::Get('acl.vote.comment.limit_time')}
                </li>
            {/if}

            {* @hook Конец блока с информацией *}
            {hook run="{$hookPrefix}_info_end" params=$params}
        </ul>

        {* Текст комментария *}
        <div class="{$component}-content">
            {* @hook Начало блока с содержимым комментария *}
            {hook run="{$hookPrefix}_content_begin" params=$params}

            <div class="{$component}-text ls-text">
                {$comment->getText()}
            </div>

            {* @hook Конец блока с содержимым комментария *}
            {hook run="{$hookPrefix}_content_end" params=$params}
        </div>

        {* Информация о редактировании *}
        {if $comment->getDateEdit()}
            <div class="{$component}-edit-info">
                {$aLang.comments.comment.edit_info}:

                <span class="{$component}-edit-info-time js-comment-edit-time">
                    {date_format date=$comment->getDateEdit() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                </span>

                {if $comment->getCountEdit() > 1}
                    ({$comment->getCountEdit()} {$comment->getCountEdit()|declension:$aLang.common.times_declension})
                {/if}
            </div>
        {/if}

        {* Действия *}
        <ul class="{$component}-actions ls-clearfix">
            {* @hook Начало списка экшенов комментария *}
            {hook run="{$hookPrefix}_actions_begin" params=$params}

            {* Ответить *}
            {if $oUserCurrent && ! $isDeleted && $showReply|default:true}
                {component 'comment.actions-item'
                    link=[ classes => 'js-comment-reply', attributes => [ 'data-id' => $commentId ] ]
                    text=$aLang.comments.comment.reply}
            {/if}

            {* Сворачивание *}
            {component 'comment.actions-item'
                classes="{$component}-fold open"
                link=[ classes => 'js-comment-fold', attributes => [ 'data-id' => $commentId ] ]
                text=$aLang.comments.folding.fold}

            {* Редактировать *}
            {if $useEdit && $oUserCurrent && $comment->IsAllowEdit()}
                {capture assign="ls_comment_edit_text"}
                    {$aLang.common.edit}

                    {* Отображение времени отведенного для редактирования *}
                    {* Используется плагин jquery.timers *}
                    {if $comment->getEditTimeRemaining()}
                        (<span class="js-comment-update-timer" data-seconds="{$comment->getEditTimeRemaining()}">...</span>)
                    {/if}
                {/capture}

                {component 'comment.actions-item'
                    link=[ classes => 'js-comment-update', attributes => [ 'data-id' => $commentId ] ]
                    text=$ls_comment_edit_text}
            {/if}

            {* Удалить *}
            {if $oUserCurrent && $comment->IsAllowDelete()}
                {component 'comment.actions-item'
                    link=[ classes => 'js-comment-remove', attributes => [ 'data-id' => $commentId ] ]
                    text=(( $isDeleted ) ? $aLang.comments.comment.restore : $aLang.common.remove)}
            {/if}

            {* @hook Конец списка экшенов комментария *}
            {hook run="{$hookPrefix}_actions_end" params=$params}
        </ul>
    {else}
        {$aLang.comments.comment.deleted}
    {/if}

    {* @hook Конец комментария *}
    {hook run="{$hookPrefix}_comment_end" params=$params}
</section>