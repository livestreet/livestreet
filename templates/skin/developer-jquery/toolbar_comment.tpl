{if $oUserCurrent}
	{assign var=aPagingCmt value=$params.aPagingCmt}
	<section class="toolbar-update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
		<div class="update-comments" id="update-comments" onclick="ls.comments.load({$params.iTargetId},'{$params.sTargetType}'); return false;"></div>
		<div class="new-comments" id="new_comments_counter" style="display: none;" onclick="ls.comments.goToNextComment();"></div>

		<input type="hidden" id="comment_last_id" value="{$params.iMaxIdComment}" />
		<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
	</section>
{/if}
	
