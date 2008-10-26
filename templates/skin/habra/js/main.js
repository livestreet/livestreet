function ajaxVoteTopic(idTopic,value) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {            
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);            	
            	document.getElementById('topic_rating_'+idTopic).innerHTML=req.responseJS.iRating;
            	if (req.responseJS.iRating<0) {
            		document.getElementById('topic_rating_'+idTopic).style.color="#d00000";
            	} else {
            		document.getElementById('topic_rating_'+idTopic).style.color="#008000";
            	}            	
            	if (value>0) {
            		showTopicVote('topic_vote_is_vote_up',idTopic);
            	} else {
            		showTopicVote('topic_vote_is_vote_down',idTopic);
            	}
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/voteTopic.php', true);    
    req.send( { idTopic: idTopic, value: value } );
}

function ajaxVoteBlog(idBlog,value) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {            
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);            	
            	document.getElementById('blog_rating_is_vote_down_'+idBlog).innerHTML=req.responseJS.iRating;
            	document.getElementById('blog_rating_is_vote_up_'+idBlog).innerHTML=req.responseJS.iRating;
            	document.getElementById('blog_count_vote_'+idBlog).innerHTML=req.responseJS.iCountVote;
            	if (req.responseJS.iRating<0) {
            		document.getElementById('blog_rating_is_vote_down_'+idBlog).style.color="#d00000";
            		document.getElementById('blog_rating_is_vote_up_'+idBlog).style.color="#d00000";
            	} else {
            		document.getElementById('blog_rating_is_vote_down_'+idBlog).style.color="#008000";
            		document.getElementById('blog_rating_is_vote_up_'+idBlog).style.color="#008000";
            	}
            	if (value>0) {
            		showBlogVote('blog_vote_is_vote_up',idBlog);
            	} else {
            		showBlogVote('blog_vote_is_vote_down',idBlog);
            	}
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/voteBlog.php', true);    
    req.send( { idBlog: idBlog, value: value } );
}


function ajaxVoteComment(idComment,value) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);            	
            	document.getElementById('comment_rating_'+idComment).innerHTML=req.responseJS.iRating;
            	if (req.responseJS.iRating<0) {
            		document.getElementById('comment_rating_'+idComment).style.color="#d00000";
            	} else {
            		document.getElementById('comment_rating_'+idComment).style.color="#008000";
            	}
            	if (value>0) {
            		showCommentVote('comment_vote_is_vote_up',idComment);
            	} else {
            		showCommentVote('comment_vote_is_vote_down',idComment);
            	}
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/voteComment.php', true);    
    req.send( { idComment: idComment, value: value } );
}


		
function ajaxJoinLeaveBlog(idBlog,type) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);     
            	       	
            	document.getElementById('blog_user_count_'+idBlog).innerHTML=req.responseJS.iCountUser;
            	
            	if (req.responseJS.sState=='join') {
            		document.getElementById('blog_action_join_'+idBlog).style.display="none";
            		document.getElementById('blog_action_leave_'+idBlog).style.display="inline";
            	} 
            	if (req.responseJS.sState=='leave') {
            		document.getElementById('blog_action_join_'+idBlog).style.display="inline";
            		document.getElementById('blog_action_leave_'+idBlog).style.display="none";
            	}
            	
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/joinLeaveBlog.php', true);    
    req.send( { idBlog: idBlog, type: type } );
}


function ajaxTopicFavourite(idTopic,type) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg); 
            	
            	if (req.responseJS.bState) {
            		document.getElementById('topic_favourite_add').style.display="none";
            		document.getElementById('topic_favourite_del').style.display="inline";
            	} 
            	if (!req.responseJS.bState) {
            		document.getElementById('topic_favourite_add').style.display="inline";
            		document.getElementById('topic_favourite_del').style.display="none";
            	}            	
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/topicFavourite.php', true);    
    req.send( { idTopic: idTopic, type: type } );
}

function ajaxUserFrend(idUser,type) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg); 
            	
            	if (req.responseJS.bState) {
            		document.getElementById('user_frend_add').style.display="none";
            		document.getElementById('user_frend_del').style.display="inline";
            	} 
            	if (!req.responseJS.bState) {
            		document.getElementById('user_frend_add').style.display="inline";
            		document.getElementById('user_frend_del').style.display="none";
            	}            	
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/userFrend.php', true);    
    req.send( { idUser: idUser, type: type } );
}

function ajaxBlogInfo(idBlog) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	
            } else {            	
            	if (document.getElementById('block_blog_info')) {
            		document.getElementById('block_blog_info').innerHTML='<p>'+req.responseJS.sText+'</p>';
            	}  
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/blogInfo.php', true);    
    req.send( { idBlog: idBlog } );
}

