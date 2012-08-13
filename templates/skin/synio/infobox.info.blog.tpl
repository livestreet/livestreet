<img src="{$oBlog->getAvatarPath(48)}" alt="avatar" /><br /><br />

<ul class="blog-info">
	<li><span>{$aLang.infobox_blog_create}</span> <strong>{date_format date=$oBlog->getDateAdd() format="j F Y"}</strong></li>
	<li><span>{$aLang.infobox_blog_topics}</span> <strong>{$oBlog->getCountTopic()}</strong></li>
	<li><span><a href="{$oBlog->getUrlFull()}users/">{$aLang.infobox_blog_users}</a></span> <strong>{$oBlog->getCountUser()}</strong></li>

	<li class="rating"><span>{$aLang.infobox_blog_rating}</span> <strong>{$oBlog->getRating()}</strong></li>
</ul>


{if $oTopicLast}
	{$aLang.infobox_blog_topic_last}:<br/>
	<a href="{$oTopicLast->getUrl()}" class="infobox-topic">{$oTopicLast->getTitle()|escape:'html'}</a>
{/if}

<br/>
<br/>
<div class="infobox-actions">
	<a href="{$oBlog->getUrlFull()}">{$aLang.infobox_blog_url}</a><br/>
	<a href="{router page='rss'}blog/{$oBlog->getUrl()}/">{$aLang.infobox_blog_rss}</a>
</div>