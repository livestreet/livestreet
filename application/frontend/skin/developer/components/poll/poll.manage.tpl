{**
 * Управления опросами (добавление/удаление/редактирование)
 *
 * @param string $sTargetId
 * @param string $sTargetType
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<div class="fieldset poll-manage js-poll-manage" data-type="{$sTargetType}" data-target-id="{$sTargetId}">
	<header class="fieldset-header">
		<h3 class="fieldset-title">{$aLang.poll.polls}</h3>
	</header>

	<div class="fieldset-body">
		{* Кнопка добавить *}
		{include 'components/button/button.tpl' text=$aLang.common.add type='button' classes='poll-manage-add js-poll-manage-add'}

		{* Список добавленных опросов *}
		{insert name="block" block="pollFormItems" params=[
			'target_type' => $sTargetType,
			'target_id'   => $sTargetId
		]}
	</div>
</div>