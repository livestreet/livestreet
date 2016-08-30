{**
 * Заметка
 *
 * @param object   $note        Заметка
 * @param integer  $targetId    ID сущности
 * @param boolean  $isEditable  Можно редактировать заметку или нет
 *}

{* Название компонента *}
{$component = 'ls-note'}
{component_define_params params=[ 'note', 'isEditable', 'targetId', 'mods', 'classes', 'attributes' ]}

{* Установка дефолтных значений *}
{$isEditable = $isEditable|default:true}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" data-param-user_id="{$targetId}" {cattr list=$attributes}>
    {* Заметка *}
    <div class="{$component}-body js-note-body">
        {* Текст *}
        <p class="{$component}-text js-note-text" {if ! $note}style="display: none"{/if}>
            {if $note}
                {$note->getText()}
            {/if}
        </p>

        {* Действия *}
        {if $isEditable}
            <ul class="{$component}-actions js-note-actions ls-clearfix" {if ! $note}style="display: none;"{/if}>
                <li><a href="#" class="js-note-actions-edit">{$aLang.common.edit}</a></li>
                <li><a href="#" class="js-note-actions-remove">{$aLang.common.remove}</a></li>
            </ul>

            {* Добавить *}
            <ul class="{$component}-actions {$component}-actions--add ls-clearfix js-note-add" {if $note}style="display: none;"{/if}>
                <li><a href="#" class="">{$aLang.user_note.add}</a></li>
            </ul>
        {/if}
    </div>

    {* Форма редактирования *}
    {if $isEditable}
        <form class="{$component}-form js-note-form" style="display: none;">
            {component 'field' template='textarea' inputClasses="$component-form-text js-note-form-text"}

            {component 'button' mods='primary' text=$aLang.common.save}
            {component 'button' type='button' classes="js-note-form-cancel" text=$aLang.common.cancel}
        </form>
    {/if}
</div>