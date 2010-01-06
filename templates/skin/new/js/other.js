function ajaxTextPreview(textId,save,divPreview) { 
	var text;    
	if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
		text = ed.getContent();
	} else {
		text = $(textId).value;	
	}	
	JsHttpRequest.query(
    	'POST '+DIR_WEB_ROOT+'/include/ajax/textPreview.php',                       
        { text: text, save: save, security_ls_key: LIVESTREET_SECURITY_KEY },
        function(result, errors) {  
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
        true
    );
}


// для опроса
function addField(btn){
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        var newTr = tr.parentNode.insertBefore(tr.cloneNode(true),tr.nextSibling);
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
        tr = btn;
        while (tr.tagName != 'TR') tr = tr.parentNode;
        tr.parentNode.removeChild(tr);
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
	if (Browser.Engine.trident) {
		//return true;
	}	
	if (!winFormImgUpload) {		
		winFormImgUpload=new StickyWin.Modal({content: $('window_load_img'), closeClassName: 'close-block', useIframeShim: false, modalOptions: {modalStyle:{'z-index':900}}});
	}
	winFormImgUpload.show();
	winFormImgUpload.pin(true);	
	$$('input[name=img_file]').set('value', '');
	$$('input[name=img_url]').set('value', 'http://');	
	return false;
}

function hideImgUploadForm() {
	winFormImgUpload.hide();
}

var winFormImgUpload;


function ajaxUploadImg(value,sToLoad) {	
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.responseJS.bStateError) {
				msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);				
			} else {				
				lsPanel.putText(sToLoad,req.responseJS.sText);
				hideImgUploadForm();
			}
		}
	}
	req.open(null, DIR_WEB_ROOT+'/include/ajax/uploadImg.php', true);
	req.send( { value: value, security_ls_key: LIVESTREET_SECURITY_KEY } );
}