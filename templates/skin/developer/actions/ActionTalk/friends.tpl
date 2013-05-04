{* TODO: Slider *}

<a href="#" class="link-dotted" onclick="jQuery('#block_talk_friends_content').toggle(); return false;">{$aLang.block_friends}</a>

<div id="block_talk_friends_content">
	{if $aUsersFriend}
		<ul class="list" id="friends">
			{foreach from=$aUsersFriend item=oFriend}
				<li>
					<input id="talk_friend_{$oFriend->getId()}" type="checkbox" name="friend[{$oFriend->getId()}]" class="input-checkbox" /> 
					<label for="talk_friend_{$oFriend->getId()}" id="talk_friend_{$oFriend->getId()}_label">{$oFriend->getLogin()}</label>
				</li>
			{/foreach}
		</ul>
		
		<footer>
			<a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | 
			<a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a>
		</footer>
	{else}
		<div class="notice-empty">{$aLang.block_friends_empty}</div>
	{/if}
</div>