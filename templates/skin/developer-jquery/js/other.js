// Userfield

function userfieldShowAddForm()
{
    $('#user_fields_form_name').val( '');
    $('#user_fields_form_title').val( '');
    $('#user_fields_form_id').val( '');
    $('#user_fields_form_action').val('add');
    $('#userfield_form').css('display','block');
}
function userfieldShowEditForm(id)
{
    $('#user_fields_form_action').val('update');
    var name = $('#field_'+id+' .userfield_admin_name').html();
    var title = $('#field_'+id+' .userfield_admin_title').html();
    $('#user_fields_form_name').val(name);
    $('#user_fields_form_title').val(title);
    $('#user_fields_form_id').val(id);
    $('#userfield_form').css('display','block');
}

function userfieldApplyForm()
{
    $('#userfield_form').css('display','none');
    if ($('#user_fields_form_action').val() == 'add') {
        addUserfield();
    } else if ($('#user_fields_form_action').val() == 'update')  {
        updateUserfield();
    }
}

function addUserfield() {
    var name = $('#user_fields_form_name').val();
    var title = $('#user_fields_form_title').val();
     jQuery.post(aRouter['admin']+'userfields', {'action':'add', 'name':name,  'title':title,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
        if (!data.bStateError) {
            liElement = '<li id="filed_'+data.id+'">'+'<span class="userfield_admin_name">'+name+'</span >("<span class="userfield_admin_title">'+title+'</span>")'+' <a href="javascript:userfieldShowEditForm('+data.id+')">'+data.lang_delete+'</a> <a href="javascript:deleteUserfield('+data.id+')">'+data.lang_delete+'</a>';
            jQuery('#user_field_list').append(liElement);
            ls.msg.notice(data.sMsgTitle,data.sMsg);
        } else {
            ls.msg.error(data.sMsgTitle,data.sMsg);
        }
     });
}

function updateUserfield() {
    var id = $('#user_fields_form_id').val();
    var name = $('#user_fields_form_name').val();
    var title = $('#user_fields_form_title').val();
     jQuery.post(aRouter['admin']+'userfields', {'action':'update', 'id':id, 'name':name,  'title':title, 'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
        if (!data.bStateError) {
            $('#field_'+id+' .userfield_admin_name').html(name);
            $('#field_'+id+' .userfield_admin_title').html(title);
            ls.msg.notice(data.sMsgTitle,data.sMsg);
        } else {
            ls.msg.error(data.sMsgTitle,data.sMsg);
        }
     });
}

function deleteUserfield(id) {
    jQuery.post(aRouter['admin']+'userfields', {'action':'delete', 'id':id,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
        if (!data.bStateError) {
            jQuery('#field_'+id).remove();
            ls.msg.notice(data.sMsgTitle,data.sMsg);
        } else {
            ls.msg.error(data.sMsgTitle,data.sMsg);
        }
    });
}