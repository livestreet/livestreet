{**
 * Выбор пользователей для чтения в ленте активности
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.stream_block_users_title}{/block}
{block name='block_type'}activity{/block}

{block name='block_content'}
	{if $oUserCurrent}
		<small class="note">{$aLang.stream_settings_note_follow_user}</small>
		
		<div class="search-form">
			<div class="search-form-search">
				<input type="text" id="activity-block-users-input" autocomplete="off" placeholder="{$aLang.stream_block_config_append}" class="search-form-input autocomplete-users width-full" />
				<div onclick="ls.stream.appendUser();" class="search-form-submit"></div>
			</div>
		</div>
		
		{if $aStreamSubscribedUsers}
			<ul id="activity-block-users" class="user-list-mini max-height-200 js-activity-block-users">
				{foreach $aStreamSubscribedUsers as $oUser}
					{$iUserId = $oUser->getId()}
					
					{if !isset($aStreamFriends.$iUserId)}
						<li id="activity-block-users-item-{$iUserId}">
							<input class="input-checkbox"
								   type="checkbox"
								   checked
								   data-user-id="{$iUserId}" />
							<a href="{$oUser->getUserWebPath()}" title="{$oUser->getLogin()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
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