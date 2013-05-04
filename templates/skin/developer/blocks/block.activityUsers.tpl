{**
 * Выбор пользователей для чтения в ленте активности
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
	{assign var='noNav' value=true}
{/block}

{block name='title'}{$aLang.stream_block_users_title}{/block}
{block name='type'}activity{/block}

{block name='content'}
	{if $oUserCurrent}
		<small class="note">{$aLang.stream_settings_note_follow_user}</small>
		
		<p><input type="text" id="activity-block-users-input" autocomplete="off" placeholder="{$aLang.stream_block_config_append}" class="autocomplete-users input-text input-width-full" /></p>
		
		{if $aStreamSubscribedUsers}
			<ul id="activity-block-users" class="user-list-mini max-height-200 js-activity-block-users">
				{foreach from=$aStreamSubscribedUsers item=oUser}
					{assign var=iUserId value=$oUser->getId()}
					
					{if !isset($aStreamFriends.$iUserId)}
						<li id="activity-block-users-item-{$iUserId}">
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
			<ul id="activity-block-users" style="display: none;"></ul>
			<p id="activity-block-users-notice">{$aLang.stream_no_subscribed_users}</p>
		{/if}
	{/if}
{/block}