<div class="block">
	<h2>{$aLang.talk_speaker_title}</h2>

	{if $oTalk->getUserId()==$oUserCurrent->getId() or $oUserCurrent->isAdministrator() }
		{literal}
			<script language="JavaScript" type="text/javascript">
			document.addEvent('domready', function() {
				new Autocompleter.Request.HTML(
					$('talk_speaker_add'),
					 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY,
					 {
						'indicatorClass': 'autocompleter-loading',
						'minLength': 1,
						'selectMode': 'pick',
						'multiple': true
					}
				);
			});

			function deleteFromTalk(element,idTalk) {
				element.getParent('li').fade(0.7);
				idTarget = element.get('id').replace('speaker_item_','');

				JsHttpRequest.query(
						'POST '+aRouter['talk']+'ajaxdeletetalkuser/',
						{ idTarget:idTarget,idTalk:idTalk, security_ls_key: LIVESTREET_SECURITY_KEY },
						function(result, errors) {
							if (!result) {
								msgErrorBox.alert('Error','Please try again later');
								element.getParent().fade(1);
							}
							if (result.bStateError) {
								msgErrorBox.alert(result.sMsgTitle,result.sMsg);
								element.getParent().fade(1);
							} else {
								element.getParent('li').destroy();
							}
						},
						true
				);

				return true;
			}
			function addListItem(sId,sLogin,sUserLink,sTalkId) {
				oUser=new Element('a',
					{
						'class'  : 'user',
						'text'   : sLogin,
						'href'   : sUserLink
					}
				);
				oLink=new Element('a',
					{
						'id'    : 'speaker_item_'+sId,
						'href'  : "#",
						'class' : 'delete',
						'events': {
							'click': function() {
								deleteFromTalk(this,sTalkId);
								return false;
							}
						}
					}
				);
				oItem=new Element('li');
				$('speakerList').adopt(oItem.adopt(oUser,oLink));
			}
			function addToTalk(idTalk) {
				sUsers=$('talk_speaker_add').get('value');
				if(sUsers.length<2) {
					msgErrorBox.alert('Error','Пользователь не указан');
					return false;
				}
				$('talk_speaker_add').set('value','');
				JsHttpRequest.query(
						'POST '+aRouter['talk']+'ajaxaddtalkuser/',
						{ users: sUsers, idTalk: idTalk, security_ls_key: LIVESTREET_SECURITY_KEY },
						function(result, errors) {
							if (!result) {
								msgErrorBox.alert('Error','Please try again later');
							}
							if (result.bStateError) {
								msgErrorBox.alert(result.sMsgTitle,result.sMsg);
							} else {
								var aUsers = result.aUsers;
								aUsers.each(function(item,index) {
									if(item.bStateError){
										msgErrorBox.alert(item.sMsgTitle, item.sMsg);
									} else {
										addListItem(item.sUserId,item.sUserLogin,item.sUserLink,idTalk);
									}
								});
							}
						},
						true
				);
				return false;
			}
			</script>
		{/literal}

		<div class="block-content">
			<form onsubmit="addToTalk({$oTalk->getId()}); return false;">
				<p><label for="talk_speaker_add">{$aLang.talk_speaker_add_label}:</label><br />
				<input type="text" id="talk_speaker_add" name="add" value="" class="input-wide" /></p>
			</form>
		</div>
	{/if}

	<div class="block-content" id="speakerListBlock">
		{if $oTalk->getTalkUsers()}
			<ul class="list" id="speakerList">
				{foreach from=$oTalk->getTalkUsers() item=oTalkUser name=users}
					{if $oTalkUser->getUserId()!=$oUserCurrent->getId()}
					{assign var="oUser" value=$oTalkUser->getUser()}
						{if $oTalkUser->getUserActive()!=$TALK_USER_DELETE_BY_AUTHOR}
							<li>
								<a class="user {if $oTalkUser->getUserActive()!=$TALK_USER_ACTIVE}inactive{/if}" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> -
								{if $oTalkUser->getUserActive()==$TALK_USER_ACTIVE and ($oTalk->getUserId()==$oUserCurrent->getId() or $oUserCurrent->isAdministrator())}<a href="#" id="speaker_item_{$oTalkUser->getUserId()}" onclick="deleteFromTalk(this,{$oTalk->getId()}); return false;" class="delete">{$aLang.blog_delete}</a>{/if}
							</li>
						{/if}
					{/if}
				{/foreach}
			</ul>
		{/if}
	</div>
</div>