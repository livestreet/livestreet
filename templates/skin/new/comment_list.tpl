	{foreach from=$aComments item=oComment}
	
		{assign var="oUser" value=$oComment->getUser()}
		{assign var="oTopic" value=$oComment->getTarget()}
		{assign var="oBlog" value=$oTopic->getBlog()}
		
				<div class="comments padding-none">
					<div class="comment">
						<div class="comment-topic"><a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a> / <a href="{$oBlog->getUrlFull()}" class="comment-blog">{$oBlog->getTitle()|escape:'html'}</a> <a href="{$oTopic->getUrl()}#comments" class="comment-total">{$oTopic->getCountComment()}</a></div>				
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
							<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
							<p><a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a></p>
							<ul>
								<li class="date">{date_format date=$oComment->getDate()}</li>								
								<li><a href="{$oTopic->getUrl()}#comment{$oComment->getId()}" class="imglink link"></a></li>  									
   								{if $oUserCurrent}
									<li class="favorite {if $oComment->getIsFavourite()}active{/if}"><a href="#" onclick="lsFavourite.toggle({$oComment->getId()},this,'comment'); return false;"></a></li>	
								{/if}	
							</ul>
							
						</div>
					</div>
				</div>
	{/foreach}	
	
	{include file='paging.tpl' aPaging=`$aPaging`}