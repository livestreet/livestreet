<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{$aChannel.title}</title>
        <link>{$aChannel.link}</link>
        <atom:link href="{$PATH_WEB_CURRENT}/" rel="self" type="application/rss+xml" />
        <description><![CDATA[{$aChannel.description}]]></description>
        <language>{$aChannel.language}</language>
        <managingEditor>{$aChannel.managingEditor} ({Router::GetPath('/')})</managingEditor>
        <webMaster>{$aChannel.managingEditor} ({Router::GetPath('/')})</webMaster>
        <copyright>{Router::GetPath('/')}</copyright>
        <generator>{$aChannel.generator}</generator>

        {foreach $aItems as $item}
            <item>
                <title>{$item.title|escape:'html'}</title>
                <guid isPermaLink="true">{$item.guid}</guid>
                <link>{$item.link}</link>
                <dc:creator>{$item.author}</dc:creator>
                <description><![CDATA[{$item.description}]]></description>
                <pubDate>{date_format date=$item.pubDate format="r"}</pubDate>
                <category>{$item.category|replace:',':'</category>
                <category>'}</category>
            </item>
        {/foreach}
    </channel>
</rss>