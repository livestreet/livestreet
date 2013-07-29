{**
 * Базовый шаблон поля формы
 *
 * Параметры
 * ---------
 * sFieldName      Имя поля (параметр name)
 * sFieldLabel     Текст лэйбла
 * sFieldNote      Подсказка (отображается пол полем)
 * sFieldRules     Правила валидации через пробел без префикса 'data-' (Плагин Parsley)
 * bFieldInline    Отображать поле как инлайновое или нет
 * bFieldNoMargin  Убрать отступы
 *}

{* Правила валидации *}
{$aFieldRules = " "|explode:$sFieldRules}

{* Список полей в которых не нужно выводить лейбл *}
{$aLabelExclude = [
    'form.field.checkbox.tpl', 
    'form.field.radio.tpl'
]}

<div class="form-field {if $bFieldInline}form-field-inline{/if} {block name='field_item_classes'}{/block} {if $bFieldNoMargin}m-0{/if}">
    {if $sFieldLabel && ! in_array($smarty.template, $aLabelExclude)}
        <label for="{$sFieldName}" class="form-field-label">{$sFieldLabel}:</label>
    {/if}

    <div class="form-field-holder">
        {block name='field_holder'}
            {block name='field_holder_input'}{/block}

            {if $sFieldNote}
                <small class="note" id="{$sFieldName}_note">{$sFieldNote}</small>
            {/if}
        {/block}
    </div>
</div>