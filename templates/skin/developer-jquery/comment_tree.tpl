{if $oUserCurrent}
	<div class="update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
		<div class="refresh"><img class="update-comments" id="update-comments" alt="" src="{cfg name='path.static.skin'}/images/update.gif" onclick="comments.load({$iTargetId},'{$sTargetType}'); return false;"/></div>
		<div class="new-comments" id="new_comments_counter" style="display: none;" onclick="comments.goToNextComment();"></div>
		<input type="hidden" id="comment_last_id" value="{$iMaxIdComment}" />
		<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
	</div>
{/if}
	
	
<h3>{$aLang.comment_title} (<span id="count-comments">{$iCountComment}</span>)</h3>
<a name="comments"></a>
	
	
<div class="comments" id="comments">
	{assign var="nesting" value="-1"}
	{foreach from=$aComments item=oComment name=rublist}
		{assign var="cmtlevel" value=$oComment->getLevel()}
		
		{if $cmtlevel>$oConfig->GetValue('module.comment.max_tree')}
			{assign var="cmtlevel" value=$oConfig->GetValue('module.comment.max_tree')}
		{/if}
		
		{if $nesting < $cmtlevel} 
		{elseif $nesting > $cmtlevel}    	
			{section name=closelist1  loop=$nesting-$cmtlevel+1}</div>{/section}
		{elseif not $smarty.foreach.rublist.first}
			</div>
		{/if}
		
		<div class="comment-wrapper" id="comment_wrapper_id_{$oComment->getId()}">
		
		{include file='comment.tpl'} 
		{assign var="nesting" value=$cmtlevel}
		{if $smarty.foreach.rublist.last}
			{section name=closelist2 loop=$nesting+1}</div>{/section}    
		{/if}
	{/foreach}
</div>				
	
{include file='comment_paging.tpl' aPagingCmt=$aPagingCmt}

{if $bAllowNewComment}
	{$sNoticeNotAllow}
{else}
	{if $oUserCurrent}
		<h4 class="reply-header" id="add_comment_root"><a href="#" onclick="comments.toggleCommentForm(0); return false;">{$aLang.comment_leave}</a></h4>
		
		<div id="reply_0" class="reply">
			<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
				<textarea name="comment_text" id="form_comment_text" class="input-wide"></textarea>
				<input type="button" value="{$aLang.comment_preview}" onclick="comments.preview();" />    	
				<input type="submit" name="submit_comment" value="{$aLang.comment_add}" onclick="comments.add('form_comment',{$iTargetId},'{$sTargetType}'); return false;" />    	
				<input type="hidden" name="reply" value="0" id="form_comment_reply" />
				<input type="hidden" name="cmt_target_id" value="{$iTargetId}" />
			</form>
		</div>
	{else}
		{$aLang.comment_unregistered}
	{/if}
{/if}	


