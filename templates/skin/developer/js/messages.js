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
        		
        initialize: function(options){  
        		var thisObj = this; 
				this.setOptions(options);
	        },
		
        get: function() { 
        		var thisObj = this; 
				this.options.reload.request -= 1;
				
				if(this.errors<this.options.reload.errors&&this.options.reload.request>1) {
					new Request.JSON({
						url: thisObj.options.reload.url,
						noCache: true,
						data: { security_ls_key: LIVESTREET_SECURITY_KEY },
						onSuccess: function(result){
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
						onFailure: function(){
							msgErrorBox.alert('Error','Please try again later');
						}
					}).send();
				}
			}
});