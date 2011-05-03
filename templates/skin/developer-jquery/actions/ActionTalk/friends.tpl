<div class="block">
	<h2>{$aLang.block_friends}</h2>

	{if $aUsersFriend}
		<div class="block-content">
			<ul class="list" id="friends">
				{foreach from=$aUsersFriend item=oFriend}
					<li><input id="talk_friend_{$oFriend->getId()}" type="checkbox" name="friend[{$oFriend->getId()}]" class="checkbox" /><label for="talk_friend_{$oFriend->getId()}" id="talk_friend_{$oFriend->getId()}_label">{$oFriend->getLogin()}</label></li>
				{/foreach}
			</ul>
		</div>
		
		<div class="bottom">
			<a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | 
			<a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a>
		</div>
	{else}
		{$aLang.block_friends_empty}
	{/if}
</div>