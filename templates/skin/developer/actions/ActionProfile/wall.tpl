{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}
			

			
{include file='actions/ActionProfile/profile_top.tpl'}
<h3 class="profile-page-header">{$aLang.user_menu_profile_wall}</h3>


<script type="text/javascript">
	ls.wall.init({
		login:'{$oUserProfile->getLogin()}'
	});
</script>

{if $oUserCurrent}
	<form class="wall-submit">
		<label for="wall-text">{$aLang.wall_add_title}:</label>
		<p><textarea rows="4" id="wall-text" class="input-text input-width-full js-wall-reply-parent-text"></textarea></p>

		<button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);" class="button button-primary js-button-wall-submit">{$aLang.wall_add_submit}</button>
	</form>
{else}
	<div class="wall-note" id="wall-note-list-empty">
		<h3>{$aLang.wall_add_quest}</h3>
	</div>
{/if}

{if !count($aWall)}
	<div class="wall-note">
		<h3>{$aLang.wall_list_empty}</h3>
	</div>
{/if}

<div id="wall-container" class="comments wall">
	{include file='actions/ActionProfile/wall_items.tpl'}
</div>


{if $iCountWall-count($aWall)}
	<a href="#" onclick="return ls.wall.loadNext();" id="wall-button-next" class="wall-more"><span class="wall-more-inner">{$aLang.wall_load_more} (<span id="wall-count-next">{$iCountWall-count($aWall)}</span>)</span></a>
{/if}



{include file='footer.tpl'}