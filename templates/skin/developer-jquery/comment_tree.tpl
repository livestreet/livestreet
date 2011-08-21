{if $oUserCurrent}
	<div class="update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
		<div class="update-comments" id="update-comments" onclick="ls.comments.load({$iTargetId},'{$sTargetType}'); return false;"></div>
		<div class="new-comments" id="new_comments_counter" style="display: none;" onclick="ls.comments.goToNextComment();"></div>
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
                         {/if}
						{if $oUserCurrent}
							<div class="comment" id="comment_preview_0" style="display: none;"><div class="comment-inner"><div class="content"></div></div></div>					
						{/if}	
			<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
				<textarea name="comment_text" id="form_comment_text" class="input-wide"></textarea>
				<input type="button" value="{$aLang.comment_preview}" onclick="ls.comments.preview();" />
				<input type="submit" name="submit_comment" value="{$aLang.comment_add}" id="comment-button-submit" onclick="ls.comments.add('form_comment',{$iTargetId},'{$sTargetType}'); return false;" />
				<input type="hidden" name="reply" value="0" id="form_comment_reply" />
				<input type="hidden" name="cmt_target_id" value="{$iTargetId}" />
			</form>
		</div>
	{else}
		{$aLang.comment_unregistered}
	{/if}
{/if}	


