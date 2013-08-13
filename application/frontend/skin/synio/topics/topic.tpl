{**
 * Топик
 *}

{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
	{include file="topics/topic.{$oTopic->getType()}.tpl"}
{/if}