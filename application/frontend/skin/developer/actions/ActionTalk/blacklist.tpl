{**
 * Черный список
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	<form onsubmit="return ls.talk.addToBlackList();">
		<p><label for="talk_blacklist_add">{$aLang.talk_balcklist_add_label}:</label>
		<input type="text" id="talk_blacklist_add" name="add" class="input-text input-width-full autocomplete-users-sep" /></p>
	</form>

	<div id="black_list_block">
		{if $aUsersBlacklist}
			<ul class="list" id="black_list">
				{foreach $aUsersBlacklist as $oUser}
					<li id="blacklist_item_{$oUser->getId()}_area"><a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - <a href="#" id="blacklist_item_{$oUser->getId()}" class="delete">{$aLang.blog_delete}</a></li>
				{/foreach}
			</ul>
		{/if}
	</div>
{/block}