function ajaxQuestionVote(idTopic,idAnswer) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            document.getElementById('debug').innerHTML = req.responseText; 
            closeWindowStatus();          
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            	if (document.getElementById('topic_question_area_'+idTopic)) {
            		document.getElementById('topic_question_area_'+idTopic).innerHTML='<p>'+req.responseJS.sText+'</p>';
            	}  
            }
        }
    }    
    showWindowStatus('Обработка голосования...');
    req.open(null, DIR_WEB_ROOT+'/include/ajax/questionVote.php', true);    
    req.send( { idTopic: idTopic, idAnswer: idAnswer } );
}

function ajaxCommentDelete(idComment) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            document.getElementById('debug').innerHTML = req.responseText; 
            closeWindowStatus();          
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            	if (document.getElementById('comment_content_'+idComment)) {
            		document.getElementById('comment_content_'+idComment).innerHTML='<font color="#c5c5c5">комментарий был удален</font>';
            	}  
            	if (document.getElementById('comment_delete_'+idComment)) {
            		document.getElementById('comment_delete_'+idComment).style.display='none';
            	}
            	if (document.getElementById('comment_repair_'+idComment)) {
            		document.getElementById('comment_repair_'+idComment).style.display='inline';
            	} 
            }
        }
    }    
    showWindowStatus('Удаление комментария...');
    req.open(null, DIR_WEB_ROOT+'/include/ajax/commentDelete.php', true);    
    req.send( { idComment: idComment } );
}

function ajaxCommentRepair(idComment) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            document.getElementById('debug').innerHTML = req.responseText; 
            closeWindowStatus();          
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            	if (document.getElementById('comment_content_'+idComment)) {
            		document.getElementById('comment_content_'+idComment).innerHTML=req.responseJS.sCommentText;
            	}  
            	if (document.getElementById('comment_delete_'+idComment)) {
            		document.getElementById('comment_delete_'+idComment).style.display='inline';
            	} 
            	if (document.getElementById('comment_repair_'+idComment)) {
            		document.getElementById('comment_repair_'+idComment).style.display='none';
            	}
            }
        }
    }    
    showWindowStatus('Восстановление комментария...');
    req.open(null, DIR_WEB_ROOT+'/include/ajax/commentRepair.php', true);    
    req.send( { idComment: idComment } );
}

function ajaxVoteUser(idUser,value) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            //document.getElementById('debug').innerHTML = req.responseText;           
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);
            } else {            	
            	msgNoticeBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg); 
            	document.getElementById('user_rating_is_vote_down_'+idUser).innerHTML=req.responseJS.iRating;
            	document.getElementById('user_rating_is_vote_up_'+idUser).innerHTML=req.responseJS.iRating;
            	document.getElementById('user_skill_'+idUser).innerHTML=req.responseJS.iSkill;
            	document.getElementById('user_count_vote_'+idUser).innerHTML=req.responseJS.iCountVote;
            	if (req.responseJS.iRating<0) {
            		document.getElementById('user_rating_is_vote_down_'+idUser).style.color="#d00000";
            		document.getElementById('user_rating_is_vote_up_'+idUser).style.color="#d00000";
            	} else {
            		document.getElementById('user_rating_is_vote_down_'+idUser).style.color="#008000";
            		document.getElementById('user_rating_is_vote_up_'+idUser).style.color="#008000";
            	}
            	if (value>0) {
            		showUserVote('user_vote_is_vote_up',idUser);
            	} else {
            		showUserVote('user_vote_is_vote_down',idUser);
            	}
            }
        }
    }    
    req.open(null, DIR_WEB_ROOT+'/include/ajax/voteUser.php', true);    
    req.send( { idUser: idUser, value: value } );
}

function ajaxUploadImg(value) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            document.getElementById('debug').innerHTML = req.responseText;  
            closeWindowStatus();         
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert('Ошибка','Возникли проблемы при загрузке изображения, попробуйте еще разок. И на всякий случай проверьте правильность URL картинки');
            	showWindow('window_load_img');
            } else {   
            	voidPutTag('topic_text',req.responseJS.sText);            	
            }
        }
    }    
    closeWindow('window_load_img');
    showWindowStatus('Загрузка изображения...');
    req.open(null, DIR_WEB_ROOT+'/include/ajax/uploadImg.php', true);    
    req.send( { value: value } );
}

function ajaxTextPreview(text,save) {    
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {         
            document.getElementById('debug').innerHTML = req.responseText;  
            closeWindowStatus();         
            if (req.responseJS.bStateError) {
            	msgErrorBox.alert('Ошибка','Возникли проблемы при обработке предпросмотра');            	
            } else {               	
            	document.getElementById('text_preview').innerHTML = req.responseJS.sText;         	
            }
        }
    }      
    showWindowStatus('Обработка предпросмотра...');
    req.open(null, DIR_WEB_ROOT+'/include/ajax/textPreview.php', true);    
    req.send( { text: text, save: save } );
}

