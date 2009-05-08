<img src="{$DIR_STATIC_SKIN}/images/folding-close.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" style="display: none;"/>
<a name="comment{$oComment->getId()}" ></a>


<ul class="info">
	<li class="avatar"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/"><img src="{$oComment->getUserProfileAvatarPath(24)}" alt="avatar" /></a></li>
	<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="author">{$oComment->getUserLogin()}</a></li>
	<li class="date">{date_format date=$oComment->getDate()}</li>
	<li><a href="#comment{$oComment->getId()}">#</a></li>	
	{if $oComment->getPid()}
		<li class="goto-comment-parent"><a href="#comment{$oComment->getPid()}" onclick="return lsCmtTree.goToParentComment($(this));" title="{$aLang.comment_goto_parent}">&uarr;</a></li>
	{/if}
	<li class="goto-comment-child hidden"><a href="#" onclick="return lsCmtTree.goToChildComment(this);" title="{$aLang.comment_goto_child}">&darr;</a></li>
	{if !$oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
		<li><a href="#" class="delete" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_delete}</a></li>
	{/if}
	{if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
		<li><a href="#" class="repair" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_repair}</a></li>
	{/if}								
</ul>


<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$VOTE_LIMIT_TIME_COMMENT}guest{/if}   {if $oComment->getUserIsVote()} voted {if $oComment->getUserVoteDelta()>0}plus{else}minus{/if}{/if}  ">
	<span class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</span>
	<a href="#" class="plus" onclick="lsVote.vote({$oComment->getId()},this,1,'topic_comment'); return false;"></a>
	<a href="#" class="minus" onclick="lsVote.vote({$oComment->getId()},this,-1,'topic_comment'); return false;"></a>
</div>	


<div id="comment_content_id_{$oComment->getId()}" class="content {if $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}self{else}new{/if}">
	<div class="text">
		{$oComment->getText()}
		<br />
		<span class="reply-link">(<a href="javascript:lsCmtTree.toggleCommentForm({$oComment->getId()});" class="reply-link">{$aLang.comment_answer}</a>)</span>		
	</div>
</div>


<div class="comment"><div class="content"><div class="text" id="comment_preview_{$oComment->getId()}" style="display: none;"></div></div></div>	
<div class="reply" id="reply_{$oComment->getId()}" style="display: none;"></div>
<div class="comment-children" id="comment-children-{$oComment->getId()}"></div>
					
					