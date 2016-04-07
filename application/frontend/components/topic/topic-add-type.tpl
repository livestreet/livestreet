{**
 * Подключение шаблона редактировани топика определенного типа
 *
 * @param object  $topic
 * @param boolean $isPreview
 *}

{component_define_params params=[ 'topic', 'type', 'blogs', 'blogId', 'skipBlogs', 'mods', 'classes', 'attributes' ]}

{$typeCode = $type->getCode()}

{if $LS->Topic_IsAllowTopicType($typeCode)}
    {$template = $LS->Component_GetTemplatePath('topic', "topic-add-type-{$typeCode}")}

    {if $template}
        {component "topic.topic-add-type-$typeCode" params=$params}
    {else}
        {component 'topic.add' params=$params}
    {/if}
{/if}