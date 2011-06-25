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

function addUserfield() {
    var name = $('user_fields_add_name').get('value');
    new Request.JSON({
            url: aRouter['admin']+'userfields',
            data: {'action':'add', 'name':name, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    var liElement = new Element('li', {
                        'id':'field_'+data.id,
                        'html':name+' '
                    });
                    var linkElement = new Element('a', {
                        'href':'javascript:deleteUserfield('+data.id+')',
                        'html':data.lang_delete
                    })
                    linkElement.inject(liElement);
                    liElement.inject($('user_field_list'));
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
}

function deleteUserfield(id) {
    new Request.JSON({
            url: aRouter['admin']+'userfields',
            data: {'action':'delete', 'id':id, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    $('field_'+id).dispose();
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
}