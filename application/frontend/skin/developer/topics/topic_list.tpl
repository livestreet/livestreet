{**
 * Список топиков
 *}

{if $aTopics}
	{add_block group='toolbar' name='toolbar/toolbar.topic.tpl' iCountTopic=count($aTopics)}

	{foreach $aTopics as $oTopic}
		{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
			{include file="topics/topic.{$oTopic->getType()}.tpl" bTopicList=true}
		{/if}
	{/foreach}

	{include file='pagination.tpl' aPaging=$aPaging}
{else}
	{$aLang.blog_no_topic}
{/if}