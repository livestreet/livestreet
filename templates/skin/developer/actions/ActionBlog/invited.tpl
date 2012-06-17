<section class="block">
	<header class="block-header">
		<h3>{$aLang.blog_admin_user_add_header}</h3>
	</header>

	<div class="block-content">
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
				<ul id="invited_list">
					{foreach from=$aBlogUsersInvited item=oBlogUser}
						{assign var='oUser' value=$oBlogUser->getUser()}
						
						<li id="blog-invite-remove-item-{$oBlogEdit->getId()}-{$oUser->getId()}">
							<a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - 
							<a href="#" onclick="return ls.blog.repeatInvite({$oUser->getId()}, {$oBlogEdit->getId()});">{$aLang.blog_user_invite_readd}</a>
							<a href="#" onclick="return ls.blog.removeInvite({$oUser->getId()}, {$oBlogEdit->getId()});">{$aLang.blog_user_invite_remove}</a>
						</li>						
					{/foreach}
				</ul>
			{else}
				<span class="notice-empty">{$aLang.blog_admin_user_add_empty}</span>
			{/if}
		</div>
	</div>
</section>