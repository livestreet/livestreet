{**
 * Выбор друзей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noNav' value=true}
	{assign var='noFooter' value=true}
{/block}

{block name='title'}{$aLang.userfeed_block_users_friends}{/block}
{block name='type'}activity{/block}
	
	
{if $oUserCurrent && count($aUserfeedFriends)}
	{block name='content'}
		<small class="note">{$aLang.userfeed_settings_note_follow_friend}</small>
		
		<ul class="user-list-mini max-height-200 js-userfeed-block-users">
			{foreach from=$aUserfeedFriends item=oUser}
				{assign var=iUserId value=$oUser->getId()}
						
				<li id="userfeed-block-users-item-{$iUserId}">
					<input class="input-checkbox"
							type="checkbox"
							data-user-id="{$iUserId}"
							{if isset($aUserfeedSubscribedUsers.$iUserId)} checked="checked"{/if} />
					<a href="{$oUser->getUserWebPath()}" title="{$oUser->getLogin()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
					<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
			{/foreach}
		</ul>
	{/block}
{/if}