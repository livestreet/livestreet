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
                	errors: 4
                }                                 
        	},
        
        errors:0,	
        		
        requestObj: new JsHttpRequest(),
        		
        initialize: function(options){  
        		var thisObj = this; 
				this.setOptions(options);
	        },
		
        get: function() { 
        		var thisObj = this; 
				this.options.reload.request -= 1;
				
				if(this.errors<this.options.reload.errors&&this.options.reload.request>1) {
					JsHttpRequest.query(
						'POST '+thisObj.options.reload.url,
						{ security_ls_key: LIVESTREET_SECURITY_KEY  },
						function(result, errors) {
							if (!result) {								
								thisObj.errors+=1;
								return null;
							}
							if(result.bStateError!=true && result.bStateError!=undefined ) {
								this.targetObj = $('new_messages');
								if (result.iCountTalkNew>0) {
									this.targetObj
										.addClass('message')
										.removeClass('message-empty')
										.innerHTML = result.iCountTalkNew;
								} else {
									this.targetObj
										.addClass('message-empty')
										.removeClass('message');
								}
								thisObj.errors=0;
							} else {								
								thisObj.errors+=1;								
							}
						},
						true
					);
				}
			}
});