{**
 * Черный список
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	<div class="talk-blacklist-form">
		<h3>{$aLang.talk_blacklist_title}</h3>

		
		<form onsubmit="return ls.talk.addToBlackList();">
			<input type="text" id="talk_blacklist_add" name="add" placeholder="{$aLang.talk_balcklist_add_label}" class="input-text input-width-full autocomplete-users-sep" />
		</form>
	</div>

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