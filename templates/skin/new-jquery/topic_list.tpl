{if count($aTopics)>0}
	{foreach from=$aTopics item=oTopic}   
	   {assign var="sTopicTemplateName" value="topic_`$oTopic->getType()`.tpl"}
	   {include file=$sTopicTemplateName bTopicList=true}
	{/foreach}	
		
    {include file='paging.tpl' aPaging="$aPaging"}			
{else}
	<div class="padding">{$aLang.blog_no_topic}</div>
{/if}