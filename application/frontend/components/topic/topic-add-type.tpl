{**
 * Подключение шаблона редактировани топика определенного типа
 *
 * @param object  $topic
 * @param boolean $isPreview
 *}

{$type = $smarty.local.type}
{$typeCode = $type->getCode()}

{if $LS->Topic_IsAllowTopicType( $typeCode )}
    {$template = $LS->Component_GetTemplatePath('topic', "topic-add-type-{$typeCode}" )}

    {* Если для указанного типа существует шаблон, то подключаем его *}
    {* Иначе подключаем дефолтный шаблон топика *}
    {if ! $template}
        {$template = $LS->Component_GetTemplatePath('topic', 'add')}
    {/if}

    {include "$template"
        topic=$smarty.local.topic
        type=$smarty.local.type
        blogs=$smarty.local.blogs
        blogId=$smarty.local.blogId
        skipBlogs=$smarty.local.skipBlogs }
{/if}