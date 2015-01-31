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
{$component = 'comment'}

{* Переменные *}
{$comment = $smarty.local.comment}
{$mods    = $smarty.local.mods}
{$useEdit = $smarty.local.useEdit|default:true}

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
{if $smarty.local.useVote && $comment->isBad()}
    {$mods = "$mods bad"}
{/if}

{* Автор комментария является автором объекта к которому оставлен комментарий *}
{if $smarty.local.authorId == $user->getId()}
    {$mods = "$mods author"}
{/if}

{* Комментарий удален *}
{if $isDeleted}
    {$mods = "$mods deleted"}

{* Комментарий текущего залогиненого пользователя *}
{elseif $oUserCurrent && $comment->getUserId() == $oUserCurrent->getId()}
    {$mods = "$mods self"}

{* Непрочитанный комментарий *}
{elseif $smarty.local.dateReadLast && strtotime($smarty.local.dateReadLast) <= strtotime($comment->getDate())}
    {$mods = "$mods new"}
{/if}


{**
 * Комментарий
 * Атрибут id используется для ссылки на комментарий через хэш в урл (например #comment123)
 *}
<section class   = "{$component} {cmods name=$component mods=$mods} {$smarty.local.classes} open js-{$component}"
         id      = "comment{$commentId}"
         data-id = "{$commentId}"
         data-parent-id = "{$comment->getPid()}"
         {cattr list=$smarty.local.attributes}>

    {* Показываем удаленные комментарии только администраторам *}
    {if ! $isDeleted || ( $oUserCurrent && $oUserCurrent->isAdministrator() )}
        {* Аватар пользователя *}
        <a href="{$user->getUserWebPath()}" class="{$component}-avatar">
            <img src="{$user->getProfileAvatarPath(64)}" alt="{$user->getDisplayName()}" />
        </a>

        {* Информация *}
        <ul class="{$component}-info clearfix">
            {* Автор комментария *}
            <li class="{$component}-username">
                <a href="{$user->getUserWebPath()}">
                    {$user->getDisplayName()}
                </a>
            </li>

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
            {if $smarty.local.useScroll|default:true}
                {if $comment->getPid()}
                    <li class          = "{$component}-scroll-to {$component}-scroll-to-parent js-comment-scroll-to-parent"
                        title          = "{$aLang.comments.comment.scroll_to_parent}">↑</li>
                {/if}

                {* Прокрутка к дочернему комментарию *}
                <li class = "{$component}-scroll-to {$component}-scroll-to-child js-comment-scroll-to-child"
                    title = "{$aLang.comments.comment.scroll_to_child}">↓</li>
            {/if}

            {* Голосование *}
            {if $smarty.local.useVote}
                <li>
                    {* Блокируем голосование для гостей или если залогиненый пользователь является автором комментария*}
                    {component 'vote'
                        classes  = "{$component}-vote js-{$component}-vote"
                        target   = $comment
                        isLocked = ($oUserCurrent && $oUserCurrent->getId() == $user->getId()) || strtotime($comment->getDate()) < $smarty.now - Config::Get('acl.vote.comment.limit_time')}
                </li>
            {/if}

            {* Избранное *}
            {if $oUserCurrent && $smarty.local.useFavourite}
                <li>
                    {component 'favourite' classes='comment-favourite js-comment-favourite' target=$comment}
                </li>
            {/if}
        </ul>

        {* Текст комментария *}
        <div class="{$component}-content text">
            {$comment->getText()}
        </div>

        {* Информация о редактировании *}
        {if $comment->getDateEdit()}
            <div class="{$component}-edit-info">
                {$aLang.comments.comment.edit_info}:

                <span class="{$component}-edit-info-time js-{$component}-edit-time">
                    {date_format date=$comment->getDateEdit() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                </span>

                {if $comment->getCountEdit() > 1}
                    ({$comment->getCountEdit()} {$comment->getCountEdit()|declension:$aLang.common.times_declension})
                {/if}
            </div>
        {/if}

        {* Действия *}
        <ul class="comment-actions clearfix">
            {* Ответить *}
            {if $oUserCurrent && ! $isDeleted && $smarty.local.showReply|default:true}
                <li>
                    <a href="#" class="link-dotted js-comment-reply" data-id="{$commentId}">{$aLang.comments.comment.reply}</a>
                </li>
            {/if}

            {* Сворачивание *}
            <li class="comment-fold js-comment-fold open" data-id="{$commentId}">
                <a href="#" class="link-dotted">{$aLang.comments.folding.fold}</a>
            </li>

            {* Редактировать *}
            {if $smarty.local.useEdit && $oUserCurrent && $comment->IsAllowEdit()}
                <li>
                    <a href="#" class="link-dotted js-comment-update" data-id="{$commentId}">
                        {$aLang.common.edit}

                        {* Отображение времени отведенного для редактирования *}
                        {* Используется плагин jquery.timers *}
                        {if $comment->getEditTimeRemaining()}
                            (<span class="js-comment-update-timer" data-seconds="{$comment->getEditTimeRemaining()}">...</span>)
                        {/if}
                    </a>
                </li>
            {/if}

            {* Удалить *}
            {if $oUserCurrent && $comment->IsAllowDelete()}
                <li>
                    <a href="#" class="link-dotted js-comment-remove" data-id="{$commentId}">
                        {( $isDeleted ) ? $aLang.comments.comment.restore : $aLang.common.remove}
                    </a>
                </li>
            {/if}
        </ul>
    {else}
        {$aLang.comments.comment.deleted}
    {/if}
</section>