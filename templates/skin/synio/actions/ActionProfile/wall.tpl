{assign var="sidebarPosition" value='left'}
{include file='header.tpl' menu='people'}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}
			

			
{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.profile.content.tpl'}


<script type="text/javascript">
	ls.wall.init({
		login:'{$oUserProfile->getLogin()}'
	});
	
	jQuery(document).ready(function($){
		$("textarea").charCount({
			allowed: 250,		
			warning: 0,	
		});
	});
</script>

{if $oUserCurrent}
	<form class="wall-submit">
		<textarea rows="4" id="wall-text" class="input-text input-width-full js-wall-reply-parent-text"></textarea>

		<button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);" class="button button-primary js-button-wall-submit">{$aLang.wall_add_submit}</button>
	</form>
{/if}


<div id="wall-container" class="wall">
	{include file='actions/ActionProfile/wall_items.tpl'}
</div>


{if $iCountWall-count($aWall)}
	<a href="#" onclick="return ls.wall.loadNext();" id="wall-button-next" class="stream-get-more"><span class="wall-more-inner">{$aLang.wall_load_more} (<span id="wall-count-next">{$iCountWall-count($aWall)}</span>)</span></a>
{/if}



{include file='footer.tpl'}