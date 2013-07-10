{**
 * Прямой эфир
 * Последние топики
 *
 * @styles css/blocks.css
 *}

<div class="block-content">
	<ul class="latest-list">
		{foreach $oTopics as $oTopic}
			{$oUser = $oTopic->getUser()}							
			{$oBlog = $oTopic->getBlog()}
			
			<li class="js-title-topic" title="{$oTopic->getText()|strip_tags|trim|truncate:150:'...'|escape:'html'}">
				<p>
					<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a>
					<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}">
						{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
					</time>
				</p>
				<a href="{$oBlog->getUrlFull()}" class="stream-blog">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
				<a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
				<span class="block-item-comments"><i class="icon-synio-comments-small"></i>{$oTopic->getCountComment()}</span>
			</li>
		{/foreach}
	</ul>
</div>

<footer class="block-footer">
	<a href="{router page='index'}new/">{$aLang.block_stream_topics_all}</a> · <a href="{router page='rss'}new/">RSS</a>
</footer>		