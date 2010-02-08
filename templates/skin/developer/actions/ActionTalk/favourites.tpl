{include file='header.tpl' menu='talk'}


<h3>{$aLang.talk_favourite_inbox}</h3>

<table class="people">
	<thead>
		<tr>
			<td class="user" width="250px">{$aLang.talk_inbox_target}</td>
			<td width="30px"></td>
			<td>{$aLang.talk_inbox_title}</td>
			<td width="150px">{$aLang.talk_inbox_date}</td>
		</tr>
	</thead>
	
	<tbody>
	{foreach from=$aTalks item=oTalk}
		{assign var="oTalkUserAuthor" value=$oTalk->getTalkUser()}
		<tr>
			<td class="user">							
				{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
					{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
					{assign var="oUser" value=$oTalkUser->getUser()}
						<a href="{$oUser->getUserWebPath()}" class="author {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}">{$oUser->getLogin()}</a>
					{/if}
				{/foreach}
			</td>							
			<td>
				<span class="favorite {if $oTalk->getIsFavourite()}active{/if}">
					<a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a>
				</span>
			</td>
			<td>	
			{if $oTalkUserAuthor->getCommentCountNew() or !$oTalkUserAuthor->getDateLast()}
				<a href="{router page='talk'}read/{$oTalk->getId()}/"><b>{$oTalk->getTitle()|escape:'html'}</b></a>
			{else}
				<a href="{router page='talk'}read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
			{/if}
			&nbsp;	
			{if $oTalk->getCountComment()}
				{$oTalk->getCountComment()} {if $oTalkUserAuthor->getCommentCountNew()}<span style="color: #008000;">+{$oTalkUserAuthor->getCommentCountNew()}</span>{/if}
			{/if}
			</td>
			<td>{date_format date=$oTalk->getDate()}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
			
			
{include file='paging.tpl' aPaging=`$aPaging`}
{include file='footer.tpl'}