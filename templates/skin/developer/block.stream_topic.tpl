<ul class="stream-content">
	{foreach from=$oTopics item=oTopic}
		{assign var="oUser" value=$oTopic->getUser()}							
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li>
			<a href="{$oUser->getUserWebPath()}" class="stream-author">{$oUser->getLogin()}</a>&nbsp;&#8594;
			<a href="{$oBlog->getUrlFull()}" class="stream-blog">{$oBlog->getTitle()|escape:'html'}</a>&nbsp;/
			<a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
			<span>({$oTopic->getCountComment()})</span>
		</li>						
	{/foreach}				
</ul>

<div class="right"><a href="{router page='new'}">{$aLang.block_stream_topics_all}</a> | <a href="{router page='rss'}new/">RSS</a></div>