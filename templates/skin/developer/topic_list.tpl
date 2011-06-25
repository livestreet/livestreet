{if count($aTopics)>0}
	{foreach from=$aTopics item=oTopic}   
		<!-- Topic -->	
                           {assign var="sTopicTemplateName" value="topic_`$oTopic->getType()`.tpl"}
                           {include file=$sTopicTemplateName bTopicList=true}
		<!-- /Topic -->
	{/foreach}	
		
    {include file='paging.tpl' aPaging="$aPaging"}			
{else}
	{$aLang.blog_no_topic}
{/if}