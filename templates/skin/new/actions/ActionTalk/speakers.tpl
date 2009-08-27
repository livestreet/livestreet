			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.talk_speaker_title}</h1>
				{literal}
						<script language="JavaScript" type="text/javascript">
						document.addEvent('domready', function() {	
							new Autocompleter.Request.HTML(
								$('talk_speaker_add'),
								 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php', 
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
			                        DIR_WEB_ROOT+'/include/ajax/deleteFromTalk.php',                      
			                        { idTarget:idTarget,idTalk:idTalk },
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
						function addListItem(sId,sLogin) {
							if($('speakerList').getElements('span:contains(kachayev)').length==1) {
								return false;
							}
							
							oSpan=new Element('span',
								{
									'class'  : 'user',
									'text'   : sLogin
								}
							);
							oLink=new Element('a',
								{
									'id'    : 'spaker_item_'+sId,
									'href'  : "#",
									'class' : 'delete',
									'events': {
										'click': function() {
											deleteFromTalk(this); 
											return false;
										}
									}
								}
							);
							oItem=new Element('li');
							$('speakerList').adopt(oItem.adopt(oSpan,oLink));
						}
						function addToTalk(idTalk) {
							sUsers=$('talk_speaker_add').get('value');
							if(sUsers.length<2) {
								msgErrorBox.alert('Error','Пользователь не указан');
								return false;
							}
							$('talk_speaker_add').set('value','');
			                JsHttpRequest.query(
			                        DIR_WEB_ROOT+'/include/ajax/addToTalk.php',                      
			                        { users: sUsers, idTalk: idTalk },
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
							                		addListItem(item.sUserId,item.sUserLogin);
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
							<input type="text" id="talk_speaker_add" name="add" value="" class="w100p" />
							</p>										
						</form>
					</div>
				
			<div class="block-content" id="speakerListBlock">
				{if $oTalk->getTalkUsers()}
					<ul class="list" id="speakerList">
						{foreach from=$oTalk->getTalkUsers() item=oUser name=users}
							{if $oUser->getUserId()!=$oUserCurrent->getId()}
							{assign var="oAdditionalUser" value=$oUser->getUser()}	
								{if $oUser->getUserActive()!=4}<li><span class="user {if $oUser->getUserActive()!=1}inactive{/if}">{$oAdditionalUser->getLogin()}</span>{if $oUser->getUserActive()==1}<a href="#" id="speaker_item_{$oUser->getUserId()}" onclick="deleteFromTalk(this,{$oTalk->getId()}); return false;" class="delete"></a>{/if}</li>{/if}						
							{/if}
						{/foreach}
					</ul>
				{/if}
			</div>
				<br />	
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>