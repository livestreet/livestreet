{**
 * Стена / Запись (Пост / Комментарий)
 *
 * @param object  $oWallEntry          Комментарий
 * @param boolean $bWallEntryShowReply Показывать или нет кнопку комментирования  
 * @param string  $sWallEntryClasses   Классы  
 *
 * TODO: Унаследовать от базового шаблона комментария
 *}

{$oUser = $oWallEntry->getUser()}

<div class="comment js-wall-comment {$sWallEntryClasses}" data-id="{$oWallEntry->getId()}">
	<a href="{$oUser->getUserWebPath()}">
		<img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="comment-avatar" />
	</a>

	<ul class="comment-info">
		<li class="comment-author"><a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a></li>
		<li class="comment-date">
			<time datetime="{date_format date=$oWallEntry->getDateAdd() format='c'}">
				{date_format date=$oWallEntry->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
			</time>
		</li>
	</ul>

	<div class="comment-content text">
		{$oWallEntry->getText()}
	</div>

	{if ( $oUserCurrent && $bWallEntryShowReply ) || $oWallEntry->isAllowDelete()}
		<ul class="comment-actions">
			{if $bWallEntryShowReply}
				<li><a href="#" class="link-dotted js-wall-comment-reply" data-id="{$oWallEntry->getId()}">{$aLang.wall_action_reply}</a></li>
			{/if}

			{if $oWallEntry->isAllowDelete()}
				<li><a href="#" class="link-dotted js-wall-comment-remove" data-id="{$oWallEntry->getId()}">{$aLang.wall_action_delete}</a></li>
			{/if}
		</ul>
	{/if}
</div>