{if $oUserCurrent}
	<div class="update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
		<div class="update-comments" id="update-comments" onclick="ls.comments.load({$iTargetId},'{$sTargetType}'); return false;"></div>
		<div class="new-comments" id="new_comments_counter" style="display: none;" onclick="ls.comments.goToNextComment();"></div>
		<input type="hidden" id="comment_last_id" value="{$iMaxIdComment}" />
		<input type="hidden" id="comment_use_paging" value="{if $aPagingCmt and $aPagingCmt.iCountPage>1}1{/if}" />
	</div>
{/if}
	
<div class="comments-header">
	<h3>{$aLang.comment_title} (<span id="count-comments">{$iCountComment}</span>)</h3>
	{if $sTargetType=='topic'}
	<a href="{router page='rss'}comments/{$iTargetId}/" class="rss">RSS</a>
	{/if}
	<a href="#" onclick="ls.comments.collapseCommentAll(); return false;" onfocus="blur();">{$aLang.comment_collapse}</a> /
	<a href="#" onclick="ls.comments.expandCommentAll(); return false;" onfocus="blur();">{$aLang.comment_expand}</a>
</div>

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
		<div class="reply-area">
			<h4 class="reply-header" id="add_comment_root"><a href="#" onclick="ls.comments.toggleCommentForm(0); return false;">{$sNoticeCommentAdd}</a></h4>
			
			<div id="reply_0" class="reply">
				{if $oConfig->GetValue('view.tinymce')}
					<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce/tiny_mce.js"></script>
					{literal}

					<script type="text/javascript">
					tinyMCE.init({
						mode : "textareas",
						theme : "advanced",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_buttons1 : "bold,italic,underline,strikethrough,lslink,lsquote",
						theme_advanced_buttons2 : "",
						theme_advanced_buttons3 : "",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true,
						theme_advanced_resize_horizontal : 0,
						theme_advanced_resizing_use_cookie : 0,
						theme_advanced_path : false,
						object_resizing : true,
						force_br_newlines : true,
						forced_root_block : '', // Needed for 3.x
						force_p_newlines : false,    
						plugins : "lseditor,safari,inlinepopups,media,pagebreak",
						convert_urls : false,
						extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
						pagebreak_separator :"<cut>",
						media_strict : false,
						language : TINYMCE_LANG,
						inline_styles:false,
						formats : {
							 underline : {inline : 'u', exact : true},
							 strikethrough : {inline : 's', exact : true}
						},
						setup : function(ed) {
							// Display an alert onclick
							ed.onKeyPress.add(function(ed, e) {
								key = e.keyCode || e.which;
								if(e.ctrlKey && (key == 13)) {
									$('#comment-button-submit').click();
									return false;
								}
							});
						 }
					});
					</script>
					{/literal}
				{else}
					{include file='window_load_img.tpl' sToLoad='form_comment_text'}
					<script type="text/javascript">
					jQuery(document).ready(function($){
						ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt"});
						// Подключаем редактор
						$('#form_comment_text').markItUp(getMarkitupCommentSettings());
					});
					</script>
				{/if}
				
				
				{if $oUserCurrent}
					<div class="comment-preview" id="comment_preview_0" style="display: none;"><div class="comment-inner"><div class="content"></div></div></div>					
				{/if}
				
				<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
					<textarea name="comment_text" id="form_comment_text" class="input-wide"></textarea>
					<input type="button" value="{$aLang.comment_preview}" onclick="ls.comments.preview();" />
					<input type="submit" name="submit_comment" value="{$aLang.comment_add}" id="comment-button-submit" onclick="ls.comments.add('form_comment',{$iTargetId},'{$sTargetType}'); return false;" />
					<input type="hidden" name="reply" value="0" id="form_comment_reply" />
					<input type="hidden" name="cmt_target_id" value="{$iTargetId}" />
				</form>
			</div>
		</div>
	{else}
		{$aLang.comment_unregistered}
	{/if}
{/if}	


