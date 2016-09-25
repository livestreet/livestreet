{**
 * Блок добавления ответа
 *
 * @param boolean $item
 * @param integer $index
 * @param boolean $allowRemove
 * @param boolean $showRemove
 * @param boolean $isTemplate
 *}

{$component = 'ls-poll-form-answer-item'}
{component_define_params params=[ 'item', 'index', 'allowRemove', 'showRemove', 'isTemplate' ]}

{$allowUpdate = $allowUpdate|default:true}
{$allowRemove = $allowRemove|default:true}
{$showRemove = $showRemove|default:true}
{$index = $index|default:0}

<li class="{$component} js-poll-form-answer-item"
    {if $isTemplate}data-is-template="true"{/if}
    {if $isTemplate}style="display: none"{/if}>

    {* ID *}
    {component 'field' template='hidden'
        name    = "answers[{$index}][id]"
        value   = "{if $item}{$item->getId()}{/if}"
        classes = "js-poll-form-answer-item-id"}

    {* Текст *}
    {component 'field' template='text'
        name         = 'answers[]'
        value        = ($item) ? $item->getTitle() : ''
        isDisabled   = ! $allowUpdate
        inputClasses = 'ls-width-full js-poll-form-answer-item-text'}

    {* Кнопка удаления *}
    {if $allowRemove}
        {component 'syn-icon'
            icon='trash'
            classes="{$component}-remove js-poll-form-answer-item-remove"
            attributes=[
                style => "{if ! $showRemove}display: none{/if}"
            ]}
    {/if}
</li>