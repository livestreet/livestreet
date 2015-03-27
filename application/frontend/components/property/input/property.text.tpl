{if $property->getParam( 'use_html' )}
    {component 'editor'
        name            = "property[{$property->getId()}]"
        value           = $property->getValue()->getValueForForm()
        label           = $property->getTitle()
        inputClasses    = 'js-editor-default' }

{else}
    {component 'field' template='textarea'
        name  = "property[{$property->getId()}]"
        value = $property->getValue()->getValueForForm()
        rows  = 10
        note  = $property->getDescription()
        label = $property->getTitle()}
{/if}