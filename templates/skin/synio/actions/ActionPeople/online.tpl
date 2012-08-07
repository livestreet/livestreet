{include file='header.tpl' menu='people'}

<table class="table table-users">
	<thead>
	<tr>
		<th class="cell-name cell-tab"><div class="cell-tab-inner">{$aLang.user}</div></th>
		<th>&nbsp;</th>
		<th class="cell-date cell-tab"><div class="cell-tab-inner active"><span>{$aLang.user_date_last}</span></div></th>
		<th class="cell-rating cell-tab">{$aLang.user_rating}</th>
	</tr>
	</thead>

	<tbody>
	{if $aUsersLast}
		{foreach from=$aUsersLast item=oUserList}
			{assign var="oSession" value=$oUserList->getSession()}
			{assign var="oUserNote" value=$oUserList->getUserNote()}
			<tr>
				<td class="cell-name">
					<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
					<div class="name {if !$oUserList->getProfileName()}no-realname{/if}">
						<p class="username word-wrap"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a></p>
						{if $oUserList->getProfileName()}<p class="realname">{$oUserList->getProfileName()}</p>{/if}
					</div>
				</td>
				<td>
					{if $oUserCurrent}
						{if $oUserNote}
							<button type="button" class="button button-action button-action-note js-infobox" title="{$oUserNote->getText()|escape:'html'}"><i class="icon-synio-comments-green"></i></button>
						{/if}
						<a href="{router page='talk'}add/?talk_users={$oUserList->getLogin()}"><button type="submit"  class="button button-action button-action-send-message"><i class="icon-synio-send-message"></i><span>{$aLang.user_write_prvmsg}</span></button></a>
					{/if}
				</td>
				<td>
					{if $oSession}
						{date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F, H:i"}
					{/if}
				</td>
				<td class="cell-rating {if $oUserList->getRating() < 0}negative{/if}"><strong>{$oUserList->getRating()}</strong></td>
			</tr>
		{/foreach}
	{else}
	<tr>
		<td colspan="4">
			{$aLang.user_empty}
		</td>
	</tr>
	{/if}
	</tbody>
</table>


{include file='paging.tpl' aPaging=$aPaging}

{include file='footer.tpl'}