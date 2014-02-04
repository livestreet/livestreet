<div>
    <a href="#" data-type="modal-toggle" data-modal-url="{router page='ajax/poll/modal-create'}" data-modal-aftershow="ls.poll.initFormCreate();" data-param-target_type="{$sTargetType}" data-param-target_id="{$sTargetId}">Добавить опрос</a>

	{$aBlockParams = []}
	{$aBlockParams.target_type = $sTargetType}
	{$aBlockParams.target_id = $sTargetId}

	{insert name="block" block="pollFormItems" params=$aBlockParams}
</div>