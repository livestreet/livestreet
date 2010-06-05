{include file='header.tpl'}
{include file='menu.talk.tpl'}

{assign var="oUser" value=$oTalk->getUser()}


<div class="topic">
	<h2 class="title">{$oTalk->getTitle()|escape:'html'}</h2>

	<ul class="actions">
		<li class="delete"><a href="{router page='talk'}delete/{$oTalk->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');" class="delete">{$aLang.talk_inbox_delete}</a></li>
	</ul>

	<div class="content">
		{$oTalk->getText()}
	</div>

	<ul class="info">
		<li class="username"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
		<li class="date">{date_format date=$oTalk->getDate()}</li>
		<li><a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;" class="favorite {if $oTalk->getIsFavourite()}active{/if}"></a></li>
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