function submitTags(sTag) {		
	window.location=DIR_WEB_ROOT+'/tag/'+sTag+'/';
	return false;
}

function matchClass( objNode, strCurrClass ) {
	return ( objNode && objNode.className.length && objNode.className.match( new RegExp('(^|\\s+)(' + strCurrClass + ')($|\\s+)') ) );
}

function getElementsByClassName(objParentNode, strNodeName, strClassName){
	var nodes = document.getElementsByTagName(strNodeName);	
	if(!strClassName){
		return nodes;	
	}	
	var nodesWithClassName = [];
	for(var i=0; i<nodes.length; i++){
		
		if(matchClass( nodes[i], strClassName )){
			//nodesWithClassName.push(nodes[i]);
			nodesWithClassName[nodesWithClassName.length] = nodes[i];
		}	
	}
	return nodesWithClassName;
}

function checkAllTalk(checkbox) {
	if ($('form_talks_list')) {
		var checkboxes = getElementsByClassName($('form_talks_list'),'input' ,'form_talks_checkbox');
		if (checkbox.checked == true) {
			for (var i = 0, length = checkboxes.length; i < length; i++) {
				checkboxes[i].checked = true;
			}
		} else {
			for (var i = 0, length = checkboxes.length; i < length; i++) {
				checkboxes[i].checked = false;
			}
		}
	}
}

function hideCommentVoteAll(idComment) {
	document.getElementById('comment_vote_self_'+idComment).style.display='none';
	document.getElementById('comment_vote_is_vote_up_'+idComment).style.display='none';
	document.getElementById('comment_vote_is_vote_down_'+idComment).style.display='none';
	document.getElementById('comment_vote_ok_'+idComment).style.display='none';
	document.getElementById('comment_vote_anonim_'+idComment).style.display='none';
}

function showCommentVote(vote,idComment) {
	hideCommentVoteAll(idComment);	
	document.getElementById(vote+'_'+idComment).style.display='inline';
}

function hideTopicVoteAll(idTopic) {
	document.getElementById('topic_vote_self_'+idTopic).style.display='none';
	document.getElementById('topic_vote_is_vote_up_'+idTopic).style.display='none';
	document.getElementById('topic_vote_is_vote_down_'+idTopic).style.display='none';
	document.getElementById('topic_vote_ok_'+idTopic).style.display='none';
	document.getElementById('topic_vote_anonim_'+idTopic).style.display='none';
}

function showTopicVote(vote,idTopic) {
	hideTopicVoteAll(idTopic);	
	document.getElementById(vote+'_'+idTopic).style.display='inline';
}

function hideBlogVoteAll(idBlog) {
	document.getElementById('blog_vote_self_'+idBlog).style.display='none';
	document.getElementById('blog_vote_is_vote_up_'+idBlog).style.display='none';
	document.getElementById('blog_vote_is_vote_down_'+idBlog).style.display='none';
	document.getElementById('blog_vote_ok_'+idBlog).style.display='none';
	document.getElementById('blog_vote_anonim_'+idBlog).style.display='none';
}

function showBlogVote(vote,idBlog) {
	hideBlogVoteAll(idBlog);	
	document.getElementById(vote+'_'+idBlog).style.display='inline';
}

function hideUserVoteAll(idUser) {
	document.getElementById('user_vote_self_'+idUser).style.display='none';
	document.getElementById('user_vote_is_vote_up_'+idUser).style.display='none';
	document.getElementById('user_vote_is_vote_down_'+idUser).style.display='none';
	document.getElementById('user_vote_ok_'+idUser).style.display='none';
	document.getElementById('user_vote_anonim_'+idUser).style.display='none';
}

function showUserVote(vote,idUser) {
	hideUserVoteAll(idUser);	
	document.getElementById(vote+'_'+idUser).style.display='inline';
}


 
// для опроса
function addField(btn){
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        var newTr = tr.parentNode.insertBefore(tr.cloneNode(true),tr.nextSibling);
        checkFieldForLast();
}
function checkFieldForLast(){	
        btns = document.getElementsByName('drop_answer');      
        for (i = 0; i < btns.length; i++){
        	btns[i].disabled = false;            
        }
        if (btns.length<=2) {
        	btns[0].disabled = true;
        	btns[1].disabled = true;
        }
}
function dropField(btn){	
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        tr.parentNode.removeChild(tr);
        checkFieldForLast();
}
