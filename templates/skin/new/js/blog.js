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