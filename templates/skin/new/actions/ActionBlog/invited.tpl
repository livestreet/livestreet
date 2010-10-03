			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
				{literal}
						<script language="JavaScript" type="text/javascript">
						document.addEvent('domready', function() {	
							new Autocompleter.Request.LS.JSON(
								$('blog_admin_user_add'),
								 aRouter['ajax']+'autocompleter/user/?security_ls_key='+LIVESTREET_SECURITY_KEY, 
								 {
									'indicatorClass': 'autocompleter-loading',
									'minLength': 1,
									'selectMode': 'pick',
									'multiple': true
								}
							);
						});
						function addUserItem(sLogin,sPath) {
							if($('invited_list_block').getElements('ul').length==0) {
								list=new Element('ul', {class:'list',id:'invited_list'});
								$('invited_list_block').adopt(list);
							}
							
							oSpan=new Element('span',{'class':'user'});
							oLink=new Element('a',{'href':sPath, 'text':sLogin});
							oItem=new Element('li');
							$('invited_list').adopt(oItem.adopt(oSpan.adopt(oLink)));
						}
						
						function addBlogInvite(idBlog) {
							sUsers=$('blog_admin_user_add').get('value');
							if(!sUsers) {
								return false;
							}
							$('blog_admin_user_add').set('value','');
							
							new Request.JSON({
								url: aRouter['blog']+'ajaxaddbloginvite/',
								noCache: true,
								data: { users: sUsers, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
								onSuccess: function(result){
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
												addUserItem(item.sUserLogin,item.sUserWebPath);
											}
										});
									}
								},
								onFailure: function(){
									msgErrorBox.alert('Error','Please try again later');
								}
							}).send();
											
							return false;
						}
						
						function reBlogInvite(idUser,idBlog) {
							
							new Request.JSON({
								url: aRouter['blog']+'ajaxrebloginvite/',
								noCache: true,
								data: { idUser: idUser, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
								onSuccess: function(result){
									if (!result) {
										msgErrorBox.alert('Error','Please try again later');
									}
									if (result.bStateError) {
										msgErrorBox.alert(result.sMsgTitle,result.sMsg);
									} else {
										msgNoticeBox.alert(result.sMsgTitle, result.sMsg);
									}
								},
								onFailure: function(){
									msgErrorBox.alert('Error','Please try again later');
								}
							}).send();
							return false;
						}						
						</script>		
				{/literal}		
		<div class="block-content">				
		<form onsubmit="addBlogInvite({$oBlogEdit->getId()}); return false;">
			<p><label for="blog_admin_user_add">{$aLang.blog_admin_user_add_label}</label><br />
			<input type="text" id="blog_admin_user_add" name="add" value="" class="w100p" /><br />
			</p>
		</form>
		</div>
				<h1>{$aLang.blog_admin_user_invited}</h1>
				<div class="block-content" id="invited_list_block">						
				{if $aBlogUsersInvited} 
					<ul class="list" id="invited_list">
						{foreach from=$aBlogUsersInvited item=oBlogUser}
							{assign var='oUser' value=$oBlogUser->getUser()}
							<li><span class="user"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></span> &mdash; <a href="#" class="local" onclick="return reBlogInvite({$oUser->getId()},{$oBlogEdit->getId()});">{$aLang.blog_user_invite_readd}</a></li>						
						{/foreach}
					</ul>
				{/if}
				</div>
				<br />	
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>