{**
 * Стена
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_wall}{/block}

{block name='layout_content'}
	<script>
		ls.wall.init({
			login:'{$oUserProfile->getLogin()}'
		});
	</script>

	{if $oUserCurrent}
		<form class="wall-submit">
			<label for="wall-text">{$aLang.wall_add_title}:</label>
			<p><textarea rows="4" id="wall-text" class="width-full js-wall-reply-parent-text"></textarea></p>

			<button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);" class="button button-primary js-button-wall-submit">{$aLang.wall_add_submit}</button>
		</form>
	{else}
		{include file='alert.tpl' sAlertStyle='info' mAlerts=$aLang.wall_add_quest sAlertAttributes='id="wall-note-list-empty"'}
	{/if}

	{if ! count($aWall)}
		{include file='alert.tpl' mAlerts=$aLang.wall_list_empty sAlertStyle='empty' sAlertAttributes='id="wall-note-list-empty"'}
	{/if}

	<div id="wall-container" class="comments wall">
		{include file='actions/ActionProfile/wall.posts.tpl'}
	</div>

	{if $iCountWall - count($aWall)}
		<div onclick="return ls.wall.loadNext();" id="wall-button-next" class="get-more">
			{$aLang.wall_load_more} (<span id="wall-count-next">{$iCountWall-count($aWall)}</span>)
		</div>
	{/if}
{/block}