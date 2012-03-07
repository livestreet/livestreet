<aside class="toolbar">
	{if $oUserCurrent}
		<section class="toolbar-update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
			<div class="update-comments" id="update-comments" onclick="ls.comments.load({$iTargetId},'{$sTargetType}'); return false;"></div>
			<div class="new-comments" id="new_comments_counter" style="display: none;" onclick="ls.comments.goToNextComment();"></div>
			
			<input type="hidden" id="comment_last_id" value="{$iMaxIdComment}" />
			<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
		</section>
	{/if}
	
	
	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		<section class="toolbar-admin">
			<a href="{cfg name='path.root.web'}/admin" title="{$aLang.admin_title}"></a>
		</section>
	{/if}
</aside>