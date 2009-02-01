{include file='header.tpl' menu='talk'}


			<div class="topic people top-blogs talk-table">
				<h1>Почтовый ящик</h1>
				<form action="" method="post" id="form_talks_list">
				<table>
					<thead>
						<tr>
							<td width="20px"><input type="checkbox" name="" onclick="checkAllTalk(this);"></th>
							<td class="user">Адресаты</td>
							<td>Тема</td>
							<td>Дата</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aTalks item=oTalk}
						<tr>
							<td><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox"></td>
							<td class="name">
							Я {if $oTalk->getCountUsers()>1} и {/if}
								{foreach from=$oTalk->getUsers() item=oUser name=users}
									{if $oUser->getId()!=$oUserCurrent->getId()}					
										<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/" class="author">{$oUser->getLogin()}</a>{if !$smarty.foreach.users.last},{/if} 
									{/if}
								{/foreach}
							</td>							
							<td>
							{if $oTalk->getCountCommentNew() or !$oTalk->getDateLastRead()}
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/read/{$oTalk->getId()}/"><b>{$oTalk->getTitle()|escape:'html'}</b></a>
							{else}
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
							{/if}
					 		&nbsp;	
							{if $oTalk->getCountComment()}
								{$oTalk->getCountComment()} {if $oTalk->getCountCommentNew()}<span style="color: #008000;">+{$oTalk->getCountCommentNew()}</span>{/if}
							{/if}
							</td>
							<td>{date_format date=$oTalk->getDate()}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<input type="submit" name="submit_talk_del" value="Удалить" onclick="return confirm('Действительно удалить переписку?');">
				</form>
			</div>


{include file='footer.tpl'}