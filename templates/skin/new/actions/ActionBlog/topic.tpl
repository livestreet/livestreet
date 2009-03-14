{if $oUserCurrent}
	{include file='header.tpl' menu='blog' showUpdateButton=true}
{else}
	{include file='header.tpl' menu='blog'}
{/if}
			{include file='topic.tpl'}			
			
			{include file='actions/ActionBlog/comment.tpl'}	
	
{include file='footer.tpl'}