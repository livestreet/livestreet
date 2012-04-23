{if $oUserCurrent}
	{assign var=aPagingCmt value=$params.aPagingCmt}
	<section class="toolbar-update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage > 1}display: none;{/if}">
		<a href="#" class="update-comments" id="update-comments" onclick="ls.comments.load({$params.iTargetId},'{$params.sTargetType}'); return false;"><i></i></a>
		<a href="#" class="new-comments" id="new_comments_counter" style="display: none;" title="{$aLang.comment_count_new}" onclick="ls.comments.goToNextComment(); return false;"></a>

		<input type="hidden" id="comment_last_id" value="{$params.iMaxIdComment}" />
		<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
	</section>
{/if}
	
