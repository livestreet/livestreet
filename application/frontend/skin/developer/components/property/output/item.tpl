{* TODO: Viewer_TemplateExists не работает *}

{$property = $smarty.local.property}

{if $property}
    {* Проверяем наличие кастомного шаблона item.[type].[target_type].tpl *}
    {$template = "./property.{$property->getType()}.{$property->getTargetType()}.tpl"}

    {if $LS->Viewer_TemplateExists( $template )}
        {include "{$template}" property=$property}
    {else}
        {* Проверяем наличие кастомного шаблона item.[type].tpl *}
        {$template = "./property.{$property->getType()}.tpl"}

        {if $LS->Viewer_TemplateExists( $template )}
            {include "{$template}" property=$property}
        {else}
            {* Показываем стандартный шаблон *}
            {include "./property.default.tpl"}
        {/if}
    {/if}
{/if}