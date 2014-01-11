{**
 * Список топиков
 *}

{if $aTopics}
	{add_block group='toolbar' name='toolbar/toolbar.topic.tpl' iCountTopic=count($aTopics)}

	{foreach $aTopics as $oTopic}
		{if $LS->Topic_IsAllowTopicType($oTopic->getType())}
			{$sTemplateType="topics/topic.type.{$oTopic->getType()}.tpl"}
			{if $LS->Viewer_TemplateExists($sTemplateType)}
				{include file=$sTemplateType bTopicList=true}
			{else}
				{include file="topics/topic_base.tpl" bTopicList=true}
			{/if}
		{/if}
	{/foreach}

	{include file='pagination.tpl' aPaging=$aPaging}
{else}
	{include file='alert.tpl' mAlerts=$aLang.blog_no_topic sAlertStyle='empty'}
{/if}