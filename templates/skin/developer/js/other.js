function ajaxTextPreview(textId,save,divPreview) { 
	var text;    
	if (BLOG_USE_TINYMCE && tinyMCE && (ed=tinyMCE.get(textId))) {
		text = ed.getContent();
	} else {
		text = $(textId).value;	
	}	
	JsHttpRequest.query(
    	DIR_WEB_ROOT+'/include/ajax/textPreview.php',                       
        { text: text, save: save },
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


function showImgUploadForm() {	
	$$('.upload-form').setStyle('display', 'block');
}

function hideImgUploadForm() {
	$$('.upload-form').setStyle('display', 'none');
}


var winFormImgUpload;


function ajaxUploadImg(value,sToLoad) {
	sToLoad=$(sToLoad);
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.responseJS.bStateError) {
				msgErrorBox.alert(req.responseJS.sMsgTitle,req.responseJS.sMsg);				
			} else {				
				sToLoad.insertAtCursor(req.responseJS.sText);
				hideImgUploadForm();
			}
		}
	}
	req.open(null, DIR_WEB_ROOT+'/include/ajax/uploadImg.php', true);
	req.send( { value: value } );
}