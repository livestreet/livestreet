/**
* Flash загрузчик
*/
var lsSWFUploadClass = new Class({	
	
	Implements: [Options,Events],
			
	initialize: function(){
		this.swfu = null;
	},
	
	initOptions: function() {
		this.options = {
			// Backend Settings
			upload_url: aRouter['photoset']+"upload",
			post_params: {'SSID':SESSION_ID, 'security_ls_key': LIVESTREET_SECURITY_KEY},

			// File Upload Settings
			file_types : "*.jpg; *.JPG;*.png;*.gif",
			file_types_description : "Images",
			file_upload_limit : "0",

			// Event Handler Settings
			file_queue_error_handler : this.handlerFileQueueError,
			file_dialog_complete_handler : this.handlerFileDialogComplete,
			upload_progress_handler : this.handlerUploadProgress,
			upload_error_handler : this.handlerUploadError,
			upload_success_handler : this.handlerUploadSuccess,
			upload_complete_handler : this.handlerUploadComplete,

			// Button Settings
			button_placeholder_id : "start-upload",
			button_width: 122,
			button_height: 30,
			button_text : '<span class="button">'+lsLang.get('topic_photoset_upload_choose')+'</span>',
			button_text_style : '.button { color: #1F8AB7; font-size: 14px; }',
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_text_left_padding: 6,
			button_text_top_padding: 3,
			button_cursor: SWFUpload.CURSOR.HAND,

			// Flash Settings
			flash_url : DIR_ROOT_ENGINE_LIB+'/external/swfupload/swfupload.swf',

			custom_settings : {				
			},

			// Debug Settings
			debug: false
		};		
	},
	
	loadSwf: function() {
		Asset.javascript(DIR_ROOT_ENGINE_LIB+'/external/swfupload/swfupload.swfobject.js');
		Asset.javascript(DIR_ROOT_ENGINE_LIB+'/external/swfupload/swfupload.js', {			
			events: {
				load: function(){
					this.initOptions();
					this.fireEvent('load');
				}.bind(this)
			}
		});
	},
	
	init: function(opt) {
		this.setOptions(opt);		
		this.swfu = new SWFUpload(this.options);
		return this.swfu;
	},
	
	handlerFileQueueError: function(file, errorCode, message) {
		//$(this).fireEvent('eFileQueueError',[file, errorCode, message]);
		if (lsSWFUpload.options.events.FileQueueError) {
			lsSWFUpload.options.events.FileQueueError.apply(this,[file, errorCode, message])
		}
	},
	
	handlerFileDialogComplete: function(numFilesSelected, numFilesQueued) {
		//$(this).fireEvent('eFileDialogComplete',[numFilesSelected, numFilesQueued]);
		if (lsSWFUpload.options.events.FileDialogComplete) {
			lsSWFUpload.options.events.FileDialogComplete.apply(this,[numFilesSelected, numFilesQueued])
		}
		if (numFilesQueued>0) {
			this.startUpload();
		}
	},
	
	handlerUploadProgress: function(file, bytesLoaded) {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		//$(this).fireEvent('eUploadProgress',[file, bytesLoaded, percent]);
		if (lsSWFUpload.options.events.UploadProgress) {
			lsSWFUpload.options.events.UploadProgress.apply(this,[file, bytesLoaded, percent])
		}
	},
	
	handlerUploadError: function(file, errorCode, message) {
		//$(this).fireEvent('eUploadError',[file, errorCode, message]);
		if (lsSWFUpload.options.events.UploadError) {
			lsSWFUpload.options.events.UploadError.apply(this,[file, errorCode, message])
		}
	},
	
	handlerUploadSuccess: function(file, serverData) {
		//$(this).fireEvent('eUploadSuccess',[file, serverData]);
		if (lsSWFUpload.options.events.UploadSuccess) {
			lsSWFUpload.options.events.UploadSuccess.apply(this,[file, serverData])
		}
	},
	
	handlerUploadComplete: function(file) {
		var next = this.getStats().files_queued;
		if (next > 0) {
			this.startUpload();
		}
		//$(this).fireEvent('eUploadComplete',[file, next]);
		if (lsSWFUpload.options.events.UploadComplete) {
			lsSWFUpload.options.events.UploadComplete.apply(this,[file, next])
		}
	}

});

var lsSWFUpload = new lsSWFUploadClass();



var lsLangClass = new Class({	
			
	initialize: function(){
		this.msgs = {};	
	},
	
	/**
	* Загрузка текстовок
	*/
	load: function(msgs) {
		this.msgs=msgs;
	},
	
	/**
	* Отображение сообщения об ошибке 
	*/
	get: function(name,replace){
		if (this.msgs[name]) {
			var value=this.msgs[name];
			if (replace) {
				(new Hash(replace)).each(function(v,k){
					value=value.replace(new RegExp('%%'+k+'%%','g'),v);
				});
			}
			return value;
		}
		return '';
	}
	
});

