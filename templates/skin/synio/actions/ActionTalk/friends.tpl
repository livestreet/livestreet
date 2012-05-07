<div class="talk-search talk-friends" id="block_talk_search">
	<header>
		<a href="#" class="link-dotted close" onclick="ls.talk.toggleSearchForm(); return false;">{$aLang.block_friends}</a>
	</header>

	
	<div class="talk-search-content" id="block_talk_friends_content">
		{if $aUsersFriend}
			<ul class="friend-list" id="friends">
				{foreach from=$aUsersFriend item=oFriend}
					<li>
						<input id="talk_friend_{$oFriend->getId()}" type="checkbox" name="friend[{$oFriend->getId()}]" class="input-checkbox" /> 
						<label for="talk_friend_{$oFriend->getId()}" id="talk_friend_{$oFriend->getId()}_label">{$oFriend->getLogin()}</label>
					</li>
				{/foreach}
			</ul>
			
			<ul class="actions">
				<li><a href="#" id="friend_check_all" class="link-dotted">{$aLang.block_friends_check}</a></li>
				<li><a href="#" id="friend_uncheck_all" class="link-dotted">{$aLang.block_friends_uncheck}</a></li>
			</ul>
		{else}
			<div class="notice-empty">{$aLang.block_friends_empty}</div>
		{/if}
	</div>
</div>