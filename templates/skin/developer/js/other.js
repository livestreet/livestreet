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

function addTopicImage(response)
{ 
    if (!response.bStateError) {
        template = '<a href="#"><img src="'+response.file+'" alt="image" /></a>'
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
	var iFrame = new iFrameFormRequest($('photoset-upload-form').getProperty('id'),{
		url: aRouter['photoset']+'upload/',
		dataType: 'json',
		params: {security_ls_key: LIVESTREET_SECURITY_KEY},
		onComplete: function(response){
			if (response.bStateError) {
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
    $('photoset-upload-form').setStyle('left', '-300px');
}

function photosetShowUploadForm()
{
    $('photoset-upload-form').setStyle('left', '50%');
}