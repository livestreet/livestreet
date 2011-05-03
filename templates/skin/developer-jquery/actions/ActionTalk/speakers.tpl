<div class="block">
	<h2>{$aLang.talk_speaker_title}</h2>

	{if $oTalk->getUserId()==$oUserCurrent->getId() or $oUserCurrent->isAdministrator()}
		<form onsubmit="return ls.talk.addToTalk({$oTalk->getId()});">
			<p><label>{$aLang.talk_speaker_add_label}:<br />
			<input type="text" id="talk_speaker_add" name="add" class="input-wide autocomplete-users" /></label></p>
			<input type="hidden" id="talk_id" value="{$oTalk->getId()}" />
		</form>
	{/if}

	<div id="speaker_list_block">
		{if $oTalk->getTalkUsers()}
			<ul class="list" id="speaker_list">
				{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
					{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
					{assign var="oUser" value=$oTalkUser->getUser()}
						{if $oTalkUser->getUserActive()!=$TALK_USER_DELETE_BY_AUTHOR}
							<li id="speaker_item_{$oTalkUser->getUserId()}_area">
								<a class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
								{if $oTalkUser->getUserActive()==$TALK_USER_ACTIVE and ($oTalk->getUserId()==$oUserCurrent->getId() or $oUserCurrent->isAdministrator())}- <a href="#" id="speaker_item_{$oTalkUser->getUserId()}" class="delete">{$aLang.blog_delete}</a>{/if}
							</li>
						{/if}
					{/if}
				{/foreach}
			</ul>
		{/if}
	</div>
</div>