var lsLang = new lsLangClass();




function ajaxTextPreview(textId,save,divPreview) {
	var text;    
	if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
		text = ed.getContent();
	} else {
		text = $(textId).value;	
	}	
	save=save ? 1 : 0;
	new Request.JSON({
		url: aRouter['ajax']+'preview/text/',
		noCache: true,
		data: { text: text, save: save, security_ls_key: LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
			if (!result) {
                msgErrorBox.alert('Error','Please try again later');           
        	}
            if (result.bStateError) {
            	msgErrorBox.alert('Error','Please try again later');
            } else {    	
            	if (!divPreview) {
            		divPreview='text_preview';
            	}            	
            	if ($(divPreview)) {
            		$(divPreview).set('html',result.sText).setStyle('display','block');
            	}
            }
		},
		onFailure: function(){
			msgErrorBox.alert('Error','Please try again later');
		}
	}).send();
}


function addField(btn){
	li = btn;
	while (li.tagName != 'LI') li = li.parentNode;
	var newTr = li.parentNode.insertBefore(li.cloneNode(true),li.nextSibling);
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
	li = btn;
	while (li.tagName != 'LI') li = li.parentNode;
	li.parentNode.removeChild(li);
	checkFieldForLast();
}



function checkAllTalk(checkbox) {
	$$('.form_talks_checkbox').each(function(chk){
		if (checkbox.checked) {
			chk.checked=true;
		} else {
			chk.checked=false;
		}
	});	
}

function checkAllReport(checkbox) {
	$$('.form_reports_checkbox').each(function(chk){
		if (checkbox.checked) {
			chk.checked=true;
		} else {
			chk.checked=false;
		}
	});	
}

function checkAllPlugins(checkbox) {
	$$('.form_plugins_checkbox').each(function(chk){
		if (checkbox.checked) {
			chk.checked=true;
		} else {
			chk.checked=false;
		}
	});
}

function showImgUploadForm() {
	$$('.upload-form').setStyle('display', 'block');
}

function hideImgUploadForm() {
	$$('.upload-form').setStyle('display', 'none');
}

var winFormImgUpload;


function ajaxUploadImg(form,sToLoad) {
	if (typeof(form)=='string') {
		form=$(form);
	}
			
	var iFrame = new iFrameFormRequest(form.getProperty('id'),{
		url: aRouter['ajax']+'upload/image/',
		dataType: 'json',
		params: {security_ls_key: LIVESTREET_SECURITY_KEY},
		onComplete: function(response){
			if (response.bStateError) {
				msgErrorBox.alert(response.sMsgTitle,response.sMsg);				
			} else {				
				lsPanel.putText(sToLoad,response.sText);
				hideImgUploadForm();
			}
		}
	});
	iFrame.send();
}




var idLastPhotoset=0;
var isLoadingPhotoset=false;


function initSwfUpload(opt) {
	opt=opt || {};
	opt.button_placeholder_id = 'photoset-start-upload';
	opt.post_params.ls_photoset_target_tmp = Cookie.read('ls_photoset_target_tmp') ? Cookie.read('ls_photoset_target_tmp') : 0;


	lsSWFUpload.addEvent('load',function() {
		var swfu = lsSWFUpload.init(opt);
	});

	lsSWFUpload.loadSwf();
}

function swfHandlerUploadProgress(file, bytesLoaded, percent) {
	$('photoset_photo_empty_progress').set('text',file.name+': '+( percent==100 ? 'resize..' : percent +'%'));
}

function swfHandlerFileDialogComplete(numFilesSelected, numFilesQueued) {
	if (numFilesQueued>0) {
		addPhotoEmpty();
	}
}

function swfHandlerUploadSuccess(file, serverData) {
	addTopicImage(JSON.decode(serverData, true));
}

function swfHandlerUploadComplete(file, next) {
	if (next>0) {
		addPhotoEmpty();
	}
}

function addPhotoEmpty() {
	var template = '<img src="'+DIR_STATIC_SKIN + '/images/loader.gif'+'" alt="image"  style="margin-left: 35px;margin-top: 20px;" />'
	+'<div id="photoset_photo_empty_progress" style="height: 60px;width: 350px;padding: 3px;border: 1px solid #DDDDDD;"></div><br />';
	liElement= new Element('li', {'id':'photoset_photo_empty','html':template});
	liElement.inject('swfu_images');
}

	
	
