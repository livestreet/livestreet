<ul class="list">
	{foreach from=$aComments item=oComment name="cmt"}
		{assign var="oUser" value=$oComment->getUser()}
		{assign var="oTopic" value=$oComment->getTarget()}
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> &rarr;
			<span class="stream-comment-icon"></span>
			<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}" class="topic-title">{$oTopic->getTitle()|escape:'html'}</a>
			<span>{$oTopic->getCountComment()}</span> &rarr;
			<a href="{$oBlog->getUrlFull()}" class="blog-title">{$oBlog->getTitle()|escape:'html'}</a>
		</li>
	{/foreach}
</ul>


<div class="bottom">
	<a href="{router page='comments'}">{$aLang.block_stream_comments_all}</a> | <a href="{router page='rss'}allcomments/">RSS</a>
</div>