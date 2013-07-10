{**
 * Выбор друзей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.userfeed_block_users_friends}{/block}
{block name='block_type'}activity{/block}
	
	
{if $oUserCurrent && count($aUserfeedFriends)}
	{block name='block_content'}
		<small class="note">{$aLang.userfeed_settings_note_follow_friend}</small>
		
		<ul class="user-list-mini max-height-200 js-userfeed-block-users">
			{foreach $aUserfeedFriends as $oUser}
				{$iUserId = $oUser->getId()}
						
				<li id="userfeed-block-users-item-{$iUserId}">
					<input class="input-checkbox"
							type="checkbox"
							data-user-id="{$iUserId}"
							{if isset($aUserfeedSubscribedUsers.$iUserId)} checked{/if} />
					<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
			{/foreach}
		</ul>
	{/block}
{/if}