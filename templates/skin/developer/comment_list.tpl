{foreach from=$aComments item=oComment}
	<div class="comment list">						
		<div class="comment-topic">
			<a href="{$oComment->getBlogUrlFull()}" class="comment-blog">{$oComment->getBlogTitle()|escape:'html'}</a> / 
			<a href="{$oComment->getTopicUrl()}">{$oComment->getTopicTitle()|escape:'html'}</a> 
			<a href="{$oComment->getTopicUrl()}#comments" class="comment-total">{$oComment->getTopicCountComment()}</a>
		</div>		

		
		<ul class="info">
			<li class="avatar"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/"><img src="{$oComment->getUserProfileAvatarPath(24)}" alt="avatar" /></a></li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="author">{$oComment->getUserLogin()}</a></li>
			<li class="date">{date_format date=$oComment->getDate()}</li>
			<li><a href="#comment{$oComment->getId()}">#</a></li>
		</ul>			
		
		
		<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if}">
			<span class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</span>
		</div>		

		
		<div class="content">						
			<div class="text">
				{if $oComment->isBad()}
					<div style="display: none;" id="comment_text_{$oComment->getId()}">
						{$oComment->getText()}
					</div>
					<a href="#" onclick="$('comment_text_{$oComment->getId()}').setStyle('display','block');$(this).setStyle('display','none');return false;">{$aLang.comment_bad_open}</a>
				{else}	
					{$oComment->getText()}
				{/if}
			</div>			
		</div>
	</div>
{/foreach}	

{include file='paging.tpl' aPaging='$aPaging'}