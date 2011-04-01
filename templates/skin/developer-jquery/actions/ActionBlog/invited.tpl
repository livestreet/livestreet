<div class="block">
	<form onsubmit="blogs.addBlogInvite({$oBlogEdit->getId()}); return false;">
		<p><label>{$aLang.blog_admin_user_add_label}<br />
		<input type="text" id="blog_admin_user_add" name="add" class="input-200 autocomplete-users" /></label></p>
	</form>

	
	<h2>{$aLang.blog_admin_user_invited}</h2>

	<div id="invited_list_block">
		{if $aBlogUsersInvited}
			<ul class="list" id="invited_list">
				{foreach from=$aBlogUsersInvited item=oBlogUser}
					{assign var='oUser' value=$oBlogUser->getUser()}
					<li><a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - <a href="#" onclick="blogs.reBlogInvite({$oUser->getId()}, {$oBlogEdit->getId()}); return false;">{$aLang.blog_user_invite_readd}</a></li>						
				{/foreach}
			</ul>
		{/if}
	</div>
</div>