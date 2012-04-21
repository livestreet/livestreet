{if count($aTopics)>0}
	{add_block group='toolbar' name='toolbar_topic.tpl' iCountTopic=count($aTopics)}

	{foreach from=$aTopics item=oTopic}
		{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
			{assign var="sTopicTemplateName" value="topic_`$oTopic->getType()`.tpl"}
			{include file=$sTopicTemplateName bTopicList=true}
		{/if}
	{/foreach}

	{include file='paging.tpl' aPaging=$aPaging}
{else}
	{$aLang.blog_no_topic}
{/if}