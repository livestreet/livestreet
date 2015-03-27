{$property = $smarty.local.property}

{if $property}
    {* Проверяем наличие кастомного шаблона item.[type].[target_type].tpl *}
    {$template = $LS->Component_GetTemplatePath('property', "output/property.{$property->getType()}.{$property->getTargetType()}" )}

    {if !$template}
        {$template = $LS->Component_GetTemplatePath('property', "output/property.{$property->getType()}" )}
        {if !$template}
            {$template = $LS->Component_GetTemplatePath('property', "output/property.default" )}
        {/if}
    {/if}

    {include "{$template}" property=$property}
{/if}