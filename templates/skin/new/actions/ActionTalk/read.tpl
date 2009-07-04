{include file='header.tpl' menu='talk'}

			{assign var="oUser" value=$oTalk->getUser()}
			
			<div class="topic talk">				
				<h1 class="title">{$oTalk->getTitle()|escape:'html'}</h1>				
				<ul class="action">
					<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.talk_inbox}</a></li>
					<li class="delete"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/delete/{$oTalk->getId()}/"  onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">{$aLang.talk_inbox_delete}</a></li>
				</ul>				
				<div class="content">
					{$oTalk->getText()}				
				</div>				
				<ul class="voting">
					<li class="date">{date_format date=$oTalk->getDate()}</li>
					<li class="author"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
				</ul>
			</div>
			
			{assign var="oTalkUser" value=$oTalk->getTalkUser()}
			
			{include 
				file='comment_tree.tpl' 	
				iTargetId=$oTalk->getId()
				sTargetType='talk'
				iCountComment=$oTalk->getCountComment()
				sDateReadLast=$oTalkUser->getDateLast()
				sNoticeCommentAdd=$aLang.topic_comment_add
			}
			
			
{include file='footer.tpl'}