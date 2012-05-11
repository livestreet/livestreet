{if $oTalk->getUserId()==$oUserCurrent->getId() or $oUserCurrent->isAdministrator()}
	<div class="talk-recipients" id="talk_recipients">
		<h3>{$aLang.talk_speaker_title}</h3>

		<form onsubmit="return ls.talk.addToTalk({$oTalk->getId()});">
			<p><label for="talk_speaker_add">{$aLang.talk_speaker_add_label}:</label>
			<input type="text" id="talk_speaker_add" name="add" class="input-text input-width-300 autocomplete-users-sep" /></p>
			<input type="hidden" id="talk_id" value="{$oTalk->getId()}" />
		</form>

		<div id="speaker_list_block">
			{if $oTalk->getTalkUsers()}
				<ul class="list" id="speaker_list">
					{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
						{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
							{assign var="oUser" value=$oTalkUser->getUser()}

							{if $oTalkUser->getUserActive()!=$TALK_USER_DELETE_BY_AUTHOR}
								<li id="speaker_item_{$oTalkUser->getUserId()}_area">
									<a class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
									{if $oTalkUser->getUserActive()==$TALK_USER_ACTIVE}- <a href="#" id="speaker_item_{$oTalkUser->getUserId()}" class="delete">{$aLang.blog_delete}</a>{/if}
								</li>
							{/if}
						{/if}
					{/foreach}
				</ul>
			{/if}
		</div>
	</div>
{/if}