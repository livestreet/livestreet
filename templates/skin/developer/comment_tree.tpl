<script type="text/javascript" src="{cfg name='path.static.skin'}/js/comments.js"></script>

<div class="comments">
	{if $oUserCurrent}
		<div class="update" id="update">
			<div class="refresh"><img class="update-comments" id="update-comments" alt="" src="{cfg name='path.static.skin'}/images/update.gif" onclick="lsCmtTree.responseNewComment({$iTargetId},'{$sTargetType}',this); return false;"/></div>
			<div class="new-comments" id="new-comments" style="display: none;" onclick="lsCmtTree.goNextComment();"></div>
		</div>
	{/if}
	
	
	<div class="header">
		<h3>{$aLang.comment_title} (<span id="count-comments">{$iCountComment}</span>)</h3>
		<a name="comments" ></a>
		{if $sTargetType=='topic'}
			<a href="{router page='rss'}comments/{$iTargetId}/">rss</a>
		{/if}
		<a href="#" onclick="lsCmtTree.collapseNodeAll(); return false;" onfocus="blur();">{$aLang.comment_collapse}</a> /
		<a href="#" onclick="lsCmtTree.expandNodeAll(); return false;" onfocus="blur();">{$aLang.comment_expand}</a>
	</div>
	
	
	{literal}
	<script language="JavaScript" type="text/javascript">
		window.addEvent('domready', function() {
			{/literal}
			lsCmtTree.setIdCommentLast({$iMaxIdComment});
			{literal}
		});					
	</script>
	{/literal}
	
	
	{assign var="nesting" value="-1"}
	{foreach from=$aComments item=oComment name=rublist}
		{assign var="cmtlevel" value=$oComment->getLevel()}
		{if $cmtlevel>$oConfig->GetValue('module.comment.max_tree')}
			{assign var="cmtlevel" value=$oConfig->GetValue('module.comment.max_tree')}
		{/if}
		{if $nesting < $cmtlevel}        
		{elseif $nesting > $cmtlevel}    	
			{section name=closelist1  loop=`$nesting-$cmtlevel+1`}</div></div>{/section}
		{elseif not $smarty.foreach.rublist.first}
			</div></div>
		{/if}
		
		<div class="comment" id="comment_id_{$oComment->getId()}">
		
		{include file='comment.tpl'}      														  
		{assign var="nesting" value=$cmtlevel}    
		{if $smarty.foreach.rublist.last}
			{section name=closelist2 loop=`$nesting+1`}</div></div>{/section}    
		{/if}
	{/foreach}
	
	
	<span id="comment-children-0"></span>				
	
	
	{if $bAllowNewComment}
		{$sNoticeNotAllow}
	{else}
		{if $oUserCurrent}
			<h3><a href="javascript:lsCmtTree.toggleCommentForm(0);">{$sNoticeCommentAdd}</a></h3>						
			<div class="comment"><div class="content" id="comment_preview_0" style="display: none;"></div></div>
			<div style="display: block;" id="reply_0" class="reply">						
				{if !$oConfig->GetValue('view.tinymce')}
					<div class="panel-form">       	 
						<a href="#" onclick="lsPanel.putTagAround('form_comment_text','b'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
						<a href="#" onclick="lsPanel.putTagAround('form_comment_text','i'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
						<a href="#" onclick="lsPanel.putTagAround('form_comment_text','u'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
						<a href="#" onclick="lsPanel.putTagAround('form_comment_text','s'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
						&nbsp;
						<a href="#" onclick="lsPanel.putTagUrl('form_comment_text','{$aLang.panel_url_promt}'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
						<a href="#" onclick="lsPanel.putQuote('form_comment_text'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/quote.gif" width="20" height="20" title="{$aLang.panel_quote}"></a>
						<a href="#" onclick="lsPanel.putTagAround('form_comment_text','code'); return false;"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
					</div>
				{/if}
				
				<form action="" method="POST" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
					{hook run='form_add_comment_begin'}
					<textarea name="comment_text" id="form_comment_text" class="input-wide"></textarea>
					{hook run='form_add_comment_end'}
					<input type="submit" name="submit_preview" value="{$aLang.comment_preview}" onclick="lsCmtTree.preview($('form_comment_reply').getProperty('value')); return false;" />
					<input type="submit" name="submit_comment" value="{$aLang.comment_add}" onclick="lsCmtTree.addComment('form_comment',{$iTargetId},'{$sTargetType}'); return false;" />    	
					<input type="hidden" name="reply" value="" id="form_comment_reply" />
					<input type="hidden" name="cmt_target_id" value="{$iTargetId}" />
				</form>
			</div>
		{else}
			{$aLang.comment_unregistered}
		{/if}
	{/if}				
</div>