{**
 * Управления опросами (добавление/удаление/редактирование)
 *
 * @param string $targetId
 * @param string $targetType
 *}

{component_define_params params=[ 'targetId', 'targetType' ]}

<div class="fieldset ls-poll-manage js-poll-manage" data-type="{$targetType}" data-target-id="{$targetId}">
    <header class="fieldset-header">
        <h3 class="fieldset-title">{$aLang.poll.polls}</h3>
    </header>

    <div class="fieldset-body">
        {* Кнопка добавить *}
        {component 'button' text=$aLang.common.add type='button' classes='ls-poll-manage-add js-poll-manage-add'}

        {* Список добавленных опросов *}
        {insert name="block" block="pollFormItems" params=[
            'target_type' => $targetType,
            'target_id'   => $targetId
        ]}
    </div>
</div>