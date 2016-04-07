{**
 * Вывод дополнительных полей для ввода данных на странице создания нового объекта
 *}

{component_define_params params=[ 'properties' ]}

{foreach $properties as $property}
    {component 'property' template='input.item' property=$property}
{/foreach}