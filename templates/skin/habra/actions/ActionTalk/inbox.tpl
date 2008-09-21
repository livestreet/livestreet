{include file='header.tpl'}

{include file='system_message.tpl'}

<BR>
<table width="100%"  border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">
<div id="content" class="ppl">

		<div class="oldmenu">
   			<div class="oldmenuitem_2 {if $sEvent=='inbox'}active{/if}"><a href="{$DIR_WEB_ROOT}/talk/">Почтовый ящик</a></div>
			<div class="oldmenuitem_2 {if $sEvent=='add'}active{/if}"><a href="{$DIR_WEB_ROOT}/talk/add/">Написать</a></div>
		</div>
		
		
		<br><br>&nbsp;
		{if $aTalks}
		<form action="" method="post" id="form_talks_list">
		<table width="100%" bgcolor="White" border="0" cellspacing="0" cellpadding="0" class="inbox">
			<tr>
				<th width="20px"><input type="checkbox" name="" onclick="checkAllTalk(this);"></th>
				<th align="left">Тема</th>				
				<th align="left">Собеседники</th>
				<th width="140px" align="left">Дата</th>
			</tr>
			
			{foreach from=$aTalks item=oTalk}
			<tr>
				<td><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox"></td>
				<td class="subj">
					{if $oTalk->getCountCommentNew() or !$oTalk->getDateLastRead()}
						<a href="{$DIR_WEB_ROOT}/talk/read/{$oTalk->getId()}/"><b>{$oTalk->getTitle()|escape:'html'}</b></a>
					{else}
						<a href="{$DIR_WEB_ROOT}/talk/read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
					{/if}
					 &nbsp;	
					{if $oTalk->getCountComment()}
						{$oTalk->getCountComment()} {if $oTalk->getCountCommentNew()}<span style="color: #008000;">+{$oTalk->getCountCommentNew()}</span>{/if}
					{/if}
				</td>				
				<td class="addressee">
				Я {if $oTalk->getCountUsers()>1} и {/if}
				{foreach from=$oTalk->getUsers() item=oUser name=users}
					{if $oUser->getId()!=$oUserCurrent->getId()}
					
					<a href="{$DIR_WEB_ROOT}/profile/{$oUser->getLogin()}/">{$oUser->getLogin()}</a>{if !$smarty.foreach.users.last},{/if} 
					{/if}
				{/foreach}
				</td>
				<td class="date">{date_format date=$oTalk->getDate()}</td>
			</tr>
			{/foreach}		
					
		</table><br>
		<input type="submit" name="submit_talk_del" value="Удалить">
		</form>
		{else}
			У вас пока нет сообщений
		{/if}
</div>

</td>
</tr>
</table>


{include file='footer.tpl'}

