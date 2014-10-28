{**
 * Прямой эфир
 * Последние топики
 *
 * @styles css/blocks.css
 *}

<div class="block-content">
	<ul class="item-list">
		{foreach $smarty.local.topics as $oTopic}
			{$oUser = $oTopic->getUser()}
			{$oBlog = $oTopic->getBlog()}

			<li class="js-title-topic" title="{$oTopic->getText()|strip_tags|trim|truncate:150:'...'|escape}">
				<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

				<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getDisplayName()}</a> &rarr;
				<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape}</a> &rarr;
				<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape}</a>

				<p>
					<time datetime="{date_format date=$oTopic->getDate() format='c'}">
						{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
					</time> |

					{lang name='comments.comments_declension' count=$oTopic->getCountComment() plural=true}
				</p>
			</li>
		{/foreach}
	</ul>
</div>