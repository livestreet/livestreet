{$_mods=''}
{if $property->getParam('use_time')}
    {$_mods='inline'}
{/if}
{component 'field.date' mods = $_mods
    name            = "property[{$property->getId()}][date]"
    inputAttributes = [ "data-lsdate-format" => 'DD.MM.YYYY' ]
    inputClasses    = "js-field-date-default"
    value           = $property->getValue()->getValueForForm()
    note            = $property->getDescription()
    label           = $property->getTitle()}

{if $property->getParam('use_time')}
    {component 'field.time' mods = $_mods
        name            = "property[{$property->getId()}][time]"
        inputAttributes = [ "data-lstime-time-format" => 'H:i' ]
        inputClasses    = "js-field-time-default"
        value           = $property->getValue()->getValueTypeObject()->getValueTimeForForm()
        note            = ($property->getDescription()) ? '&nbsp;' : ''}
{/if}
