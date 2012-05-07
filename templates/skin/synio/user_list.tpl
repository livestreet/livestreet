<table class="table table-users">
	{if $bUsersUseOrder}
		<thead>
			<tr>
				<th class="cell-follow"><i class="icon-synio-star-blue"></i></th>
				<th class="cell-name"><a href="{$sUsersRootPage}?order=user_login&order_way={if $sUsersOrder=='user_login'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_login'}class="{$sUsersOrderWay}"{/if}>{$aLang.user}</a></th>
				<th></th>
				<th class="cell-skill"><a href="{$sUsersRootPage}?order=user_skill&order_way={if $sUsersOrder=='user_skill'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_skill'}class="{$sUsersOrderWay}"{/if}>{$aLang.user_skill}</a></th>
				<th class="cell-rating"><a href="{$sUsersRootPage}?order=user_rating&order_way={if $sUsersOrder=='user_rating'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_rating'}class="{$sUsersOrderWay}"{/if}>{$aLang.user_rating}</a></th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-follow"><i class="icon-synio-star-blue"></i></th>
				<th class="cell-name">{$aLang.user}</th>
				<th></th>
				<th class="cell-skill">{$aLang.user_skill}</th>
				<th class="cell-rating">{$aLang.user_rating}</th>
			</tr>
		</thead>
	{/if}

	<tbody>
		{if $aUsersList}
			{foreach from=$aUsersList item=oUserList}
				{assign var="oSession" value=$oUserList->getSession()}
				<tr>
					<td class="cell-follow"><i class="follow"></i></td>
					<td class="cell-name">
						<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
						<div class="name {if !$oUserList->getProfileName()}no-realname{/if}">
							<p class="username word-wrap"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a></p>
							{if $oUserList->getProfileName()}<p class="realname">{$oUserList->getProfileName()}</p>{/if}
						</div>
					</td>
					<td>			
						<button class="button button-action button-action-add-friend"><i class="icon-synio-add-friend"></i><span>В друзья</span></button>
						<button class="button button-action button-action-send-message"><i class="icon-synio-send-message"></i></button>
					</td>
					<td class="cell-skill">{$oUserList->getSkill()}</td>
					<td class="cell-rating"><strong>{$oUserList->getRating()}</strong></td>
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