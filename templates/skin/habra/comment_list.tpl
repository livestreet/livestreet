	<div class="userselected">
	   
		{foreach from=$aComments item=oComment}
		<div class="entry_item">
			<table border="0"><tr><td width="15%"> 
				<div class="comment">
					<div class="where">	
						<a href="{$oComment->getBlogUrlFull()}" class="userinfo_navtext_title_sec">{$oComment->getBlogTitle()}</a> / 
  						<a href="{$oComment->getTopicUrl()}#comments" class="userinfo_navtext_title">{$oComment->getTopicTitle()}</a> 
  						<span style="color: #ff0000;">{$oComment->getTopicCountComment()}</span>
					</div>
				</div>
			</td><td width="85%">
				<div class="commenttext">
					<div class="comment_item">
 						<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/">
 						<img class="comments_avatar"   src="{$oComment->getUserProfileAvatarPath(24)}" width="24" height="24" alt="" title="{$oComment->getUserLogin()}" border="0">
 						</a>
 						<div class="service_text_comments_holder">
 							<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/" class="comments_nickname">{$oComment->getUserLogin()}</a> 							
 							<span class="comments_date">{date_format date=$oComment->getDate()}</span> 
 							<a href="{$oComment->getTopicUrl()}#comment{$oComment->getId()}" class="small" title=" ссылка ">#</a> 
						</div>
						 <div class="rating_comment_holder">
						 	<span class="comments_rating_off" style="color: {if $oComment->getRating()<0}#d00000{else}#008000{/if};">{$oComment->getRating()}</span>&nbsp;&nbsp;
						 </div>
						<div class="comment_text">
					        {$oComment->getText()}
      					</div>
					</div>
				</div>
			</td></tr></table>
		</div>   
 		{/foreach} 		
	</div>
	
	{include file='paging.tpl' aPaging=`$aPaging`}