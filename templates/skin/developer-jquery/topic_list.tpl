{if count($aTopics)>0}
	{foreach from=$aTopics item=oTopic}   
		{include file='topic.tpl'}	
	{/foreach}	
		
    {include file='paging.tpl' aPaging="$aPaging"}			
{else}
	{$aLang.blog_no_topic}
{/if}