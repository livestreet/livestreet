{include file='header.tpl' menu='talk'}


<h2>{$aLang.talk_inbox}</h2>

<form action="" method="post" id="form_talks_list">
	<table class="people">
		<thead>
			<tr>
				<td class="user" width="20px"><input type="checkbox" name="" class="input-checkbox" onclick="checkAllTalk(this);"></th>
				<td width="250px">{$aLang.talk_inbox_target}</td>
				<td>{$aLang.talk_inbox_title}</td>
				<td width="150px">{$aLang.talk_inbox_date}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aTalks item=oTalk}
			<tr>
				<td class="user"><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox input-checkbox"></td>
				<td>							
					{foreach from=$oTalk->getUsers() item=oUser name=users}
						{if $oUser->getId()!=$oUserCurrent->getId()}					
							<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/" class="author">{$oUser->getLogin()}</a>
						{/if}
					{/foreach}
				</td>							
				<td>
				{if $oTalk->getCountCommentNew() or !$oTalk->getDateLastRead()}
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/read/{$oTalk->getId()}/"><strong>{$oTalk->getTitle()|escape:'html'}</strong></a>
				{else}
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
				{/if}
				&nbsp;	
				{if $oTalk->getCountComment()}
					{$oTalk->getCountComment()} {if $oTalk->getCountCommentNew()}<span style="color: #390;">+{$oTalk->getCountCommentNew()}</span>{/if}
				{/if}
				</td>
				<td>{date_format date=$oTalk->getDate()}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<input type="submit" name="submit_talk_del" value="{$aLang.talk_inbox_delete}" onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">
</form>


{include file='footer.tpl'}