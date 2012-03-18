{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}
			

			
{include file='actions/ActionProfile/profile_top.tpl'}
<h3 class="profile-page-header">Стена</h3>


<script>
	ls.wall.init({
		login:'{$oUserProfile->getLogin()}'
	});
</script>


<form class="wall-submit">
	<label for="wall-text">Написать на стене:</label>
	<p><textarea rows="4" id="wall-text" class="input-text input-width-full"></textarea></p>

	<button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);" class="button button-primary">Отправить</button>
</form>


<div id="wall-container" class="comments wall">
	{include file='actions/ActionProfile/wall_items.tpl'}
</div>


{if count($aWall)}
	<a href="#" onclick="return ls.wall.loadNext();" id="wall-button-next" class="wall-more">К предыдущим записям (<span id="wall-count-next">{$iCountWall-count($aWall)}</span>)</a>
{/if}


<form id="wall-reply-form" class="wall-submit wall-submit-reply" style="display: none;">
	<p><textarea rows="4" id="wall-reply-text" class="input-text input-width-full"></textarea></p>
	<button type="button" onclick="ls.wall.addReply(jQuery('#wall-reply-text').val());" class="button button-primary">Отправить</button>
</form>



{include file='footer.tpl'}