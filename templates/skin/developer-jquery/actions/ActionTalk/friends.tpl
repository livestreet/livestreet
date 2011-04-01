<div class="block">
	<h2>{$aLang.block_friends}</h2>

	{if $aUsersFriend}
		<div class="block-content">
			<ul class="list" id="friends">
				{foreach from=$aUsersFriend item=oFriend}
					<li><label><input type="checkbox" name="friend[{$oFriend->getId()}]" class="checkbox" />{$oFriend->getLogin()}</label></li>
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