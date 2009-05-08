<ul class="stream-content">
	{foreach from=$oTopics item=oTopic name="cmt"}
		<li>
			<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oTopic->getUserLogin()}/" class="stream-author">{$oTopic->getUserLogin()}</a>&nbsp;&#8594;
			<a href="{$oTopic->getBlogUrlFull()}" class="stream-blog">{$oTopic->getBlogTitle()|escape:'html'}</a>&nbsp;/
			<a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
			<span>({$oTopic->getCountComment()})</span>
		</li>						
	{/foreach}				
</ul>