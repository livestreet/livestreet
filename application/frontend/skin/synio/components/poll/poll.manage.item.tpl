{**
 * Добавленный опрос в блоке управления опросами
 *
 * @param ModulePoll_EntityPoll $poll Опрос
 *}

<li class="ls-poll-manage-item js-poll-manage-item" data-poll-id="{$poll->getId()}" data-poll-target-tmp="{$poll->getTargetTmp()}">
    {* Заголовок *}
    {$poll->getTitle()}

    {* Действия *}
    <ul class="ls-poll-manage-item-actions">
        {* Редактировать *}
        {* Показывает модальное окно с формой редактирования опроса *}
        <li class="js-poll-manage-item-edit" title="{$aLang.common.edit}" data-poll-id="{$poll->getId()}" data-poll-target-tmp="{$poll->getTargetTmp()}">
            {component 'syn-icon' icon='edit'}
        </li>

        {* Удалить *}
        <li class="js-poll-manage-item-remove" title="{$aLang.common.remove}" data-poll-id="{$poll->getId()}" data-poll-target-tmp="{$poll->getTargetTmp()}">
            {component 'syn-icon' icon='trash'}
        </li>
    </ul>
</li>