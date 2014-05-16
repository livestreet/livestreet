{**
 * Тулбар
 * Кнопка обновления комментариев
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{extends 'components/toolbar/toolbar.item.tpl'}

{block 'toolbar_item_options' append}
	{$_sMods = 'update'}
	{$_bShow = !! $oUserCurrent}
	{$_sAttributes = 'id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage > 1}display: none;{/if}"'}

	{$aPagingCmt = $params.aPagingCmt}
{/block}

{block 'toolbar_item'}
	<a href="#" class="update-comments" id="update-comments" data-target-id="{$params.iTargetId}" data-target-type="{$params.sTargetType}"><i></i></a>
	<a href="#" class="new-comments" id="new_comments_counter" style="display: none;" title="{$aLang.comments.comment.count_new}"></a>

	<input type="hidden" id="comment_last_id" value="{$params.iMaxIdComment}" />
	<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
{/block}