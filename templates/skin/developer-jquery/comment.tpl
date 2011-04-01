{assign var="oUser" value=$oComment->getUser()}
{assign var="oVote" value=$oComment->getVote()}

<div id="comment_id_{$oComment->getId()}" class="comment {if !$oUserCurrent or ($oUserCurrent and !$oUserCurrent->isAdministrator())}not-admin{/if} {if $oComment->getDelete()} deleted{elseif $oUserCurrent and $oComment->getUserId()==$oUserCurrent->getId()} self{elseif $sDateReadLast<=$oComment->getDate()} new{/if}" >
{if !$oComment->getDelete() or $bOneComment or ($oUserCurrent and $oUserCurrent->isAdministrator())}
	<a name="comment{$oComment->getId()}" ></a>
	
	
	<ul class="info">
		<li class="avatar"><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" /></a></li>
		<li class="username"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
		<li class="date">{date_format date=$oComment->getDate()}</li>
		<li><a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}#comment{/if}{$oComment->getId()}">#</a></li>	
		{if $oComment->getPid()}
			<li class="goto-comment-parent"><a href="#" onclick="comments.goToParentComment({$oComment->getId()},{$oComment->getPid()}); return false;" title="{$aLang.comment_goto_parent}">↑</a></li>
		{/if}
		<li class="goto-comment-child"><a href="#" title="{$aLang.comment_goto_child}">↓</a></li>
		{if $oUserCurrent and !$bNoCommentFavourites}
			<li><a href="#" onclick="favourite.toggle({$oComment->getId()},this,'comment'); return false;" class="favourite {if $oComment->getIsFavourite()}active{/if}"></a></li>
		{/if}
		{if !$oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}
			<li><a href="#" class="delete" onclick="comments.toggle(this,{$oComment->getId()}); return false;">{$aLang.comment_delete}</a></li>
		{/if}
		{if $oComment->getDelete() and $oUserCurrent and $oUserCurrent->isAdministrator()}   										
			<li><a href="#" class="repair" onclick="comments.toggle(this,{$oComment->getId()}); return false;">{$aLang.comment_repair}</a></li>
		{/if}
		
		{if $oComment->getTargetType()!='talk'}						
			<li class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$oConfig->GetValue('acl.vote.comment.limit_time')}guest{/if}   {if $oVote} voted {if $oVote->getDirection()>0}plus{else}minus{/if}{/if}  ">
				<a href="#" class="plus" onclick="vote.vote({$oComment->getId()},this,1,'comment'); return false;"></a>
				<span class="total">{$oComment->getRating()}</span>
				<a href="#" class="minus" onclick="vote.vote({$oComment->getId()},this,-1,'comment'); return false;"></a>
			</li>
		{/if}
	</ul>
	
	
	<div id="comment_content_id_{$oComment->getId()}" class="content">						
		{$oComment->getText()}
	</div>
		
		
	{if $oUserCurrent}
		<div class="actions">
			{if !$oComment->getDelete() and !$bAllowNewComment}<a href="#" onclick="comments.toggleCommentForm({$oComment->getId()}); return false;" class="reply-link">{$aLang.comment_answer}</a>{/if}
		</div>
	{/if}
{else}				
	{$aLang.comment_was_delete}
{/if}
</div>