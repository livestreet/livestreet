{**
 * Вывод дополнительных полей для ввода данных на странице создания нового объекта
 *}

{foreach $smarty.local.properties as $property}
    {component 'property' template='input.item' property=$property}
{/foreach}