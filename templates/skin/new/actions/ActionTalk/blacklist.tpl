			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.talk_blacklist_title}</h1>
				{literal}
						<script language="JavaScript" type="text/javascript">
						document.addEvent('domready', function() {	
							new Autocompleter.Request.HTML(
								$('talk_blacklist_add'),
								 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY, 
								 {
									'indicatorClass': 'autocompleter-loading',
									'minLength': 1,
									'selectMode': 'pick',
									'multiple': true
								}
							);
						});						
						
						function deleteFromBlackList(element) {
							element.getParent('li').fade(0.7);							
							idTarget = element.get('id').replace('blacklist_item_','');
		
			                JsHttpRequest.query(
			                        'POST '+aRouter['talk']+'ajaxdeletefromblacklist/',			                        
			                        { idTarget: idTarget, security_ls_key: LIVESTREET_SECURITY_KEY },
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
							                
							                if($('blackList').getElements('li').length==0) {
							                	$('blackList').destroy();
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
								$('list_uncheck_all').removeProperty('style');
								list=new Element('ul', {'class':'list',id:'blackList'});
								$('blackListBlock').adopt(list);
							}
							
							oSpan=new Element('span',
								{
									'class'  : 'user',
									'text'   : sLogin
								}
							);
							oLink=new Element('a',
								{
									'id'    : 'blacklist_item_'+sId,
									'href'  : "#",
									'class' : 'delete',
									'events': {
										'click': function() {
											deleteFromBlackList(this); 
											return false;
										}
									}
								}
							);
							oItem=new Element('li');
							$('blackList').adopt(oItem.adopt(oSpan,oLink));
						}
						function addToBlackList() {
							sUsers=$('talk_blacklist_add').get('value');
							if(sUsers.length<2) {
								msgErrorBox.alert('Error','Пользователь не указан');
								return false;
							}
							$('talk_blacklist_add').set('value','');
			                JsHttpRequest.query(
			                       'POST '+aRouter['talk']+'ajaxaddtoblacklist/',                      
			                        { users: sUsers, security_ls_key: LIVESTREET_SECURITY_KEY },
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
						<form onsubmit="addToBlackList(); return false;">
							<p><label for="talk_blacklist_add">{$aLang.talk_balcklist_add_label}:</label><br />
							<input type="text" id="talk_blacklist_add" name="add" value="" class="w100p" /><br />
							</p>										
						</form>
					</div>
				
				<div class="block-content" id="blackListBlock">						
				{if $aUsersBlacklist}
					{literal}
						<script>
						window.addEvent('domready', function() { 
							$('list_uncheck_all').addEvents({
								'click': function(){
									$('blackList').getElements('a').each(function(item,index){
										deleteFromBlackList(item);
									});
									return false;
								}
							});							
						});
						</script>						
					{/literal}
					<ul class="list" id="blackList">
						{foreach from=$aUsersBlacklist item=oUser}
							<li><span class="user">{$oUser->getLogin()}</span><a href="#" id="blacklist_item_{$oUser->getId()}" onclick="deleteFromBlackList(this); return false;" class="delete"></a></li>						
						{/foreach}
					</ul>
				{/if}
				</div>
				<div class="right"><a href="#" id="list_uncheck_all" {if !$aUsersBlacklist}style="display:none;"{/if}>{$aLang.talk_balcklist_delete_all}</a></div>
					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>