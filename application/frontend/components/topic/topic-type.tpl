{**
 * Подключение шаблона топика определенного типа
 *
 * @param object  $topic
 * @param boolean $isPreview
 *}

{component_define_params params=[ 'topic', 'isPreview', 'isList', 'mods', 'classes', 'attributes' ]}

{$type = $topic->getType()}

{if $LS->Topic_IsAllowTopicType($type)}
    {$template = $LS->Component_GetTemplatePath('topic', "topic-type-{$type}")}

    {if $template}
        {component "topic.topic-type-$type" params=$params}
    {else}
        {component 'topic' params=$params}
    {/if}
{/if}