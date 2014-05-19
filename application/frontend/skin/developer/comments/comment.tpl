{**
 * Комментарий
 *
 * @param boolean bAllowNewComment      true если разрешно добавлять новые комментарии
 * @param boolean bOneComment
 * @param boolean bNoCommentFavourites  true если не нужно выводить кнопку добавления в избранное
 * @param integer iAuthorId             ID автора топика
 * @param boolean bList                 true если комментарий выводится в списках (например на странице Избранные комментарии)
 *
 * @styles css/comments.css
 *}

{$oUser = $oComment->getUser()}


{* Выводим ссылки на блог и топик в котором находится комментарий (только в списках) *}
{if $bList}
	{$oTopic = $oComment->getTarget()}
	{$oBlog = $oTopic->getBlog()}

	<div class="comment-path">
		<a href="{$oBlog->getUrlFull()}" class="comment-path-blog">{$oBlog->getTitle()|escape}</a> &rarr;
		<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape}</a>
		<a href="{$oTopic->getUrl()}#comments">({$oTopic->getCountComment()})</a>
	</div>
{/if}


{* Комментарий *}
<section data-id="{$oComment->getId()}" id="comment{$oComment->getId()}" class="js-comment comment open
														{if ! $bList}
															{if $oComment->isBad()}
																comment-bad
															{/if}

															{if $oComment->getDelete()}
																comment-deleted
															{elseif $oUserCurrent and $oComment->getUserId() == $oUserCurrent->getId()}
																comment-self
															{elseif $sDateReadLast <= $oComment->getDate()}
																comment-new
															{/if}
														{else}
															comment-list-item
														{/if}">
	{if ! $oComment->getDelete() or ($oUserCurrent and $oUserCurrent->isAdministrator())}
		{* Аватар пользователя *}
		<a href="{$oUser->getUserWebPath()}">
			<img src="{$oUser->getProfileAvatarPath(48)}" alt="{$oUser->getDisplayName()}" class="comment-avatar" />
		</a>

		{* Информация *}
		<ul class="comment-info">
			{* Автор комментария *}
			<li class="comment-username {if $iAuthorId == $oUser->getId()}comment-username-author{/if}" title="{if $sAuthorNotice}{$sAuthorNotice}{/if}">
				<a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a>
			</li>

			{* Дата *}
			<li class="comment-date">
				<a href="{if Config::Get('module.comment.use_nested')}{router page='comments'}{else}#comment{/if}{$oComment->getId()}" class="link-dotted" title="{$aLang.comments.comment.url}">
					<time datetime="{date_format date=$oComment->getDate() format='c'}">{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
				</a>
			</li>

			{* Прокрутка к родительскии/дочернии комментариям *}
			{if ! $bList and $oComment->getPid()}
				<li class="comment-scroll-to comment-scroll-to-parent js-comment-scroll-to-parent" title="{$aLang.comments.comment.scroll_to_parent}" data-id="{$oComment->getId()}" data-parent-id="{$oComment->getPid()}">↑</li>
			{/if}

			<li class="comment-scroll-to comment-scroll-to-child js-comment-scroll-to-child" title="{$aLang.comments.comment.scroll_to_child}">↓</li>

			{* Голосование *}
			{if $oComment->getTargetType() != 'talk'}
				<li>{include 'components/vote/vote.tpl' sClasses='js-vote-comment' oObject=$oComment bIsLocked=($oUserCurrent && $oUserCurrent->getId() == $oUser->getId())}</li>
			{/if}

			{* Избранное *}
			{if $oUserCurrent and ! $bNoCommentFavourites}
				<li>{include 'components/favourite/favourite.tpl' sClasses='js-favourite-comment' oObject=$oComment}</li>
			{/if}
		</ul>


		{* Текст комментария *}
		<div id="comment_content_id_{$oComment->getId()}" class="comment-content text">
			{$oComment->getText()}
		</div>

		{* Информация о редактировании *}
		{if $oComment->getDateEdit()}
		<div>
			{$aLang.comments.comment.edit_info}: {date_format date=$oComment->getDateEdit() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
			{if $oComment->getCountEdit()>1}
				({$oComment->getCountEdit()} {$oComment->getCountEdit()|declension:$aLang.common.times_declension})
			{/if}
		</div>
		{/if}

		{* Кнопки ответа, удаления и т.д. *}
		{if $oUserCurrent}
			<ul class="comment-actions">
				{if ! $bList and ! $oComment->getDelete() and ! $bAllowNewComment}
					<li><a href="#" class="link-dotted js-comment-reply" data-id="{$oComment->getId()}">{$aLang.comments.comment.reply}</a></li>
				{/if}

				<li class="link-dotted comment-fold js-comment-fold open" data-id="{$oComment->getId()}" style="display: none"><a href="#">{$aLang.comments.folding.fold}</a></li>

				{if $oComment->IsAllowEdit()}
                    <li>
						<a href="#" class="link-dotted js-comment-update" data-id="{$oComment->getId()}">
							{$aLang.common.edit}
							{if $oComment->getEditTimeRemaining()}
								(<span class="js-comment-update-timer" data-seconds="{$oComment->getEditTimeRemaining()}"></span>)
							{/if}
						</a>
					</li>
				{/if}

				{if $oComment->IsAllowDelete()}
					<li><a href="#" class="link-dotted js-comment-remove" data-id="{$oComment->getId()}">{($oComment->getDelete()) ? $aLang.comments.comment.restore : $aLang.common.remove}</a></li>
				{/if}

				{hook run='comment_action' comment=$oComment}
			</ul>
		{/if}
	{else}
		{$aLang.comments.comment.deleted}
	{/if}
</section>