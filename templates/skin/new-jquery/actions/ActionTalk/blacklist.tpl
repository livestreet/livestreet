<div class="block">
	<h2>{$aLang.talk_blacklist_title}</h2>
	

	<form onsubmit="return ls.talk.addToBlackList();">
		<p><label>{$aLang.talk_balcklist_add_label}:<br />
		<input type="text" id="talk_blacklist_add" name="add" class="input-wide autocomplete-users" /></label></p>
	</form>


	<div id="black_list_block">
		{if $aUsersBlacklist}
			<ul class="list" id="black_list">
				{foreach from=$aUsersBlacklist item=oUser}
					<li id="blacklist_item_{$oUser->getId()}_area"><a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - <a href="#" id="blacklist_item_{$oUser->getId()}" class="delete">{$aLang.blog_delete}</a></li>
				{/foreach}
			</ul>
		{/if}
	</div>
</div>