function addTopicImage(response)
{ 
	$('photoset_photo_empty').destroy();
    if (!response.bStateError) {
        template = '<img src="'+response.file+'" alt="image" />'
                            +'<textarea onBlur="topicImageSetDescription('+response.id+', this.value)"></textarea><br />'
                            +'<a href="javascript:deleteTopicImage('+response.id+')" class="image-delete">'+lsLang.get('topic_photoset_photo_delete')+'</a>'
                            +'<span class="photo-preview-state"><a href="javascript:setTopicMainPhoto('+response.id+')" class="mark-as-preview">'+lsLang.get('topic_photoset_mark_as_preview')+'</a></span>';
        liElement= new Element('li', {'id':'photo_'+response.id,'html':template})
        liElement.inject('swfu_images');
        msgNoticeBox.alert(response.sMsgTitle,response.sMsg);
    } else {
        msgErrorBox.alert(response.sMsgTitle,response.sMsg);
    }
    photosetCloseForm();
}

function deleteTopicImage(id)
{
	if (!confirm(lsLang.get('topic_photoset_photo_delete_confirm'))) {return;}
    new Request.JSON({
		url: aRouter['photoset']+'deleteimage',
		data: {'id':id, 'security_ls_key': LIVESTREET_SECURITY_KEY },
		onSuccess: function(response){
                    if (!response.bStateError) {
                        $('photo_'+id).dispose();
                        msgNoticeBox.alert(response.sMsgTitle,response.sMsg);
                    } else {
                        msgErrorBox.alert(response.sMsgTitle,response.sMsg);
                    }
                }
        	}).send();
}

function setTopicMainPhoto(id)
{
    $('topic_main_photo').set('value', id);
    $$('..marked-as-preview').each(function (el) {
        el.removeClass('marked-as-preview');
        el.getElement('span').set('html','<a href="javascript:setTopicMainPhoto('+el.get('id').slice(el.get('id').lastIndexOf('_')+1)+')" class="mark-as-preview">'+lsLang.get('topic_photoset_mark_as_preview')+'</a>')
    });
    $('photo_'+id).addClass('marked-as-preview');
    $('photo_'+id).getElement('span').set('html', lsLang.get('topic_photoset_is_preview'));
}

function topicImageSetDescription(id, text)
{
    new Request.JSON({
		url: aRouter['photoset']+'setimagedescription',
		data: {'id':id, 'text':text, 'security_ls_key': LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
                    if (!result.bStateError) {
                        
                    } else {
                        msgErrorBox.alert('Error','Please try again later');
                    }
                }
        	}).send();
}

function getMorePhotos(topic_id)
{
	
	if (isLoadingPhotoset) return;
	isLoadingPhotoset=true;
	    
    new Request.JSON({
		url: aRouter['photoset']+'getmore',
		data: {'topic_id':topic_id, 'last_id':idLastPhotoset, 'security_ls_key': LIVESTREET_SECURITY_KEY },
		onSuccess: function(result){
					isLoadingPhotoset=false;
                    if (!result.bStateError) {
                        if (result.photos) {                            
                            result.photos.each(function(photo) {
                                var image = '<a class="photoset-image" href="'+photo.path+'" rel="milkbox[photoset]" title="'+photo.description+'"><img src="'+photo.path_thumb+'" alt="'+photo.description+'" /></a>';
                                var liElement = new Element('li', {'html':image});
                                liElement.inject($('topic-photo-images'));
                                idLastPhotoset=photo.id;
                                $$('.photoset-image').each(function(el) {el.removeEvents('click')});
                                milkbox = new Milkbox();
                            });
                        }
                        if (!result.bHaveNext || !result.photos) {
                        	$('topic-photo-more').dispose();
                        }
                    } else {
                        msgErrorBox.alert('Error','Please try again later');
                    }
                }
        	}).send();
}

function photosetUploadPhoto() 
{
	addPhotoEmpty();
	var iFrame = new iFrameFormRequest($('photoset-upload-form').getProperty('id'),{
		url: aRouter['photoset']+'upload/',
		dataType: 'json',
		params: {security_ls_key: LIVESTREET_SECURITY_KEY},
		onComplete: function(response){
			if (response.bStateError) {
				$('photoset_photo_empty').destroy();
				msgErrorBox.alert(response.sMsgTitle,response.sMsg);
			} else {
				addTopicImage(response);
			}
		}
	});
	iFrame.send();
         photosetCloseForm();
}

function photosetCloseForm() {
    if ($('photoset-upload-file')) {
        $('photoset-upload-file').set('value', '');
    }
     $('photoset-upload-form').setStyle('display','none');
}

function photosetShowUploadForm()
{    
    $('photoset-upload-form').position({relativeTo: $('photoset-start-upload'), position: 'bottomLeft', offset: {x: -15, y:-27}});
    $('photoset-upload-form').setStyle('display','block');
}

function photosetShowMainPhoto(id)
{
	$('photoset-main-preview-'+id).setStyle('width',$('photoset-main-image-'+id).getSize().x);
	if ($('photoset-photo-count-'+id)) {
		$('photoset-photo-count-'+id).show();
	}
	if ($('photoset-photo-desc-'+id)) {
		$('photoset-photo-desc-'+id).show();
	}
}