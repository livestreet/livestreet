{include file='header.tpl' menu='talk' showUpdateButton=true}
{assign var="oUser" value=$oTalk->getUser()}

<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/comments.js"></script>


<div class="topic talk">				
	<h2 class="title">{$oTalk->getTitle()|escape:'html'}</h2>						
	<div class="content">
		{$oTalk->getText()}				
	</div>		
	<ul class="action">
		<li><a href="{router page='talk'}">{$aLang.talk_inbox}</a></li>
		<li class="delete"><a href="{router page='talk'}delete/{$oTalk->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">{$aLang.talk_inbox_delete}</a></li>
	</ul>	
	<ul class="info">
		<li class="date">{date_format date=$oTalk->getDate()}</li>
		<li class="author"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
		<li class="favorite {if $oTalk->getIsFavourite()}active{else}guest{/if}"><a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a></li>
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