{$aLang.infobox_blog_create} &mdash; {$oBlog->getDateAdd()}<br/>
{$aLang.infobox_blog_topics} &mdash; {$oBlog->getCountTopic()}<br/>
{$aLang.infobox_blog_users} &mdash; {$oBlog->getCountUser()}<br/>
{$aLang.infobox_blog_rating} &mdash; {$oBlog->getRating()}<br/>
{$aLang.infobox_blog_limit_rating} &mdash; {$oBlog->getLimitRatingTopic()}<br/>

{if $oTopicLast}
	<br/>
	{$aLang.infobox_blog_topic_last}:<br/>
	<a href="{$oTopicLast->getUrl()}">{$oTopicLast->getTitle()|escape:'html'}</a>
{/if}

<br/>
<br/>
<a href="{$oBlog->getUrlFull()}">{$aLang.infobox_blog_url}</a><br/>
<a href="{router page='rss'}blog/{$oBlog->getUrl()}/">{$aLang.infobox_blog_rss}</a><br/>