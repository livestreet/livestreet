{**
 * Вывод дополнительных полей для ввода данных на странице создания нового объекта
 *}

{foreach $smarty.local.properties as $property}
    {include './item.tpl' property=$property}
{/foreach}