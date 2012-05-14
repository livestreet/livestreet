{add_block group='toolbar' name='toolbar_comment.tpl'
	aPagingCmt=$aPagingCmt
	iTargetId=$iTargetId
	sTargetType=$sTargetType
	iMaxIdComment=$iMaxIdComment
}


{if $oUserCurrent}
<script type="text/javascript">
	jQuery(document).ready(function($){
		$(document).click(function(){
			if (!$('#reply-top-form').is(':visible')) {
				$('#reply').hide();
				$('#reply-top-form').show();
			}
		});
		
		$('body').on('click', '#reply-top', function(e) {
			e.stopPropagation();
		});
	});
	
	ls.comments.expandReplyTop = function() {
		$('#reply').show().appendTo('#reply-top');
		$('#reply-top-form').hide();
		$('#form_comment_text').val('');
		$('#form_comment_reply').val(0);
	}
</script>
{/if}


<div class="comments" id="comments">
	<header class="comments-header">
		<h3>{$iCountComment} {$iCountComment|declension:$aLang.comment_declension:'russian'}</h3>
		
		{if $bAllowSubscribe and $oUserCurrent}
			<div class="subscribe">
				<input {if $oSubscribeComment and $oSubscribeComment->getStatus()}checked="checked"{/if} type="checkbox" id="comment_subscribe" class="input-checkbox" onchange="ls.subscribe.toggle('{$sTargetType}_new_comment','{$iTargetId}','',this.checked);">
				<label for="comment_subscribe">{$aLang.comment_subscribe}</label>
			</div>
		{/if}
	
		<a name="comments"></a>
	</header>
	
	{*
	{if $oUserCurrent}
		<div id="reply-top">
			<div class="wall-submit wall-submit-reply wall-submit-comment" id="reply-top-form">
				<textarea rows="4" class="input-text input-width-full" placeholder="{$aLang.wall_reply_placeholder}" onclick="ls.comments.expandReplyTop();"></textarea>
			</div>
		</div>
	{/if}
	*}

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
		{if $oConfig->GetValue('view.tinymce')}
			<script src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>
			<script type="text/javascript">
				jQuery(function($){
					tinyMCE.init(ls.settings.getTinymceComment());
				});
			</script>
		{else}
			{include file='window_load_img.tpl' sToLoad='form_comment_text'}
			<script type="text/javascript">
				jQuery(function($){
					ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
					// Подключаем редактор
					jQuery('#form_comment_text').markItUp(ls.settings.getMarkitupComment());
				});
			</script>
		{/if}
		
	
		<h4 class="reply-header" id="comment_id_0">
			<a href="#" class="link-dotted" onclick="ls.comments.toggleCommentForm(0); return false;">{$sNoticeCommentAdd}</a>
		</h4>
		
		
		<div id="reply" class="reply">		
			<form method="post" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
				{hook run='form_add_comment_begin'}
				
				<textarea name="comment_text" id="form_comment_text" class=""></textarea>
				
				{hook run='form_add_comment_end'}
				
				<button name="submit_comment" 
						id="comment-button-submit" 
						onclick="ls.comments.add('form_comment',{$iTargetId},'{$sTargetType}'); return false;" 
						class="button button-primary">{$aLang.comment_add}</button>
				<button type="button" onclick="ls.comments.preview();" class="button">{$aLang.comment_preview}</button>
				
				<input type="hidden" name="reply" value="0" id="form_comment_reply" />
				<input type="hidden" name="cmt_target_id" value="{$iTargetId}" />
			</form>
		</div>
	{else}
		{$aLang.comment_unregistered}
	{/if}
{/if}	


