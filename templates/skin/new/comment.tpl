							
						{assign var="oUser" value=$oComment->getUser()}
						{assign var="oVote" value=$oComment->getVote()}
						{if !$oComment->getDelete() or $bOneComment or ($oUserCurrent and $oUserCurrent->isAdministrator())}
							<img src="{cfg name='path.static.skin'}/images/close.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" {if $bOneComment}style="display: none;"{/if} />
							<a name="comment{$oComment->getId()}" ></a>	
							{if $oComment->getTargetType()!='talk'}						
							<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$oConfig->GetValue('acl.vote.comment.limit_time')}guest{/if}   {if $oVote} voted {if $oVote->getDirection()>0}plus{else}minus{/if}{/if}  ">
								<div class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</div>
								<a href="#" class="plus" onclick="lsVote.vote({$oComment->getId()},this,1,'comment'); return false;"></a>
								<a href="#" class="minus" onclick="lsVote.vote({$oComment->getId()},this,-1,'comment'); return false;"></a>
							</div>
							{/if}
							<div id="comment_content_id_{$oComment->getId()}" class="content {if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}del{elseif $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}self{elseif $sDateReadLast<=$oComment->getDate()}new{/if}">								
								{if !$bOneComment and $oUserCurrent and $oComment->getUserId()!=$oUserCurrent->getId() and $sDateReadLast<=$oComment->getDate()}
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
								<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
								<p><a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a></p>
								<ul>
									<li class="date">{date_format date=$oComment->getDate()}</li>
									{if $oUserCurrent and !$oComment->getDelete() and !$bAllowNewComment}
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
   									{if $oUserCurrent and !$bNoCommentFavourites}
										<li class="favorite {if $oComment->getIsFavourite()}active{/if}"><a href="#" onclick="lsFavourite.toggle({$oComment->getId()},this,'comment'); return false;"></a></li>	
									{/if}												
								</ul> 
							</div>		
							<div class="comment"><div class="content"><div class="text" id="comment_preview_{$oComment->getId()}" style="display: none;"></div></div></div>					
							<div class="reply" id="reply_{$oComment->getId()}" style="display: none;"></div>	
						{else}				
							<span class="delete">{$aLang.comment_was_delete}</span><br><br>
						{/if}		
							<div class="comment-children" id="comment-children-{$oComment->getId()}"> 
							{if $bOneComment}
							</div>
							{/if}