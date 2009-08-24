var lsTalkMessagesClass = new Class({
        Implements: Options,
        
		options: {    
        		target: {
        			id: 'new_messages' ,
        			class_new: 'message',
        			class_empty: 'message-empty'                      
                },
                reload: {
                	request: 0,
                	url: '',
                }                                 
        	},
        	
        requestObj: new JsHttpRequest(),
        		
        initialize: function(options){   
				this.setOptions(options);
				this.requestObj.onreadystatechange = function(){
					if (this.readyState == 4) {         
						if (this.responseJS.bStateError) {
							msgErrorBox.alert(
								this.responseJS.sMsgTitle,
								this.responseJS.sMsg
							); 
						} else {
							this.targetObj = $('new_messages');
						
							if (this.responseJS.iCountTalkNew>0) {
								this.targetObj
									.addClass('message')
									.removeClass('message-empty')
									.innerHTML = this.responseJS.iCountTalkNew;
							} else {
								this.targetObj
									.addClass('message-empty')
									.removeClass('message');
							}
						}
					}					
				};
	        },
		
        get: function() { 
				this.options.reload.request -= 1;
				if(this.options.reload.request>1) {
					this.requestObj.open(
						null, this.options.reload.url, true
					);    
					this.requestObj.send();
				}	
			}
});