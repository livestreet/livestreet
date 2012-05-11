<section class="block block-type-blog">
	<header class="block-header">
		<h3><a href="#">Офисы IT-компаний</a></h3>
	</header>
	
	{*<div class="block-content">
		{$iCountBlogUsers} {$iCountBlogUsers|declension:$aLang.reader_declension:'russian'}<br />
		{$oBlog->getCountTopic()} {$oBlog->getCountTopic()|declension:$aLang.topic_declension:'russian'}
	</div>	
	
	<footer>
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
			<button class="button button-small" id="blog-join" onclick="ls.blog.toggleJoin(this,{$oBlog->getId()}); return false;">{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</button>
		{/if}
		<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="rss">RSS</a>
	</footer>*}
</section>