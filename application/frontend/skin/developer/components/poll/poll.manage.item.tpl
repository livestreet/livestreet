{**
 * Добавленный опрос в блоке управления опросами
 *
 * @param ModulePoll_EntityPoll $oPoll Опрос
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<li class="poll-manage-item js-poll-manage-item" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}">
	{* Заголовок *}
	{$oPoll->getTitle()}

	{* Действия *}
	<ul class="user-list-small-item-actions">
		{* Редактировать *}
		{* Показывает модальное окно с формой редактирования опроса *}
		<li class="icon-edit js-poll-manage-item-edit"
			title="{$aLang.common.edit}" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}"></li>

		{* Удалить *}
		<li class="icon-remove js-poll-manage-item-remove" title="{$aLang.common.remove}" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}"></li>
	</ul>
</li>