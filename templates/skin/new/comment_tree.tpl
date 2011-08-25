<script type="text/javascript" src="{cfg name='path.static.skin'}/js/comments.js?v=2"></script>

			<!-- Comments -->
			<div class="comments">
				{if $oUserCurrent}
				<div class="update" id="update" style="{if $aPagingCmt and $aPagingCmt.iCountPage>1}display:none;{/if}">
					<div class="tl"></div>
					<div class="wrapper">
						<div class="refresh">
							<img class="update-comments" id="update-comments" alt="" src="{cfg name='path.static.skin'}/images/update.gif" onclick="lsCmtTree.responseNewComment({$iTargetId},'{$sTargetType}',this); return false;"/>
						</div>
						<div class="new-comments" id="new-comments" style="display: none;" onclick="lsCmtTree.goNextComment();">							
						</div>
					</div>
					<div class="bl"></div>
				</div>
				{/if}
				
				<!-- Comments Header -->
				<div class="header">
					<h3>{$aLang.comment_title} (<span id="count-comments">{$iCountComment}</span>)</h3>
					<a name="comments" ></a>
					{if $sTargetType=='topic'}
					<a href="{router page='rss'}comments/{$iTargetId}/" class="rss">RSS</a>
					{/if}
					<a href="#" onclick="lsCmtTree.collapseNodeAll(); return false;" onfocus="blur();">{$aLang.comment_collapse}</a> /
					<a href="#" onclick="lsCmtTree.expandNodeAll(); return false;" onfocus="blur();">{$aLang.comment_expand}</a>
				</div>
				<!-- /Comments Header -->			
				
				{literal}
				<script language="JavaScript" type="text/javascript">
					window.addEvent('domready', function() {
						{/literal}
						lsCmtTree.setIdCommentLast({$iMaxIdComment});
						{if $aPagingCmt and $aPagingCmt.iCountPage>1}
							lsCmtTree.setUsePaging(1);
						{/if}
						{literal}
					});					
				</script>
				{/literal}
				
				{assign var="nesting" value="-1"}
				{foreach from=$aComments item=oComment name=rublist}
					{assign var="cmtlevel" value=$oComment->getLevel()}
					{if $cmtlevel>$oConfig->GetValue('module.comment.max_tree')}
						{assign var="cmtlevel" value=$oConfig->GetValue('module.comment.max_tree')}
					{/if}
   					{if $nesting < $cmtlevel}        
    				{elseif $nesting > $cmtlevel}    	
        				{section name=closelist1  loop=($nesting-$cmtlevel+1)}</div></div>{/section}
    				{elseif not $smarty.foreach.rublist.first}
        				</div></div>
    				{/if}
    				
    				
    				 
    				<div class="comment" id="comment_id_{$oComment->getId()}">
    				    				
    					{include file='comment.tpl'}      												
							  
    				{assign var="nesting" value=$cmtlevel}    
    				{if $smarty.foreach.rublist.last}
        				{section name=closelist2 loop=($nesting+1)}</div></div>{/section}    
    				{/if}
				{/foreach}
				
				<span id="comment-children-0"></span>				
				<br>
				
				{if $bAllowNewComment}
					{$sNoticeNotAllow}
				{else}
					{if $oUserCurrent}
						<h3 class="reply-title"><a href="javascript:lsCmtTree.toggleCommentForm(0);">{$sNoticeCommentAdd}</a></h3>						
						<div class="comment"><div class="content"><div class="text" id="comment_preview_0" style="display: none;"></div></div></div>
						<div style="display: block;" id="reply_0" class="reply">						
						{if !$oConfig->GetValue('view.tinymce')}
            					<div class="panel_form">
            						{hook run='form_add_comment_panel_begin'}
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold.png" title="{$aLang.panel_b}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic.png" title="{$aLang.panel_i}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline.png" title="{$aLang.panel_u}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.png" title="{$aLang.panel_s}"></a>	 			
	 								&nbsp;
	 								<a href="#" onclick="lsPanel.putTagUrl('form_comment_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.png"  title="{$aLang.panel_url}"></a>
	 								<a href="#" onclick="lsPanel.putTagUser('form_comment_text','{$aLang.panel_user_promt}'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/user.png"  title="{$aLang.panel_user}" /></a>
	 								<a href="#" onclick="lsPanel.putQuote('form_comment_text'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/quote.png" title="{$aLang.panel_quote}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.png" title="{$aLang.panel_code}"></a>
	 								{hook run='form_add_comment_panel_end'}
	 							</div>
                                                      {else}
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
                                                                            $('form_comment').getElement('input[name=submit_comment]').click();
                                                                            return false;
                                                                        }
                                                                    });
                                                                 }
                                                            });
                                                            </script>
                                                            {/literal}
	 					{/if}
						<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
							{hook run='form_add_comment_begin'}
    						<textarea name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>
    						{hook run='form_add_comment_end'}
    						<input type="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />&nbsp;
    						<input type="submit" name="submit_comment" value="{$aLang.comment_add}" onclick="lsCmtTree.addComment('form_comment',{$iTargetId},'{$sTargetType}'); return false;">    	
    						<input type="hidden" name="reply" value="" id="form_comment_reply">
    						<input type="hidden" name="cmt_target_id" value="{$iTargetId}">
    					</form>
						</div>
					{else}
						{$aLang.comment_unregistered}<br>
					{/if}
				{/if}		
				
				{include file='comment_paging.tpl' aPagingCmt=$aPagingCmt}
				
						
			</div>
			<!-- /Comments -->