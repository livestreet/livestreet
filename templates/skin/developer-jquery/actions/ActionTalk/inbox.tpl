{assign var="sidebarPosition" value='left'}
{include file='header.tpl' noShowSystemMessage=false}

{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.talk.tpl'}


{if $aTalks}
	{include file='actions/ActionTalk/filter.tpl'}

	<form action="{router page='talk'}" method="post" id="form_talks_list">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		
		<button name="submit_talk_del" onclick="return (jQuery('.form_talks_checkbox:checked').size() == 0)?false:confirm('{$aLang.talk_inbox_delete_confirm}');" class="button">{$aLang.talk_inbox_delete}</button>
		<button name="submit_talk_read" onclick="return (jQuery('.form_talks_checkbox:checked').size() == 0)?false:true;" class="button">{$aLang.talk_inbox_make_read}</button>
		<br /><br />
		<table class="table">
			<thead>
				<tr>
					<th><input type="checkbox" name="" onclick="ls.tools.checkAll('form_talks_checkbox', this, true);"></th>
					<th>{$aLang.talk_inbox_target}</th>
					<th></th>
					<th>{$aLang.talk_inbox_title}</th>
					<th class="ta-r">{$aLang.talk_inbox_date}</th>
				</tr>
			</thead>

			<tbody>
				{foreach from=$aTalks item=oTalk}
					{assign var="oTalkUserAuthor" value=$oTalk->getTalkUser()}
					<tr>
						<td><input type="checkbox" name="talk_select[{$oTalk->getId()}]" class="form_talks_checkbox input-checkbox" /></td>
						<td>
							{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
								{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
								{assign var="oUser" value=$oTalkUser->getUser()}
									<a href="{$oUser->getUserWebPath()}" class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}">{$oUser->getLogin()}</a>
								{/if}
							{/foreach}
						</td>
						<td class="ta-c">
							<a href="#" onclick="return ls.favourite.toggle({$oTalk->getId()},this,'talk');" class="favourite {if $oTalk->getIsFavourite()}active{/if}"></a>
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
						<td class="ta-r">{date_format date=$oTalk->getDate()}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</form>
{else}
	<div class="notice-empty">Тут ничего нет</div>
{/if}

			
{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}