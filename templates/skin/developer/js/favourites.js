var lsFavourite;

var lsFavouriteClass = new Class({
                                           
        Implements: Options,

        options: {
                classes_action: {                        
                        active:    'active',
                        quest:     'quest'
                },
                classes_element: {
                        favorite:  'favorite'                        
                }              
        },
       
        typeFavourite: {                
                topic: {
                        url: DIR_WEB_ROOT+'/include/ajax/topicFavourite.php',
                        targetName: 'idTopic'
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
                if (this.objFavourite.getParent('.'+this.options.classes_element.favorite).hasClass(this.options.classes_action.active)) {
                	value=0;
                } 
                
                var params = new Hash();
                params['type']=value;
                params[this.typeFavourite[type].targetName]=idTarget;
                
                JsHttpRequest.query(
                        this.typeFavourite[type].url,                       
                        params,
                        function(result, errors) {     
                                thisObj.onToggle(result, errors, thisObj);                               
                        },
                        true
                );             
        },
       
        onToggle: function(result, errors, thisObj) {            
        	if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}      
        	if (result.bStateError) {
                msgErrorBox.alert(result.sMsgTitle,result.sMsg);
        	} else {
                msgNoticeBox.alert(result.sMsgTitle,result.sMsg);
               
                var divFavourite=thisObj.objFavourite.getParent('.'+thisObj.options.classes_element.favorite);
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