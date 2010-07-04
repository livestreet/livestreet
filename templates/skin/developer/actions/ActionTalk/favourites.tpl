{include file='header.tpl'}
{include file='menu.talk.tpl'}


<h2>{$aLang.talk_favourite_inbox}</h2>

{if $aTalks}
<table class="table">
	<thead>
		<tr>
			<td width="150">{$aLang.talk_inbox_target}</td>
			<td width="20"></td>
			<td>{$aLang.talk_inbox_title}</td>
			<td width="170" align="center">{$aLang.talk_inbox_date}</td>
		</tr>
	</thead>

	<tbody>
	{foreach from=$aTalks item=oTalk}
		{assign var="oTalkUserAuthor" value=$oTalk->getTalkUser()}
		<tr>
			<td>
				{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
					{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
					{assign var="oUser" value=$oTalkUser->getUser()}
						<a href="{$oUser->getUserWebPath()}" class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}">{$oUser->getLogin()}</a>
					{/if}
				{/foreach}

			</td>
			<td align="center">
				<a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;" class="favorite {if $oTalk->getIsFavourite()}active{/if}"></a>
			</td>
			<td>
			{if $oTalkUserAuthor->getCommentCountNew() or !$oTalkUserAuthor->getDateLast()}
				<a href="{router page='talk'}read/{$oTalk->getId()}/"><strong>{$oTalk->getTitle()|escape:'html'}</strong></a>
			{else}
				<a href="{router page='talk'}read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
			{/if}
			&nbsp;
			{if $oTalk->getCountComment()}
				{$oTalk->getCountComment()} {if $oTalkUserAuthor->getCommentCountNew()}+{$oTalkUserAuthor->getCommentCountNew()}{/if}
			{/if}
			</td>
			<td align="center">{date_format date=$oTalk->getDate()}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
{else}
	{$aLang.talk_favourite_empty}
{/if}


{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}