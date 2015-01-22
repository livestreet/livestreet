{**
 * Подключение шаблона топика определенного типа
 *
 * @param object  $topic
 * @param boolean $isPreview
 *}

{$topic = $smarty.local.topic}
{$type = $topic->getType()}

{if $LS->Topic_IsAllowTopicType( $type )}
	{$template = $smarty.current_dir|cat:"/topic.type.{$type}.tpl"}

	{* Если для указанного типа существует шаблон, то подключаем его *}
	{* Иначе подключаем дефолтный шаблон топика *}
	{if ! $LS->Viewer_TemplateExists( $template )}
		{$template = './topic.tpl'}
	{/if}

	{include "$template" topic=$topic isList=$smarty.local.isList isPreview=$smarty.local.isPreview}
{/if}