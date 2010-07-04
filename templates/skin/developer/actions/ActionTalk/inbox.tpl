{include file='header.tpl' noShowSystemMessage=false}
{include file='menu.talk.tpl'}


<form action="{router page='talk'}" method="post" id="form_talks_list">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<table class="table">
		<thead>
			<tr>
				<td width="20"><input type="checkbox" name="" onclick="checkAllTalk(this);"></td>
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
				<td><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox" /></td>
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

	<input type="submit" name="submit_talk_del" value="{$aLang.talk_inbox_delete}" onclick="return ($$('.form_talks_checkbox:checked').length==0)?false:confirm('{$aLang.talk_inbox_delete_confirm}');" />
</form>

			
{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}