{**
 * Подключение шаблона топика определенного типа
 *
 * @param object $topic
 *}

{$topic = $smarty.local.topic}
{$type = $topic->getType()}

{if $LS->Topic_IsAllowTopicType( $type )}
	{$template = "./topic.type.{$type}.tpl"}

	{* Если для указанного типа существует шаблон, то подключаем его *}
	{* Иначе подключаем дефолтный шаблон топика *}
	{if ! $LS->Viewer_TemplateExists( $template )}
		{$template = './topic.tpl'}
	{/if}

	{include "$template" topic=$topic isList=$smarty.local.isList}
{/if}