
function userfieldShowAddForm()
{
    $('user_fields_form_name').set('value', '');
    $('user_fields_form_title').set('value', '');
    $('user_fields_form_pattern').set('value', '');
    $('user_fields_form_id').set('value', '');
    $('user_fields_form_action').set('value','add');
    $('userfield_form').setStyle('display','block');
}
function userfieldShowEditForm(id)
{
    $('user_fields_form_action').set('value','update');
    var name = $('field_'+id).getElement(' .userfield_admin_name').get('text');
    var title = $('field_'+id).getElement('.userfield_admin_title').get('text');
    var pattern = $('field_'+id).getElement('.userfield_admin_pattern').get('text');
    $('user_fields_form_name').set('value', name);
    $('user_fields_form_title').set('value', title);
    $('user_fields_form_pattern').set('value', pattern);
    $('user_fields_form_id').set('value', id);
    $('userfield_form').setStyle('display','block');
}

function userfieldApplyForm()
{
    $('userfield_form').setStyle('display','none');
    if ($('user_fields_form_action').get('value') == 'add') {
        addUserfield();
    } else if ($('user_fields_form_action').get('value') == 'update')  {
        updateUserfield();
    }
}

function userfieldCloseForm()
{
    $('userfield_form').setStyle('display','none');
}

function addUserfield() {
    var name = $('user_fields_form_name').get('value');
    var title = $('user_fields_form_title').get('value');
    var pattern = $('user_fields_form_pattern').get('value');
    new Request.JSON({
            url: aRouter['admin']+'userfields',
            data: {'action':'add', 'name':name, 'title':title, 'pattern':pattern, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    var liElement = new Element('li', {
                        'id':'field_'+data.id,
                        'html':'<span class="userfield_admin_name"></span> / <span class="userfield_admin_title"></span> / <span class="userfield_admin_pattern"></span>'
                    });
                    var actionsElement = new Element('div', {
                        'class':'uf-actions',
						'html': '<a href="javascript:userfieldShowEditForm('+data.id+')"><img src="'+DIR_STATIC_SKIN+'/images/edit.gif"></a> '+
								'<a href="javascript:deleteUserfield('+data.id+')"><img src="'+DIR_STATIC_SKIN+'/images/delete.gif"></a>'
                    });
                    actionsElement.inject(liElement);
                    liElement.inject($('user_field_list'));
                    $('field_'+data.id).getElement(' .userfield_admin_name').set('text', name);
                    $('field_'+data.id).getElement('.userfield_admin_title').set('text', title);
                    $('field_'+data.id).getElement('.userfield_admin_pattern').set('text', pattern);
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
}

function updateUserfield() {
    var id = $('user_fields_form_id').get('value');
    var name = $('user_fields_form_name').get('value');
    var title = $('user_fields_form_title').get('value');
    var pattern = $('user_fields_form_pattern').get('value');
    new Request.JSON({
            url: aRouter['admin']+'userfields',
            data: {'action':'update', 'id':id, 'name':name, 'title':title, 'pattern':pattern, 'security_ls_key':LIVESTREET_SECURITY_KEY},
            onSuccess: function(data) { // запрос выполнен уcпешно
                if (!data.bStateError) {
                    $('field_'+id).getElement(' .userfield_admin_name').set('text', name);
                    $('field_'+id).getElement('.userfield_admin_title').set('text', title);
                    $('field_'+id).getElement('.userfield_admin_pattern').set('text', pattern);
                    msgNoticeBox.alert(data.sMsgTitle,data.sMsg);
                } else {
                    msgErrorBox.alert(data.sMsgTitle,data.sMsg);
                }
            }
        }).send();
}

function deleteUserfield(id) {
	if (!confirm(lsLang.get('user_field_delete_confirm'))) {return;}
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