{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
	{assign var="sTopicTemplateName" value="topic_`$oTopic->getType()`.tpl"}
	{include file=$sTopicTemplateName}
{/if}