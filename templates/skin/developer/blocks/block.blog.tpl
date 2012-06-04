{if $oTopic}
	{assign var="oBlog" value=$oTopic->getBlog()}
	{if $oBlog->getType()!='personal'}
	<section class="block block-type-blog">
		<header class="block-header">
			<h3><a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a></h3>
		</header>

		<div class="block-content">
			<span id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</span> {$oBlog->getCountUser()|declension:$aLang.reader_declension:'russian'}<br />
			{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension:'russian'}
			
			<br />
			<br />
			
			{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
				<button type="submit" class="button button-primary {if $oBlog->getUserIsJoin()}active{/if}" id="blog-join" data-only-text="1" onclick="ls.blog.toggleJoin(this,{$oBlog->getId()}); return false;">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</button>&nbsp;&nbsp;
			{/if}
			<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="rss">RSS</a>
		</div>
	</section>
	{/if}
{/if}