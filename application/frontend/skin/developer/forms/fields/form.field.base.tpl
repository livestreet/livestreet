{**
 * Базовый шаблон поля формы
 *
 * @param string  sFieldName      Имя поля (параметр name)
 * @param string  sFieldLabel     Текст лэйбла
 * @param string  sFieldNote      Подсказка (отображается под полем)
 * @param string  sFieldRules     Правила валидации через пробел без префикса 'data-' (Плагин Parsley)
 * @param boolean bFieldInline    Отображать поле как инлайновое или нет
 * @param boolean bFieldNoMargin  Убрать отступы
 *}

{* Правила валидации *}
{$aFieldRules = " "|explode:$sFieldRules}

{if $sFieldEntity}
	{if !$sFieldEntityField}
    	{$sFieldEntityField=$sFieldName}
	{/if}
	{field_make_rule entity=$sFieldEntity field=$sFieldEntityField scenario=$sFieldEntityScenario assign=aFieldRules}
{/if}

{block name='field_before'}{/block}

<div class="form-field {if $bFieldInline}form-field-inline{/if} {block name='field_classes'}{/block} {if $bFieldNoMargin}m-0{/if}">
    {if $sFieldLabel && ! $bFieldNoLabel}
        <label for="{if $sFieldId}{$sFieldId}{else}{$sFieldName}{/if}" class="form-field-label">{$sFieldLabel}:</label>
    {/if}

    <div class="form-field-holder">
        {block name='field_holder'}
            {if $sFieldNote}
                <small class="note" id="{$sFieldName}_note">{$sFieldNote}</small>
            {/if}
        {/block}
    </div>
</div>

{block name='field_after'}{/block}