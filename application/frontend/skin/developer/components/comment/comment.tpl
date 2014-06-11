{**
 * Комментарий
 *
 * @component comment
 * @styles    css/comments.css
 * @scripts   js/comments.js
 *
 * @param object   $oComment          Комментарий
 * @param string   $sClasses          Дополнительные классы
 * @param string   $sAttributes       Атрибуты
 * @param string   $sMods             Модификаторы
 * @param boolean  $bShowVote         (true) Показывать или нет голосование
 * @param boolean  $bShowReply        (true) Показывать или нет кнопку Ответить
 * @param integer  $iAuthorId
 * @param string   $sDateReadLast
 *}

{* Название компонента *}
{$sComponent = 'comment'}

{* Переменные *}
{$oComment   = $smarty.local.oComment}
{$sMods      = $smarty.local.sMods}
{$bShowEdit  = $smarty.local.bShowEdit|default:true}
{$bIsHidden  = $smarty.local.bIsHidden}
{$oUser      = $oComment->getUser()}
{$iCommentId = $oComment->getId()}

{* Получаем ссылку на комментарий *}
{* TODO: Вынести в бэкенд *}
{$sPermalink = ( Config::Get('module.comment.use_nested') ) ? "{router page='comments'}{$iCommentId}" : "#comment{$iCommentId}"}

{**
 * Добавляем модификаторы
 *}

{* Комментарий с отрицательным рейтингом *}
{if $smarty.local.bShowVote && $oComment->isBad()}
    {$sMods = "$sMods bad"}
{/if}

{* Автор комментария является автором объекта к которому оставлен комментарий *}
{if $smarty.local.iAuthorId == $oUser->getId()}
    {$sMods = "$sMods author"}
{/if}

{* Комментарий удален *}
{if $bIsHidden}
    {$sMods = "$sMods deleted"}

{* Комментарий текущего залогиненого пользователя *}
{elseif $oUserCurrent && $oComment->getUserId() == $oUserCurrent->getId()}
    {$sMods = "$sMods self"}

{* Непрочитанный комментарий *}
{elseif $smarty.local.sDateReadLast && strtotime($smarty.local.sDateReadLast) <= strtotime($oComment->getDate())}
    {$sMods = "$sMods new"}
{/if}


{**
 * Комментарий
 * Атрибут id используется для ссылки на комментарий через хэш в урл #comment123
 *}
<section class   = "{$sComponent} {mod name=$sComponent mods=$sMods} {$smarty.local.sClasses} open js-{$sComponent}"
         id      = "comment{$iCommentId}"
         data-id = "{$iCommentId}"
         {$smarty.local.sAttributes}>

    {* Показываем удаленные комментарии только администраторам *}
    {if ! $bIsHidden || ( $oUserCurrent && $oUserCurrent->isAdministrator() )}
        {* Аватар пользователя *}
        <a href="{$oUser->getUserWebPath()}" class="{$sComponent}-avatar">
            <img src="{$oUser->getProfileAvatarPath(64)}" alt="{$oUser->getDisplayName()}" />
        </a>

        {* Информация *}
        <ul class="{$sComponent}-info clearfix">
            {* Автор комментария *}
            <li class="{$sComponent}-username">
                <a href="{$oUser->getUserWebPath()}">
                    {$oUser->getDisplayName()}
                </a>
            </li>

            {* Дата добавления комментария *}
            {* Так же является ссылкой на комментарий *}
            <li class="{$sComponent}-date">
                <a href="{$sPermalink}" title="{$aLang.comments.comment.url}">
                    <time datetime="{date_format date=$oComment->getDate() format='c'}">
                        {date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                    </time>
                </a>
            </li>

            {* Прокрутка к родительскому комментарию *}
            {if $smarty.local.bShowScroll}
                {if $oComment->getPid()}
                    <li class          = "{$sComponent}-scroll-to {$sComponent}-scroll-to-parent js-comment-scroll-to-parent"
                        title          = "{$aLang.comments.comment.scroll_to_parent}"
                        data-id        = "{$iCommentId}"
                        data-parent-id = "{$oComment->getPid()}">↑</li>
                {/if}

                {* Прокрутка к дочернему комментарию *}
                <li class = "{$sComponent}-scroll-to {$sComponent}-scroll-to-child js-comment-scroll-to-child"
                    title = "{$aLang.comments.comment.scroll_to_child}">↓</li>
            {/if}

            {* Голосование *}
            {if $smarty.local.bShowVote}
                <li>
                    {* Блокируем голосование для гостей или если залогиненый пользователь является автором комментария*}
                    {include 'components/vote/vote.tpl'
                            sClasses  = "{$sComponent}-vote js-vote-{$sComponent}"
                            oObject   = $oComment
                            bIsLocked = ($oUserCurrent && $oUserCurrent->getId() == $oUser->getId())}
                </li>
            {/if}

            {* Избранное *}
            {if $oUserCurrent && $smarty.local.bShowFavourite}
                <li>
                    {include 'components/favourite/favourite.tpl' sClasses='comment-favourite js-favourite-comment' oObject=$oComment}
                </li>
            {/if}
        </ul>

        {* Текст комментария *}
        <div class="{$sComponent}-content text">
            {$oComment->getText()}
        </div>

        {* Информация о редактировании *}
        {if $oComment->getDateEdit()}
            <div class="{$sComponent}-edit-info">
                {$aLang.comments.comment.edit_info}:

                <span class="{$sComponent}-edit-info-time js-{$sComponent}-edit-time">
                    {date_format date=$oComment->getDateEdit() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                </span>

                {if $oComment->getCountEdit() > 1}
                    ({$oComment->getCountEdit()} {$oComment->getCountEdit()|declension:$aLang.common.times_declension})
                {/if}
            </div>
        {/if}

        {* Действия *}
        <ul class="comment-actions clearfix">
            {* Ответить *}
            {if $oUserCurrent && ! $bIsHidden && $smarty.local.bShowReply|default:true}
                <li>
                    <a href="#" class="link-dotted js-comment-reply" data-id="{$iCommentId}">{$aLang.comments.comment.reply}</a>
                </li>
            {/if}

            {* Сворачивание *}
            <li class="comment-fold js-comment-fold open" data-id="{$iCommentId}">
                <a href="#" class="link-dotted">{$aLang.comments.folding.fold}</a>
            </li>

            {* Редактировать *}
            {if $smarty.local.bShowEdit && $oUserCurrent && $oComment->IsAllowEdit()}
                <li>
                    <a href="#" class="link-dotted js-comment-update" data-id="{$iCommentId}">
                        {$aLang.common.edit}

                        {* Отображение времени отведенного для редактирования *}
                        {* Используется плагин jquery.timers *}
                        {if $oComment->getEditTimeRemaining()}
                            (<span class="js-comment-update-timer" data-seconds="{$oComment->getEditTimeRemaining()}">...</span>)
                        {/if}
                    </a>
                </li>
            {/if}

            {* Удалить *}
            {if $oUserCurrent && $oComment->IsAllowDelete()}
                <li>
                    <a href="#" class="link-dotted js-comment-remove" data-id="{$iCommentId}">
                        {( $bIsHidden ) ? $aLang.comments.comment.restore : $aLang.common.remove}
                    </a>
                </li>
            {/if}
        </ul>
    {else}
        {$aLang.comments.comment.deleted}
    {/if}
</section>