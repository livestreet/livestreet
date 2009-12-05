{include file='header.tpl' menu='talk' showUpdateButton=true}

			{assign var="oUser" value=$oTalk->getUser()}
			
			<div class="topic talk">
				<div class="favorite {if $oTalk->getIsFavourite()}active{else}guest{/if}"><a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a></div>			
				<h1 class="title">{$oTalk->getTitle()|escape:'html'}</h1>				
				<ul class="action">
					<li><a href="{router page='talk'}">{$aLang.talk_inbox}</a></li>
					<li class="delete"><a href="{router page='talk'}delete/{$oTalk->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"  onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">{$aLang.talk_inbox_delete}</a></li>
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
			
			{if !$bNoComments}
			{include 
				file='comment_tree.tpl' 	
				iTargetId=$oTalk->getId()
				sTargetType='talk'
				iCountComment=$oTalk->getCountComment()
				sDateReadLast=$oTalkUser->getDateLast()
				sNoticeCommentAdd=$aLang.topic_comment_add
				bNoCommentFavourites=true
			}
			{/if}
			
			
{include file='footer.tpl'}