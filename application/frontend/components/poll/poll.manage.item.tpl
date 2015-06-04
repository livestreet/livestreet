{**
 * Добавленный опрос в блоке управления опросами
 *
 * @param ModulePoll_EntityPoll $oPoll Опрос
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<li class="ls-poll-manage-item js-poll-manage-item" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}">
	{* Заголовок *}
	{$oPoll->getTitle()}

	{* Действия *}
	<ul class="user-list-small-item-actions">
		{* Редактировать *}
		{* Показывает модальное окно с формой редактирования опроса *}
		<li class="js-poll-manage-item-edit" title="{$aLang.common.edit}" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}">
			{component 'icon' icon='edit'}
		</li>

		{* Удалить *}
		<li class="js-poll-manage-item-remove" title="{$aLang.common.remove}" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}">
			{component 'icon' icon='remove'}
		</li>
	</ul>
</li>