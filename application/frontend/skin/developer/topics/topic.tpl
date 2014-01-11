{**
 * Топик
 *}

{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
	{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
		{$sTemplateType="topics/topic.type.{$oTopic->getType()}.tpl"}
		{if $LS->Viewer_TemplateExists($sTemplateType)}
			{include file=$sTemplateType}
		{else}
			{include file="topics/topic_base.tpl"}
		{/if}
	{/if}
{/if}