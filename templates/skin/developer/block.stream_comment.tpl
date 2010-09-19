<ul class="list">
	{foreach from=$aComments item=oComment name="cmt"}
		{assign var="oUser" value=$oComment->getUser()}
		{assign var="oTopic" value=$oComment->getTarget()}
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li>
			<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> &rarr;
			<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
			<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">{$oTopic->getTitle()|escape:'html'}</a>
			{$oTopic->getCountComment()}
		</li>
	{/foreach}
</ul>

<div class="bottom">
	<a href="{router page='comments'}">{$aLang.block_stream_comments_all}</a>
</div>