<table class="table table-users">
	{if $bUsersUseOrder}
		<thead>
			<tr>
				<th class="cell-name cell-tab">
					<div class="cell-tab-inner {if $sUsersOrder=='user_login'}active{/if}"><a href="{$sUsersRootPage}?order=user_login&order_way={if $sUsersOrder=='user_login'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_login'}class="{$sUsersOrderWay}"{/if}><span>{$aLang.user}</span></a></div>
				</th>
				<th>&nbsp;</th>
				<th class="cell-skill cell-tab">
					<div class="cell-tab-inner {if $sUsersOrder=='user_skill'}active{/if}"><a href="{$sUsersRootPage}?order=user_skill&order_way={if $sUsersOrder=='user_skill'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_skill'}class="{$sUsersOrderWay}"{/if}><span>{$aLang.user_skill}</span></a></div>
				</th>
				<th class="cell-rating cell-tab">
					<div class="cell-tab-inner {if $sUsersOrder=='user_rating'}active{/if}"><a href="{$sUsersRootPage}?order=user_rating&order_way={if $sUsersOrder=='user_rating'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_rating'}class="{$sUsersOrderWay}"{/if}><span>{$aLang.user_rating}</span></a></div>
				</th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-name">{$aLang.user}</th>
				<th>&nbsp;</th>
				<th class="cell-skill">{$aLang.user_skill}</th>
				<th class="cell-rating cell-tab">
					<div class="cell-tab-inner active"><span>{$aLang.user_rating}</span></div>
				</th>
			</tr>
		</thead>
	{/if}

	<tbody>
		{if $aUsersList}
			{foreach from=$aUsersList item=oUserList}
				{assign var="oSession" value=$oUserList->getSession()}
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
							<a href="{router page='talk'}add/?talk_users={$oUserList->getLogin()}"><button type="submit"  class="button button-action button-action-send-message"><i class="icon-synio-send-message"></i><span>{$aLang.user_write_prvmsg}</span></button></a>
						{/if}
					</td>
					<td class="cell-skill">{$oUserList->getSkill()}</td>
					<td class="cell-rating {if $oUserList->getRating() < 0}negative{/if}"><strong>{$oUserList->getRating()}</strong></td>
				</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="5">
					{if $sUserListEmpty}
						{$sUserListEmpty}
					{else}
						{$aLang.user_empty}
					{/if}
				</td>
			</tr>
		{/if}
	</tbody>
</table>


{include file='paging.tpl' aPaging=$aPaging}