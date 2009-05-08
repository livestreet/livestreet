{include file='header.tpl' menu='talk'}


<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/comments.js"></script>


<div class="topic talk">				
	<h2 class="title">{$oTalk->getTitle()|escape:'html'}</h2>						
	<div class="content">
		{$oTalk->getText()}				
	</div>		
	<ul class="action">
		<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.talk_inbox}</a></li>
		<li class="delete"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/delete/{$oTalk->getId()}/" onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">{$aLang.talk_inbox_delete}</a></li>
	</ul>	
	<ul class="info">
		<li class="date">{date_format date=$oTalk->getDate()}</li>
		<li class="author"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oTalk->getUserLogin()}/">{$oTalk->getUserLogin()}</a></li>
	</ul>
</div>


<div class="comments">
				
	<div class="header">
		{if $oTalk->getCountComment()}<h3>{$aLang.talk_comments} ({$oTalk->getCountComment()}){/if}</h3>					
	</div>
	

	{assign var="nesting" value="-1"}
	{foreach from=$aCommentsNew item=aComment name=rublist}
		{if $nesting < $aComment.level}        
		{elseif $nesting > $aComment.level}    	
			{section name=closelist1  loop=`$nesting-$aComment.level+1`}</div></div>{/section}
		{elseif not $smarty.foreach.rublist.first}
			</div></div>
		{/if}    
		<div class="comment" id="comment_id_{$aComment.obj->getId()}">    					
				<img src="{$DIR_STATIC_SKIN}/images/folder-close.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding" />
				<a name="comment{$aComment.obj->getId()}" ></a>	
				
				<ul class="info">
					<li class="avatar"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$aComment.obj->getUserLogin()}/"><img src="{$aComment.obj->getUserProfileAvatarPath(24)}" alt="avatar" /></a></li>
					<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$aComment.obj->getUserLogin()}/" class="author">{$aComment.obj->getUserLogin()}</a></li>
					<li class="date">{date_format date=$aComment.obj->getDate()}</li>
					<li><a href="#comment{$aComment.obj->getId()}">#</a></li>	
				</ul>
				
				
				<div id="comment_content_id_{$aComment.obj->getId()}" class="content {if $oUserCurrent and $aComment.obj->getUserId()==$oUserCurrent->getId()}self{else}{if $oTalk->getDateLastRead()<=$aComment.obj->getDate()}new{/if}{/if}">																							
					<div class="text">
						{$aComment.obj->getText()}<br />
						<span class="reply-link">(<a href="javascript:lsCmtTree.toggleCommentForm({$aComment.obj->getId()});" class="reply-link">{$aLang.comment_answer}</a>)</span>
					</div>
				</div>

				
				<div class="comment"><div class="content"><div class="text" id="comment_preview_{$aComment.obj->getId()}" style="display: none;"></div></div></div>				
				<div class="reply" id="reply_{$aComment.obj->getId()}" style="display: none;"></div>									
				<div class="comment-children" id="comment-children-{$aComment.obj->getId()}">    
		{assign var="nesting" value="`$aComment.level`"}    
		{if $smarty.foreach.rublist.last}
			{section name=closelist2 loop=`$nesting+1`}</div></div>{/section}    
		{/if}
	{/foreach}
	
	<span id="comment-children-0"></span>				
	<br />

	
	<h3 class="reply-title"><a href="javascript:lsCmtTree.toggleCommentForm(0);">{$aLang.topic_comment_add}</a></h3>
	<div class="comment"><div class="content"><div class="text" id="comment_preview_0" style="display: none;"></div></div></div>
	<div style="display: block;" id="reply_0" class="reply">				
		<form action="" method="POST" id="form_comment"  enctype="multipart/form-data">
			<textarea name="comment_text" id="form_comment_text"></textarea>    	
			<input type="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />&nbsp;
			<input type="submit" name="submit_comment" value="{$aLang.comment_add}">    	
			<input type="hidden" name="reply" value="0" id="form_comment_reply">    						
		</form>					
	</div>
</div>


{include file='footer.tpl'}