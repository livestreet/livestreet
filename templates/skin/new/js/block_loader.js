var lsBlockLoaderClass = new Class({
                                           
        Implements: Options,

        options: {    
        		classes_nav: {
        				nav: 	 'block-nav',
        				content: 'block-content',
                        active:  'active'                        
                }                           
        },
       
        type: {
                comment_stream: {
                        url: DIR_WEB_ROOT+'/include/ajax/stream_comment.php'                       
                },
                topic_stream: {
                        url: DIR_WEB_ROOT+'/include/ajax/stream_topic.php'                        
                },
                blogs_top: {
                        url: DIR_WEB_ROOT+'/include/ajax/blogs_top.php'                        
                },
                blogs_join: {
                        url: DIR_WEB_ROOT+'/include/ajax/blogs_join.php'                        
                },
                blogs_self: {
                        url: DIR_WEB_ROOT+'/include/ajax/blogs_self.php'                        
                }
        },

        initialize: function(options){         
                this.setOptions(options);                      
        },
        
        toggle: function(obj,type,params) {
        	if (!this.type[type]) {
            	return false;
            }
            thisObj=this;
            this.obj=$(obj);
            
            var liCurrent=thisObj.obj.getParent('li');
            var blockNav=liCurrent.getParent('ul.'+thisObj.options.classes_nav.nav);
            var liList=blockNav.getChildren('li');
            
            liList.each(function(li,index) {   
            	li.removeClass(thisObj.options.classes_nav.active);        	
        	});
        	
        	liCurrent.addClass(this.options.classes_nav.active);
            
        	var blockContent=blockNav.getParent('div').getChildren('div.'+this.options.classes_nav.content)[0].set('html','');
        	this.showStatus(blockContent);
        	     
        	if(!params) {
        		params={ security_ls_key: LIVESTREET_SECURITY_KEY };   	
        	} else {
        		params['security_ls_key']=LIVESTREET_SECURITY_KEY;
        	}
            JsHttpRequest.query(
            	'POST '+this.type[type].url,                       
            	params,
                function(result, errors) {     
                	thisObj.onLoad(result, errors, blockContent);                               
                },
                true
            );
            
		},
		
		onLoad: function(result, errors, blockContent) {
			blockContent.set('html','');
			if (!result) {
                msgErrorBox.alert('Error','Please try again later');
        	}
        	if (result.bStateError) {
                //msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        	} else {
        		blockContent.set('html',result.sText);
        	}
		},
		
		showStatus: function(obj) {
			var newDiv = new Element('div');
			newDiv.setStyle('text-align','center');
			newDiv.set('html','<img src="'+DIR_STATIC_SKIN+'/images/loader.gif" >');
			
			newDiv.inject(obj);
		}
});
