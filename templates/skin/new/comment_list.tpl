	{foreach from=$aComments item=oComment}
				<div class="comments padding-none">
					<div class="comment">						
						<div class="comment-topic"><a href="{$oComment->getTopicUrl()}">{$oComment->getTopicTitle()|escape:'html'}</a> / <a href="{$oComment->getBlogUrlFull()}" class="comment-blog">{$oComment->getBlogTitle()|escape:'html'}</a> <a href="{$oComment->getTopicUrl()}#comments" class="comment-total">{$oComment->getTopicCountComment()}</a></div>				
						<div class="voting {if $oComment->getRating()>0}positive{elseif $oComment->getRating()<0}negative{/if}">
							<div class="total">{if $oComment->getRating()>0}+{/if}{$oComment->getRating()}</div>
						</div>										
						<div class="content">
							<div class="tb"><div class="tl"><div class="tr"></div></div></div>							
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
							<div class="bl"><div class="bb"><div class="br"></div></div></div>
						</div>						
						<div class="info">
							<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/"><img src="{$oComment->getUserProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
							<p><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="author">{$oComment->getUserLogin()}</a></p>
							<ul>
								<li class="date">{date_format date=$oComment->getDate()}</li>								
								<li><a href="{$oComment->getTopicUrl()}#comment{$oComment->getId()}" class="imglink link"></a></li>								
							</ul>
						</div>
					</div>
				</div>
	{/foreach}	
	
	{include file='paging.tpl' aPaging=`$aPaging`}