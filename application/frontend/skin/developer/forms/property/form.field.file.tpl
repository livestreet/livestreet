{$value = $oProperty->getValue()}
{$valueType = $value->getValueTypeObject()}

{include 'components/field/field.file.tpl'
         name    = "property[{$oProperty->getId()}][file]"
         classes = 'width-300'
         note    = $oProperty->getDescription()
         label   = $oProperty->getTitle()}

{$file = $value->getDataOne('file')}

{if $file}
    Загружен файл: {$file.name}.{$file.extension} <br/>
    <label>
        <input type="checkbox" name="property[{$oProperty->getId()}][remove]" value="1"> &mdash; удалить файл
    </label>
    <br/>
{/if}