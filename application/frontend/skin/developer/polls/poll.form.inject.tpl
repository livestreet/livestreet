{**
 * Форма добавления
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<div class="fieldset">
	<header class="fieldset-header">
		<h3 class="fieldset-title">Опросы</h3>
	</header>

	<div class="fieldset-body">
	    <a href="#" class="button button-primary" data-type="modal-toggle" data-modal-url="{router page='ajax/poll/modal-create'}" data-param-target_type="{$sTargetType}" data-param-target_id="{$sTargetId}">Добавить опрос</a>
	
		<br>
		<br>

		{$aBlockParams = []}
		{$aBlockParams.target_type = $sTargetType}
		{$aBlockParams.target_id = $sTargetId}

		{insert name="block" block="pollFormItems" params=$aBlockParams}
	</div>
</div>