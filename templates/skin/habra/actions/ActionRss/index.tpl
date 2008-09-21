<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
	<title>{$aChannel.title}</title>
	<link>{$aChannel.link}</link>
	<description><![CDATA[{$aChannel.description}]]></description>
	<language>{$aChannel.language}</language>
	<managingEditor>{$aChannel.managingEditor}</managingEditor>
	<generator>{$aChannel.generator}</generator>
{foreach from=$aItems item=oItem}
		<item>
			<title><![CDATA[{$oItem.title|escape:'html'}]]></title>
			<guid isPermaLink="true">{$oItem.guid}</guid>
			<link>{$oItem.link}</link>
			<description><![CDATA[{$oItem.description}]]></description>
			<pubDate>{date_format date=$oItem.pubDate format="r"}</pubDate>
			<author>{$oItem.author}</author>
			<category>{$oItem.category|replace:',':'</category>
			<category>'}</category>
		</item>
{/foreach}
</channel>
</rss>
