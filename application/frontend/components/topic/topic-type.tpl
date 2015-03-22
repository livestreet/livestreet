{**
 * Подключение шаблона топика определенного типа
 *
 * @param object  $topic
 * @param boolean $isPreview
 *}

{$topic = $smarty.local.topic}
{$type = $topic->getType()}

{if $LS->Topic_IsAllowTopicType( $type )}
	{$template = $LS->Component_GetTemplatePath('topic', "topic-type-{$type}" )}

	{* Если для указанного типа существует шаблон, то подключаем его *}
	{* Иначе подключаем дефолтный шаблон топика *}
	{if ! $template}
		{$template = $LS->Component_GetTemplatePath('topic', 'topic')}
	{/if}

	{include "$template" topic=$topic isList=$smarty.local.isList isPreview=$smarty.local.isPreview}
{/if}