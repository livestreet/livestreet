{**
 * Топик
 *}

{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
	{assign var="sTopicTemplateName" value="topics/topic.`$oTopic->getType()`.tpl"}
	{include file=$sTopicTemplateName}
{/if}