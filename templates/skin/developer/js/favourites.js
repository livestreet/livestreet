var lsFavourite;

var lsFavouriteClass = new Class({
                                           
        Implements: Options,

        options: {
                classes_action: {                        
                        active:    'active'
                },
                classes_element: {
                        favorite:  'favorite'                        
                }              
        },
       
        typeFavourite: {                
                topic: {
                        url: aRouter['ajax']+'favourite/topic/',
                        targetName: 'idTopic'
                },
                comment: {
                        url: aRouter['ajax']+'favourite/comment/',
                        targetName: 'idComment'                	
                },
                talk : {
                        url: aRouter['ajax']+'favourite/talk/',
                        targetName: 'idTalk'                	                	
                }
        },

        initialize: function(options){         
                this.setOptions(options);                      
        },
       
        toggle: function(idTarget,objFavourite,type) {          
                if (!this.typeFavourite[type]) {
                        return false;
                }
                               
                this.idTarget=idTarget;
                this.objFavourite=$(objFavourite);
                this.value=value;
                this.type=type;        
                thisObj=this;
                  
                var value=1;      
                if (this.objFavourite.hasClass(this.options.classes_action.active)) {
                	value=0;
                } 
                
                var params = new Hash();
                params['type']=value;
                params[this.typeFavourite[type].targetName]=idTarget;
                params['security_ls_key']=LIVESTREET_SECURITY_KEY;
                
                new Request.JSON({
                	url: this.typeFavourite[type].url,
                	noCache: true,
                	data: params,
                	onSuccess: function(result){
                		thisObj.onToggle(result, thisObj);
                	},
                	onFailure: function(){
                		msgErrorBox.alert('Error','Please try again later');
                	}
                }).send();
        },
       
        onToggle: function(result, thisObj) {            
        	if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}      
        	if (result.bStateError) {
                msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        	} else {
                msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
               
                var divFavourite=thisObj.objFavourite;
                divFavourite.removeClass(thisObj.options.classes_action.active);
                if (result.bState) {
                	divFavourite.addClass(thisObj.options.classes_action.active);
                }
        	}      
        }
       
});

window.addEvent('domready', function() {       
      lsFavourite=new lsFavouriteClass();
});