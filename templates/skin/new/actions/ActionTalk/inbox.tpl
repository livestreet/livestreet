{include file='header.tpl' menu='talk'}


			<div class="topic people top-blogs talk-table">
				<h1>{$aLang.talk_inbox}</h1>
				<form action="" method="post" id="form_talks_list">
				<table>
					<thead>
						<tr>
							<td width="20px"><input type="checkbox" name="" onclick="checkAllTalk(this);"></td>
							<td class="user">{$aLang.talk_inbox_target}</td>
							<td></td>
							<td>{$aLang.talk_inbox_title}</td>
							<td>{$aLang.talk_inbox_date}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aTalks item=oTalk}
						{assign var="oTalkUser" value=$oTalk->getTalkUser()}
						<tr>
							<td><input type="checkbox" name="talk_del[{$oTalk->getId()}]" class="form_talks_checkbox"></td>
							<td class="name">							
								{foreach from=$oTalk->getTalkUsers() item=oUser name=users}
									{if $oUser->getUserId()!=$oUserCurrent->getId()}
									{assign var="oAdditionalUser" value=$oUser->getUser()}					
										<a href="{$oAdditionalUser->getUserWebPath()}" class="author {if !$oUser->getIsActive()}inactive{/if}">{$oAdditionalUser->getLogin()}</a>
									{/if}
								{/foreach}
							</td>							
							<td class="talk">
								<span class="favorite {if $oTalk->getIsFavourite()}active{/if}">
									<a href="#" onclick="lsFavourite.toggle({$oTalk->getId()},this,'talk'); return false;"></a>
								</span>
							</td>
							<td>	
							{if $oTalkUser->getCommentCountNew() or !$oTalkUser->getDateLast()}
								<a href="{router page='talk'}read/{$oTalk->getId()}/"><b>{$oTalk->getTitle()|escape:'html'}</b></a>
							{else}
								<a href="{router page='talk'}read/{$oTalk->getId()}/">{$oTalk->getTitle()|escape:'html'}</a>
							{/if}
					 		&nbsp;	
							{if $oTalk->getCountComment()}
								{$oTalk->getCountComment()} {if $oTalkUser->getCommentCountNew()}<span style="color: #008000;">+{$oTalkUser->getCommentCountNew()}</span>{/if}
							{/if}
							</td>
							<td>{date_format date=$oTalk->getDate()}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<input type="submit" name="submit_talk_del" value="{$aLang.talk_inbox_delete}" onclick="return confirm('{$aLang.talk_inbox_delete_confirm}');">
				</form>
			</div>
{include file='paging.tpl' aPaging=`$aPaging`}
{include file='footer.tpl'}