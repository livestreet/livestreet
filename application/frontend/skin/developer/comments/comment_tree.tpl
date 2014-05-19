{**
 * Комментарии
 *
 * @styles css/comments.css
 *}

{* Добавляем в тулбар кнопку обновления комментариев *}
{add_block group='toolbar' name='toolbar/toolbar.comment.tpl'
	aPagingCmt = $aPagingCmt
	iTargetId = $iTargetId
	sTargetType = $sTargetType
	iMaxIdComment = $iMaxIdComment}


{hook run='comment_tree_begin' iTargetId=$iTargetId sTargetType=$sTargetType}


{**
 * Комментарии
 *}
<div class="comments js-comments" id="comments">
	{**
	 * Хидер
	 *}
	{if ! $bForbidNewComment || ( $bForbidNewComment && $iCountComment )}
		<header class="comments-header">
			<h3 class="comments-title js-comments-title">{$iCountComment} {$iCountComment|declension:$aLang.comments.comments_declension}</h3>

			{* Подписка на комментарии *}
			{if $bAllowSubscribe and $oUserCurrent}
				<p><label class="comments-subscribe">
					<input
						type="checkbox"
						id="comment_subscribe"
						class="input-checkbox"
						onchange="ls.subscribe.toggle('{$sTargetType}_new_comment','{$iTargetId}','',this.checked);"
						{if $oSubscribeComment and $oSubscribeComment->getStatus()}checked{/if}>
					{$aLang.comments.subscribe}
				</label></p><br>
			{/if}

			{* Свернуть/развернуть все *}
			<a href="#" class="link-dotted js-comments-fold-all">{$aLang.comments.folding.fold_all}</a> |
			<a href="#" class="link-dotted js-comments-unfold-all">{$aLang.comments.folding.unfold_all}</a>
		</header>
	{/if}

	{**
	 * Комментарии
	 *}
	{$iCurrentLevel = -1}
	{$iMaxLevel = Config::Get('module.comment.max_tree')}

	{foreach $aComments as $oComment}
		{$iCommentLevel = $oComment->getLevel()}

		{if $iCommentLevel > $iMaxLevel}
			{$iCommentLevel = $iMaxLevel}
		{/if}

		{if $iCurrentLevel > $iCommentLevel}
			{section name=closelist1 loop=$iCurrentLevel - $iCommentLevel + 1}</div>{/section}
		{elseif $iCurrentLevel == $iCommentLevel && ! $oComment@first}
			</div>
		{/if}

		<div class="comment-wrapper js-comment-wrapper" data-id="{$oComment->getId()}">

		{include './comment.tpl'}

		{$iCurrentLevel = $iCommentLevel}

		{if $oComment@last}
			{section name=closelist2 loop=$iCurrentLevel + 1}</div>{/section}
		{/if}
	{/foreach}
</div>


{**
 * Пагинация
 *}
{include './comment_pagination.tpl' aPagingCmt=$aPagingCmt}

{hook run='comment_tree_end' iTargetId=$iTargetId sTargetType=$sTargetType}


{**
 * Форма добавления комментария
 *}
{if $bForbidNewComment}
	{include 'components/alert/alert.tpl' sMods='info' mAlerts=$sNoticeNotAllow}
{else}
	{if $oUserCurrent}
		{* Ссылка открывающая форму *}
		<h4 class="comment-reply-root js-comment-reply js-comment-reply-root" data-id="0">
			<a href="#" class="link-dotted">{$sNoticeCommentAdd}</a>
		</h4>

		{include './comment.form.tpl'}
	{else}
		{include 'components/alert/alert.tpl' sMods='info' mAlerts=$aLang.comments.alerts.unregistered}
	{/if}
{/if}