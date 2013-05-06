{**
 * Выбор пользователей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.userfeed_block_users_title}{/block}
{block name='block_type'}activity{/block}

{if $oUserCurrent}
	{block name='block_content'}
		<small class="note">{$aLang.userfeed_settings_note_follow_user}</small>
		
		<div class="stream-settings-userlist">
			<p><input type="text" id="userfeed-block-users-input" autocomplete="off" placeholder="{$aLang.userfeed_block_users_append}" class="autocomplete-users input-text input-width-full" /></p>
			
			{if count($aUserfeedSubscribedUsers)}
				<ul id="userfeed-block-users" class="user-list-mini max-height-200 js-userfeed-block-users">
					{foreach from=$aUserfeedSubscribedUsers item=oUser}
						{assign var=iUserId value=$oUser->getId()}
						
						{if !isset($aUserfeedFriends.$iUserId)}
							<li id="userfeed-block-users-item-{$iUserId}">
								<input class="input-checkbox"
										type="checkbox"
										checked="checked"
										data-user-id="{$iUserId}" />
								<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
							</li>
						{/if}
					{/foreach}
				 </ul>
			{else}
				<ul id="userfeed_block_users_list"></ul>
			{/if}
		</div>
	{/block}
{/if}