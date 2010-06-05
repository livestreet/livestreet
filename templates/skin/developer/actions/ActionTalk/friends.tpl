<div class="block">
	<h2>{$aLang.block_friends}</h2>

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
					<li><label><input type="checkbox" name="friend[{$oFriend->getId()}]" class="checkbox" /><a href="#">{$oFriend->getLogin()}</a></label></li>
				{/foreach}
			</ul>
		</div>
		<div class="bottom"><a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | <a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a></div>

	{else}
		{$aLang.block_friends_empty}
	{/if}
</div>