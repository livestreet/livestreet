{**
 * Содержимое поповера с информацией о блоге
 *}

<ul class="dotted-list blog-info">
	<li class="dotted-list-item">
		<span class="dotted-list-item-label">{$aLang.infobox_blog_create}</span>
		<span class="dotted-list-item-value">{date_format date=$oBlog->getDateAdd() format="j F Y"}</span>
	</li>
	<li class="dotted-list-item">
		<span class="dotted-list-item-label">{$aLang.infobox_blog_topics}</span>
		<span class="dotted-list-item-value">{$oBlog->getCountTopic()}</span>
	</li>
	<li class="dotted-list-item">
		<span class="dotted-list-item-label"><a href="{$oBlog->getUrlFull()}users/">{$aLang.infobox_blog_users}</a></span>
		<span class="dotted-list-item-value">{$oBlog->getCountUser()}</span>
	</li>
	<li class="dotted-list-item blog-info-rating">
		<span class="dotted-list-item-label">{$aLang.infobox_blog_rating}</span>
		<span class="dotted-list-item-value">{$oBlog->getRating()}</span>
	</li>
</ul>

{if $oTopicLast}
	{$aLang.infobox_blog_topic_last}:<br/>
	<a href="{$oTopicLast->getUrl()}" class="popover-blog-info-topic">{$oTopicLast->getTitle()|escape:'html'}</a>

	<br/>
	<br/>
{/if}

<div class="popover-blog-info-actions">
	<a href="{$oBlog->getUrlFull()}">{$aLang.infobox_blog_url}</a><br/>
	<a href="{router page='rss'}blog/{$oBlog->getUrl()}/">{$aLang.infobox_blog_rss}</a>
</div>