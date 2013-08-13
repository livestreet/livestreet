{**
 * Базовый шаблон поля формы
 *
 * Параметры
 * ---------
 * sFieldName      Имя поля (параметр name)
 * sFieldLabel     Текст лэйбла
 * sFieldNote      Подсказка (отображается под полем)
 * sFieldRules     Правила валидации через пробел без префикса 'data-' (Плагин Parsley)
 * bFieldInline    Отображать поле как инлайновое или нет
 * bFieldNoMargin  Убрать отступы
 *}

{* Правила валидации *}
{$aFieldRules = " "|explode:$sFieldRules}

{block name='field_before'}{/block}

<div class="form-field {if $bFieldInline}form-field-inline{/if} {block name='field_classes'}{/block} {if $bFieldNoMargin}m-0{/if}">
    {if $sFieldLabel && ! $bFieldNoLabel}
        <label for="{$sFieldName}" class="form-field-label">{$sFieldLabel}:</label>
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