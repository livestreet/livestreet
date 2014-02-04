<div id="poll-form-item-{$oPoll->getId()}">
	Опрос: {$oPoll->getTitle()} &mdash;
	<a href="#" data-type="modal-toggle" data-modal-url="{router page='ajax/poll/modal-update'}" data-modal-aftershow="ls.poll.initFormUpdate();" data-param-id="{$oPoll->getId()}" data-param-target_tmp="{$oPoll->getTargetTmp()}">{$aLang.common.edit}</a>
	<a href="#" onclick="return ls.poll.removePoll({$oPoll->getId()},'{$oPoll->getTargetTmp()}');">{$aLang.common.remove}</a>
</div>