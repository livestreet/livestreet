{assign var="sidebarPosition" value='left'}
{include file='header.tpl' noShowSystemMessage=false}

{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.talk.tpl'}


{if $aTalks}
	{include file='actions/ActionTalk/filter.tpl'}

	<form action="{router page='talk'}" method="post" id="form_talks_list">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		<input type="hidden" name="submit_talk_read" id="form_talks_list_submit_read" value="" />
		<input type="hidden" name="submit_talk_del" id="form_talks_list_submit_del" value="" />

		<button type="submit" onclick="ls.talk.makeReadTalks()" class="button">{$aLang.talk_inbox_make_read}</button>
		<button type="submit" onclick="if (confirm('{$aLang.talk_inbox_delete_confirm}')){ ls.talk.removeTalks() };" class="button">{$aLang.talk_inbox_delete}</button>
		<br /><br />
		<table class="table table-talk">
			<thead>
				<tr>
					<th class="cell-checkbox"><input type="checkbox" name="" class="input-checkbox" onclick="ls.tools.checkAll('form_talks_checkbox', this, true);"></th>
					<th class="cell-favourite"></th>
					<th class="cell-recipients">{$aLang.talk_inbox_target}</th>
					<th class="cell-title">{$aLang.talk_inbox_title}</th>
					<th class="cell-date ta-r">{$aLang.talk_inbox_date}</th>
				</tr>
			</thead>

			<tbody>
				{foreach from=$aTalks item=oTalk}
					{assign var="oTalkUserAuthor" value=$oTalk->getTalkUser()}
					<tr>
						<td class="cell-checkbox"><input type="checkbox" name="talk_select[{$oTalk->getId()}]" class="form_talks_checkbox input-checkbox" /></td>
						<td class="cell-favourite">
							<a href="#" onclick="return ls.favourite.toggle({$oTalk->getId()},this,'talk');" class="favourite {if $oTalk->getIsFavourite()}active{/if}"></a>
						</td>
						<td>
							{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
								{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
								{assign var="oUser" value=$oTalkUser->getUser()}
									<a href="{$oUser->getUserWebPath()}" class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}">{$oUser->getLogin()}</a>
								{/if}
							{/foreach}
						</td>
						<td>
							{strip}
								<a href="{router page='talk'}read/{$oTalk->getId()}/" class="js-title-comment" title="{$oTalk->getTextLast()|strip_tags|truncate:100:'...'}">
									{if $oTalkUserAuthor->getCommentCountNew() or !$oTalkUserAuthor->getDateLast()}
										<strong>{$oTalk->getTitle()|escape:'html'}</strong>
									{else}
										{$oTalk->getTitle()|escape:'html'}
									{/if}
								</a>
							{/strip}
							
							{if $oTalk->getCountComment()}
								({$oTalk->getCountComment()}{if $oTalkUserAuthor->getCommentCountNew()} +{$oTalkUserAuthor->getCommentCountNew()}{/if})
							{/if}
							{if $oUserCurrent->getId()==$oTalk->getUserIdLast()}
								&rarr;
							{else}
								&larr;
							{/if}
						</td>
						<td class="cell-date ta-r">{date_format date=$oTalk->getDate() format="j F Y, H:i"}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</form>
{else}
	<div class="notice-empty">{$aLang.talk_inbox_empty}</div>
{/if}

			
{include file='paging.tpl' aPaging=$aPaging}
{include file='footer.tpl'}