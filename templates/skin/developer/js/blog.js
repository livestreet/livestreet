function ajaxJoinLeaveBlog(obj,idBlog) {
	obj=$(obj);
	JsHttpRequest.query(
    	'POST '+DIR_WEB_ROOT+'/include/ajax/joinLeaveBlog.php',
        { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
        function(result, errors) {
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
        true
    );
}


function ajaxBlogInfo(idBlog) { 	
	JsHttpRequest.query(
    	'POST '+DIR_WEB_ROOT+'/include/ajax/blogInfo.php',                       
        { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY  },
        function(result, errors) {  
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
        true
    );
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

	JsHttpRequest.query(
		   'POST '+aRouter['blog']+'ajaxaddbloginvite/',
			{ users: sUsers, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
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
							addUserItem(item.sUserLogin,item.sUserWebPath);
						}
					});
				}
			},
			true
	);
	return false;
}


function reBlogInvite(idUser,idBlog) {
	JsHttpRequest.query(
		   'POST '+aRouter['blog']+'ajaxrebloginvite/',
			{ idUser: idUser, idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY },
			function(result, errors) {
				if (!result) {
					msgErrorBox.alert('Error','Please try again later');
				}
				if (result.bStateError) {
					msgErrorBox.alert(result.sMsgTitle,result.sMsg);
				} else {
					msgNoticeBox.alert(result.sMsgTitle, result.sMsg);
				}
			},
			true
	);
	return false;
}