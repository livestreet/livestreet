{**
 * Список диалогов
 *
 * @param array   $talks
 * @param boolean $selectable
 * @param boolean $paging
 *}

{component_define_params params=[ 'talks', 'selectable' ]}

<div class="js-talk-list">
    {if $talks}
        <form action="{router page='talk'}" method="post" id="talk-form">
            {* Скрытые поля *}
            {component 'field' template='hidden.security-key'}
            {component 'field' template='hidden' name='form_action' id='talk-form-action'}

            {* Экшнбар *}
            {if $selectable}
                {component 'actionbar' template='item.select'
                    classes  = 'js-talk-actionbar-select'
                    target   = '.js-talk-list-item'
                    assign   = select
                    items    = [
                        [ 'text' => $aLang.talk.actionbar.read, 'filter' => ":not('.talk-unread')" ],
                        [ 'text' => $aLang.talk.actionbar.unread, 'filter' => ".talk-unread" ]
                    ]}

                {component 'actionbar'
                    classes='talk-list-actionbar'
                    items=[
                        [ 'buttons' => [ 'html' => $select ] ],
                        [
                            'buttons' => [
                                [ 'icon' => 'check', 'classes' => 'js-talk-form-button', 'attributes' => [ 'data-action' => 'mark_as_read', 'title' => $aLang.talk.actionbar.mark_as_read ], 'mods' => 'icon' ],
                                [ 'icon' => 'trash', 'classes' => 'js-talk-form-button', 'attributes' => [ 'data-action' => 'remove' , 'title' => $aLang.common.remove ], 'mods' => 'icon' ]
                            ]
                        ]
                    ]}
            {/if}

            {* Список сообщений *}
            <table class="ls-table talk-list">
                <tbody>
                    {foreach $talks as $talk}
                        {* Создатель диалога *}
                        {$author = $talk->getTalkUser()}

                        {* Все участники диалога *}
                        {$users = $talk->getTalkUsers()}

                        {* Кол-во участников диалога *}
                        {$usersCount = count($users)}

                        <tr class="talk-list-item {if $author->getCommentCountNew() or ! $author->getDateLast()}talk-unread{/if} js-talk-list-item" data-id="{$talk->getId()}">
                            {* Выделение *}
                            {if $selectable}
                                <td class="cell-checkbox">
                                    <input type="checkbox" name="talk_select[{$talk->getId()}]" data-id="{$talk->getId()}" />
                                </td>
                            {/if}

                            {* Избранное *}
                            <td class="cell-favourite">
                                {component 'favourite' classes='js-favourite-talk' target=$talk}
                            </td>

                            {* Основная информация о диалоге *}
                            <td class="cell-info">
                                <div class="talk-list-item-info">
                                    {* Участники диалога *}
                                    {if $usersCount > 2}
                                        <a href="{router page='talk'}read/{$talk->getId()}/" class="talk-list-item-info-avatar">
                                            <img src="{cfg name='path.skin.web'}/assets/images/avatars/avatar_male_64x64crop.png" />
                                        </a>

                                        {lang name='talk.participants' count=$usersCount plural=true}
                                    {else}
                                        {* Если участников двое, то отображаем только собеседника *}
                                        {foreach $users as $user}
                                            {$user = $user->getUser()}

                                            {if $user->getUserId() != $oUserCurrent->getId()}
                                                <a href="{$user->getUserWebPath()}" class="talk-list-item-info-avatar">
                                                    <img src="{$user->getProfileAvatarPath(64)}" alt="{$user->getLogin()}" />
                                                </a>

                                                <a href="{$user->getUserWebPath()}" class="ls-word-wrap">{$user->getDisplayName()}</a>
                                            {/if}
                                        {/foreach}
                                    {/if}

                                    {* Дата *}
                                    <time class="talk-list-item-info-date" datetime="{date_format date=$talk->getDate() format='c'}" title="{date_format date=$talk->getDate() format='j F Y, H:i'}">
                                        {date_format date=$talk->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                                    </time>
                                </div>
                            </td>

                            {* Заголовок и текст последнего сообщения *}
                            <td>
                                <div class="talk-list-item-extra">
                                    {* Заголовок *}
                                    <h2 class="talk-list-item-title">
                                        <a href="{router page='talk'}read/{$talk->getId()}/">
                                            {$talk->getTitle()|escape}
                                        </a>
                                    </h2>

                                    {* Текст последнего сообщения *}
                                    <div class="talk-list-item-text">
                                        {(($talk->getCommentLast()) ? $talk->getCommentLast()->getText() : $talk->getText())|strip_tags|truncate:120:"..."|escape}
                                    </div>

                                    {* Кол-во сообщений *}
                                    {if $talk->getCountComment()}
                                        <div class="talk-list-item-count">
                                            {$talk->getCountComment()}

                                            {if $author->getCommentCountNew()}
                                                <strong>+{$author->getCommentCountNew()}</strong>
                                            {/if}
                                        </div>
                                    {/if}
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </form>
    {else}
        {component 'blankslate' text=$aLang.talk.notices.empty}
    {/if}

    {component 'pagination' total=+$paging.iCountPage current=+$paging.iCurrentPage url="{$paging.sBaseUrl}/page__page__/{$paging.sGetParams}"}
</div>