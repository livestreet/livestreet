{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}
			
			
<div class="profile">
	<img src="{$oUserProfile->getProfileAvatarPath(48)}" alt="avatar" class="avatar" />

	<div id="vote_area_user_{$oUserProfile->getId()}" class="vote {if $oUserProfile->getRating()>=0}vote-count-positive{else}vote-count-negative{/if} {if $oVote} voted {if $oVote->getDirection()>0}voted-up{elseif $oVote->getDirection()<0}voted-down{/if}{/if}">
		<a href="#" class="vote-up" onclick="return ls.vote.vote({$oUserProfile->getId()},this,1,'user');"></a>
		<div id="vote_total_user_{$oUserProfile->getId()}" class="vote-count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">{$oUserProfile->getRating()}</div>
		<a href="#" class="vote-down" onclick="return ls.vote.vote({$oUserProfile->getId()},this,-1,'user');"></a>
	</div>

	<p class="strength">
		{$aLang.user_skill}: <strong class="total" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</strong>
	</p>


	<h2 class="page-header user-login">{$oUserProfile->getLogin()}</h2>

	{if $oUserProfile->getProfileName()}
		<p class="user-name">{$oUserProfile->getProfileName()|escape:'html'}</p>
	{/if}

	{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
		<ul id="profile_actions">
			{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
			<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>
		</ul>
	{/if}
</div>

<h3 class="profile-page-header">Стена</h3>


{include file='menu.profile.tpl'}



<script type="text/javascript">

		ls.wall.init({
			login:'{$oUserProfile->getLogin()}'
		});

</script>
Написать на стену:<br>
<textarea rows="4" cols="30" id="wall-text"></textarea>
<br>
<input type="submit" value="отправить" onclick="ls.wall.add(jQuery('#wall-text').val(),0);">

<div id="wall-contener">
	{include file='actions/ActionProfile/wall_items.tpl'}
</div>

{if count($aWall)}
	<br>
	<a href="#" onclick="return ls.wall.loadNext();" id="wall-button-next">Показать еще, еще <span id="wall-count-next">{$iCountWall-count($aWall)}</span></a>
{/if}

<div id="wall-reply-form" style="display:none;">
	<textarea rows="4" cols="30" id="wall-reply-text"></textarea>
	<br>
	<input type="submit" value="отправить" onclick="ls.wall.addReply(jQuery('#wall-reply-text').val());">
</div>

{include file='footer.tpl'}