<ul class="list">
	{foreach from=$oTopics item=oTopic name="cmt"}
		{assign var="oUser" value=$oTopic->getUser()}							
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> &rarr;
			<span class="stream-topic-icon"></span>
			<a href="{$oTopic->getUrl()}" class="topic-title">{$oTopic->getTitle()|escape:'html'}</a>
			<span>{$oTopic->getCountComment()}</span> &rarr;
			<a href="{$oBlog->getUrlFull()}" class="blog-title">{$oBlog->getTitle()|escape:'html'}</a>
		</li>						
	{/foreach}				
</ul>


<div class="bottom">
	<a href="{router page='new'}">{$aLang.block_stream_topics_all}</a> | <a href="{router page='rss'}new/">RSS</a>
</div>
					