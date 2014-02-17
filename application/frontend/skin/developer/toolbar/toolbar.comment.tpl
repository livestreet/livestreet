{**
 * Тулбар
 * Кнопка обновления комментариев
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{if $oUserCurrent}
	{$aPagingCmt = $params.aPagingCmt}

	<section class="toolbar-update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage > 1}display: none;{/if}">
		<a href="#" class="update-comments" id="update-comments" data-target-id="{$params.iTargetId}" data-target-type="{$params.sTargetType}"><i></i></a>
		<a href="#" class="new-comments" id="new_comments_counter" style="display: none;" title="{$aLang.comments.comment.count_new}"></a>

		<input type="hidden" id="comment_last_id" value="{$params.iMaxIdComment}" />
		<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
	</section>
{/if}