{**
 * Добавленный опрос в форме добавления
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<li class="poll-form-list-item js-poll-form-list-item" data-poll-id="{$oPoll->getId()}" data-poll-target-tmp="{$oPoll->getTargetTmp()}">
	{$oPoll->getTitle()}

	<ul class="user-list-small-item-actions">
		<li class="icon-edit js-poll-form-list-item-edit" title="{$aLang.common.edit}" data-type="modal-toggle" data-modal-url="{router page='ajax/poll/modal-update'}" data-param-id="{$oPoll->getId()}" data-param-target_tmp="{$oPoll->getTargetTmp()}"></li>
		<li class="icon-remove js-poll-form-list-item-remove" title="{$aLang.common.remove}"></li>
	</ul>
</li>