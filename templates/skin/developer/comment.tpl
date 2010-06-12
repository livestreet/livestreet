{assign var="oUser" value=$oComment->getUser()}
{assign var="oVote" value=$oComment->getVote()}

<div class="comment-inner">
{if !$oComment->getDelete() or $bOneComment or ($oUserCurrent and $oUserCurrent->isAdministrator())}
	<a name="comment{$oComment->getId()}" ></a>
	

	<ul class="info {if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}del{elseif $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()}self{elseif $sDateReadLast<=$oComment->getDate()}new{/if}">
		<li class="avatar"><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" /></a></li>
		<li class="username"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
		<li class="date">{date_format date=$oComment->getDate()}</li>
		<li><a href="#comment{$oComment->getId()}">#</a></li>	
		{if $oComment->getPid()}
			<li class="goto-comment-parent"><a href="#comment{$oComment->getPid()}" onclick="return lsCmtTree.goToParentComment($(this));" title="{$aLang.comment_goto_parent}">↑</a></li>
		{/if}
		<li class="goto-comment-child hidden"><a href="#" onclick="return lsCmtTree.goToChildComment(this);" title="{$aLang.comment_goto_child}">↓</a></li>
		{if $oUserCurrent and !$bNoCommentFavourites}
			<li><a href="#" onclick="lsFavourite.toggle({$oComment->getId()},this,'comment'); return false;" class="favorite {if $oComment->getIsFavourite()}active{/if}"></a></li>
		{/if}
		{if !$oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}
			<li><a href="#" class="delete" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_delete}</a></li>
		{/if}
		{if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
			<li><a href="#" class="repair" onclick="lsCmtTree.toggleComment(this,{$oComment->getId()}); return false;">{$aLang.comment_repair}</a></li>
		{/if}
		
		{if $oComment->getTargetType()!='talk'}						
			<li class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$oConfig->GetValue('acl.vote.comment.limit_time')}guest{/if}   {if $oVote} voted {if $oVote->getDirection()>0}plus{else}minus{/if}{/if}  ">
				<a href="#" class="plus" onclick="lsVote.vote({$oComment->getId()},this,1,'comment'); return false;"></a>
				<span class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</span>
				<a href="#" class="minus" onclick="lsVote.vote({$oComment->getId()},this,-1,'comment'); return false;"></a>
			</li>
		{/if}
	</ul>
	
	
	<div id="comment_content_id_{$oComment->getId()}" class="content">
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
		
		{if $oComment->isBad()}
			<span class="bad">{$oComment->getText()}</span>
		{else}	
			{$oComment->getText()}
		{/if}
		
		<br />
		{if $oUserCurrent and !$oComment->getDelete() and !$bAllowNewComment}
			<a href="javascript:lsCmtTree.toggleCommentForm({$oComment->getId()});" class="reply-link">{$aLang.comment_answer}</a>
		{/if}
		<a href="#" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" {if $bOneComment}style="display: none;"{/if}>{$aLang.comment_fold}</a>
	</div>
{else}				
	<div class="deleted">{$aLang.comment_was_delete}</div>
{/if}
</div>


{if $oUserCurrent}
	<div class="comment" id="comment_preview_{$oComment->getId()}" style="display: none;"><div class="comment-inner"><div class="content"></div></div></div>					
	<div class="reply" id="reply_{$oComment->getId()}" style="display: none;"></div>	
{/if}	


<div class="comment-children" id="comment-children-{$oComment->getId()}">
{if $bOneComment}</div>{/if}