<section class="block">
	<h3>{$aLang.blog_admin_user_add_header}</h3>

	
	<form onsubmit="return ls.blog.addInvite({$oBlogEdit->getId()});">
		<p>
			<label for="blog_admin_user_add">{$aLang.blog_admin_user_add_label}:</label>
			<input type="text" id="blog_admin_user_add" name="add" class="input-text input-width-full autocomplete-users-sep" />
		</p>
	</form>

	<br />
	<h3>{$aLang.blog_admin_user_invited}:</h3>

	<div id="invited_list_block">
		{if $aBlogUsersInvited}
			<ul class="user-list" id="invited_list">
				{foreach from=$aBlogUsersInvited item=oBlogUser}
					{assign var='oUser' value=$oBlogUser->getUser()}
					
					<li>
						<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" /></a>
						<a href="{$oUser->getUserWebPath()}" class="username">{$oUser->getLogin()}</a><br />
						<a href="#" onclick="return ls.blog.repeatInvite({$oUser->getId()}, {$oBlogEdit->getId()});">{$aLang.blog_user_invite_readd}</a>
					</li>						
				{/foreach}
			</ul>
		{else}
			<span class="notice-empty">{$aLang.blog_admin_user_add_empty}</span>
		{/if}
	</div>
</section>