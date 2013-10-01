{**
 * Прямой эфир
 * Топики отсортированные по времени последнего комментария
 *
 * @styles css/blocks.css
 *}

<div class="block-content">
	<ul class="item-list">
		{foreach $aComments as $oComment}
			{$oUser = $oComment->getUser()}
			{$oTopic = $oComment->getTarget()}
			{$oBlog = $oTopic->getBlog()}

			<li class="js-title-comment" title="{$oComment->getText()|strip_tags|trim|truncate:100:'...'|escape:'html'}">
				<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

				<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getDisplayName()}</a> &rarr;
				<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
				<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">{$oTopic->getTitle()|escape:'html'}</a>

				<p>
					<time datetime="{date_format date=$oComment->getDate() format='c'}">{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time> |
					{$oTopic->getCountComment()}&nbsp;{$oTopic->getCountComment()|declension:$aLang.comment_declension}
				</p>
			</li>
		{/foreach}
	</ul>
</div>

<footer class="block-footer">
	<a href="{router page='comments'}">{$aLang.block_stream_comments_all}</a> | <a href="{router page='rss'}allcomments/">RSS</a>
</footer>