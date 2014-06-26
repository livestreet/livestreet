{**
 * Комментарии
 *
 * @param string   $sTargetType
 * @param integer  $iTargetId
 * @param array    $aComments
 * @param boolean  $bForbidAdd
 * @param string   $sHeading
 * @param integer  $iCountComment
 * @param boolean  $bAllowSubscribe
 *
 * @styles css/comments.css
 *}

{$sComponent = 'comments'}

{block 'comment-list-options'}
	{$iTargetId     = $smarty.local.iTargetId}
	{$sTargetType   = $smarty.local.sTargetType}
	{$iCountComment = $smarty.local.iCountComment}
	{$bForbidAdd    = $smarty.local.bForbidAdd}

	{if $bForbidAdd}
		{$sMods = "$sMods forbid"}
	{/if}
{/block}

{add_block group='toolbar' name='toolbar/toolbar.comment.tpl' target='.js-comment'}

<div class="{$sComponent} js-comments {mod name=$sComponent mods=$sMods} {$smarty.local.sClasses}"
	id="comments"
	data-target-type="{$sTargetType}"
	data-target-id="{$iTargetId}"
	data-comment-last-id="{$iMaxIdComment}">
	{**
	 * Заголовок
	 *}
	<header class="comments-header">
		<h3 class="comments-title js-comments-title">
			{lang name='comments.comments_declension' count=$iCountComment plural=true}
		</h3>
	</header>


	{**
	 * Экшнбар
	 *}

	{* Свернуть/развернуть все комментарии *}
	{$aItems = [ [ 'classes' => 'js-comments-fold-all-toggle', 'text' => $aLang.comments.folding.fold_all ] ]}

	{* Подписка на комментарии *}
	{if $bAllowSubscribe and $oUserCurrent}
		{* Подписан пользователь на комментарии или нет *}
		{$bIsSubscribed = $oSubscribeComment && $oSubscribeComment->getStatus()}

		{$aItems[] = [
			'classes'    => "comments-subscribe js-comments-subscribe {if $bIsSubscribed}active{/if}",
			'attributes' => "data-type=\"{$sTargetType}\" data-target-id=\"{$iTargetId}\"",
			'text'       => ( $bIsSubscribed ) ? $aLang.comments.unsubscribe : $aLang.comments.subscribe
		]}
	{/if}

	{* TODO: Добавить хук *}

	{include 'components/actionbar/actionbar.tpl' aItems=$aItems sClasses='comments-actions'}


	{**
	 * Комментарии
	 *}
	<div class="comment-list js-comment-list" data-target-type="{$sTargetType}" data-target-id="{$iTargetId}">
		{include './comment-tree.tpl'
			aComments      = $smarty.local.aComments
			bForbidAdd     = $bForbidAdd
			bShowFavourite = $smarty.local.bShowFavourite
			bShowVote      = $smarty.local.bShowVote}
	</div>


	{**
	 * TODO: Пагинация
	 *}
	{*include 'comments/comment_pagination.tpl' aPagingCmt=$aPagingCmt*}


	{**
	 * Форма добавления комментария
	 *}

	{* Проверяем запрещено комментирование или нет *}
	{if $bForbidAdd}
		{include 'components/alert/alert.tpl' sMods='info' mAlerts=$sNoticeNotAllow}

	{* Если разрешено то показываем форму добавления комментария *}
	{else}
		{if $oUserCurrent}
			{* Кнопка открывающая форму *}
			<h4 class="comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
				<a href="#" class="link-dotted">{$sNoticeCommentAdd}</a>
			</h4>
		{else}
			{include 'components/alert/alert.tpl' sMods='info' mAlerts=$aLang.comments.alerts.unregistered}
		{/if}
	{/if}

	{* Форма добавления комментария *}
	{if $oUserCurrent && ( ! $bForbidAdd || ( $bForbidAdd && $iCountComment ) )}
		{include './comment-form.tpl' sTargetType=$sTargetType iTargetId=$iTargetId}
	{/if}
</div>