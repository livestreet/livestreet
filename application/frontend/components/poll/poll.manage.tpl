{**
 * Управления опросами (добавление/удаление/редактирование)
 *
 * @param string $targetId
 * @param string $targetType
 *}

<div class="fieldset ls-poll-manage js-poll-manage" data-type="{$smarty.local.targetType}" data-target-id="{$smarty.local.targetId}">
    <header class="fieldset-header">
        <h3 class="fieldset-title">{$aLang.poll.polls}</h3>
    </header>

    <div class="fieldset-body">
        {* Кнопка добавить *}
        {component 'button' text=$aLang.common.add type='button' classes='ls-poll-manage-add js-poll-manage-add'}

        {* Список добавленных опросов *}
        {insert name="block" block="pollFormItems" params=[
            'target_type' => $smarty.local.targetType,
            'target_id'   => $smarty.local.targetId
        ]}
    </div>
</div>