function addUserfield() {
    var name = jQuery('#user_fields_add_name').val();
    jQuery.post(aRouter['admin']+'userfields', {'action':'add', 'name':name,  'security_ls_key':LIVESTREET_SECURITY_KEY}, function(data) { 
        if (!data.bStateError) {
            liElement = '<li id="filed_'+data.id+'">'+name+' <a href="javascript:deleteUserfield('+data.id+')">'+data.lang_delete+'</a>';
            jQuery('#user_field_list').append(liElement);
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