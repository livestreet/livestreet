{assign var="oBlog" value=$oTopic->getBlog()}
{assign var="oUser" value=$oTopic->getUser()}
{assign var="oVote" value=$oTopic->getVote()}


<article class="topic topic-type-{$oTopic->getType()}">
	<header class="topic-header">
		<h1 class="topic-title">
			{if $oTopic->getPublish() == 0}   
				<img src="{cfg name='path.static.skin'}/images/draft.png" title="{$aLang.topic_unpublish}" alt="{$aLang.topic_unpublish}" />
			{/if}
			
			{if $oTopic->getType() == 'link'} 
                <img src="{cfg name='path.static.skin'}/images/topic_link.png" title="{$aLang.topic_link}" alt="{$aLang.topic_link}" />
			{/if}
			
			{if $bTopicList}
				<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
			{else}
				{$oTopic->getTitle()|escape:'html'}
			{/if}
		</h1>
		
		
		{if $oTopic->getType() == 'link'}
			<div class="topic-url">
				<a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl()}</a>
			</div>
		{/if}
		
		
		<div class="topic-info">
			<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" pubdate title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
				{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}
			</time>
			
			<a href="{$oBlog->getUrlFull()}" class="topic-blog">{$oBlog->getTitle()|escape:'html'}</a>
		</div>
	</header>