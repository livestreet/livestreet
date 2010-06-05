<ul class="list">
	{foreach from=$oTopics item=oTopic name="cmt"}
		{assign var="oUser" value=$oTopic->getUser()}							
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li>
			<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> &rarr;
			<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
			<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
			{$oTopic->getCountComment()}
		</li>						
	{/foreach}				
</ul>

<div class="bottom">
	<a href="{router page='new'}">{$aLang.block_stream_topics_all}</a>
</div>
					