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
<section id="comment_id_{$oComment->getId()}" class="comment
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
		<a name="comment{$oComment->getId()}"></a>
		
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
				<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}#comment{/if}{$oComment->getId()}" class="link-dotted" title="{$aLang.comment_url_notice}">
					<time datetime="{date_format date=$oComment->getDate() format='c'}">{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
				</a>
			</li>
			
			{* Ссылки на родительские/дочерние комментарии *}
			{if ! $bList and $oComment->getPid()}
				<li class="comment-goto comment-goto-parent">
					<a href="#" onclick="ls.comments.goToParentComment({$oComment->getId()},{$oComment->getPid()}); return false;" title="{$aLang.comment_goto_parent}">↑</a>
				</li>
			{/if}

			<li class="comment-goto comment-goto-child"><a href="#" title="{$aLang.comment_goto_child}">↓</a></li>
			
			{**
			 * Блок голосования
			 * Не выводим блок голосования в личных сообщениях и списках
			 *}
			{if $oComment->getTargetType() != 'talk'}	
				<li>{include 'vote.tpl' sVoteType='comment' oVoteObject=$oComment bVoteIsLocked=($oUserCurrent->getId() == $oUser->getId())}</li>
			{/if}
			
			{* Избранное *}
			{if $oUserCurrent and ! $bNoCommentFavourites}
				<li>{include 'favourite.tpl' sFavouriteType='comment' oFavouriteObject=$oComment}</li>
			{/if}
		</ul>
		

		{* Текст комментария *}
		<div id="comment_content_id_{$oComment->getId()}" class="comment-content text">
			{$oComment->getText()}
		</div>


		{* Кнопки ответа, удаления и т.д. *}
		{if $oUserCurrent}
			<ul class="comment-actions">
				{if ! $bList and ! $oComment->getDelete() and ! $bAllowNewComment}
					<li><a href="#" onclick="ls.comments.toggleCommentForm({$oComment->getId()}); return false;" class="reply-link link-dotted">{$aLang.comment_answer}</a></li>
				{/if}
					
				{if ! $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}
					<li><a href="#" class="comment-delete link-dotted" onclick="ls.comments.toggle(this,{$oComment->getId()}); return false;">{$aLang.comment_delete}</a></li>
				{/if}
				
				{if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
					<li><a href="#" class="comment-repair link-dotted" onclick="ls.comments.toggle(this,{$oComment->getId()}); return false;">{$aLang.comment_repair}</a></li>
				{/if}
				
				{hook run='comment_action' comment=$oComment}
			</ul>
		{/if}
	{else}				
		{$aLang.comment_was_delete}
	{/if}	
</section>