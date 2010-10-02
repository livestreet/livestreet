function ajaxJoinLeaveBlog(obj,idBlog) {
	obj=$(obj);
	
	new Request.JSON({
		url: aRouter['blog']+'ajaxblogjoin/',
		noCache: true,
		data: { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
			if (!result) {
                msgErrorBox.alert('Error','Please try again later');
        	}
            if (result.bStateError) {
            	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
            } else {
            	msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
            	if (obj)  {
            		obj.set('html',LANG_JOIN);
            		if (result.bState) {
            			obj.set('html',LANG_LEAVE);
            		}
            		divCount=$('blog_user_count_'+idBlog);
            		if (divCount) {
            			divCount.set('text',result.iCountUser);
            		}
            	}
            }
		},
		onFailure: function(){
			msgErrorBox.alert('Error','Please try again later');
		}
	}).send();
}


function ajaxBlogInfo(idBlog) {
	new Request.JSON({
		url: aRouter['blog']+'ajaxbloginfo/',
		noCache: true,
		data: { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
			if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}
            if (result.bStateError) {
            	
            } else {            	
            	if ($('block_blog_info')) {
            		$('block_blog_info').set('html',result.sText);            		
            	}
            }
		},
		onFailure: function(){
			msgErrorBox.alert('Error','Please try again later');
		}
	}).send();
}


function toggleBlogDeleteForm(id,link) {
	link=$(link);
	var obj=$(id);
	var slideObj = new Fx.Slide(obj);
	if (obj.getStyle('display')=='none') {
		slideObj.hide();
		obj.setStyle('display','block');
	}
	link.toggleClass('inactive');
	slideObj.toggle();
}


function addUserItem(sLogin,sPath) {
	if($('invited_list_block').getElements('ul').length==0) {
		list=new Element('ul', {'class':'list','id':'invited_list'});
		$('invited_list_block').adopt(list);
	}

	oLink=new Element('a',{'href':sPath, 'text':sLogin});
	oItem=new Element('li');
	$('invited_list').adopt(oItem.adopt(oLink));
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