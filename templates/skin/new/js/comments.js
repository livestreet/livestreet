var lsCmtTreeClass = new Class({
					   
	Implements: Options,	
	
	options: {
		img: {
			path: 		'images/',			
			openName:  	'open.gif', 
			closeName: 	'close.gif'
		},
		classes: {
			visible: 	'lsCmtTree_visible',
			hidden:  	'lsCmtTree_hidden',			
			openImg:  	'lsCmtTree_open',			
			closeImg:  	'lsCmtTree_close'			
		}		
	},

	initialize: function(options){		
		this.setOptions(options);		
		this.make();		
		this.aCommentNew=[];
		this.iCurrentShowFormComment=0;	
		this.countNewComment=0;
		
		this.hideCommentForm(this.iCurrentShowFormComment);
	},

	make: function(){
		var thisObj = this;
		var aImgFolding=$$('img.folding');
		aImgFolding.each(function(img, i){
			var divComment = img.getParent('div').getChildren('div.comment-children')[0];			
			if (divComment && divComment.getChildren('div.comment')[0]) {
				thisObj.makeImg(img);
			} else {
				img.setStyle('display','none');
			}
		});		
	},
	
	makeImg: function(img) {
		var thisObj = this;
		img.setStyle('cursor', 'pointer');
		img.setStyle('display','inline');
		img.addClass(this.options.classes.closeImg);
		img.removeEvents('click');
		img.addEvent('click',function(){
			thisObj.toggleNode(img);		
		});
	},
	
	toggleNode: function(img) {	
		var b = img.hasClass(this.options.classes.closeImg);		
		if (b) {				
			this.collapseNode(img);
		} else {					
			this.expandNode(img);
		}
	},
	
	expandNode: function(img) {				
		var thisObj = this;
		img.setProperties({'src': this.options.img.path + this.options.img.closeName});
		img.removeClass(this.options.classes.openImg);
		img.addClass(this.options.classes.closeImg);		  
		var divComment = img.getParent('div').getChildren('div.comment-children')[0];	
		
		/*
		var idComment = img.getParent('div').getProperty('id').substr(11);
		var slideTree = new Fx.Slide($('comment-children-'+idComment));							
		slideTree.slideIn();
		*/
		
		divComment.removeClass(thisObj.options.classes.hidden);
		divComment.addClass(thisObj.options.classes.visible);		
	},
	
	collapseNode: function(img) {
		var thisObj = this;
		img.setProperties({'src': this.options.img.path + this.options.img.openName});
		img.removeClass(this.options.classes.closeImg);
		img.addClass(this.options.classes.openImg);		    
		var divComment = img.getParent('div').getChildren('div.comment-children')[0];		
		
		/*
		var idComment = img.getParent('div').getProperty('id').substr(11);	
		var slideTree = new Fx.Slide($('comment-children-'+idComment));							
		slideTree.slideOut();
		*/
		
		divComment.removeClass(thisObj.options.classes.visible);
		divComment.addClass(thisObj.options.classes.hidden);		
	},
	
	expandNodeAll: function() {
		var thisObj = this;
		var aImgFolding=$$('img.folding-open');		
		aImgFolding.each(function(img, i){			
			thisObj.expandNode(img);
		});
	},
	
	injectComment: function(idCommentParent,idComment,sHtml) {		
		var newComment = new Element('div',{'class':'comment', 'id': 'comment_id_'+idComment});
		newComment.set('html',sHtml);		
		if (idCommentParent) {
			this.expandNodeAll();	
			var divChildren = $('comment-children-'+idCommentParent);		
			var imgParent = divChildren.getParent('div').getChildren('img.folding')[0];		
			this.makeImg(imgParent);
			divChildren.appendChild(newComment);
		} else {
			var divChildren = $('comment-children-0');
			newComment.inject(divChildren,'before');
		}	
	},	
	
	responseNewComment: function(idTopic,objImg,selfIdComment) {
		var thisObj=this;			
		var aDivComments=$$('.comment');		
		aDivComments.each(function(item,index){
			var divContent=item.getChildren('div.content')[0];
			if (divContent.hasClass('new')) {
				divContent.removeClass('new');
			}			
		});		
		
		var idCommentLast=this.idCommentLast;
		objImg=$(objImg);
		objImg.setProperty('src',DIR_STATIC_SKIN+'/images/update_act.gif');	
		(function(){		
		JsHttpRequest.query(
        	DIR_WEB_ROOT+'/include/ajax/commentResponse.php',
        	{ idCommentLast: idCommentLast, idTopic: idTopic },
        	function(result, errors) {        		
        		objImg.setProperty('src',DIR_STATIC_SKIN+'/images/update.gif'); 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}      
        		if (result.bStateError) {
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {   
        			var aCmt=result.aComments;         			
        			if (aCmt.length>0 && result.iMaxIdComment) {
        				thisObj.setIdCommentLast(result.iMaxIdComment);
        				var countComments=$('count-comments');
        				countComments.set('text',parseInt(countComments.get('text'))+aCmt.length);
        				if ($('block_stream_comment') && lsBlockStream) {
        					lsBlockStream.toggle($('block_stream_comment'),'comment_stream');
        				}
        			}        			      	       			       			
        			if (selfIdComment) {
        				thisObj.setCountNewComment(aCmt.length-1);
        			} else {
        				thisObj.setCountNewComment(aCmt.length);
        			}
        			thisObj.aCommentNew=[];
        			aCmt.each(function(item,index) {   
        				if (!(selfIdComment && selfIdComment==item.id)) {
        					thisObj.aCommentNew.extend([item.id]);
        				}        				 				
        				thisObj.injectComment(item.idParent,item.id,item.html);
        			}); 
        			
        			if (selfIdComment && $('comment_id_'+selfIdComment)) {
						docScroller.toElement($('comment_id_'+selfIdComment));
					}
        		}                           
	        },
        	true
       );
       }).delay(1000);
	},
	
	setIdCommentLast: function(id) {
		this.idCommentLast=id;
	},
	
	setCountNewComment: function(count) {
		this.countNewComment=count;		
		var divCountNew=$('new-comments');
        if (this.countNewComment>0) {
        	divCountNew.set('text',this.countNewComment); 
        	divCountNew.setStyle('display','block');        	
        } else {
        	this.countNewComment=0;
        	divCountNew.set('text',0);         	
        	divCountNew.setStyle('display','none');
        }
	},
	
	goNextComment: function() {		
		if (this.aCommentNew[0]) {
			if ($('comment_id_'+this.aCommentNew[0])) {
				var offsetY=0;
				//для скролла в центр
				//offsetY=-window.getSize().y/2;
				docScroller.setOptions({ 
					duration:500, 
					offset: {
        				'x': 0,
        				'y': offsetY
   					}
 				});
				docScroller.toElement($('comment_id_'+this.aCommentNew[0]));
			}			
			this.aCommentNew.erase(this.aCommentNew[0]);
		}		
		this.setCountNewComment(this.countNewComment-1);
	},
	
	addComment: function(formObj,topicId) {
		var thisObj=this;
		formObj=$(formObj);		
		JsHttpRequest.query(
        	DIR_WEB_ROOT+'/include/ajax/commentAdd.php',
        	{ params: formObj },
        	function(result, errors) {         		 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}      
        		if (result.bStateError) {        			
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {        			
        			thisObj.responseNewComment(topicId,$('update-comments'),result.sCommentId);	
        			thisObj.hideCommentForm(thisObj.iCurrentShowFormComment);        								
        		}                           
	        },
        	true
       );
	},
	
	addCommentScroll: function(commentId) {
		this.aCommentNew.extend([commentId]);
		this.setCountNewComment(this.countNewComment+1);
	},
	
	toggleComment: function(obj,commentId) {
		var divContent=$('comment_content_id_'+commentId);
		if (!divContent) {
			return false;
		}
		
		var thisObj=this;			
		JsHttpRequest.query(
        	DIR_WEB_ROOT+'/include/ajax/commentToggle.php',
        	{ idComment: commentId },
        	function(result, errors) {         		 
            	if (!result) {
                	msgErrorBox.alert('Error','Please try again later');           
        		}      
        		if (result.bStateError) {        			
                	msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        		} else {   
        			msgNoticeBox.alert(result.sMsgTitle,result.sMsg);     			
        			divContent.removeClass('old').removeClass('self').removeClass('new').removeClass('del');
        			obj.removeClass('delete').removeClass('repair');
        			if (result.bState) {
        				divContent.addClass('del');
        				obj.addClass('repair');
        			} else {
        				obj.addClass('delete');
        			}
					obj.set('text',result.sTextToggle);        			        								
        		}                           
	        },
        	true
       );
	},
	
	toggleCommentForm: function(idComment) {
		if (!$('reply_'+this.iCurrentShowFormComment) || !$('reply_'+idComment)) {
			return;
		} 
		divCurrentForm=$('reply_'+this.iCurrentShowFormComment);
		divNextForm=$('reply_'+idComment);
		
		var slideCurrentForm = new Fx.Slide(divCurrentForm);
		var slideNextForm = new Fx.Slide(divNextForm);
		
		if (this.iCurrentShowFormComment==idComment) {
			slideCurrentForm.toggle();
			$('form_comment_text').focus();
			return;
		}
		
		slideCurrentForm.slideOut();
		divNextForm.set('html',divCurrentForm.get('html'));
		divCurrentForm.set('html','');
		divNextForm.setStyle('display','block');
		slideNextForm.hide();
		
		slideNextForm.slideIn();
		
		$('form_comment_text').focus();
		$('form_comment_text').setProperty('value','');
		$('form_comment_reply').setProperty('value',idComment);
		this.iCurrentShowFormComment=idComment;
	},
	
	hideCommentForm: function(idComment) {
		if ($('reply_'+idComment)) {
			var slideForm = new Fx.Slide('reply_'+idComment);							
			slideForm.hide();
		}
	}
	
});


var lsCmtTree;
var docScroller;
var formCommentSlide;

window.addEvent('domready', function() {  	
    lsCmtTree = new lsCmtTreeClass({
    	img: {
    		path: DIR_STATIC_SKIN+'/images/'
    	},
    	classes: {
    		openImg: 'folding-open',
    		closeImg: 'folding'
    	}
    });     
    
    docScroller = new Fx.Scroll(document.getDocument()); 
   // formCommentSlide = new Fx.Slide('form_comment2');
});

