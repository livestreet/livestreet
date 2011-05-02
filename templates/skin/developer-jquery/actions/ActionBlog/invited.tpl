<div class="block">
	<form onsubmit="return ls.blog.addInvite({$oBlogEdit->getId()});">
		<p><label>{$aLang.blog_admin_user_add_label}<br />
		<input type="text" id="blog_admin_user_add" name="add" class="input-200 autocomplete-users" /></label></p>
	</form>

	
	<h2>{$aLang.blog_admin_user_invited}</h2>

	<div id="invited_list_block">
		{if $aBlogUsersInvited}
			<ul class="list" id="invited_list">
				{foreach from=$aBlogUsersInvited item=oBlogUser}
					{assign var='oUser' value=$oBlogUser->getUser()}
					<li><a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - <a href="#" onclick="return ls.blog.repeatInvite({$oUser->getId()}, {$oBlogEdit->getId()});">{$aLang.blog_user_invite_readd}</a></li>						
				{/foreach}
			</ul>
		{/if}
	</div>
</div>