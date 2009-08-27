			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.talk_blacklist_title}</h1>
				{literal}
						<script language="JavaScript" type="text/javascript">
						document.addEvent('domready', function() {	
							new Autocompleter.Request.HTML(
								$('talk_blacklist_add'),
								 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php', 
								 {
									'indicatorClass': 'autocompleter-loading',
									'minLength': 1,
									'selectMode': 'pick',
									'multiple': true
								}
							);
						});						
						
						function deleteFromBlackList(element) {
							element.setProperty('disabled','disabled').getParent().fade(0.7);
							
							idTarget = 	element.getProperty('name').replace('blacklist[','').replace(']','');		
			                JsHttpRequest.query(
			                        DIR_WEB_ROOT+'/include/ajax/deleteFromBlackList.php',                      
			                        { idTarget: idTarget },
			                        function(result, errors) {     
			                            if (!result) {
							                msgErrorBox.alert('Error','Please try again later');
							                element.setProperties(
							                	{
							                		'checked':true,
							                		'disabled':false
							                	}
							                ).getParent().fade(1);           
							        	}    
							        	if (result.bStateError) {
							                msgErrorBox.alert(result.sMsgTitle,result.sMsg);
							                element.setProperties(
							                	{
							                		'checked':true,
							                		'disabled':false
							                	}
							                ).getParent().fade(1);
							        	} else {
							                msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
							                element.getParent('li').destroy();
							                
							                if($('blackList').getElements('li').length==0) {
							                	$('blackList').destroy();
												p = new Element('p', {'text': 'Принимать от всех'});
							                	$('blackListBlock').adopt(p);
							                	$('list_uncheck_all').setProperty('style','display:none');
							                }
							        	}                                 
			                        },
			                        true
			                ); 
										                
							return true;
						}
						function addListItem(sId,sLogin) {
							if($('blackListBlock').getElements('li').length==0) {
								
								$('blackListBlock').getElement('p').destroy();
								$('list_uncheck_all').removeProperty('style');
								
								list=new Element('ul', {class:'list',id:'blackList'});
								$('blackListBlock').adopt(list);
							}
							
							oLink=new Element('a',
								{
									'class'  : 'stream-author',
									'href'   : "#",
									'text'   : sLogin,
									'events' : {
										'click': function(){
											checkbox=this.getPrevious('input[type=checkbox]');
											checkbox.setProperty('checked',!checkbox.getProperty('checked'));
											deleteFromBlackList(checkbox);											
											return false;
										}
									}
								}
							);
							oCheck=new Element('input',
								{
									'type': 'checkbox',
									'checked': true,
									'name': 'blacklist['+sId+']',
									'events': {
										'click': function() {
											deleteFromBlackList(this); 
											return true;
										}
									}
								}
							);
							oItem=new Element('li');
							$('blackList').adopt(oItem.adopt(oCheck,oLink));
						}
						function addToBlackList() {
							sUsers=$('talk_blacklist_add').get('value');
							if(sUsers.length<2) {
								msgErrorBox.alert('Error','Пользователь не указан');
								return false;
							}
							$('talk_blacklist_add').set('value','');
			                JsHttpRequest.query(
			                        DIR_WEB_ROOT+'/include/ajax/addToBlackList.php',                      
			                        { users: sUsers },
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
							        				msgErrorBox.alert(item.sUserLogin, item.sMsg);;
							        			} else {
							                		addListItem(item.sUserId,item.sUserLogin);							        				
							                		msgNoticeBox.alert(item.sUserLogin, item.sMsg);
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
										
				{if $aUsersBlacklist}
					{literal}
						<script>
						window.addEvent('domready', function() { 
							$('blackList').getElements('a').addEvents({
								'click': function() {
									checkbox=this.getPrevious('input[type=checkbox]');
									checkbox.setProperty('checked',!checkbox.getProperty('checked'));
									deleteFromBlackList(checkbox);
									return false;
								}
							});
							$('list_uncheck_all').addEvents({
								'click': function(){
									$('blackList').getElements('a').each(function(item,index){
										checkbox=item.getPrevious('input[type=checkbox]');
										checkbox.setProperty('checked',!checkbox.getProperty('checked'));
										deleteFromBlackList(checkbox);
									});
									return false;
								}
							});							
						});
						</script>						
					{/literal}
					<div class="block-content" id="blackListBlock">
					
						<ul class="list" id="blackList">
							{foreach from=$aUsersBlacklist item=oUser}
								<li><input type="checkbox" name="blacklist[{$oUser->getId()}]" checked onclick="deleteFromBlackList(this); return true;"/><a href="#" class="stream-author">{$oUser->getLogin()}</a></li>						
							{/foreach}
						</ul>
					</div>
					<div class="right"><a href="#" id="list_uncheck_all">{$aLang.talk_balcklist_delete_all}</a></div>
				{else}
					<p>{$aLang.talk_blacklist_empty}</p><br/>
				{/if}					
					<div class="block-content">
						<form onsubmit="addToBlackList(); return false;">
							<p><label for="talk_blacklist_add">{$aLang.talk_balcklist_add_label}:</label><br />
							<input type="text" id="talk_blacklist_add" name="add" value="{$_aRequest.sender}" class="w100p" /><br />
	       					<span class="form_note">{$aLang.talk_balcklist_add_notice}</span>
							</p>
							<p class="buttons">								
								<input type="submit" name="talk_blacklist_submit" value="{$aLang.talk_balcklist_add_submit}"/>
							</p>										
						</form>			
					</div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>