<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/comments.js"></script>

			<!-- Comments -->
			<div class="comments">
				{if $oUserCurrent}
				<div class="update" id="update">
					<div class="tl"></div>
					<div class="wrapper">
						<div class="refresh">
							<img class="update-comments" id="update-comments" alt="" src="{$DIR_STATIC_SKIN}/images/update.gif" onclick="lsCmtTree.responseNewComment({$oTopic->getId()},this); return false;"/>
						</div>
						<div class="new-comments" id="new-comments" style="display: none;" onclick="lsCmtTree.goNextComment();">							
						</div>
					</div>
					<div class="bl"></div>
				</div>
				{/if}
				
				<!-- Comments Header -->
				<div class="header">
					<h3>{$aLang.comment_title} (<span id="count-comments">{$oTopic->getCountComment()}</span>)</h3>
					<a name="comments" ></a>
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_RSS}/comments/{$oTopic->getId()}/" class="rss">RSS</a>
					<a href="#" onclick="lsCmtTree.collapseNodeAll(); return false;" onfocus="blur();">{$aLang.comment_collapse}</a> /
					<a href="#" onclick="lsCmtTree.expandNodeAll(); return false;" onfocus="blur();">{$aLang.comment_expand}</a>
				</div>
				<!-- /Comments Header -->			
				
				{literal}
				<script language="JavaScript" type="text/javascript">
					window.addEvent('domready', function() {
						{/literal}
						lsCmtTree.setIdCommentLast({$iMaxIdComment});
						{literal}
					});					
				</script>
				{/literal}
				
				{assign var="nesting" value="-1"}
				{foreach from=$aComments item=oComment name=rublist}
					{assign var="cmtlevel" value=$oComment->getLevel()}					
					{if $cmtlevel>$BLOG_COMMENT_MAX_TREE_LEVEL}
						{assign var="cmtlevel" value=$BLOG_COMMENT_MAX_TREE_LEVEL}
					{/if}
   					{if $nesting < $cmtlevel}        
    				{elseif $nesting > $cmtlevel}    	
        				{section name=closelist1  loop=`$nesting-$cmtlevel+1`}</div></div>{/section}
    				{elseif not $smarty.foreach.rublist.first}
        				</div></div>
    				{/if}    
    				<div class="comment" id="comment_id_{$oComment->getId()}">
    					{if !$oComment->getDelete() or ($oUserCurrent and $oUserCurrent->isAdministrator())}
							<img src="{$DIR_STATIC_SKIN}/images/close.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" />
							<a name="comment{$oComment->getId()}" ></a>							
							<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$VOTE_LIMIT_TIME_COMMENT}guest{/if}   {if $oComment->getUserIsVote()} voted {if $oComment->getUserVoteDelta()>0}plus{else}minus{/if}{/if}  ">
								<div class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</div>
								<a href="#" class="plus" onclick="lsVote.vote({$oComment->getId()},this,1,'topic_comment'); return false;"></a>
								<a href="#" class="minus" onclick="lsVote.vote({$oComment->getId()},this,-1,'topic_comment'); return false;"></a>
							</div>						
							<div id="comment_content_id_{$oComment->getId()}" class="content {if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}del{elseif $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}self{elseif $oTopic->getDateRead()<=$oComment->getDate()}new{/if}">								
								{if $oUserCurrent and $oComment->getUserId()!=$oUserCurrent->getId() and $oTopic->getDateRead()<=$oComment->getDate()}
									{literal}
									<script language="JavaScript" type="text/javascript">
										window.addEvent('domready', function() {
										{/literal}
											lsCmtTree.addCommentScroll({$oComment->getId()});
										{literal}
										});					
									</script>
									{/literal}
								{/if}							
								<div class="tb"><div class="tl"><div class="tr"></div></div></div>								
								<div class="text">
									{if $oComment->isBad()}
										<div style="display: none;" id="comment_text_{$oComment->getId()}">
					    				{$oComment->getText()}
					    				</div>
					   					 <a href="#" onclick="$('comment_text_{$oComment->getId()}').style.display='block';$(this).style.display='none';return false;">{$aLang.comment_bad_open}</a>
									{else}	
					    				{$oComment->getText()}
									{/if}								
								</div>				
								<div class="bl"><div class="bb"><div class="br"></div></div></div>
							</div>							
							<div class="info">
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/"><img src="{$oComment->getUserProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
								<p><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="author">{$oComment->getUserLogin()}</a></p>
								<ul>
									<li class="date">{date_format date=$oComment->getDate()}</li>
									{if $oUserCurrent and !$oComment->getDelete() and !$oTopic->getForbidComment()}
										<li><a href="javascript:lsCmtTree.toggleCommentForm({$oComment->getId()});" class="reply-link">{$aLang.comment_answer}</a></li>
									{/if}									
									<li><a href="#comment{$oComment->getId()}" class="imglink link"></a></li>	
									{if $oComment->getPid()}
										<li class="goto-comment-parent"><a href="#comment{$oComment->getPid()}" onclick="return lsCmtTree.goToParentComment($(this));" title="{$aLang.comment_goto_parent}">↑</a></li>
									{/if}
									<li class="goto-comment-child hidden"><a href="#" onclick="return lsCmtTree.goToChildComment(this);" title="{$aLang.comment_goto_child}">↓</a></li>
									{if !$oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
   										<li><a href="#" class="delete" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_delete}</a></li>
   									{/if}
   									{if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
   										<li><a href="#" class="repair" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_repair}</a></li>
   									{/if}								
								</ul>
							</div>		
							<div class="comment"><div class="content"><div class="text" id="comment_preview_{$oComment->getId()}" style="display: none;"></div></div></div>					
							<div class="reply" id="reply_{$oComment->getId()}" style="display: none;"></div>	
						{else}				
							<span class="delete">{$aLang.comment_was_delete}</span><br><br>
						{/if}		
							<div class="comment-children" id="comment-children-{$oComment->getId()}">    
    				{assign var="nesting" value=$cmtlevel}    
    				{if $smarty.foreach.rublist.last}
        				{section name=closelist2 loop=`$nesting+1`}</div></div>{/section}    
    				{/if}
				{/foreach}
				
				<span id="comment-children-0"></span>				
				<br>
				
				{if $oTopic->getForbidComment()}
					{$aLang.topic_comment_notallow}
				{else}
					{if $oUserCurrent}
						<h3 class="reply-title"><a href="javascript:lsCmtTree.toggleCommentForm(0);">{$aLang.topic_comment_add}</a></h3>						
						<div class="comment"><div class="content"><div class="text" id="comment_preview_0" style="display: none;"></div></div></div>
						<div style="display: block;" id="reply_0" class="reply">						
						{if !$BLOG_USE_TINYMCE}
            					<div class="panel_form" style="background: #eaecea; margin-top: 2px;">       	 
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','b'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','i'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','u'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','s'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
	 								&nbsp;
	 								<a href="#" onclick="lsPanel.putTagUrl('form_comment_text','{$aLang.panel_url_promt}'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
	 								<a href="#" onclick="lsPanel.putQuote('form_comment_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
	 								<a href="#" onclick="lsPanel.putTagAround('form_comment_text','code'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
	 							</div>
	 					{/if}
						<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">							
    						<textarea name="comment_text" id="form_comment_text" style="width: 100%; height: 100px;"></textarea>    	
    						<input type="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />&nbsp;
    						<input type="submit" name="submit_comment" value="{$aLang.comment_add}" onclick="lsCmtTree.addComment('form_comment',{$oTopic->getId()}); return false;">    	
    						<input type="hidden" name="reply" value="" id="form_comment_reply">
    						<input type="hidden" name="cmt_topic_id" value="{$oTopic->getId()}" id="cmt_topic_id">
    					</form>
						</div>
					{else}
						{$aLang.comment_unregistered}<br>
					{/if}
				{/if}				
			</div>
			<!-- /Comments -->