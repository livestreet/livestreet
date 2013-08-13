{**
 * Прямой эфир
 * Последние топики
 *
 * @styles css/blocks.css
 *}

<div class="block-content">
	<ul class="item-list">
		{foreach $oTopics as $oTopic}
			{$oUser = $oTopic->getUser()}
			{$oBlog = $oTopic->getBlog()}

			<li class="js-title-topic" title="{$oTopic->getText()|strip_tags|trim|truncate:150:'...'|escape:'html'}">
				<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

				<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a> &rarr;
				<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
				<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>

				<p>
					<time datetime="{date_format date=$oTopic->getDate() format='c'}">{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time> |
					{$oTopic->getCountComment()} {$oTopic->getCountComment()|declension:$aLang.comment_declension}
				</p>
			</li>
		{/foreach}
	</ul>
</div>

<footer class="block-footer">
	<a href="{router page='index'}new/">{$aLang.block_stream_topics_all}</a> | <a href="{router page='rss'}new/">RSS</a>
</footer>