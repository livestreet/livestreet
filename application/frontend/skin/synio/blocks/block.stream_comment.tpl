{**
 * Прямой эфир
 * Топики отсортированные по времени последнего комментария
 *
 * @styles css/blocks.css
 *}

<div class="block-content">
	<ul class="latest-list">
		{foreach $aComments as $oComment}
			{$oUser = $oComment->getUser()}
			{$oTopic = $oComment->getTarget()}
			{$oBlog = $oTopic->getBlog()}
			
			<li class="js-title-comment" title="{$oComment->getText()|strip_tags|trim|truncate:100:'...'|escape:'html'}">
				<p>
					<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getDisplayName()}</a>
					<time datetime="{date_format date=$oComment->getDate() format='c'}" title="{date_format date=$oComment->getDate() format="j F Y, H:i"}">
						{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
					</time>
				</p>
				<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
				<span class="block-item-comments"><i class="icon-synio-comments-small"></i>{$oTopic->getCountComment()}</span>
			</li>
		{/foreach}
	</ul>
</div>

<footer class="block-footer">
	<a href="{router page='comments'}">{$aLang.block_stream_comments_all}</a> · <a href="{router page='rss'}allcomments/">RSS</a>
</footer>