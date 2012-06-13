<div class="comments comment-list">
	{foreach from=$aComments item=oComment}
		{assign var="oUser" value=$oComment->getUser()}
		{assign var="oTopic" value=$oComment->getTarget()}
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		
		
		
		
		<section class="comment">
			<ul class="comment-info">
				<li class="comment-author">
					<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="comment-avatar" /></a>
					<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
				<li class="comment-date">
					<time datetime="{date_format date=$oComment->getDate() format='c'}">{date_format date=$oComment->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
				</li>
				{if $oUserCurrent and !$bNoCommentFavourites}
					<li class="comment-favourite">
						<div onclick="return ls.favourite.toggle({$oComment->getId()},this,'comment');" class="favourite {if $oComment->getIsFavourite()}active{/if}"></div>
						<span class="favourite-count" id="fav_count_comment_{$oComment->getId()}">{if $oComment->getCountFavourite() > 0}{$oComment->getCountFavourite()}{/if}</span>
					</li>
				{/if}
				<li class="comment-link">
					<a href="{if $oConfig->GetValue('module.comment.nested_per_page')}{router page='comments'}{else}{$oTopic->getUrl()}#comment{/if}{$oComment->getId()}" title="{$aLang.comment_url_notice}">
						<i class="icon-synio-link"></i>
					</a>
				</li>
				<li id="vote_area_comment_{$oComment->getId()}" class="vote 
																		{if $oComment->getRating() > 0}
																			vote-count-positive
																		{elseif $oComment->getRating() < 0}
																			vote-count-negative
																		{/if}">
					<span class="vote-count" id="vote_total_comment_{$oComment->getId()}">{$oComment->getRating()}</span>
				</li>
			</ul>
					
					
			<div class="comment-content">						
				<div class="text">						
					{if $oComment->isBad()}
						{$oComment->getText()}						
					{else}
						{$oComment->getText()}
					{/if}		
				</div>
			</div>
			
			
			<div class="comment-path">
				<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
				<a href="{$oTopic->getUrl()}" class="comment-path-topic">{$oTopic->getTitle()|escape:'html'}</a>
				<a href="{$oTopic->getUrl()}#comments" class="comment-path-comments">{$oTopic->getCountComment()}</a>
			</div>
		</section>
	{/foreach}	
</div>


{include file='paging.tpl' aPaging=$aPaging}