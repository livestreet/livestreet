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
            		obj.getParent().removeClass('active');
            		if (result.bState) {
            			obj.getParent().addClass('active');
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
		data: { idBlog: idBlog, security_ls_key: LIVESTREET_SECURITY_KEY  },
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