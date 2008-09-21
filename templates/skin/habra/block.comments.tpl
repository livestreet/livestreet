<div class="habrablock">
	<h3 class="new_group_sections">Прямой&nbsp;эфир&nbsp;&#8595;</h3>	
	
	<div class="live_section">
		{foreach from=$aComments item=oComment} 
			<div class="live_section_navtext_title">
				<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/"><img src="{$DIR_STATIC_SKIN}/img/user.gif" border="0" width="10" height="10" alt="посмотреть профиль" title="посмотреть профиль"></a>&nbsp;
				<a href="{$DIR_WEB_ROOT}/profile/{$oComment->getUserLogin()}/" class="live_section_nickname">{$oComment->getUserLogin()}</a>&nbsp;&#8594;&nbsp;
				<a href="{$oComment->getBlogUrlFull()}" class="live_section_navtext_title_sec">{$oComment->getBlogTitle()|escape:'html'}</a> / 
				<a href="{$DIR_WEB_ROOT}/blog/{if $oComment->getBlogUrl()}{$oComment->getBlogUrl()}/{/if}{$oComment->getTopicId()}.html#comment{$oComment->getId()}" class="live_section_navtext_title">{$oComment->getTopicTitle()|escape:'html'}</a>&nbsp;<span class="red">{$oComment->getTopicCountComment()}</span>
   			</div>
   		{/foreach}
	</div>	
   
	<div class="live_section_title_all" align="right">
		<span style="color:#666666">&#187;</span> <a href="{$DIR_WEB_ROOT}/comments/">весь прямой эфир</a>
	</div>
</div>
