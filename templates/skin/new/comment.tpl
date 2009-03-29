					
							<img src="{$DIR_STATIC_SKIN}/images/close.gif" alt="+" title="Свернуть ветку комментариев" class="folding" style="display: none;"/>
							<a name="comment{$oComment->getId()}" ></a>
							
							<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId()}guest{/if}   {if $oComment->getUserIsVote()} voted {if $oComment->getUserVoteDelta()>0}plus{else}minus{/if}{/if}  ">
								<div class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</div>
								<a href="#" class="plus" onclick="lsVote.vote({$oComment->getId()},this,1,'topic_comment'); return false;"></a>
								<a href="#" class="minus" onclick="lsVote.vote({$oComment->getId()},this,-1,'topic_comment'); return false;"></a>
							</div>
						
							<div class="content {if $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}self{else}new{/if}">
								<div class="tb"><div class="tl"><div class="tr"></div></div></div>
								
								<div class="text">
									{$oComment->getText()}
								</div>
				
								<div class="bl"><div class="bb"><div class="br"></div></div></div>
							</div>
							
							<div class="info">
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/"><img src="{$oComment->getUserProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
								<p><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="author">{$oComment->getUserLogin()}</a></p>
								<ul>
									<li class="date">{date_format date=$oComment->getDate()}</li>
									<li><a href="javascript:lsCmtTree.toggleCommentForm({$oComment->getId()});" class="reply-link">Ответить</a></li>									
									<li><a href="#comment{$oComment->getId()}" class="imglink link"></a></li>
									{if $oComment->getPid()}
										<li class="goto-comment-parent"><a href="#comment{$oComment->getPid()}" onclick="return lsCmtTree.goToParentComment($(this));" title="Ответ на">↑</a></li>
									{/if}
									<li class="goto-comment-child hidden"><a href="#" onclick="return lsCmtTree.goToChildComment(this);" title="Обратно к ответу">↓</a></li>									
								</ul>
							</div>
							
							<div class="comment"><div class="content"><div class="text" id="comment_preview_{$oComment->getId()}" style="display: none;"></div></div></div>	
							<div class="reply" id="reply_{$oComment->getId()}" style="display: none;"></div>
							
							<div class="comment-children" id="comment-children-{$oComment->getId()}">
							</div>
					