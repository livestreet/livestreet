{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}
			

			
{include file='actions/ActionProfile/profile_top.tpl'}
<h3 class="profile-page-header">{$aLang.user_menu_profile_wall}</h3>


<script>
	ls.wall.init({
		login:'{$oUserProfile->getLogin()}'
	});
</script>

{if $oUserCurrent}
	<form class="wall-submit">
		<label for="wall-text">Написать на стене:</label>
		<p><textarea rows="4" id="wall-text" class="input-text input-width-full js-wall-reply-parent-text"></textarea></p>

		<button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);" class="button button-primary">Отправить</button>
	</form>
{/if}


<div id="wall-container" class="comments wall">
	{include file='actions/ActionProfile/wall_items.tpl'}
</div>


{if count($aWall)}
	<a href="#" onclick="return ls.wall.loadNext();" id="wall-button-next" class="wall-more">К предыдущим записям (<span id="wall-count-next">{$iCountWall-count($aWall)}</span>)</a>
{/if}



{include file='footer.tpl'}