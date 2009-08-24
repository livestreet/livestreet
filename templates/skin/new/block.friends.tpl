			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_friends}</h1>
					
				{if $aUsersFriend}
					<div class="block-content">
					{literal}
						<script language="JavaScript" type="text/javascript">
						function friendToogle(element) {
							login=element.getNext('a').get('text');
							to=$('talk_users')
								.getProperty('value')
									.split(',')
										.map(function(item,index){
											return item.trim();
										}).filter(function(item,index){
											return item.length>0;
										});
							$('talk_users').setProperty(
								'value', 
								(element.getProperty('checked'))
									? to.include(login).join(',')
									: to.erase(login).join(',')
							);							
						}
						window.addEvent('domready', function() { 
							// сканируем список друзей      
							var lsCheckList=$('friends')
												.getElements('input[type=checkbox]')
													.addEvents({
														'click': function(){
															return friendToogle(this);
														}
													});
							// toogle checkbox`а при клике на ссылку-логин
							$('friends').getElements('a').addEvents({
								'click': function() {
									checkbox=this.getPrevious('input[type=checkbox]');
									checkbox.setProperty('checked',!checkbox.getProperty('checked'));
									friendToogle(checkbox);
									return false;
								}
							});
							// выделить всех друзей
							$('friend_check_all').addEvents({
								'click': function(){
									lsCheckList.each(function(item,index){
										if(!item.getProperty('checked')) {
											item.setProperty('checked',true);
											friendToogle(item);
										}
									});
									return false;
								}
							});
							// снять выделение со всех друзей
							$('friend_uncheck_all').addEvents({
								'click': function(){
									lsCheckList.each(function(item,index){
										if(item.getProperty('checked')) {
											item.setProperty('checked',false);
											friendToogle(item);
										}
									});
									return false;
								}
							});							
						});
						</script>
					{/literal}
					
						<ul class="list" id="friends">
							{foreach from=$aUsersFriend item=oFriend}
								<li><input type="checkbox" name="friend[{$oFriend->getId()}]"/> <a href="#" class="stream-author">{$oFriend->getLogin()}</a></li>						
							{/foreach}
						</ul>
					</div>
					<div class="right"><a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | <a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a></div>

				{else}
					{$aLang.block_friends_empty}
				{/if}
					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>