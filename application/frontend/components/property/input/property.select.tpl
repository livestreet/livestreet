{* Формируем массив с активными пунктами *}
{$selectedValues = []}

{foreach $property->getValue()->getValueForForm() as $value}
    {$selectedValues[] = $value@key}
{/foreach}

{* Формируем значения для селекта *}
{$items = []}
{if !$property->getValidateRuleOne('allowMany')}
    {$items[] = [
        'value' => 0,
        'text' => '&mdash;'
    ]}
{/if}

{foreach $property->getSelects() as $item}
    {$items[] = [
        'value' => $item->getId(),
        'text' => $item->getValue()
    ]}
{/foreach}

{component 'field' template='select'
    name          = "property[{$property->getId()}][]"
    label         = $property->getTitle()
    note          = $property->getDescription()
    items         = $items
    isMultiple    = $property->getValidateRuleOne('allowMany')
    selectedValue = $selectedValues}