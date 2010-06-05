{literal}
<script language="JavaScript" type="text/javascript">
document.addEvent('domready', function() {
	new Autocompleter.Request.HTML(
		$('blog_admin_user_add'),
		 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY,
		 {
			'indicatorClass': 'autocompleter-loading',
			'minLength': 1,
			'selectMode': 'pick',
			'multiple': true
		}
	);
});
</script>
{/literal}


<div class="block">
	<form onsubmit="addBlogInvite({$oBlogEdit->getId()}); return false;">
		<p><label for="blog_admin_user_add">{$aLang.blog_admin_user_add_label}</label><br />
		<input type="text" id="blog_admin_user_add" name="add" value="" class="input-200" /></p>
	</form>


	<h2>{$aLang.blog_admin_user_invited}</h2>

	<div class="block-content" id="invited_list_block">
		{if $aBlogUsersInvited}
			<ul class="list" id="invited_list">
				{foreach from=$aBlogUsersInvited item=oBlogUser}
					{assign var='oUser' value=$oBlogUser->getUser()}
					<li><a href="{$oUser->getUserWebPath()}" class="user">{$oUser->getLogin()}</a> - <a href="#" class="local" onclick="return reBlogInvite({$oUser->getId()},{$oBlogEdit->getId()});">{$aLang.blog_user_invite_readd}</a></li>						
				{/foreach}
			</ul>
		{/if}
	</div>
</div>