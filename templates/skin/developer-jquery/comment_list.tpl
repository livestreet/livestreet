<div class="comments comment-list">
	{foreach from=$aComments item=oComment}
		{assign var="oUser" value=$oComment->getUser()}
		{assign var="oTopic" value=$oComment->getTarget()}
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		
		
		<div class="comment-path">
			<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
			<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
			<a href="{$oTopic->getUrl()}#comments">({$oTopic->getCountComment()})</a>
		</div>
		
		
		<section class="comment">
			<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="comment-avatar" /></a>
		
		
			<ul class="comment-info clearfix">
				<li class="comment-author"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
				<li class="comment-date"><time datetime="{date_format date=$oComment->getDate() format='c'}">{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time></li>
				<li><a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}">#</a></li> 				
				<li class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if} {if !$oUserCurrent || $oComment->getUserId()==$oUserCurrent->getId() ||  strtotime($oComment->getDate())<$smarty.now-$oConfig->GetValue('acl.vote.comment.limit_time')}guest{/if}   {if $oVote} voted {if $oVote->getDirection()>0}plus{else}minus{/if}{/if}  ">
					<span class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</span>
				</li>
			</ul>
					
					
			<div class="comment-content text">						
				{if $oComment->isBad()}
					{$oComment->getText()}						
				{else}
					{$oComment->getText()}
				{/if}		
			</div>
		</section>
	{/foreach}	
</div>


{include file='paging.tpl' aPaging="$aPaging"}